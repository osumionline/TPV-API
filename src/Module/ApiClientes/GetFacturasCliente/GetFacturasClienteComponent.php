<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\GetFacturasCliente;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\Component\Model\FacturaList\FacturaListComponent;

class GetFacturasClienteComponent extends OComponent {
  private ?ClientesService $cs = null;

  public string $status = 'ok';
  public ?FacturaListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
    $this->list = new FacturaListComponent();
  }

	/**
	 * Función para obtener la lista de facturas de un cliente
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
			$this->list->list = $this->cs->getFacturasCliente($id);
		}
	}
}
