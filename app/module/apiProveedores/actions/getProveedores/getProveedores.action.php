<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\ProveedorListComponent;

#[OModuleAction(
	url: '/get-proveedores',
	services: ['proveedores'],
	components: ['model/proveedor_list']
)]
class getProveedoresAction extends OAction {
	/**
	 * Función para obtener la lista de proveedores
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$proveedor_list_component = new ProveedorListComponent(['list' => $this->proveedores_service->getProveedores()]);

		$this->getTemplate()->add('list', $proveedor_list_component);
	}
}