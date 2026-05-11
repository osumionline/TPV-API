<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiInformes\GetInformeVentas;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\InformesService;

class GetInformeVentasComponent extends OComponent {
	private ?InformesService $is = null;

  public string $status = 'ok';
	public string $data   = '[]';

  public function __construct() {
    parent::__construct();
		$this->is = inject(InformesService::class);
  }

	/**
	 * Función para obtener los datos del informe de ventas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
    $id_category = $req->getParamInt('idCategory');
		$month       = $req->getParamInt('month');
		$year        = $req->getParamInt('year');

		if (is_null($id_category) || is_null($month) || is_null($year)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->data = json_encode($this->is->getInformeVentas($id_category, $month, $year));
		}
	}
}
