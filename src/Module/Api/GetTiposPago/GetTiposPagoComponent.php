<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetTiposPago;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\GeneralService;
use Osumi\OsumiFramework\App\Component\Model\TipoPagoList\TipoPagoListComponent;

class GetTiposPagoComponent extends OComponent {
  private ?GeneralService $gs = null;

  public ?TipoPagoListComponent $list = null;

  public function __construct() {
    parent::__construct();
    $this->gs = inject(GeneralService::class);
  }

	/**
	 * Función para obtener la lista de tipos de pago
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->list = new TipoPagoListComponent(['list' => $this->gs->getTiposPago()]);
	}
}
