<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\LineaPedido;

class fixPedidosTask extends OTask {
	public function __toString() {
		return "fixPedidos: Nueva tarea fixPedidos";
	}

	public function run(array $options=[]): void {
		$db = new ODB();
		$sql = "SELECT * FROM `linea_pedido`";
		$db->query($sql);

		while ($res = $db->next()) {
			$lp = new LineaPedido();
			$lp->update($res);

			$articulo = $lp->getArticulo();
			$lp->set('nombre_articulo', $articulo->get('nombre'));
			$lp->save();
		}
	}
}
