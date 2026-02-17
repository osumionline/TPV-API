<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCompras\SavePedido;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\ComprasService;
use Osumi\OsumiFramework\App\DTO\PedidoDTO;
use Osumi\OsumiFramework\App\Model\Pedido;
use Osumi\OsumiFramework\App\Model\LineaPedido;
use Osumi\OsumiFramework\App\Model\CodigoBarras;
use Osumi\OsumiFramework\App\Model\VistaPedido;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\HistoricoArticulo;
use Osumi\OsumiFramework\App\Utils\AppData;
use \DateTime;

class SavePedidoComponent extends OComponent {
  private ?ComprasService $cs = null;

  public string       $status  = 'ok';
  public string       $message = '';
  public string | int $id      = 'null';

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ComprasService::class);
  }

	/**
	 * Función para guardar un pedido
	 *
	 * @param PedidoDTO $data Objeto con toda la información sobre un pedido
	 * @return void
	 */
	public function run(PedidoDTO $data): void {
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

		if ($this->status === 'ok') {
			$pedido = Pedido::create();
			if (!is_null($data->id)) {
				$pedido = Pedido::findOne(['id' => $data->id]);
			}

			$recepcionado = $pedido->recepcionado;

			$cb_errors = [];
			if (!$recepcionado) {
				// Compruebo posibles códigos de barras
				foreach ($data->lineas as $linea) {
					if (!is_null($linea['codBarras'])) {
						$cb = CodigoBarras::findOne(['codigo_barras' => strval($linea['codBarras'])]);
						if (!is_null($cb)) {
							$cb_errors[] = $linea['codBarras'].' ('.urldecode($linea['nombreArticulo']).')';
						}
					}
				}
			}

			if (count($cb_errors) > 0) {
				$this->status = 'error';
			}

			if ($this->status === 'ok') {
				// Guardo datos del pedido
				$pedido->id_proveedor = $data->idProveedor;
				$pedido->metodo_pago  = $data->idMetodoPago;
				$pedido->tipo         = $data->tipo;
				$pedido->num          = !is_null($data->num) ? urldecode($data->num) : null;
				$pedido->importe      = $data->importe;
				$pedido->portes       = $data->portes;
				$pedido->descuento    = $data->descuento;
				if (!is_null($data->fechaPago)) {
          $dateTime  = DateTime::createFromFormat('d/m/Y', urldecode($data->fechaPago));
          $timestamp = $dateTime->getTimestamp();
    			$pedido->fecha_pago = date('Y-m-d H:i:s', $timestamp);
				}
				else {
					$pedido->fecha_pago = null;
				}
				if (!is_null($data->fechaPedido)) {
          $dateTime  = DateTime::createFromFormat('d/m/Y', urldecode($data->fechaPedido));
          $timestamp = $dateTime->getTimestamp();
    			$pedido->fecha_pedido = date('Y-m-d H:i:s', $timestamp);
				}
				else {
					$pedido->fecha_pedido = null;
				}
				$pedido->re           = $data->re;
				$pedido->europeo      = $data->ue;
				$pedido->faltas       = $data->faltas;
				$pedido->recepcionado = $data->recepcionado;
				if ($data->recepcionado && is_null($pedido->fecha_recepcionado)) {
					$pedido->fecha_recepcionado = date('Y-m-d H:i:s', time());
				}
				$pedido->observaciones = !is_null($data->observaciones) ? urldecode($data->observaciones) : null;

				$pedido->save();
				$data->id = $pedido->id;

				if (!$recepcionado) {
					// Borro líneas del pedido
					$this->cs->borrarLineasPedido($pedido->id);

					// Guardo nuevas líneas del pedido
					foreach ($data->lineas as $linea) {
						$ind = array_search($linea['iva'], $iva_list);
						$lp = LineaPedido::create();
            $lp->id_pedido       = $pedido->id;
						$lp->id_articulo     = $linea['idArticulo'];
						$lp->nombre_articulo = urldecode($linea['nombreArticulo']);
						$lp->codigo_barras   = $linea['codBarras'];
						$lp->unidades        = $linea['unidades'];
						$lp->palb            = $linea['palb'];
						$lp->puc             = $linea['puc'];
						$lp->pvp             = $linea['pvp'];
						$lp->margen          = $linea['margen'];
						$lp->iva             = $linea['iva'];
						$lp->re              = $data->re ? $re_list[$ind] : 0;
						$lp->descuento       = $linea['descuento'];
						$lp->save();

						// Si el pedido está recepcionado
						if ($pedido->recepcionado) {
							$articulo = Articulo::findOne(['id' => $linea['idArticulo']]);

							$stock_previo = $articulo->stock;

              $articulo->stock  = $articulo->stock + $linea['unidades'];
							$articulo->palb   = $linea['palb'];
							$articulo->puc    = $linea['puc'];
							$articulo->pvp    = $linea['pvp'];
							$articulo->margen = $linea['margen'];
							$articulo->iva    = $linea['iva'];
							$articulo->re     = $re_list[$ind];
							$articulo->save();

							// Histórico
							$ha = HistoricoArticulo::create();
              $ha->id_articulo  = $articulo->id;
							$ha->tipo         = HistoricoArticulo::FROM_PEDIDO;
							$ha->stock_previo = $stock_previo;
							$ha->diferencia   = $linea['unidades'];
							$ha->stock_final  = $articulo->stock;
							$ha->id_venta     = null;
							$ha->id_pedido    = $pedido->id;
							$ha->puc          = $articulo->puc;
							$ha->pvp          = $articulo->pvp;
							$ha->save();

							// Si viene un nuevo código de barras se lo creo
							if (!is_null($linea['codBarras'])) {
								$cb = CodigoBarras::create();
								$cb->id_articulo   = $linea['idArticulo'];
								$cb->codigo_barras = strval($linea['codBarras']);
								$cb->por_defecto   = false;
								$cb->save();
							}

							// TODO: falta event log
						}
					}
				}
			}

			// Borro todas las líneas de la vista del pedido
			$this->cs->borrarVistaPedido($pedido->id);

			// Guardo nueva vista
			foreach ($data->vista as $vista) {
				$vp = VistaPedido::create();
				$vp->id_pedido = $pedido->id;
				$vp->id_column = $vista['idColumn'];
				$vp->status    = $vista['status'];
				$vp->save();
			}

			// Actualizo PDFs adjuntos
			$this->cs->updatePedidoPDFs($pedido, $data->pdfs);
		}

    $this->id = empty($data->id) ? 'null' : $data->id;
	}
}
