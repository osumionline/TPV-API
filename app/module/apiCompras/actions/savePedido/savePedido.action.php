<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\PedidoDTO;
use OsumiFramework\App\Model\Pedido;

#[OModuleAction(
	url: '/save-pedido'
)]
class savePedidoAction extends OAction {
	/**
	 * Función para guardar un pedido
	 *
	 * @param PedidoDTO $data Objeto con toda la información sobre un pedido
	 * @return void
	 */
	public function run(PedidoDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$pedido = new Pedido();
			if (!is_null($data->getId())) {
				$pedido->find(['id' => $data->getId()]);
			}
			
			$pedido->set('id_proveedor', $data->getIdProveedor());
			$pedido->set('albaran_factura', $data->getAlbaranFactura() === 'albaran');
			$pedido->set('num_albaran_factura', $data->getNumAlbaranFactura());
			$pedido->set('importe', $data->getImporte());
			$pedido->set('portes', $data->getPortes());
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
