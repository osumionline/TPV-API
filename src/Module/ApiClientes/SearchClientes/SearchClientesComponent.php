<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\SearchClientes;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\Component\Model\ClienteList\ClienteListComponent;

class SearchClientesComponent extends OComponent {
  private ?ClientesService $cs = null;

  public string $status = 'ok';
  public ?ClienteListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
    $this->list = new ClienteListComponent();
  }

	/**
	 * Función para buscar clientes
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$name = $req->getParamString('name');

		if (is_null($name)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->list->list = $this->cs->searchClientes($name);
		}
	}
}
