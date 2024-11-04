<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\NewBackup;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Tools\OTools;

class NewBackupComponent extends OComponent {
	/**
	 * Función para crear una nueva copia de seguridad
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		OTools::runTask('backup');
		exit;
	}
}
