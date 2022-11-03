<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\InstallationDTO;

#[OModuleAction(
	url: '/save-installation',
	services: ['general']
)]
class saveInstallationAction extends OAction {
	/**
	 * Función guardar los datos iniciales de configuración
	 *
	 * @param InstallationDTO $data Objeto con la información sobre la instalación
	 * @return void
	 */
	public function run(InstallationDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$this->general_service->saveAppData($data);
		}

		$this->getTemplate()->add('status', $status);
	}
}
