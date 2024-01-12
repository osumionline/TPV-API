<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\VentaDTO;
use OsumiFramework\App\Utils\AppData;
use OsumiFramework\App\Model\Reserva;
use OsumiFramework\App\Model\LineaReserva;
use OsumiFramework\App\Model\Articulo;

#[OModuleAction(
	url: '/save-reserva',
	services: ['general', 'ventas', 'imprimir']
)]
class saveReservaAction extends OAction {
	/**
	 * Función para guardar una reserva
	 *
	 * @param VentaDTO Datos de la reserva
	 * @return void
	 */
	public function run(VentaDTO $data):void {
		$status  = 'ok';
		$id      = 'null';
		$importe = 'null';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$reserva = new Reserva();
			$reserva->set('id_cliente', ($data->getIdCliente() != -1) ? $data->getIdCliente() : null);
			$reserva->set('total',       $data->getTotal());
			$reserva->save();

			$app_data = $this->general_service->getAppData();

			foreach ($data->getLineas() as $linea) {
				$nombre = $linea['descripcion'];
				$puc = 0;
				$pvp = $linea['pvp'];
				$iva = $linea['iva'];

				if ($linea['idArticulo'] != 0) {
					$art = new Articulo();
					$art->find(['id' => $linea['idArticulo']]);
					$nombre = $art->get('nombre');
					$puc    = $art->get('puc');
					$pvp    = $art->get('pvp');
					$iva    = $art->get('iva');
				}

				$lr = new LineaReserva();
				$lr->set('id_reserva', $reserva->get('id'));
				$lr->set('id_articulo', $linea['idArticulo'] != 0 ? $linea['idArticulo'] : null);
				$lr->set('nombre_articulo', $nombre);
				$lr->set('puc', $puc);
				$lr->set('pvp', $pvp);
				$lr->set('iva', $iva);
				$importe = $linea['importe'];

				if (!$linea['descuentoManual']) {
					$lr->set('descuento', $linea['descuento']);
					$lr->set('importe_descuento', null);
					$importe = ( $importe * (1 - ($linea['descuento'] / 100) ) );
				}
				else {
					$lr->set('descuento', null);
					$lr->set('importe_descuento', $linea['descuento']);
					$importe = $importe - $linea['descuento'];
				}

				$lr->set('importe', $importe);
				$lr->set('unidades', $linea['cantidad']);
				$lr->save();

				// Reduzco el stock
				if ($linea['idArticulo'] != 0) {
					$art->set('stock', $art->get('stock') - $linea['cantidad']);
					$art->save();
				}
			}

			if ($data->getImprimir() == 'reserva') {
				$venta = $this->ventas_service->getVentaFromReserva($reserva);
				$ticket_pdf = $this->imprimir_service->generateTicket($venta, 'reserva');
				$this->imprimir_service->imprimirTicket($ticket_pdf);
			}

			$id = $reserva->get('id');
			$importe = $reserva->get('total');
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('id',      $id);
		$this->getTemplate()->add('importe', $importe);
	}
}
