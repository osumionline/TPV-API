<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\GetReservas;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\Component\Model\ReservaList\ReservaListComponent;

class GetReservasComponent extends OComponent {
  private ?ClientesService $cs = null;

  public string $status = 'ok';
  public ?ReservaListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
  }

	/**
	 * Función para obtener la lista de reservas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->list = new ReservaListComponent(['list' => $this->cs->getReservas()]);
	}
}
