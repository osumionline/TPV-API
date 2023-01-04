<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\DTO\TipoPagoDTO;
use OsumiFramework\App\Model\TipoPago;

#[OModuleAction(
	url: '/save-tipo-pago',
	services: ['general']
)]
class saveTipoPagoAction extends OAction {
	/**
	 * Función para guardar un tipo de pago
	 *
	 * @param TipoPagoDTO $data Objeto con toda la información sobre un tipo de pago
	 * @return void
	 */
	public function run(TipoPagoDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$tp = new TipoPago();
			if (!is_null($data->getId())) {
				$tp->find(['id' => $data->getId()]);
			}
			$orden = $data->getOrden();
			if (is_null($orden)) {
				$orden = $this->general_service->getNewTipoPagoOrden();
			}
			$tp->set('nombre',      urldecode($data->getNombre()));
			$tp->set('slug',        OTools::slugify(urldecode($data->getNombre())));
			$tp->set('afecta_caja', $data->getAfectaCaja());
			$tp->set('orden',       $orden);
			$tp->set('fisico',      $data->getFisico());

			$tp->save();

			if (!is_null($data->getFoto()) && !str_starts_with($data->getFoto(), 'http')) {
				$ruta = $tp->getRutaFoto();
				// Si ya tenía una imagen, primero la borro
				if (file_exists($ruta)) {
					unlink($ruta);
				}
				$this->general_service->saveFoto($data->getFoto(), $tp);
			}

			$data->setId( $tp->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
