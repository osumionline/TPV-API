<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Caja;
use OsumiFramework\App\Model\CajaTipo;

#[OModuleAction(
	url: '/open-box',
	services: ['general']
)]
class openBoxAction extends OAction {
	/**
	 * Función para abrir la caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';

		$caja = new Caja();
		$caja->set('apertura',             date('Y-m-d H:i:s', time()));
		$caja->set('cierre',               null);
		$caja->set('ventas',               0);
		$caja->set('beneficios',           0);
		$caja->set('venta_efectivo',       0);
		$caja->set('operaciones_efectivo', 0);
		$caja->set('descuento_efectivo',   0);
		$caja->set('venta_otros',          0);
		$caja->set('operaciones_otros',    0);
		$caja->set('descuento_otros',      0);
		$caja->set('importe_pagos_caja',   0);
		$caja->set('num_pagos_caja',       0);
		$caja->set('importe_apertura',     0);
		$caja->set('importe_cierre',       0);
		$caja->set('importe_cierre_real',  0);
		$caja->set('importe_retirado',     0);

		$caja->save();

		$previous_id = $caja->get('id') -1;
		$previous_caja = new Caja();
		if ($previous_caja->find(['id' => $previous_id])) {
			// Si la caja anterior no fue cerrada manualmente, se cierra automáticamente
			if (is_null($previous_caja->get('cierre'))) {
				// La anterior caja se cierra en el momento en que la nueva se abre
				$previous_caja->set('cierre', $caja->get('apertura', 'Y-m-d H:i:s'));

				// Al cerrar la anterior caja actualizamos los valores comprobando las ventas
				$datos = $this->general_service->getVentasDia($previous_caja);

				$pagos_caja = $this->general_service->getPagosCajaDia($previous_caja);

				$previous_caja->set('ventas',               $datos['ventas']);
				$previous_caja->set('beneficios',           $datos['beneficios'] - $pagos_caja['importe']);
				$previous_caja->set('venta_efectivo',       $datos['venta_efectivo']);
				$previous_caja->set('operaciones_efectivo', $datos['operaciones_efectivo']);
				$previous_caja->set('descuento_efectivo',   $datos['descuento_efectivo']);
				$previous_caja->set('venta_otros',          $datos['venta_otros']);
				$previous_caja->set('operaciones_otros',    $datos['operaciones_otros']);
				$previous_caja->set('descuento_otros',      $datos['descuento_otros']);

				$previous_caja->set('importe_pagos_caja', $pagos_caja['importe']);
				$previous_caja->set('num_pagos_caja',     $pagos_caja['num']);

				$previous_caja->set('importe_cierre', $previous_caja->get('importe_apertura') + $datos['venta_efectivo']);
				// Si se cierra automáticamente no tenemos forma de saber el importe de cierre real, por lo que asumimos que es correcto
				$previous_caja->set('importe_cierre_real', $previous_caja->get('importe_cierre'));
				// Si se cierra automáticamente no tenemos forma de saber si se ha retirado dinero de la caja, por lo que asumimos que no se ha retirado nada
				$previous_caja->set('importe_retirado', 0);

				$previous_caja->save();

				// Guardamos el desglosado de tipos de pago
				foreach ($datos['tipos_pago'] as $tp) {
					$ct = new CajaTipo();
					$ct->set('id_caja', $previous_caja->get('id'));
					$ct->set('id_tipo_pago', $tp['id']);
					$ct->set('operaciones', $tp['operaciones']);
					$ct->set('importe_total', $tp['importe_total']);
					$ct->set('importe_descuento', $tp['importe_descuento']);
					// Al ser un cierre de caja automático asumimos que el importe real es correcto
					$ct->set('importe_real', $tp['importe_total']);

					$ct->save();
				}
			}

			// Al abrir una caja nueva el importe que debería haber en caja es el que había al cerrar la anterior
			$caja->set('importe_apertura', $previous_caja->get('importe_cierre_real'));
			$caja->save();
		}

		// Limpieza de carpeta tmp
		foreach (glob($this->getConfig()->getDir('ofw_tmp')."*.html") as $nombre_fichero) {
    	unlink($nombre_fichero);
		}
		foreach (glob($this->getConfig()->getDir('ofw_tmp')."*.pdf") as $nombre_fichero) {
    	unlink($nombre_fichero);
		}
		foreach (glob($this->getConfig()->getDir('ofw_tmp')."*.png") as $nombre_fichero) {
    	unlink($nombre_fichero);
		}

		$this->getTemplate()->add('status', $status);
	}
}
