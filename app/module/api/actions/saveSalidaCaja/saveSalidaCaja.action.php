<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\DTO\SalidaCajaDTO;
use OsumiFramework\App\Model\PagoCaja;

#[OModuleAction(
	url: '/save-salida-caja'
)]
class saveSalidaCajaAction extends OAction {
	/**
	 * Función para guardar una salida de caja
	 *
	 * @param SalidaCajaDTO $data Objeto con los datos de la salida de caja
	 * @return void
	 */
	public function run(SalidaCajaDTO $data):void {
		$status  = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$pc = new PagoCaja();
			if (!is_null($data->getId())) {
				$pc->find(['id' => $data->getId()]);
			}
			$pc->set('concepto', urldecode($data->getConcepto()));
			$pc->set('descripcion', urldecode($data->getDescripcion()));
			$pc->set('importe', $data->getImporte());

			$pc->save();
		}

		$this->getTemplate()->add('status',  $status);
	}
}
