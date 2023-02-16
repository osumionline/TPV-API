<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Pedido;
use OsumiFramework\App\Service\articulosService;

class fixPedidosTask extends OTask {
	private ?articulosService $articulos_service = null;

	function __construct() {
		$this->articulos_service = new articulosService();
	}

	public function __toString() {
		return "fixPedidos: tarea para arreglar los puc y margenes de los articulos de los pedidos";
	}

	public function run(array $options=[]): void {
		$db = new ODB();
		$sql = "SELECT * FROM `pedido` WHERE `recepcionado` = 1";
		$db->query($sql);

		while ($res = $db->next()) {
			$pedido = new Pedido();
			$pedido->update($res);

			echo "Actualizo Pedido ".$pedido->get('id')."\n";
			$lineas = $pedido->getLineas();

			foreach ($lineas as $linea) {
				$articulo = $linea->getArticulo();
				echo "  Articulo ".$articulo->get('nombre')."\n";
				echo "    IVA: ".$articulo->get('iva')." - RE: ".$articulo->get('re')."\n";
				echo "    PUC original: ".$articulo->get('puc')."\n";

				$total_iva = $articulo->get('iva') + $articulo->get('re');
				$nuevo_puc = floatval(number_format($articulo->get('palb') * (($total_iva + 100) / 100), 2, '.', ''));
				$articulo->set('puc', $nuevo_puc);
				echo "    Nuevo PUC: ".$nuevo_puc."\n";
				echo "    Margen original: ".$articulo->get('margen')."\n";

				$nuevo_margen = $this->articulos_service->getMargen($articulo->get('puc'), $articulo->get('pvp'));
				$articulo->set('margen', floatval(number_format($nuevo_margen, 2, '.', '')));
				echo "    Nuevo margen: ".$articulo->get('margen')."\n";
				$articulo->save();
			}
		}
	}
}
