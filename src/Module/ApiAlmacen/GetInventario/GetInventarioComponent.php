<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiAlmacen\GetInventario;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\AlmacenService;
use Osumi\OsumiFramework\App\DTO\InventarioDTO;
use Osumi\OsumiFramework\App\Component\Api\InventarioItemList\InventarioItemListComponent;

class GetInventarioComponent extends OComponent {
  private ?AlmacenService $als = null;

  public string $status = 'ok';
  public ?InventarioItemListComponent $list = null;
  public int $pags = 0;
  public float $total_pvp = 0;
  public float $total_puc = 0;

  public function __construct() {
    parent::__construct();
    $this->als = inject(AlmacenService::class);
    $this->list = new InventarioItemListComponent();
  }

	/**
	 * Función para obtener el listado de elementos para el inventario.
	 *
	 * @param InventarioDTO $data Objeto con la información de los filtros para buscar en el inventario
	 * @return void
	 */
	public function run(InventarioDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$inventario = $this->als->getInventario($data);

			$this->list->list = $inventario['list'];
			$this->pags       = $inventario['pags'];
			$this->total_pvp  = $inventario['total_pvp'];
			$this->total_puc  = $inventario['total_puc'];
		}
	}
}
