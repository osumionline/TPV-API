<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiAlmacen\ExportInventario;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\AlmacenService;
use Osumi\OsumiFramework\App\Service\ArticulosService;
use Osumi\OsumiFramework\App\DTO\InventarioDTO;

class ExportInventarioComponent extends OComponent {
	private ?AlmacenService $als = null;
	private ?ArticulosService $ars = null;

  public function __construct() {
    $this->als = inject(AlmacenService::class);
		$this->ars = inject(ArticulosService::class);
  }

	/**
	 * Función para exportar los datos del inventario
	 *
	 * @param InventarioDTO $data Objeto con la información de los filtros para buscar en el inventario
	 * @return void
	 */
	public function run(InventarioDTO $data): void {
		$data->num  = null;
		$inventario = $this->als->getInventario($data);

		$lines = ["Localizador;Marca;Referencia;Nombre;Stock;PVP;Margen"];

		foreach ($inventario['list'] as $item) {
			$lines[] = implode(';', [
				$item['localizador'],
				'"'.$item['marca'].'"',
				'"'.$item['referencia'].'"',
				'"'.$item['nombre'].'"',
				$item['stock'],
				number_format($item['pvp'], 2, ','),
				number_format($this->ars->getMargen($item['puc'], $item['pvp']), 2, ',')
			]);
		}

		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="inventario.csv"');

		echo implode("\n", $lines);
		exit();
	}
}
