<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Service\ArticulosService;

class FixMargenTask extends OTask {
	public function __toString() {
		return "FixMargen: tarea para actualizar todos los márgenes de los artículos";
	}

  private ?ArticulosService $articulos_service = null;

	function __construct() {
		$this->articulos_service = inject(ArticulosService::class);
	}

	public function run(array $options = []): void {
    $list = Articulo::all();
    $total = count($list);
    echo "Número de artículos: ".$total."\n";
    $cont = 0;

    foreach ($list as $articulo) {
      $cont++;
      $margen = $this->articulos_service->getMargen($articulo->puc, $articulo->pvp);

      echo $cont."/".$total." - NOMBRE: ".$articulo->nombre."\n";
      echo "PUC: ".$articulo->puc."\n";
      echo "PVP: ".$articulo->pvp."\n";
      echo "MARGEN ACTUAL: ".$articulo->margen."\n";
      echo "MARGEN CALCULADO: ".$margen."\n\n";

      $articulo->margen = $margen;
      $articulo->save();
    }

    echo "Finalizado recalculo de margenes.\n\n";
	}
}
