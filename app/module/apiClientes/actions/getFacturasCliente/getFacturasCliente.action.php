<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\FacturaListComponent;

#[OModuleAction(
	url: '/get-facturas-cliente',
	services: ['clientes']
)]
class getFacturasClienteAction extends OAction {
	/**
	 * Función para obtener la lista de facturas de un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$factura_list_component = new FacturaListComponent(['list' => []]);

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$factura_list_component->setValue('list', $this->clientes_service->getFacturasCliente($id));
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $factura_list_component);
	}
}
