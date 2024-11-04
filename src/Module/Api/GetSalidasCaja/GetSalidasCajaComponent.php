<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetSalidasCaja;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\GeneralService;
use Osumi\OsumiFramework\App\Component\Model\PagoCajaList\PagoCajaListComponent;

class GetSalidasCajaComponent extends OComponent {
  private ?GeneralService $gs = null;

  public string $status = 'ok';
  public ?PagoCajaListComponent $list = null;

  public function __construct() {
    parent::__construct();
    $this->gs = inject(GeneralService::class);
    $this->list = new PagoCajaListComponent();
  }

	/**
	 * Función para obtener las salidas de caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$modo   = $req->getParamString('modo');
		$fecha  = $req->getParamString('fecha');
		$desde  = $req->getParamString('desde');
		$hasta  = $req->getParamString('hasta');

		if (is_null($fecha) && is_null($desde) && is_null($hasta)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->list->list = $this->gs->getSalidasCaja($modo, $fecha, $desde, $hasta);
		}
	}
}
