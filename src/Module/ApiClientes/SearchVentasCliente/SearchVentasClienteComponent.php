<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\SearchVentasCliente;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\Component\Model\VentaList\VentaListComponent;

class SearchVentasClienteComponent extends OComponent {
  private ?ClientesService $cs = null;

  public string $status = 'ok';
  public ?VentaListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
    $this->list = new VentaListComponent();
  }

	/**
	 * Función para buscar entre las ventas de un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id    = $req->getParamInt('id');
		$month = $req->getParamInt('month');
    $year  = $req->getParamInt('year');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->list->list = $this->cs->searchVentasCliente($id, $month, $year);
		}
	}
}
