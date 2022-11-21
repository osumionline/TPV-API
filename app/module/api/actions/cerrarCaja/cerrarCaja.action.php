<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\CierreCajaDTO;
use OsumiFramework\App\Model\CajaTipo;

#[OModuleAction(
	url: '/cerrar-caja',
	services: ['general']
)]
class cerrarCajaAction extends OAction {
	/**
	 * Función para realizar un cierre de caja
	 *
	 * @param CierreCajaDTO $data Objeto con toda la información para cerrar una caja
	 * @return void
	 */
	public function run(CierreCajaDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$c = $this->general_service->getCaja($data->getDate());
			if (!is_null($c)) {
				$datos = $this->general_service->getVentasDia($c);
				$pagos_caja = $this->general_service->getPagosCajaDia($c);

				$c->set('cierre',               date('Y-m-d H:i:s', time()));
				$c->set('ventas',               $datos['ventas']);
				$c->set('beneficios',           $datos['beneficios'] - $pagos_caja['importe']);
				$c->set('venta_efectivo',       $datos['venta_efectivo']);
				$c->set('operaciones_efectivo', $datos['operaciones_efectivo']);
				$c->set('descuento_efectivo',   $datos['descuento_efectivo']);
				$c->set('venta_otros',          $datos['venta_otros']);
				$c->set('operaciones_otros',    $datos['operaciones_otros']);
				$c->set('descuento_otros',      $datos['descuento_otros']);

				$c->set('importe_pagos_caja',   $pagos_caja['importe']);
				$c->set('num_pagos_caja',       $pagos_caja['num']);

				$c->set('importe_cierre',       $c->get('importe_apertura') + $datos['venta_efectivo']);
				$c->set('importe_cierre_real',  $data->getImporteReal());

				$c->save();

				// Guardamos el desglosado de tipos de pago
				foreach ($data->getTipos() as $tipo) {
					$ct = new CajaTipo();
					$ct->set('id_caja',           $c->get('id'));
					$ct->set('id_tipo_pago',      $tipo['id']);
					$ct->set('operaciones',       $datos['tipos_pago']['tipo_pago_'.$tipo['id']]['operaciones']);
					$ct->set('importe_total',     $datos['tipos_pago']['tipo_pago_'.$tipo['id']]['importe_total']);
					$ct->set('importe_descuento', $datos['tipos_pago']['tipo_pago_'.$tipo['id']]['importe_descuento']);
					$ct->set('importe_real',      $tipo['real']);

					$ct->save();
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
