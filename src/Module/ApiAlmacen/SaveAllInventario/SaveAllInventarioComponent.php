<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiAlmacen\SaveAllInventario;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ArticulosService;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\CodigoBarras;
use Osumi\OsumiFramework\App\Model\HistoricoArticulo;
use Osumi\OsumiFramework\App\Component\Api\StatusIdMessageList\StatusIdMessageListComponent;

class SaveAllInventarioComponent extends OComponent {
  private ?ArticulosService $ars = null;

  public string $status = 'ok';
  public ?StatusIdMessageListComponent $result = null;

  public function __construct() {
    parent::__construct();
		$this->ars = inject(ArticulosService::class);
    $this->result = new StatusIdMessageListComponent();
  }

	/**
	 * Función para guardar masivamente los cambios en el inventario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$list = $req->getParam('list');
		$status_list = [];

		if (is_null($list) || !is_array($list)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			foreach ($list as $item) {
				$item_status = ['status' => 'ok', 'id' => $item['id'], 'message' => ''];
				$articulo = Articulo::findOne(['id' => $item['id']]);
				if (!is_null($articulo)) {
					// Si viene un código de barras compruebo que no esté siendo usado
					if (!is_null($item['codigoBarras'])) {
						$cb = CodigoBarras::findOne(['codigo_barras' => $item['codigoBarras']]);
						if (!is_null($cb)) {
							$item_status['status'] = 'error';
							$cb_articulo = $cb->getArticulo();
							$item_status['message'] = 'El código de barras "'.$item['codigoBarras'].'" pertenece al artículo "'.$cb_articulo->nombre.'".';
						}
					}

					// Si no viene código de barras o si es válido, sigo adelante
					if ($item_status['status'] == 'ok') {
						$stock_previo = $articulo->stock;
						$stock_final  = $item['stock'];
						$diferencia   = $stock_final - $stock_previo;

						$articulo->stock  = $item['stock'];
						$articulo->pvp    = $item['pvp'];
						$articulo->margen = $this->ars->getMargen($item['puc'], $item['pvp']);
						$articulo->save();

						if (!is_null($item['codigoBarras'])) {
							$cb = CodigoBarras::create();
							$cb->id_articulo   = $articulo->id;
							$cb->codigo_barras = $item['codigoBarras'];
							$cb->por_defecto   = false;
							$cb->save();
						}

						// Histórico
						$ha = HistoricoArticulo::create();
						$ha->id_articulo  = $articulo->id;
						$ha->tipo         = HistoricoArticulo::FROM_INVENTARIO_ALL;
						$ha->stock_previo = $stock_previo;
						$ha->diferencia   = $diferencia;
						$ha->stock_final  = $stock_final;
						$ha->id_venta     = null;
						$ha->id_pedido    = null;
						$ha->puc          = $articulo->puc;
						$ha->pvp          = $articulo->pvp;
						$ha->save();
					}
				}
				else {
					$item_status['status']  = 'error';
					$item_status['message'] = 'Artículo con id '.$item['id'].' no encontrado.';
				}
				$status_list[] = $item_status;
			}
			$this->result->list = $status_list;
		}
	}
}
