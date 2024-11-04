<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Plugins\OEmailSMTP;
use OsumiFramework\OFW\Plugins\OTicketBai;
use OsumiFramework\App\DTO\VentaDTO;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\LineaVenta;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\Reserva;
use OsumiFramework\App\Model\LineaReserva;
use OsumiFramework\App\Model\HistoricoArticulo;
use OsumiFramework\App\Model\Cliente;
use OsumiFramework\App\Component\Imprimir\TicketEmailComponent;
use OsumiFramework\App\Utils\AppData;

#[OModuleAction(
	url: '/save-venta',
	services: ['imprimir', 'general', 'ventas', 'clientes']
)]
class saveVentaAction extends OAction {
	/**
	 * Función para guardar una venta
	 *
	 * @param VentaDTO Datos de la venta
	 * @return void
	 */
	public function run(VentaDTO $data):void {
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';
		$status  = 'ok';
		$id      = 'null';
		$importe = 'null';
		$cambio  = 'null';

		if (!$data->isValid()) {
			$status = 'error';
		}

		// Compruebo que vengan unidades en cada línea de la venta
		if (is_array($data->getLineas()) && count($data->getLineas()) > 0) {
			foreach ($data->getLineas() as $linea) {
				if (is_null($linea['cantidad'])) {
					$status = 'error';
					break;
				}
			}
		}

		if ($status=='ok') {
			$venta = new Venta();
			$venta->set('num_venta',      $this->ventas_service->generateNumVenta());
			$venta->set('id_empleado',    $data->getIdEmpleado());
			$venta->set('id_cliente',     ($data->getIdCliente() != -1) ? $data->getIdCliente() : null);
			$venta->set('total',          $data->getTotal());
			$venta->set('entregado',      $data->getEfectivo());
			$venta->set('pago_mixto',     $data->getPagoMixto());
			$venta->set('id_tipo_pago',   $data->getIdTipoPago());
			$venta->set('entregado_otro', $data->getTarjeta());
			$venta->set('saldo',          null);
			$venta->set('facturada',      false);
			$venta->set('tbai_huella',    null);
			$venta->set('tbai_qr',        null);
			$venta->set('tbai_url',       null);
			$venta->save();

			$reservas = [];
			$from_reserva  = null;
			$linea_reserva = null;

			foreach ($data->getLineas() as $linea) {
				$nombre = $linea['descripcion'];
				$puc = 0;
				$pvp = $linea['pvp'];
				$iva = $linea['iva'];

				if (!is_null($linea['fromReserva'])) {
					$from_reserva = $linea['fromReserva'];
					$linea_reserva = new LineaReserva();
					$linea_reserva->find(['id' => $linea['fromReservaLineaId']]);
					if (array_search($from_reserva, $reservas) === false) {
						array_push($reservas, $from_reserva);
					}
				}

				if ($linea['idArticulo'] != 0) {
					$art = new Articulo();
					$art->find(['id' => $linea['idArticulo']]);
					$nombre = $art->get('nombre');
					$puc    = $art->get('puc');
					$pvp    = $art->get('pvp');
					$iva    = $art->get('iva');
				}

				$lv = new LineaVenta();
				$lv->set('id_venta', $venta->get('id'));
				$lv->set('id_articulo', $linea['idArticulo'] != 0 ? $linea['idArticulo'] : null);
				$lv->set('nombre_articulo', $nombre);
				$lv->set('puc', $puc);
				$lv->set('pvp', $pvp);
				$lv->set('iva', $iva);
				$importe = $linea['importe'];

				if (!$linea['descuentoManual']) {
					$lv->set('descuento', $linea['descuento']);
					$lv->set('importe_descuento', null);
					$importe = ( $importe * (1 - ($linea['descuento'] / 100) ) );
				}
				else {
					$lv->set('descuento', null);
					$lv->set('importe_descuento', $linea['descuento']);
					$importe = $importe - $linea['descuento'];
				}

				$lv->set('importe', $importe);
				$lv->set('devuelto', 0);
				$lv->set('unidades', $linea['cantidad']);
				$lv->set('regalo', $linea['regalo']);
				$lv->save();

				// Reduzco el stock
				if ($linea['idArticulo'] != 0) {
					$restar = $linea['cantidad'];

					// Si la línea es de una reserva, hay que recuperar el stock
					if (!is_null($linea_reserva)) {
						$restar = $linea['cantidad'] - $linea_reserva->get('unidades');
					}

					// Histórico
					$stock_previo = $art->get('stock');

					// Reduzco stock
					$art->set('stock', $art->get('stock') - $restar);
					$art->save();

					// Histórico
					$ha = new HistoricoArticulo();
					$ha->set('id_articulo',  $art->get('id'));
					$ha->set('tipo',         HistoricoArticulo::FROM_VENTA);
					$ha->set('stock_previo', $stock_previo);
					$ha->set('diferencia',   $restar);
					$ha->set('stock_final',  $art->get('stock'));
					$ha->set('id_venta',     $venta->get('id'));
					$ha->set('id_pedido',    null);
					$ha->set('puc',          $art->get('puc'));
					$ha->set('pvp',          $art->get('pvp'));
					$ha->save();

					// Si la línea proviene de una venta, es una devolución, por lo que hay que marcar cuantas unidades se han devuelto de esa línea original
					if (!is_null($linea['fromVenta'])) {
						$lv_dev = new LineaVenta();
						$lv_dev->find(['id_articulo' => $linea['idArticulo'], 'id_venta' => $linea['fromVenta']]);
						$lv_dev->set('devuelto', $linea['cantidad'] * -1);
						$lv_dev->save();
					}
				}
			}

			// Si la venta era de una reserva, luego tengo que borrarla
			if (count($reservas) > 0) {
				foreach ($reservas as $id_reserva) {
					$reserva = new Reserva();
					$reserva->find(['id' => $id_reserva]);
					$reserva->deleteFull();
				}
			}

			// TicketBai
			try {
				$tbai_conf = $this->getConfig()->getPluginConfig('ticketbai');
				if ($tbai_conf['token'] !== '' && $tbai_conf['nif'] !== '') {
					$tbai = new OTicketBai( ($this->getConfig()->getEnvironment()=='prod') );

					if ($tbai->checkStatus()) {
						$this->getLog()->info('TicketBai status OK');
						$response = $tbai->nuevoTbai($venta->getDatosTBai());
						if (is_array($response)) {
							$this->getLog()->info('TicketBai response OK');
							$venta->set('tbai_huella', $response['huella_tbai']);
							$venta->set('tbai_qr',     $response['qr']);
							$venta->set('tbai_url',    $response['url']);
							$venta->save();
						}
						else {
							$this->getLog()->error('Ocurrió un error al generar el TicketBai de la venta '.$venta->get('id'));
							$this->getLog()->error(var_export($response, true));
						}
					}
				}
			}
			catch (Throwable $e) {
				$this->getLog()->error('Ocurrió un error al generar el TicketBai de la venta '.$venta->get('id'));
				$status = 'ok-tbai-error';
			}

			$ticket_pdf = null;
			$ticket_regalo_pdf = null;

			// Imprimir ticket
			if ($data->getImprimir() == 'si') {
				$ticket_pdf = $this->imprimir_service->generateTicket($venta, 'venta');
				$this->imprimir_service->imprimirTicket($ticket_pdf);
			}

			// Imprimir ticket regalo
			if ($data->getImprimir() == 'regalo') {
				$ticket_regalo_pdf = $this->imprimir_service->generateTicket($venta, 'regalo');
				$this->imprimir_service->imprimirTicket($ticket_regalo_pdf);
			}

			// Enviar ticket por email
			if ($data->getImprimir() == 'email') {
				$ticket_pdf = $this->imprimir_service->generateTicket($venta, 'venta');
				$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
				$app_data = new AppData($app_data_file);
				if (!$app_data->getLoaded()) {
					echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
					exit();
				}

				$email_conf = $this->getConfig()->getPluginConfig('email_smtp');

				$content = new TicketEmailComponent(['id' => $venta->get('id'), 'nombre' => $app_data->getNombre()]);
				$email = new OEmailSMTP();
				$email->addRecipient(urldecode($data->getEmail()));
				$email->setSubject($app_data->getNombre().' - Ticket venta '.$venta->get('id'));
				$email->setMessage(strval($content));
				$email->setFrom($email_conf['user']);
				$email->addAttachment($ticket_pdf);
				$email->send();
			}

			// Imprimir ticket y generar factura
			if ($data->getImprimir() == 'factura') {
				$ticket_pdf = $this->imprimir_service->generateTicket($venta, 'venta');
				$this->imprimir_service->imprimirTicket($ticket_pdf);
				$cliente = new Cliente();
				$cliente->find(['id' => $data->getIdCliente()]);
				$datos = $cliente->getDatosFactura();
				$num_factura = $this->clientes_service->generateNumFactura();
				$factura = $this->clientes_service->createNewFactura(null, $num_factura, $data->getIdCliente(), $datos, true);
				$importe = $this->clientes_service->updateFacturaVentas($factura->get('id'), [$venta->get('id')], true);
				$factura->set('importe', $importe);
				$factura->save();
				$status = 'ok-factura-'.$factura->get('id');
			}

			$id = $venta->get('id');
			$importe = $venta->get('total');
			$cambio = $venta->getCambio();
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('id',      $id);
		$this->getTemplate()->add('importe', $importe);
		$this->getTemplate()->add('cambio',  $cambio);
	}
}
