<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\MarcaDTO;
use OsumiFramework\App\Model\Marca;

#[OModuleAction(
	url: '/save-marca',
	services: ['marcas']
)]
class saveMarcaAction extends OAction {
	/**
	 * Función para guardar una marca
	 *
	 * @param MarcaDTO $data Objeto con toda la información sobre una marca
	 * @return void
	 */
	public function run(MarcaDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$marca = new Marca();
			if (!is_null($data->getId())) {
				$marca->find(['id' => $data->getId()]);
			}

			$marca->set('nombre',        urldecode($data->getNombre()));
			$marca->set('direccion',     urldecode($data->getDireccion()));
			$marca->set('telefono',      urldecode($data->getTelefono()));
			$marca->set('email',         urldecode($data->getEmail()));
			$marca->set('web',           urldecode($data->getWeb()));
			$marca->set('observaciones', urldecode($data->getObservaciones()));

			$marca->save();

			if (!is_null($data->getFoto()) && !str_starts_with($data->getFoto(), 'http')) {
				$ruta = $marca->getRutaFoto();
				// Si ya tenía una imagen, primero la borro
				if (file_exists($ruta)) {
					unlink($ruta);
				}
				$this->marcas_service->saveFoto($data->getFoto(), $marca);
			}

			$data->setId( $marca->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
