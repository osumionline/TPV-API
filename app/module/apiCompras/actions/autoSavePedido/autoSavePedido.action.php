<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\PedidoDTO;
use OsumiFramework\App\Model\Pedido;
use OsumiFramework\App\Model\LineaPedido;
use OsumiFramework\App\Model\VistaPedido;

#[OModuleAction(
	url: '/auto-save-pedido',
	services: ['compras']
)]
class autoSavePedidoAction extends OAction {
	/**
	 * Función para auto-guardar un pedido
	 *
	 * @param PedidoDTO $data Objeto con toda la información sobre un pedido
	 * @return void
	 */
	public function run(PedidoDTO $data):void {
		$pedido = new Pedido();
		if (!is_null($data->getId())) {
			$pedido->find(['id' => $data->getId()]);
		}

		// Guardo datos del pedido
		if (!is_null($data->getIdProveedor()) && $data->getIdProveedor() != -1) {
			$pedido->set('id_proveedor', $data->getIdProveedor());
		}
		else {
			$pedido->set('id_proveedor', null);
		}
		$pedido->set('metodo_pago', $data->getIdMetodoPago());
		$pedido->set('tipo', $data->getTipo());
		$pedido->set('num', $data->getNum());
		$pedido->set('importe', $data->getImporte());
		$pedido->set('portes', $data->getPortes());
		$pedido->set('descuento', $data->getDescuento());
		if (!is_null($data->getFechaPago())) {
			$pedido->set('fecha_pago', urldecode($data->getFechaPago()), '%e/%c/%Y');
		}
		else {
			$pedido->set('fecha_pago', null);
		}
		if (!is_null($data->getFechaPedido())) {
			$pedido->set('fecha_pedido', urldecode($data->getFechaPedido()), '%e/%c/%Y');
		}
		else {
			$pedido->set('fecha_pedido', null);
		}
		$pedido->set('re', $data->getRe());
		$pedido->set('europeo', $data->getUe());
		$pedido->set('faltas', $data->getFaltas());
		$pedido->set('recepcionado', $data->getRecepcionado());
		if (!is_null($data->getObservaciones())) {
			$pedido->set('observaciones', urldecode($data->getObservaciones()));
		}

		$pedido->save();
		$data->setId($pedido->get('id'));

		// Borro líneas del pedido
		$this->compras_service->borrarLineasPedido($pedido->get('id'));

		// Guardo nuevas líneas del pedido
		foreach ($data->getLineas() as $linea) {
			$lp = new LineaPedido();
			$lp->set('id_pedido', $pedido->get('id'));
			$lp->set('id_articulo', $linea['idArticulo']);
			$lp->set('nombre_articulo', urldecode($linea['nombreArticulo']));
			$lp->set('codigo_barras', $linea['codBarras']);
			$lp->set('unidades', $linea['unidades']);
			$lp->set('palb', $linea['palb']);
			$lp->set('pvp', $linea['pvp']);
			$lp->set('iva', $linea['iva']);
			$lp->set('re', $data->getRe() ? $linea['re'] : null);
			$lp->set('descuento', $linea['descuento']);
			$lp->save();
		}

		// Borro todas las líneas de la vista del pedido
		$this->compras_service->borrarVistaPedido($pedido->get('id'));

		// Guardo nueva vista
		foreach ($data->getVista() as $vista) {
			$vp = new VistaPedido();
			$vp->set('id_pedido', $pedido->get('id'));
			$vp->set('id_column', $vista['idColumn']);
			$vp->set('status', $vista['status']);
			$vp->save();
		}

		// Actualizo PDFs adjuntos
		$this->compras_service->updatePedidoPDFs($pedido, $data->getPdfs());

		$this->getTemplate()->add('id', $data->getId());
	}
}
