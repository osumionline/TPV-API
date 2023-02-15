<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Articulo;

class fixpucsTask extends OTask {
	public function __toString() {
		return "fixpucs: Tarea para corregir todos los puc";
	}

	public function run(array $options=[]): void {
		$db = new ODB();
		$sql = "SELECT * FROM `articulo` WHERE `palb` > 0 AND `puc` = 0";
		$db->query($sql);

		while ($res = $db->next()) {
			$articulo = new Articulo();
			$articulo->update($res);
			echo "ARTICULO: ".$articulo->get('nombre')."\n";
			$puc = number_format( $articulo->get('palb') * ((($articulo->get('iva') + $articulo->get('re')) + 100) / 100) , 2, '.', '');
			echo "PALB: ".$articulo->get('palb')." - IVA: ".$articulo->get('iva')." - RE: ".$articulo->get('re')." - PUC: ".floatval($puc)."\n";
			$articulo->set('puc', floatval($puc));
			$articulo->save();
		}
	}
}
