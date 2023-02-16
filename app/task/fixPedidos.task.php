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
		$sql = "SELECT * FROM `pedido`";
		$db->query($sql);

		while ($res = $db->next()) {
			$pedido = new Pedido();
			$pedido->update($res);

			echo "Actualizo Pedido ".$pedido->get('id')."\n";
			$lineas = $pedido->getLineas();

			foreach ($lineas as $linea) {
				$articulo = $linea->getArticulo();
				echo "  Articulo ".$articulo->get('nombre')."\n";
				$linea->set('puc', $articulo->get('puc'));
				$linea->set('margen', $articulo->get('margen'));
				$linea->save();
			}
		}
	}
}
