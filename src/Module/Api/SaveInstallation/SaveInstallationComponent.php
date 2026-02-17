<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveInstallation;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\GeneralService;
use Osumi\OsumiFramework\App\DTO\InstallationDTO;

class SaveInstallationComponent extends OComponent {
  private ?GeneralService $gs = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->gs = inject(GeneralService::class);
  }

  private function isValid(InstallationDTO $data): bool {
		return (
			$data->nombre !== '' &&
			$data->nombre_comercial !== '' &&
			$data->cif !== '' &&
			$data->logo !== '' &&
			$data->color !== '' &&
			$data->tipo_iva !== '' &&
			count($data->iva_list) > 0 &&
			(
				!$data->venta_online ||
				(
					$data->venta_online &&
					$data->url_api !== '' &&
					$data->secret_api !== ''
				)
			)
		);
	}

	/**
	 * Función guardar los datos iniciales de configuración
	 *
	 * @param InstallationDTO $data Objeto con la información sobre la instalación
	 * @return void
	 */
	public function run(InstallationDTO $data): void {
		if (!$this->isValid($data)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->gs->saveAppData($data);
		}
	}
}
