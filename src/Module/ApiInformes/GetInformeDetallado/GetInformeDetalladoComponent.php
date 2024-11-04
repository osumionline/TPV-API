<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiInformes\GetInformeDetallado;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\InformesService;
use Osumi\OsumiFramework\App\Component\Api\InformeMensualItemList\InformeMensualItemListComponent;

class GetInformeDetalladoComponent extends OComponent {
	private ?InformesService $is = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
		$this->is = inject(InformesService::class);
  }

	/**
	 * Función para obtener los datos del informe mensual
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$month = $req->getParamInt('month');
		$year  = $req->getParamInt('year');

		if (is_null($month) || is_null($year)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
		}
	}
}
