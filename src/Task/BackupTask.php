<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\Tools\OTools;

class BackupTask extends OTask {
	public function __toString() {
		return "Backup: tarea para crear copias de seguridad";
	}

	public function run(array $options = []): void {
		$file = $this->getConfig()->getDir('ofw_export').$this->getConfig()->getDb('name').'.sql';
		if (file_exists($file)) {
			$this->getLog()->info("BACKUP: El archivo existía, será borrado.");
			unlink($file);
		}

		$ret = OTools::runOFWTask('backupDB', ['silent' => 'true']);
		if ($ret['status'] === 'error') {
			$this->getLog()->info("BACKUP: Ocurrió un error al crear la copia de seguridad.");
			exit;
		}

		if (file_exists($file)) {
			$this->getLog()->info("BACKUP: Nuevo archivo de backup creado.");
			$new_file = $this->getConfig()->getExtra('backup_folder').'backup_'.date('Ymd', time()).'.sql';
			if (rename($file, $new_file)) {
				$this->getLog()->info("BACKUP: Archivo de backup copiado correctamente.");
			}
			else {
				$this->getLog()->info("BACKUP: ERROR: no se pudo copiar el archivo.");
			}
		}
	}
}
