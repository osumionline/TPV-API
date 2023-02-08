<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\PedidoDTO;
use OsumiFramework\App\Model\Pedido;
use OsumiFramework\App\Model\LineaPedido;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Model\VistaPedido;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Utils\AppData;

#[OModuleAction(
	url: '/save-pedido',
	services: ['compras']
)]
class savePedidoAction extends OAction {
	/**
	 * Función para guardar un pedido
	 *
	 * @param PedidoDTO $data Objeto con toda la información sobre un pedido
	 * @return void
	 */
	public function run(PedidoDTO $data):void {
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';
		$status  = 'ok';
		$message = '';

		if (!$data->isValid()) {
			$status = 'error';
		}

		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}

		$iva_list = $app_data->getIvaList();
		$re_list  = $app_data->getReList();

		if ($status == 'ok') {
			$pedido = new Pedido();
			if (!is_null($data->getId())) {
				$pedido->find(['id' => $data->getId()]);
			}

			$recepcionado = $pedido->get('recepcionado');

			$cb_errors = [];
			if (!$recepcionado) {
				// Compruebo posibles códigos de barras
				foreach ($data->getLineas() as $linea) {
					if (!is_null($linea['codBarras'])) {
						$cb = new CodigoBarras();
						if ($cb->find(['codigo_barras' => strval($linea['codBarras'])])) {
							array_push($cb_errors, $linea['codBarras'].' ('.urldecode($linea['nombreArticulo']).')');
						}
					}
				}
			}

			if (count($cb_errors) > 0) {
				$status = 'error';
			}

			if ($status == 'ok') {
				// Guardo datos del pedido
				$pedido->set('id_proveedor', $data->getIdProveedor());
				$pedido->set('metodo_pago', $data->getIdMetodoPago());
				$pedido->set('tipo', $data->getTipo());
				$pedido->set('num', !is_null($data->getNum()) ? urldecode($data->getNum()) : null);
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
				if ($data->getRecepcionado() && is_null($pedido->get('fecha_recepcionado'))) {
					$pedido->set('fecha_recepcionado', date('Y-m-d H:i:s', time()));
				}
				$pedido->set('observaciones', !is_null($data->getObservaciones()) ? urldecode($data->getObservaciones()) : null);

				$pedido->save();
				$data->setId($pedido->get('id'));

				if (!$recepcionado) {
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
						$ind = array_search($linea['iva'], $iva_list);
						$lp->set('re', $data->getRe() ? $re_list[$ind] : 0);
						$lp->set('descuento', $linea['descuento']);
						$lp->save();

						// Si el pedido está recepcionado
						if ($pedido->get('recepcionado')) {
							$articulo = new Articulo();
							$articulo->find(['id' => $linea['idArticulo']]);
							$articulo->set('stock', $articulo->get('stock') + $linea['unidades']);
							$articulo->set('palb', $linea['palb']);
							$articulo->set('pvp', $linea['pvp']);
							$articulo->set('iva', $linea['iva']);
							$ind = array_search($linea['iva'], $iva_list);
							$articulo->set('re', $re_list[$ind]);
							$articulo->save();

							// Si viene un nuevo código de barras se lo creo
							if (!is_null($linea['codBarras'])) {
								$cb = new CodigoBarras();
								$cb->set('id_articulo', $linea['idArticulo']);
								$cb->set('codigo_barras', strval($linea['codBarras']));
								$cb->set('por_defecto', false);
								$cb->save();
							}

							// TODO: falta event log
						}
					}
				}
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
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('id',      empty($data->getId()) ? 'null' : $data->getId());
		$this->getTemplate()->add('message', $message);
	}
}
