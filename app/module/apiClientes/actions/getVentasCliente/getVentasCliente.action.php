<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\VentaListComponent;

#[OModuleAction(
	url: '/get-ventas-cliente',
	services: ['clientes']
)]
class getVentasClienteAction extends OAction {
	/**
	 * Función para obtener las ventas de un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$facturadas = $req->getParamString('facturadas');
		$id_factura_include = $req->getParamInt('idFacturaInclude');
		$venta_list_component = new VentaListComponent(['list' => []]);

		if (is_null($id) || is_null($facturadas)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$venta_list_component->setValue('list', $this->clientes_service->getVentasCliente($id, $facturadas, $id_factura_include));
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $venta_list_component);
	}
}
