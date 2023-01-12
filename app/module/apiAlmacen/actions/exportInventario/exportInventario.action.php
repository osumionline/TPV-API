<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\InventarioDTO;

#[OModuleAction(
	url: '/export-inventario',
	services: ['almacen', 'articulos']
)]
class exportInventarioAction extends OAction {
	/**
	 * Función para exportar los datos del inventario
	 *
	 * @param InventarioDTO $data Objeto con la información de los filtros para buscar en el inventario
	 * @return void
	 */
	public function run(InventarioDTO $data): void {
		$data->setNum(null);
		$inventario = $this->almacen_service->getInventario($data);

		$lines = ["Localizador;Marca;Referencia;Nombre;Stock;PVP;Margen"];

		foreach ($inventario['list'] as $item) {
			array_push($lines, implode(';',[
				$item['localizador'],
				'"'.$item['marca'].'"',
				'"'.$item['referencia'].'"',
				'"'.$item['nombre'].'"',
				$item['stock'],
				number_format($item['pvp'], 2, ','),
				number_format($this->articulos_service->getMargen($item['puc'], $item['pvp']), 2, ',')
			]));
		}

		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="inventario.csv"');

		echo implode("\n", $lines);
		exit;
	}
}
