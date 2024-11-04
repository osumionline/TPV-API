<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\DeleteTipoPago;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\GeneralService;

class DeleteTipoPagoComponent extends OComponent {
  private ?GeneralService $gs = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->gs = inject(GeneralService::class);
  }

	/**
	 * Función para borrar un tipo de pago
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
			if (!$this->gs->deleteTipoPago($id)) {
				$this->status = 'error';
			}
		}
	}
}
