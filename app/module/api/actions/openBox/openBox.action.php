<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Caja;

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
		$caja->set('apertura',         date('Y-m-d H:i:s', time()));
		$caja->set('cierre',           null);
		$caja->set('ventas',           null);
		$caja->set('beneficios',       null);
		$caja->set('venta_efectivo',   null);
		$caja->set('venta_otros',      null);
		$caja->set('importe_apertura', null);
		$caja->set('importe_cierre',   null);

		$caja->save();

		$previous_id = $caja->get('id') -1;
		$previous_caja = new Caja();
		if ($previous_caja->find(['id' => $previous_id])) {
			// La anterior caja se cierra en el momento en que la nueva se abre
			$previous_caja->set('cierre', $caja->get('apertura', 'Y-m-d H:i:s'));

			// Al cerrar la anterior caja actualizamos los valores comprobando las ventas
			$datos = $this->general_service->getVentasDia($previous_caja);

			$previous_caja->set('ventas', $datos['ventas']);
			$previous_caja->set('beneficios', $datos['beneficios'] - $this->general_service->getPagosCajaDia($previous_caja));
			$previous_caja->set('venta_efectivo', $datos['venta_efectivo']);
			$previous_caja->set('venta_otros', $datos['venta_otros']);
			$previous_caja->set('importe_cierre', $previous_caja->get('importe_apertura') + $datos['venta_efectivo']);

			$previous_caja->save();

			// Al abrir una caja nueva el importe que debería haber en caja es el que había al cerrar la anterior
			$caja->set('importe_apertura', $previous_caja->get('importe_cierre'));
			$caja->save();
		}

		$this->getTemplate()->add('status', $status);
	}
}
