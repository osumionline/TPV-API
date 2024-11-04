<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\GetVentasCliente;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\Component\Model\VentaList\VentaListComponent;

class GetVentasClienteComponent extends OComponent {
  private ?ClientesService $cs = null;

  public string $status = 'ok';
  public ?VentaListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
    $this->list = new VentaListComponent();
  }

	/**
	 * Función para obtener las ventas de un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id                 = $req->getParamInt('id');
		$id_factura_include = $req->getParamInt('idFacturaInclude');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->list->list = $this->cs->getVentasCliente($id, $id_factura_include);
		}
	}
}
