<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Plugins\OEmail;
use OsumiFramework\App\DTO\VentaDTO;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\LineaVenta;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Component\Ticket\TicketEmailComponent;

#[OModuleAction(
	url: '/save-venta',
	services: ['ticket']
)]
class saveVentaAction extends OAction {
	/**
	 * Función para guardar una venta
	 *
	 * @param VentaDTO Datos de la venta
	 * @return void
	 */
	public function run(VentaDTO $data):void {
		$status  = 'ok';
		$id      = 'null';
		$importe = 'null';
		$cambio  = 'null';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$venta = new Venta();
			$venta->set('id_empleado',    $data->getIdEmpleado());
			$venta->set('id_cliente',     ($data->getIdCliente() != -1) ? $data->getIdCliente() : null);
			$venta->set('total',          $data->getTotal());
			$venta->set('entregado',      $data->getEfectivo());
			$venta->set('pago_mixto',     $data->getPagoMixto());
			$venta->set('id_tipo_pago',   $data->getIdTipoPago());
			$venta->set('entregado_otro', $data->getTarjeta());
			$venta->set('saldo', null);
			$venta->save();

			foreach ($data->getLineas() as $linea) {
				$art = new Articulo();
				$art->find(['id' => $linea['idArticulo']]);

				$lv = new LineaVenta();
				$lv->set('id_venta', $venta->get('id'));
				$lv->set('id_articulo', $linea['idArticulo']);
				$lv->set('nombre_articulo', $art->get('nombre'));
				$lv->set('puc', $art->get('puc'));
				$lv->set('pvp', $art->get('pvp'));
				$lv->set('iva', $art->get('iva'));
				$lv->set('re', $art->get('re'));
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
				$lv->save();

				// Reduzco el stock
				$art->set('stock', $art->get('stock') -1);
				$art->save();
			}

			$ticket_pdf = null;
			$ticket_regalo_pdf = null;

			if ($data->getImprimir() == 'si' || $data->getImprimir() == 'regalo' || $data->getImprimir() == 'email') {
				$ticket_pdf = $this->ticket_service->generateTicket($venta, false);
				if ($data->getImprimir() == 'regalo') {
					$ticket_regalo_pdf = $this->ticket_service->generateTicket($venta, true);
				}

				if ($data->getImprimir() == 'email') {
					$content = new TicketEmailComponent();
					$this->getLog()->debug('EMAIL CONTENT: ');
					$this->getLog()->debug(var_export(strval($content), true));
					$this->getLog()->debug('RUTA PDF: '.$ticket_pdf);
					$email = new OEmail();
					$email->addRecipient(urldecode($data->getEmail()));
					$email->setSubject('TIENDA - Ticket venta X');
					$email->setMessage(strval($content));
					$email->setFrom('tienda@tpv.osumi.es');
					$email->addAttachment($ticket_pdf);
					$email->send();
				}
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
