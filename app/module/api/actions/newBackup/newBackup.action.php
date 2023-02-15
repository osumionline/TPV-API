<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Tools\OTools;

#[OModuleAction(
	url: '/new-backup'
)]
class newBackupAction extends OAction {
	/**
	 * Función para crear una nueva copia de seguridad
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		OTools::runTask('backup');
		exit;
	}
}
