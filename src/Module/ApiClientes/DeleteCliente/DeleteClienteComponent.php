<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\DeleteCliente;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ClientesService;

class DeleteClienteComponent extends OComponent {
  private ?ClientesService $cs = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
  }

	/**
	 * Función para borrar un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			if (!$this->cs->deleteCliente($id)) {
				$this->status = 'error';
			}
		}
	}
}
