<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Model\Pedido;

class FixPedidosTask extends OTask {
	public function __toString() {
		return "fixPedidos: tarea para arreglar los puc y margenes de los articulos de los pedidos";
	}

	public function run(array $options=[]): void {
		$pedidos = Pedido::all();

		foreach ($pedidos as $pedido) {
			echo "Actualizo Pedido {$pedido->id}\n";
			$lineas = $pedido->getLineas();

			foreach ($lineas as $linea) {
				$articulo = $linea->getArticulo();
				echo "  Articulo {$articulo->nombre}\n";
				$linea->puc    = $articulo->puc;
				$linea->margen = $articulo->margen;
				$linea->save();
			}
		}
	}
}
