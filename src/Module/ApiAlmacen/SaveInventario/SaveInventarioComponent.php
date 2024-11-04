<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiAlmacen\SaveInventario;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\ArticulosService;
use Osumi\OsumiFramework\App\DTO\InventarioItemDTO;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\CodigoBarras;
use Osumi\OsumiFramework\App\Model\HistoricoArticulo;
use Osumi\OsumiFramework\App\Component\Api\StatusIdMessage\StatusIdMessageComponent;

class SaveInventarioComponent extends OComponent {
	private ?ArticulosService $ars = null;

  public ?StatusIdMessageComponent $result = null;

  public function __construct() {
    parent::__construct();
		$this->ars = inject(ArticulosService::class);
  }

	/**
	 * Función para actualizar un artículo desde el inventario
	 *
	 * @param InventarioItemDTO $data Objeto con información sobre un artículo.
	 * @return void
	 */
	public function run(InventarioItemDTO $data): void {
		$status       = 'ok';
		$id           = 'null';
		$message      = '';
		$this->result = new StatusIdMessageComponent(['status' => ['status' => 'error', 'id' => $id, 'message' => $message]]);

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status === 'ok') {
			$articulo = Articulo::findOne(['id' => $data->id]);
			if (!is_null($articulo)) {
				$id = $data->id;

				// Si viene un código de barras compruebo que no esté siendo usado
				if (!is_null($data->codigo_barras)) {
					$cb = CodigoBarras::findOne(['codigo_barras' => $data->codigo_barras]);
					if (!is_null($cb)) {
						$cb_articulo = $cb->getArticulo();
						$status  = 'error';
						$message = 'El código de barras "' . $data->codigo_barras . '" pertenece al artículo "' . $cb_articulo->nombre . '".';
					}
				}

				// Si no viene código de barras o si es válido, sigo adelante
				if ($status == 'ok') {
					$stock_previo = $articulo->stock;
					$stock_final  = $data->stock;
					$diferencia   = $stock_final - $stock_previo;

					$articulo->stock  = $data->stock;
					$articulo->pvp    = $data->pvp;
					$articulo->margen = $this->ars->getMargen($data->puc, $data->pvp);
					$articulo->save();

					if (!is_null($data->codigo_barras)) {
						$cb = CodigoBarras::create();
						$cb->id_articulo   = $articulo->id;
						$cb->codigo_barras = $data->codigo_barras;
						$cb->por_defecto   = false;
						$cb->save();
					}

					// Histórico
					$ha = HistoricoArticulo::create();
					$ha->id_articulo  = $articulo->id;
					$ha->tipo         = HistoricoArticulo::FROM_INVENTARIO;
					$ha->stock_previo = $stock_previo;
					$ha->diferencia   = $diferencia;
					$ha->stock_final  = $stock_final;
					$ha->id_venta     = null;
					$ha->id_pedido    = null;
					$ha->puc          = $articulo->puc;
					$ha->pvp          = $articulo->pvp;
					$ha->save();
				}
			} else {
				$status  = 'error';
				$message = 'Artículo con id ' . $data->id . ' no encontrado.';
			}

			$this->result->status = ['status' => $status, 'id' => $id, 'message' => $message];
		}
	}
}
