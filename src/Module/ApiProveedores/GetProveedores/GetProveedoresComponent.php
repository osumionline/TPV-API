<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiProveedores\GetProveedores;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ProveedoresService;
use Osumi\OsumiFramework\App\Component\Model\ProveedorList\ProveedorListComponent;

class GetProveedoresComponent extends OComponent {
  private ?ProveedoresService $ps = null;

  public ?ProveedorListComponent $list = null;

  public function __construct() {
    parent::__construct();
    $this->ps = inject(ProveedoresService::class);
  }

	/**
	 * Función para obtener la lista de proveedores
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->list = new ProveedorListComponent(['list' => $this->ps->getProveedores()]);
	}
}
