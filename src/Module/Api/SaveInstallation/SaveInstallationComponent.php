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

	/**
	 * Función guardar los datos iniciales de configuración
	 *
	 * @param InstallationDTO $data Objeto con la información sobre la instalación
	 * @return void
	 */
	public function run(InstallationDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->gs->saveAppData($data);
		}
	}
}
