<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiEmpleados\GetEmpleados;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\EmpleadosService;
use Osumi\OsumiFramework\App\Component\Model\EmpleadoList\EmpleadoListComponent;

class GetEmpleadosComponent extends OComponent {
  private ?EmpleadosService $es = null;

  public ?EmpleadoListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->es = inject(EmpleadosService::class);
  }

	/**
	 * Función para obtener la lista de empleados
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->list = new EmpleadoListComponent(['list' => $this->es->getEmpleados()]);
	}
}
