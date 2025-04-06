<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\SaveVenta;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Plugins\OEmailSMTP;
use Osumi\OsumiFramework\Plugins\OTicketBai;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\Service\ImprimirService;
use Osumi\OsumiFramework\App\Service\VentasService;
use Osumi\OsumiFramework\App\DTO\VentaDTO;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\LineaVenta;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\Reserva;
use Osumi\OsumiFramework\App\Model\LineaReserva;
use Osumi\OsumiFramework\App\Model\HistoricoArticulo;
use Osumi\OsumiFramework\App\Model\Cliente;
use Osumi\OsumiFramework\App\Component\Imprimir\TicketEmail\TicketEmailComponent;
use Osumi\OsumiFramework\App\Utils\AppData;

class SaveVentaComponent extends OComponent {
	private ?ClientesService $cs = null;
	private ?ImprimirService $is = null;
	private ?VentasService $vs = null;

  public string         $status  = 'ok';
	public string | int   $id      = 'null';
	public string | float $importe = 'null';
	public string | float $cambio  = 'null';

  public function __construct() {
    parent::__construct();
    $this->cs = inject(ClientesService::class);
		$this->is = inject(ImprimirService::class);
		$this->vs = inject(VentasService::class);
  }

	/**
	 * Función para guardar una venta
	 *
	 * @param VentaDTO Datos de la venta
	 * @return void
	 */
	public function run(VentaDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		// Compruebo que vengan unidades en cada línea de la venta
		if (is_array($data->lineas) && count($data->lineas) > 0) {
			foreach ($data->lineas as $linea) {
				if (is_null($linea['cantidad'])) {
					$this->status = 'error';
					break;
				}
			}
		}

		if ($this->status === 'ok') {
			$venta = Venta::create();
			$venta->num_venta      = $this->vs->generateNumVenta();
			$venta->id_empleado    = $data->id_empleado;
			$venta->id_cliente     = ($data->id_cliente !== -1) ? $data->id_cliente : null;
			$venta->total          = $data->total;
			$venta->entregado      = $data->efectivo;
			$venta->pago_mixto     = $data->pago_mixto;
			$venta->id_tipo_pago   = $data->id_tipo_pago;
			$venta->entregado_otro = $data->tarjeta;
			$venta->saldo          = null;
			$venta->facturada      = false;
			$venta->tbai_huella    = null;
			$venta->tbai_qr        = null;
			$venta->tbai_url       = null;
			$venta->save();

			$reservas = [];
			$from_reserva  = null;
			$linea_reserva = null;

			foreach ($data->lineas as $linea) {
				$nombre = $linea['descripcion'];
				$puc    = 0;
				$pvp    = $linea['pvp'];
				$iva    = $linea['iva'];

				if (!is_null($linea['fromReserva'])) {
					$from_reserva = $linea['fromReserva'];
					$linea_reserva = LineaReserva::findOne(['id' => $linea['fromReservaLineaId']]);
					if (array_search($from_reserva, $reservas) === false) {
						$reservas[] = $from_reserva;
					}
				}

				if ($linea['idArticulo'] !== 0) {
					$art = Articulo::findOne(['id' => $linea['idArticulo']]);
					$nombre = $art->nombre;
					$puc    = $art->puc;
					$pvp    = $art->pvp;
					$iva    = $art->iva;
				}

				$lv = LineaVenta::create();
				$lv->id_venta        = $venta->id;
				$lv->id_articulo     = $linea['idArticulo'] !== 0 ? $linea['idArticulo'] : null;
				$lv->nombre_articulo = $nombre;
				$lv->puc             = $puc;
				$lv->pvp             = $pvp;
				$lv->iva             = $iva;
				$this->importe = $linea['importe'];

				if (!$linea['descuentoManual']) {
					$lv->descuento         = $linea['descuento'];
					$lv->importe_descuento = null;
					$this->importe = ($this->importe * (1 - ($linea['descuento'] / 100)));
				}
				else {
					$lv->descuento         = null;
					$lv->importe_descuento = $linea['descuento'];
					$this->importe = $this->importe - $linea['descuento'];
				}

				$lv->importe  = $this->importe;
				$lv->devuelto = 0;
				$lv->unidades = $linea['cantidad'];
				$lv->regalo   = $linea['regalo'];
				$lv->save();

				// Reduzco el stock
				if ($linea['idArticulo'] !== 0) {
					$restar = $linea['cantidad'];

					// Si la línea es de una reserva, hay que recuperar el stock
					if (!is_null($linea_reserva)) {
						$restar = $linea['cantidad'] - $linea_reserva->unidades;
					}

					// Histórico
					$stock_previo = $art->stock;

					// Reduzco stock
					$art->stock = $art->stock - $restar;
					$art->save();

					// Histórico
					$ha = HistoricoArticulo::create();
					$ha->id_articulo  = $art->id;
					$ha->tipo         = HistoricoArticulo::FROM_VENTA;
					$ha->stock_previo = $stock_previo;
					$ha->diferencia   = $restar;
					$ha->stock_final  = $art->stock;
					$ha->id_venta     = $venta->id;
					$ha->id_pedido    = null;
					$ha->puc          = $art->puc;
					$ha->pvp          = $art->pvp;
					$ha->save();

					// Si la línea proviene de una venta, es una devolución, por lo que hay que marcar cuantas unidades se han devuelto de esa línea original
					if (!is_null($linea['fromVenta'])) {
						$lv_dev = LineaVenta::findOne(['id_articulo' => $linea['idArticulo'], 'id_venta' => $linea['fromVenta']]);
						$lv_dev->devuelto = $linea['cantidad'] * -1;
						$lv_dev->save();
					}
				}
			}

			// Si la venta era de una reserva, luego tengo que borrarla
			if (count($reservas) > 0) {
				foreach ($reservas as $id_reserva) {
					$reserva = Reserva::findOne(['id' => $id_reserva]);
					$reserva->deleteFull();
				}
			}

			// TicketBai
			try {
				$tbai_conf = $this->getConfig()->getPluginConfig('ticketbai');
				if ($tbai_conf['token'] !== '' && $tbai_conf['nif'] !== '') {
					$tbai = new OTicketBai(($this->getConfig()->getEnvironment() === 'prod'));

					if ($tbai->checkStatus()) {
						$this->getLog()->info('TicketBai status OK');
						$response = $tbai->nuevoTbai($venta->getDatosTBai());
						if (is_array($response)) {
							$this->getLog()->info('TicketBai response OK');
							$venta->tbai_huella = $response['huella_tbai'];
							$venta->tbai_qr     = $response['qr'];
							$venta->tbai_url    = $response['url'];
							$venta->save();
						}
						else {
							$this->getLog()->error('Ocurrió un error al generar el TicketBai de la venta ' . $venta->id);
							$this->getLog()->error(var_export($response, true));
						}
					}
				}
			} catch (\Throwable $e) {
				$this->getLog()->error('Ocurrió un error al generar el TicketBai de la venta ' . $venta->id);
				$status = 'ok-tbai-error';
			}

			$ticket_pdf = null;
			$ticket_regalo_pdf = null;

			// Imprimir ticket
			if ($data->imprimir === 'si') {
				$ticket_pdf = $this->is->generateTicket($venta, 'venta');
				$this->is->imprimirTicket($ticket_pdf);
			}

			// Imprimir ticket regalo
			if ($data->imprimir === 'regalo') {
				$ticket_regalo_pdf = $this->is->generateTicket($venta, 'regalo');
				$this->is->imprimirTicket($ticket_regalo_pdf);
			}

			// Enviar ticket por email
			if ($data->imprimir === 'email') {
				$ticket_pdf = $this->is->generateTicket($venta, 'venta');
				$app_data_file = $this->getConfig()->getDir('ofw_cache') . 'app_data.json';
				$app_data = new AppData($app_data_file);
				if (!$app_data->getLoaded()) {
					echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
					exit();
				}

				$email_conf = $this->getConfig()->getPluginConfig('email_smtp');

				try {
					$content = new TicketEmailComponent(['id' => $venta->id, 'nombre' => $app_data->getNombre()]);
					$email = new OEmailSMTP();
					$email->addRecipient(urldecode($data->email));
					$email->setSubject($app_data->getNombre() . ' - Ticket venta ' . $venta->id);
					$email->setMessage(strval($content));
					$email->setFrom($email_conf['user']);
					$email->addAttachment($ticket_pdf);
					$email->send();
				}
				catch (Throwable $t) {
					$this->getLog()->error("Error enviando email: " . $t->getMessage());
					$this->status = 'ok-email-error';
				}
			}

			// Imprimir ticket y generar factura
			if ($data->imprimir === 'factura') {
				$ticket_pdf = $this->is->generateTicket($venta, 'venta');
				$this->is->imprimirTicket($ticket_pdf);
				$cliente       = Cliente::findOne(['id' => $data->id_cliente]);
				$datos         = $cliente->getDatosFactura();
				$num_factura   = $this->cs->generateNumFactura();
				$factura       = $this->cs->createNewFactura(null, $num_factura, $data->id_cliente, $datos, true);
				$this->importe = $this->cs->updateFacturaVentas($factura->id, [$venta->id], true);

				$factura->importe = $this->importe;
				$factura->save();
				$this->status = 'ok-factura-' . $factura->id;
			}

			$this->id      = $venta->id;
			$this->importe = $venta->total;
			$this->cambio  = $venta->getCambio();
		}
	}
}
