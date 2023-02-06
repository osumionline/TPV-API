<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Reserva;

#[OModuleAction(
	url: '/delete-reserva'
)]
class deleteReservaAction extends OAction {
	/**
	 * Función para borrar una reserva
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$reserva = new Reserva();
			if ($reserva->find(['id' => $id])) {
				$lineas = $reserva->getLineas();
				foreach ($lineas as $linea) {
					$articulo = $linea->getArticulo();
					$articulo->set('stock', $articulo->get('stock') + $linea->get('unidades'));
					$articulo->save();
				}
				// recupero el stock
				$reserva->deleteFull();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
