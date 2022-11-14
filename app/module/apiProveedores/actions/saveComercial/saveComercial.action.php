<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\ComercialDTO;
use OsumiFramework\App\Model\Comercial;

#[OModuleAction(
	url: '/save-comercial'
)]
class saveComercialAction extends OAction {
	/**
	 * Función para guardar un comercial de un proveedor
	 *
	 * @param ComercialDTO $data Objeto con toda la información sobre un comercial
	 * @return void
	 */
	public function run(ComercialDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$comercial = new Comercial();
			if (!is_null($data->getId())) {
				$comercial->find(['id' => $data->getId()]);
			}

			$comercial->set('id_proveedor',  $data->getIdProveedor());
			$comercial->set('nombre',        urldecode($data->getNombre()));
			$comercial->set('telefono',      urldecode($data->getTelefono()));
			$comercial->set('email',         urldecode($data->getEmail()));
			$comercial->set('observaciones', urldecode($data->getObservaciones()));

			$comercial->save();

			$data->setId( $comercial->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
