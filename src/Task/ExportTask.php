<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Service\ExportService;

class ExportTask extends OTask {
	private ?ExportService $export_service = null;

	public function __construct() {
		$this->export_service = inject(ExportService::class);
	}

	public function __toString(): string {
		return 'export: Genera un paquete .otpv para migrar los datos a Osumi TPV Client.';
	}

	public function run(array $options = []): void {
		$keep_temp = $this->getBooleanOption($options, 'keep-temp');

		echo "\nExportación de Osumi TPV para Osumi TPV Client\n";
		echo "================================================\n\n";

		if ($keep_temp) {
			echo "AVISO: Se conservará el directorio temporal al finalizar.\n\n";
		}

		$result = $this->export_service->export(
			$keep_temp,
			static function(string $message): void {
				echo '[' . date('H:i:s') . '] ' . $message . "\n";
			}
		);

		echo "\n";

		if ($result['status'] === 'error') {
			echo "ERROR: No se ha podido completar la exportación.\n";

			foreach ($result['errors'] as $error) {
				echo '- ' . $error['message'] . "\n";
			}

			if (!is_null($result['workspacePath'])) {
				echo "\nDirectorio temporal conservado:\n";
				echo $result['workspacePath'] . "\n";
			}

			echo "\n";
			exit(1);
		}

		echo "Exportación completada.\n";
		echo 'Estado: ' . $result['status'] . "\n";
		echo 'Archivo: ' . $result['packagePath'] . "\n";
		echo 'Tamaño: ' . $this->formatBytes($result['packageSize']) . "\n";
		echo 'Archivos incluidos: ' . $result['includedFileCount'] . "\n";
		echo 'Archivos ausentes: ' . $result['missingFileCount'] . "\n";
		echo 'Archivos ilegibles: ' . $result['unreadableFileCount'] . "\n";
		echo 'Advertencias: ' . count($result['warnings']) . "\n";

		if (!is_null($result['workspacePath'])) {
			echo "\nDirectorio temporal conservado:\n";
			echo $result['workspacePath'] . "\n";
		}

		echo "\n";
	}

	private function getBooleanOption(array $options, string $name): bool {
		if (!array_key_exists($name, $options)) {
			return false;
		}

		$value = $options[$name];
		if (is_bool($value)) {
			return $value;
		}
		if (is_int($value)) {
			return $value === 1;
		}
		if (!is_string($value)) {
			return false;
		}

		$parsed_value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		return $parsed_value ?? false;
	}

	private function formatBytes(int $bytes): string {
		if ($bytes < 1024) {
			return $bytes . ' B';
		}

		$units = ['KB', 'MB', 'GB', 'TB'];
		$value = $bytes / 1024;
		$unit_index = 0;

		while ($value >= 1024 && $unit_index < count($units) - 1) {
			$value /= 1024;
			$unit_index++;
		}

		return number_format($value, 2, ',', '.') . ' ' . $units[$unit_index];
	}
}
