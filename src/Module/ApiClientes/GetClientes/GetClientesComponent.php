<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\GetClientes;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\Component\Model\ClienteList\ClienteListComponent;

class GetClientesComponent extends OComponent {
  private ?ClientesService $cs = null;

  public string $status = 'ok';
  public ?ClienteListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
  }

	/**
	 * Función para obtener la lista de clientes
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->list = new ClienteListComponent(['list' => $this->cs->getClientes()]);
	}
}
