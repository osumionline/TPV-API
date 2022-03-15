<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Model\Proveedor;
use OsumiFramework\App\Service\proveedoresService;
use OsumiFramework\App\DTO\ProveedorDTO;

#[ORoute(
	type: 'json',
	prefix: '/api-proveedores'
)]
class apiProveedores extends OModule {
  private ?proveedoresService $proveedores_service = null;

  function __construct() {
		$this->proveedores_service = new proveedoresService();
  }

  /**
	 * Función para obtener la lista de proveedores
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-proveedores')]
	public function getProveedores(ORequest $req): void {
		$list = $this->proveedores_service->getProveedores();

		$this->getTemplate()->addComponent('list', 'model/proveedor_list', ['list' => $list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para guardar un proveedor
	 *
	 * @param ProveedorDTO $data Objeto con toda la información sobre un proveedor
	 * @return void
	 */
	#[ORoute('/save-proveedor')]
	public function saveProveedor(ProveedorDTO $data): void {
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

			$this->proveedores_service->updateProveedoresMarcas($proveedor->get('id'), $data->getMarcas());

			$data->setId( $proveedor->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
