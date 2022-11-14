<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\ProveedorDTO;
use OsumiFramework\App\Model\Proveedor;

#[OModuleAction(
	url: '/save-proveedor',
	services: ['proveedores']
)]
class saveProveedorAction extends OAction {
	/**
	 * Función para guardar un proveedor
	 *
	 * @param ProveedorDTO $data Objeto con toda la información sobre un proveedor
	 * @return void
	 */
	public function run(ProveedorDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$proveedor = new Proveedor();
			if (!is_null($data->getId())) {
				$proveedor->find(['id' => $data->getId()]);
			}

			$proveedor->set('nombre',        urldecode($data->getNombre()));
			$proveedor->set('direccion',     urldecode($data->getDireccion()));
			$proveedor->set('telefono',      urldecode($data->getTelefono()));
			$proveedor->set('email',         urldecode($data->getEmail()));
			$proveedor->set('web',           urldecode($data->getWeb()));
			$proveedor->set('observaciones', urldecode($data->getObservaciones()));

			$proveedor->save();

			if (!is_null($data->getFoto()) && !str_starts_with($data->getFoto(), 'http') && !str_starts_with($data->getFoto(), '/')) {
				$ruta = $proveedor->getRutaFoto();
				// Si ya tenía una imagen, primero la borro
				if (file_exists($ruta)) {
					unlink($ruta);
				}
				$this->proveedores_service->saveFoto($data->getFoto(), $proveedor);
			}

			$this->proveedores_service->updateProveedoresMarcas($proveedor->get('id'), $data->getMarcas());

			$data->setId( $proveedor->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
