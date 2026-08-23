<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Closure;
use DateTimeImmutable;
use DateTimeZone;
use FilesystemIterator;
use JsonException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use Throwable;
use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;

class ExportService extends OService {
<<<<<<< HEAD
	private const FORMAT_VERSION = 2;
	private const SCHEMA_VERSION = 'legacy-2026-07';
	private const PACKAGE_PREFIX = 'osumi-tpv-migration-';
	private const WORKSPACE_PREFIX = 'osumi-tpv-export-';
	private const EXPORTED_PLUGIN_FIELDS = [
		'email_smtp' => [
			'host',
			'port',
			'secure',
			'user',
			'pass'
		],
		'ticketbai' => [
			'token',
			'nif'
		]
	];

=======
	private const FORMAT_VERSION = 1;
	private const SCHEMA_VERSION = 'legacy-2026-07';
	private const PACKAGE_PREFIX = 'osumi-tpv-migration-';
	private const WORKSPACE_PREFIX = 'osumi-tpv-export-';
>>>>>>> 5839a412eb104a058a66212a692db6c5bb96f5a1
	private const EXPECTED_TABLES = [
		'articulo',
		'articulo_etiqueta',
		'articulo_etiqueta_web',
		'articulo_foto',
		'caducidad',
		'caja',
		'caja_tipo',
		'categoria',
		'cliente',
		'codigo_barras',
		'comercial',
		'empleado',
		'empleado_rol',
		'etiqueta',
		'etiqueta_web',
		'factura',
		'factura_venta',
		'foto',
		'historico_almacen',
		'historico_articulo',
		'linea_pedido',
		'linea_reserva',
		'linea_venta',
		'marca',
		'pago_caja',
		'pdf_pedido',
		'pedido',
		'proveedor',
		'proveedor_marca',
		'reserva',
		'tipo_pago',
		'venta',
		'vista_pedido'
	];

	private ?Closure $progress_callback = null;
	private array $warnings = [];
	private array $errors = [];
	private array $file_inventory = [];
	private array $copied_package_paths = [];
	private int $included_file_count = 0;
	private int $missing_file_count = 0;
	private int $unreadable_file_count = 0;
	private int $optional_not_present_count = 0;

	/**
	 * Genera un paquete de migración .otpv.
	 *
	 * @param bool          $keep_temp Conserva el workspace temporal para depuración.
	 * @param callable|null $progress  Callback que recibe los mensajes de progreso.
	 *
	 * @return array Resultado estructurado de la exportación.
	 */
	public function export(bool $keep_temp = false, ?callable $progress = null): array {
		$this->resetState($progress);

		$started_at = $this->getUtcDate();
		$workspace_path = null;
		$package_dir = null;
		$package_path = null;
		$package_file_name = self::PACKAGE_PREFIX . date('Y-m-d-His') . '.otpv';
		$package_size = 0;
		$database_info = [
			'tables'          => 0,
			'expectedTables'  => count(self::EXPECTED_TABLES),
			'missingTables'   => [],
			'extraTables'     => [],
			'tableRows'       => [],
			'totalRows'       => 0,
			'dumpSize'        => 0
		];

		try {
			$this->progress('Comprobando los requisitos del sistema...');
			$requirements = $this->checkRequirements();
			$package_file_name = $this->createAvailablePackageFileName();

			$this->progress('Creando el directorio temporal de trabajo...');
			$workspace_path = $this->createWorkspace();
			$package_dir = $workspace_path . 'package/';
			$this->createDirectory($package_dir, 0700);

			$this->progress('Leyendo la versión de la aplicación y de MariaDB...');
			$application_version = $this->getApplicationVersion();
			$framework_version = OTools::getVersion();
			$database_version = $this->getDatabaseVersion();
			$database_engine = stripos($database_version, 'mariadb') !== false ? 'MariaDB' : 'MySQL';

			$this->progress('Comprobando la estructura y el número de registros de la base de datos...');
			$database_info = $this->getDatabaseInformation();
			if (count($database_info['missingTables']) > 0) {
				throw new RuntimeException(
					'Faltan tablas obligatorias del esquema legacy: ' . implode(', ', $database_info['missingTables']) . '.'
				);
			}
			if (count($database_info['extraTables']) > 0) {
				$this->addWarning(
					'EXTRA_DATABASE_TABLES',
					'La base de datos contiene tablas adicionales que también quedarán incluidas en el dump.',
					['tables' => $database_info['extraTables']]
				);
			}

			$this->progress('Generando el dump completo de MariaDB...');
			$dump_info = $this->createDatabaseDump(
				$workspace_path,
				$package_dir,
				$requirements['dumpExecutable'],
				$requirements['dumpToolVersion'],
				$database_info['totalRows']
			);
			$database_info['dumpSize'] = $dump_info['size'];

			$this->progress('Copiando app_data.json...');
			$this->copyAppData($package_dir);

<<<<<<< HEAD
			$this->progress('Exportando la configuración de plugins...');
			$this->exportPluginConfig($package_dir);

=======
>>>>>>> 5839a412eb104a058a66212a692db6c5bb96f5a1
			$this->progress('Copiando el logo del negocio...');
			$this->copyLogo($package_dir);

			$this->progress('Recopilando las fotografías registradas...');
			$this->collectPhotos($package_dir);

			$this->progress('Recopilando las imágenes de marcas...');
			$this->collectBrands($package_dir);

			$this->progress('Recopilando las imágenes de proveedores...');
			$this->collectProviders($package_dir);

			$this->progress('Recopilando los iconos de tipos de pago...');
			$this->collectPaymentTypeIcons($package_dir);

			$this->progress('Recopilando los PDF de pedidos...');
			$this->collectOrderPdfs($package_dir);

			$this->progress('Generando manifest.json...');
			$manifest = $this->buildManifest(
				$application_version,
				$framework_version,
				$database_engine,
				$database_version,
				$requirements['dumpExecutable'],
				$requirements['dumpToolVersion']
			);
			$this->writeJson($package_dir . 'manifest.json', $manifest);

			$status = count($this->warnings) > 0 ? 'success_with_warnings' : 'success';
			$completed_at = $this->getUtcDate();

			$this->progress('Generando export-report.json...');
			$report = $this->buildReport(
				$status,
				$started_at,
				$completed_at,
				$package_file_name,
				$database_info
			);
			$this->writeJson($package_dir . 'export-report.json', $report);

			$this->progress('Calculando los hashes SHA-256...');
			$this->createChecksums($package_dir);

			$this->progress('Creando y verificando el paquete .otpv...');
			$package_path = $this->createPackage($workspace_path, $package_dir, $package_file_name);
			$size = filesize($package_path);
			if ($size === false) {
				throw new RuntimeException('No se ha podido obtener el tamaño del paquete generado.');
			}
			$package_size = $size;

			$this->progress('El paquete de migración se ha generado correctamente.');

			return [
				'status'              => $status,
				'packagePath'         => $package_path,
				'packageFileName'     => basename($package_path),
				'startedAt'           => $started_at,
				'completedAt'         => $completed_at,
				'warnings'            => $this->warnings,
				'errors'              => [],
				'includedFileCount'   => $this->included_file_count,
				'missingFileCount'    => $this->missing_file_count,
				'unreadableFileCount' => $this->unreadable_file_count,
				'packageSize'         => $package_size,
				'workspacePath'       => $keep_temp ? $workspace_path : null
			];
		}
		catch (Throwable $e) {
			$this->errors[] = [
				'code'    => 'EXPORT_FAILED',
				'message' => $e->getMessage()
			];

			if (!is_null($package_path) && file_exists($package_path)) {
				@unlink($package_path);
				$package_path = null;
			}

			if (!is_null($package_dir) && is_dir($package_dir)) {
				try {
					$error_report = $this->buildReport(
						'error',
						$started_at,
						$this->getUtcDate(),
						$package_file_name,
						$database_info
					);
					$this->writeJson($package_dir . 'export-report.json', $error_report);
				}
				catch (Throwable) {
					// No ocultamos el error original si tampoco puede escribirse el informe de depuración.
				}
			}

			$this->progress('La exportación ha fallado.');

			return [
				'status'              => 'error',
				'packagePath'         => null,
				'packageFileName'     => null,
				'startedAt'           => $started_at,
				'completedAt'         => $this->getUtcDate(),
				'warnings'            => $this->warnings,
				'errors'              => $this->errors,
				'includedFileCount'   => $this->included_file_count,
				'missingFileCount'    => $this->missing_file_count,
				'unreadableFileCount' => $this->unreadable_file_count,
				'packageSize'         => 0,
				'workspacePath'       => $keep_temp ? $workspace_path : null
			];
		}
		finally {
			if (!is_null($workspace_path) && !$keep_temp && is_dir($workspace_path)) {
				$this->removeDirectory($workspace_path);
			}
		}
	}

	private function resetState(?callable $progress): void {
		$this->progress_callback = is_null($progress) ? null : Closure::fromCallable($progress);
		$this->warnings = [];
		$this->errors = [];
		$this->file_inventory = [];
		$this->copied_package_paths = [];
		$this->included_file_count = 0;
		$this->missing_file_count = 0;
		$this->unreadable_file_count = 0;
		$this->optional_not_present_count = 0;
	}

	private function progress(string $message): void {
		if (!is_null($this->progress_callback)) {
			($this->progress_callback)($message);
		}
	}

	private function checkRequirements(): array {
		if (!function_exists('exec')) {
			throw new RuntimeException('La función exec de PHP no está disponible.');
		}
		if (!class_exists(\ZipArchive::class)) {
			throw new RuntimeException('La extensión ZipArchive de PHP no está disponible.');
		}

		$tmp_dir = $this->requireConfiguredDirectory('ofw_tmp', true, true);
		$export_dir = $this->requireConfiguredDirectory('ofw_export', true, true);
		$this->requireConfiguredDirectory('ofw_cache', false, false);
		$this->requireConfiguredDirectory('public', false, false);
		$this->requireConfiguredDirectory('base', false, false);

		if (!is_writable($tmp_dir)) {
			throw new RuntimeException('La carpeta temporal de Osumi Framework no tiene permisos de escritura.');
		}
		if (!is_writable($export_dir)) {
			throw new RuntimeException('La carpeta de exportaciones de Osumi Framework no tiene permisos de escritura.');
		}

		$db_driver = strtolower((string) $this->getConfig()->getDB('driver'));
		if (!in_array($db_driver, ['mysql', 'mariadb'], true)) {
			throw new RuntimeException('El exportador solo admite conexiones MySQL o MariaDB.');
		}

		$db_host = trim((string) $this->getConfig()->getDB('host'));
		$db_user = trim((string) $this->getConfig()->getDB('user'));
		$db_name = trim((string) $this->getConfig()->getDB('name'));
		if ($db_host === '' || $db_user === '' || $db_name === '') {
			throw new RuntimeException('La configuración de la base de datos está incompleta.');
		}

		$this->validateAppDataFile();
		$this->validateMandatoryLogo();
		$this->getApplicationVersion();

		$dump_executable = $this->findDumpExecutable();
		$dump_tool_version = $this->getCommandVersion($dump_executable);

		return [
			'dumpExecutable'  => $dump_executable,
			'dumpToolVersion' => $dump_tool_version
		];
	}

	private function requireConfiguredDirectory(string $key, bool $create, bool $writable): string {
		$path = $this->getConfig()->getDir($key);
		if (!is_string($path) || trim($path) === '') {
			throw new RuntimeException('No se ha encontrado la ruta configurada para ' . $key . '.');
		}

		$path = $this->withTrailingSeparator($path);
		if (!is_dir($path)) {
			if (!$create) {
				throw new RuntimeException('No existe la carpeta configurada para ' . $key . '.');
			}
			$this->createDirectory($path, 0750);
		}
		if ($writable && !is_writable($path)) {
			throw new RuntimeException('La carpeta configurada para ' . $key . ' no tiene permisos de escritura.');
		}

		return $path;
	}

	private function createWorkspace(): string {
		$tmp_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('ofw_tmp'));

		for ($attempt = 0; $attempt < 10; $attempt++) {
			$suffix = bin2hex(random_bytes(6));
			$workspace = $tmp_dir . self::WORKSPACE_PREFIX . date('Ymd-His') . '-' . $suffix . '/';
			if (!file_exists($workspace)) {
				$this->createDirectory($workspace, 0700);
				return $workspace;
			}
		}

		throw new RuntimeException('No se ha podido crear un directorio temporal único.');
	}

	private function createDirectory(string $path, int $permissions): void {
		if (is_dir($path)) {
			return;
		}
		if (!mkdir($path, $permissions, true) && !is_dir($path)) {
			throw new RuntimeException('No se ha podido crear una carpeta necesaria para la exportación.');
		}
	}

	private function getApplicationVersion(): string {
		$composer_path = $this->withTrailingSeparator((string) $this->getConfig()->getDir('base')) . 'composer.json';
		if (!is_file($composer_path) || !is_readable($composer_path)) {
			throw new RuntimeException('No se puede leer composer.json para obtener la versión de Osumi TPV.');
		}

		try {
			$composer = json_decode((string) file_get_contents($composer_path), true, 512, JSON_THROW_ON_ERROR);
		}
		catch (JsonException) {
			throw new RuntimeException('El archivo composer.json no contiene un JSON válido.');
		}

		$version = $composer['version'] ?? null;
		if (!is_string($version) || trim($version) === '') {
			throw new RuntimeException('composer.json no define la versión de Osumi TPV.');
		}

		return trim($version);
	}

	private function getDatabaseVersion(): string {
		$db = new ODB();
		$db->query('SELECT VERSION() AS `version`');
		$result = $db->next();

		if (!is_array($result) || !array_key_exists('version', $result)) {
			throw new RuntimeException('No se ha podido obtener la versión de MariaDB.');
		}

		return (string) $result['version'];
	}

	private function getDatabaseInformation(): array {
		$database_name = (string) $this->getConfig()->getDB('name');
		$db = new ODB();
		$db->query(
			'SELECT `TABLE_NAME` AS `name` FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` = ? AND `TABLE_TYPE` = \'BASE TABLE\' ORDER BY `TABLE_NAME`',
			[$database_name]
		);

		$actual_tables = [];
		while ($result = $db->next()) {
			if (isset($result['name']) && is_string($result['name'])) {
				$actual_tables[] = $result['name'];
			}
		}

		$missing_tables = array_values(array_diff(self::EXPECTED_TABLES, $actual_tables));
		$extra_tables = array_values(array_diff($actual_tables, self::EXPECTED_TABLES));
		sort($missing_tables);
		sort($extra_tables);

		$table_rows = [];
		$total_rows = 0;
		foreach (self::EXPECTED_TABLES as $table) {
			if (!in_array($table, $actual_tables, true)) {
				continue;
			}

			$db->query('SELECT COUNT(*) AS `total` FROM `' . $table . '`');
			$result = $db->next();
			$count = is_array($result) && array_key_exists('total', $result) ? (int) $result['total'] : 0;
			$table_rows[$table] = $count;
			$total_rows += $count;
		}

		return [
			'tables'          => count($actual_tables),
			'expectedTables'  => count(self::EXPECTED_TABLES),
			'missingTables'   => $missing_tables,
			'extraTables'     => $extra_tables,
			'tableRows'       => $table_rows,
			'totalRows'       => $total_rows,
			'dumpSize'        => 0
		];
	}

	private function createDatabaseDump(
		string $workspace_path,
		string $package_dir,
		string $dump_executable,
		string $dump_tool_version,
		int $total_rows
	): array {
		$credentials_path = $workspace_path . 'mariadb-client.cnf';
		$stderr_path = $workspace_path . 'database-dump.stderr.log';
		$dump_path = $package_dir . 'database.sql';

		try {
			$this->createDatabaseCredentialsFile($credentials_path);

			$options = [
				escapeshellarg($dump_executable),
				'--defaults-extra-file=' . escapeshellarg($credentials_path),
				'--single-transaction',
				'--quick',
				'--complete-insert',
				'--skip-extended-insert',
				'--hex-blob',
				'--default-character-set=utf8mb4',
				'--result-file=' . escapeshellarg($dump_path)
			];

			if ($this->requiresColumnStatisticsOption($dump_executable, $dump_tool_version)) {
				$options[] = '--column-statistics=0';
			}

			$options[] = escapeshellarg((string) $this->getConfig()->getDB('name'));
			$command = implode(' ', $options) . ' 2> ' . escapeshellarg($stderr_path);

			$output = [];
			$exit_code = 0;
			exec($command, $output, $exit_code);

			$stderr = is_file($stderr_path) ? trim((string) file_get_contents($stderr_path)) : '';
			if ($exit_code !== 0) {
				throw new RuntimeException(
					'El comando de dump ha fallado con el código ' . $exit_code . '. Ejecuta de nuevo con --keep-temp=true para consultar el diagnóstico.'
				);
			}
			if ($stderr !== '') {
				if (preg_match('/\b(error|failed|denied|unknown option|not found)\b/i', $stderr) === 1) {
					throw new RuntimeException(
						'El comando de dump ha informado de un error. Ejecuta de nuevo con --keep-temp=true para consultar el diagnóstico.'
					);
				}
				$this->addWarning(
					'DATABASE_DUMP_STDERR',
					'El comando de dump ha generado mensajes de diagnóstico no bloqueantes.'
				);
			}

			$validation = $this->validateDatabaseDump($dump_path, $total_rows);
			return [
				'size'         => $validation['size'],
				'createdTables' => $validation['createdTables'],
				'hasInserts'    => $validation['hasInserts']
			];
		}
		finally {
			if (is_file($credentials_path)) {
				@unlink($credentials_path);
			}
		}
	}

	private function createDatabaseCredentialsFile(string $path): void {
		$host = (string) $this->getConfig()->getDB('host');
		$user = (string) $this->getConfig()->getDB('user');
		$password = (string) $this->getConfig()->getDB('pass');

		$content = "[client]\n";
		$content .= 'host=' . $this->escapeOptionFileValue($host) . "\n";
		$content .= 'user=' . $this->escapeOptionFileValue($user) . "\n";
		$content .= 'password=' . $this->escapeOptionFileValue($password) . "\n";
		$content .= "default-character-set=utf8mb4\n";

		if (file_put_contents($path, $content, LOCK_EX) === false) {
			throw new RuntimeException('No se ha podido crear la configuración temporal de MariaDB.');
		}
		if (DIRECTORY_SEPARATOR !== '\\' && !chmod($path, 0600)) {
			@unlink($path);
			throw new RuntimeException('No se han podido aplicar permisos seguros a la configuración temporal de MariaDB.');
		}
	}

	private function escapeOptionFileValue(string $value): string {
		$escaped = str_replace(
			['\\', '"', "\n", "\r", "\t"],
			['\\\\', '\\"', '\\n', '\\r', '\\t'],
			$value
		);

		return '"' . $escaped . '"';
	}

	private function requiresColumnStatisticsOption(string $executable, string $version): bool {
		return strtolower(basename($executable)) === 'mysqldump'
			&& preg_match('/Distrib\s+8\./i', $version) === 1;
	}

	private function validateDatabaseDump(string $dump_path, int $total_rows): array {
		if (!is_file($dump_path) || !is_readable($dump_path)) {
			throw new RuntimeException('No se ha generado el archivo database.sql.');
		}

		$size = filesize($dump_path);
		if ($size === false || $size <= 0) {
			throw new RuntimeException('El archivo database.sql está vacío.');
		}

		$handle = fopen($dump_path, 'rb');
		if ($handle === false) {
			throw new RuntimeException('No se puede leer el archivo database.sql generado.');
		}

		$created_tables = [];
		$has_inserts = false;
		try {
			while (($line = fgets($handle)) !== false) {
				if (preg_match('/^CREATE TABLE(?: IF NOT EXISTS)? `([^`]+)`/i', $line, $matches) === 1) {
					$created_tables[] = $matches[1];
				}
				if (!$has_inserts && preg_match('/^INSERT INTO `/i', $line) === 1) {
					$has_inserts = true;
				}
			}
		}
		finally {
			fclose($handle);
		}

		$created_tables = array_values(array_unique($created_tables));
		$missing_tables = array_values(array_diff(self::EXPECTED_TABLES, $created_tables));
		if (count($missing_tables) > 0) {
			throw new RuntimeException(
				'El dump no contiene la estructura de estas tablas: ' . implode(', ', $missing_tables) . '.'
			);
		}
		if ($total_rows > 0 && !$has_inserts) {
			throw new RuntimeException('El dump no contiene sentencias INSERT aunque la base de datos tiene registros.');
		}

		return [
			'size'          => $size,
			'createdTables' => $created_tables,
			'hasInserts'    => $has_inserts
		];
	}

	private function validateAppDataFile(): void {
		$path = $this->withTrailingSeparator((string) $this->getConfig()->getDir('ofw_cache')) . 'app_data.json';
		if (!is_file($path) || !is_readable($path)) {
			throw new RuntimeException('No se puede leer /ofw/cache/app_data.json.');
		}

		try {
			json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
		}
		catch (JsonException) {
			throw new RuntimeException('El archivo /ofw/cache/app_data.json no contiene un JSON válido.');
		}
	}

	private function copyAppData(string $package_dir): void {
		$source = $this->withTrailingSeparator((string) $this->getConfig()->getDir('ofw_cache')) . 'app_data.json';
		$destination = $package_dir . 'app_data.json';
		if (!copy($source, $destination)) {
			throw new RuntimeException('No se ha podido copiar app_data.json al paquete.');
		}
	}

<<<<<<< HEAD
	private function exportPluginConfig(string $package_dir): void {
		$plugin_config = [];

		foreach (self::EXPORTED_PLUGIN_FIELDS as $plugin_name => $fields) {
			$config = $this->getConfig()->getPluginConfig($plugin_name);
			if (is_null($config)) {
				$plugin_config[$plugin_name] = null;
				continue;
			}

			$plugin_config[$plugin_name] = [];
			$missing_fields = [];

			foreach ($fields as $field) {
				if (array_key_exists($field, $config)) {
					$plugin_config[$plugin_name][$field] = $config[$field];
				}
				else {
					$plugin_config[$plugin_name][$field] = null;
					$missing_fields[] = $field;
				}
			}

			if (count($missing_fields) > 0) {
				$this->addWarning(
					'INCOMPLETE_PLUGIN_CONFIG',
					'La configuración de un plugin está incompleta.',
					[
						'plugin'        => $plugin_name,
						'missingFields' => $missing_fields
					]
				);
			}
		}

		$this->writeJson($package_dir . 'plugin_config.json', $plugin_config);
	}

=======
>>>>>>> 5839a412eb104a058a66212a692db6c5bb96f5a1
	private function validateMandatoryLogo(): void {
		$public_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('public'));
		$logo_path = $public_dir . 'logo.jpg';
		if (!is_file($logo_path) || !is_readable($logo_path) || is_link($logo_path)) {
			throw new RuntimeException('El archivo obligatorio /public/logo.jpg no existe o no se puede leer.');
		}
		if (!$this->isPathInside($logo_path, $public_dir)) {
			throw new RuntimeException('El logo obligatorio está fuera de la carpeta public permitida.');
		}
	}

	private function copyLogo(string $package_dir): void {
		$public_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('public'));
		$this->copyLegacyFile(
			$public_dir . 'logo.jpg',
			$public_dir,
			$package_dir,
			'files/logo/logo.jpg',
			'logo',
			null,
			null,
			null,
			'logo.jpg',
			true,
			false
		);
	}

	private function collectPhotos(string $package_dir): void {
		$db = new ODB();
		$db->query('SELECT `id` FROM `foto` ORDER BY `id`');
		$public_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('public'));
		$source_root = $public_dir . 'fotos/';

		while ($result = $db->next()) {
			$id = (int) $result['id'];
			$file_name = $id . '.webp';
			$this->copyLegacyFile(
				$source_root . $file_name,
				$source_root,
				$package_dir,
				'files/fotos/' . $file_name,
				'foto',
				'foto',
				$id,
				null,
				$file_name,
				false,
				false
			);
		}
	}

	private function collectBrands(string $package_dir): void {
		$db = new ODB();
		$db->query('SELECT `id` FROM `marca` ORDER BY `id`');
		$public_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('public'));
		$source_root = $public_dir . 'marcas/';

		while ($result = $db->next()) {
			$id = (int) $result['id'];
			$file_name = $id . '.webp';
			$this->copyLegacyFile(
				$source_root . $file_name,
				$source_root,
				$package_dir,
				'files/marcas/' . $file_name,
				'marca',
				'marca',
				$id,
				null,
				$file_name,
				false,
				true
			);
		}
	}

	private function collectProviders(string $package_dir): void {
		$db = new ODB();
		$db->query('SELECT `id` FROM `proveedor` ORDER BY `id`');
		$public_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('public'));
		$source_root = $public_dir . 'proveedores/';

		while ($result = $db->next()) {
			$id = (int) $result['id'];
			$file_name = $id . '.webp';
			$this->copyLegacyFile(
				$source_root . $file_name,
				$source_root,
				$package_dir,
				'files/proveedores/' . $file_name,
				'proveedor',
				'proveedor',
				$id,
				null,
				$file_name,
				false,
				true
			);
		}
	}

	private function collectPaymentTypeIcons(string $package_dir): void {
		$db = new ODB();
		$db->query('SELECT `id`, `slug` FROM `tipo_pago` ORDER BY `id`');
		$public_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('public'));
		$source_root = $public_dir . 'tipos-pago/';

		while ($result = $db->next()) {
			$id = (int) $result['id'];
			$slug = (string) $result['slug'];

			if (!$this->isSafeSlug($slug)) {
				$this->missing_file_count++;
				$this->file_inventory[] = [
					'logicalCategory' => 'tipo_pago',
					'sourceTable'     => 'tipo_pago',
					'legacyId'        => $id,
					'relatedId'       => null,
					'packagePath'     => null,
					'originalName'    => null,
					'storedName'      => null,
					'size'            => null,
					'mimeType'        => null,
					'sha256'          => null,
					'status'          => 'invalid_reference'
				];
				$this->addWarning(
					'INVALID_FILE_REFERENCE',
					'El tipo de pago tiene un slug no válido para construir la ruta del icono.',
					[
						'table'    => 'tipo_pago',
						'legacyId' => $id
					]
				);
				continue;
			}

			$file_name = 'icon-' . $slug . '.webp';
			$this->copyLegacyFile(
				$source_root . $file_name,
				$source_root,
				$package_dir,
				'files/tipos-pago/' . $file_name,
				'tipo_pago',
				'tipo_pago',
				$id,
				null,
				$file_name,
				false,
				false
			);
		}
	}

	private function collectOrderPdfs(string $package_dir): void {
		$db = new ODB();
		$db->query('SELECT `id`, `id_pedido`, `nombre` FROM `pdf_pedido` ORDER BY `id`');
		$public_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('public'));
		$source_root = $public_dir . 'pdf/';

		while ($result = $db->next()) {
			$id = (int) $result['id'];
			$id_pedido = (int) $result['id_pedido'];
			$file_name = $id . '.pdf';
			$original_name = isset($result['nombre']) && is_string($result['nombre']) && trim($result['nombre']) !== ''
				? $this->sanitizeOriginalName($result['nombre'])
				: $file_name;

			$this->copyLegacyFile(
				$source_root . $file_name,
				$source_root,
				$package_dir,
				'files/pdf/' . $file_name,
				'pdf_pedido',
				'pdf_pedido',
				$id,
				$id_pedido,
				$original_name,
				false,
				false,
				$file_name
			);
		}
	}

	private function copyLegacyFile(
		string $source_path,
		string $allowed_root,
		string $package_dir,
		string $package_path,
		string $logical_category,
		?string $source_table,
		?int $legacy_id,
		?int $related_id,
		string $original_name,
		bool $blocking,
		bool $optional,
		?string $stored_name = null
	): void {
		$this->assertSafePackagePath($package_path);

		if (!file_exists($source_path)) {
			$status = $optional ? 'not_present' : 'absent';
			if ($optional) {
				$this->optional_not_present_count++;
			}
			else {
				$this->missing_file_count++;
				$this->addWarning(
					'MISSING_FILE',
					'No se ha encontrado un archivo referenciado por la base de datos.',
					[
						'table'       => $source_table,
						'legacyId'    => $legacy_id,
						'packagePath' => $package_path
					]
				);
			}

			$this->file_inventory[] = $this->buildFileInventoryItem(
				$logical_category,
				$source_table,
				$legacy_id,
				$related_id,
				$package_path,
				$original_name,
				$stored_name ?? basename($package_path),
				null,
				null,
				null,
				$status
			);

			if ($blocking) {
				throw new RuntimeException('No se ha encontrado un archivo obligatorio para la exportación.');
			}
			return;
		}

		if (!is_file($source_path) || !is_readable($source_path) || is_link($source_path)) {
			$this->unreadable_file_count++;
			$this->file_inventory[] = $this->buildFileInventoryItem(
				$logical_category,
				$source_table,
				$legacy_id,
				$related_id,
				$package_path,
				$original_name,
				$stored_name ?? basename($package_path),
				null,
				null,
				null,
				'unreadable'
			);
			$this->addWarning(
				'UNREADABLE_FILE',
				'Un archivo existente no se puede leer o es un enlace simbólico.',
				[
					'table'       => $source_table,
					'legacyId'    => $legacy_id,
					'packagePath' => $package_path
				]
			);

			if ($blocking) {
				throw new RuntimeException('No se puede leer un archivo obligatorio para la exportación.');
			}
			return;
		}

		if (!$this->isPathInside($source_path, $allowed_root)) {
			throw new RuntimeException('Se ha detectado un archivo fuera de una carpeta legacy permitida.');
		}

		if (array_key_exists($package_path, $this->copied_package_paths)) {
			$existing = $this->copied_package_paths[$package_path];
			$this->file_inventory[] = $this->buildFileInventoryItem(
				$logical_category,
				$source_table,
				$legacy_id,
				$related_id,
				$package_path,
				$original_name,
				$stored_name ?? basename($package_path),
				$existing['size'],
				$existing['mimeType'],
				$existing['sha256'],
				'included_reference'
			);
			return;
		}

		$destination = $package_dir . str_replace('/', DIRECTORY_SEPARATOR, $package_path);
		$this->createDirectory(dirname($destination) . DIRECTORY_SEPARATOR, 0700);
		if (!copy($source_path, $destination)) {
			throw new RuntimeException('No se ha podido copiar un archivo legacy al paquete.');
		}

		$size = filesize($destination);
		$sha256 = hash_file('sha256', $destination);
		if ($size === false || $sha256 === false) {
			throw new RuntimeException('No se han podido calcular los metadatos de un archivo copiado.');
		}
		$mime_type = $this->getMimeType($destination);

		$this->included_file_count++;
		$this->copied_package_paths[$package_path] = [
			'size'     => $size,
			'mimeType' => $mime_type,
			'sha256'   => $sha256
		];
		$this->file_inventory[] = $this->buildFileInventoryItem(
			$logical_category,
			$source_table,
			$legacy_id,
			$related_id,
			$package_path,
			$original_name,
			$stored_name ?? basename($package_path),
			$size,
			$mime_type,
			$sha256,
			'included'
		);
	}

	private function buildFileInventoryItem(
		string $logical_category,
		?string $source_table,
		?int $legacy_id,
		?int $related_id,
		?string $package_path,
		?string $original_name,
		?string $stored_name,
		?int $size,
		?string $mime_type,
		?string $sha256,
		string $status
	): array {
		return [
			'logicalCategory' => $logical_category,
			'sourceTable'     => $source_table,
			'legacyId'        => $legacy_id,
			'relatedId'       => $related_id,
			'packagePath'     => $package_path,
			'originalName'    => $original_name,
			'storedName'      => $stored_name,
			'size'            => $size,
			'mimeType'        => $mime_type,
			'sha256'          => $sha256,
			'status'          => $status
		];
	}

	private function buildManifest(
		string $application_version,
		string $framework_version,
		string $database_engine,
		string $database_version,
		string $dump_executable,
		string $dump_tool_version
	): array {
		return [
			'formatVersion'      => self::FORMAT_VERSION,
			'application'        => 'Osumi TPV',
			'applicationVersion' => $application_version,
			'framework'          => 'Osumi Framework',
			'frameworkVersion'   => $framework_version,
			'databaseEngine'     => $database_engine,
			'databaseVersion'    => $database_version,
			'databaseName'       => (string) $this->getConfig()->getDB('name'),
			'schemaVersion'      => self::SCHEMA_VERSION,
			'createdAt'          => $this->getUtcDate(),
			'encoding'           => 'utf8mb4',
			'dumpFormat'         => [
				'completeInsert' => true,
				'extendedInsert' => false,
				'hexBlob'        => true
			],
			'dumpTool'           => [
				'name'    => basename($dump_executable),
				'version' => $dump_tool_version
			],
			'contents'           => [
<<<<<<< HEAD
				'database'     => true,
				'appData'      => true,
				'pluginConfig' => true,
				'logo'         => true,
				'files'        => $this->included_file_count > 0
=======
				'database' => true,
				'appData'  => true,
				'logo'     => true,
				'files'    => $this->included_file_count > 0
>>>>>>> 5839a412eb104a058a66212a692db6c5bb96f5a1
			]
		];
	}

	private function buildReport(
		string $status,
		string $started_at,
		string $completed_at,
		string $package_file_name,
		array $database_info
	): array {
		return [
			'status'          => $status,
			'startedAt'       => $started_at,
			'completedAt'     => $completed_at,
			'packageFileName' => $package_file_name,
			'database'        => $database_info,
			'files'           => [
				'included'           => $this->included_file_count,
				'missing'            => $this->missing_file_count,
				'unreadable'         => $this->unreadable_file_count,
				'optionalNotPresent' => $this->optional_not_present_count,
				'inventory'          => $this->file_inventory
			],
			'warnings'        => $this->warnings,
			'errors'          => $this->errors
		];
	}

	private function createChecksums(string $package_dir): void {
		$files = $this->listPackageFiles($package_dir);
		$checksums = [];

		foreach ($files as $relative_path => $absolute_path) {
			if ($relative_path === 'checksums.json') {
				continue;
			}
			$hash = hash_file('sha256', $absolute_path);
			if ($hash === false) {
				throw new RuntimeException('No se ha podido calcular un hash SHA-256 del paquete.');
			}
			$checksums[$relative_path] = $hash;
		}

		ksort($checksums);
		$this->writeJson(
			$package_dir . 'checksums.json',
			[
				'algorithm' => 'sha256',
				'files'     => $checksums
			]
		);
	}

	private function createPackage(string $workspace_path, string $package_dir, string $package_file_name): string {
		$temporary_zip = $workspace_path . 'migration-package.zip';
		$export_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('ofw_export'));
		$final_path = $export_dir . $package_file_name;
		$lock_path = $final_path . '.lock';
		$lock_handle = @fopen($lock_path, 'x');
		if ($lock_handle === false) {
			throw new RuntimeException('Otra exportación está utilizando el mismo nombre de paquete.');
		}

		try {
			if (DIRECTORY_SEPARATOR !== '\\') {
				@chmod($lock_path, 0600);
			}
			fclose($lock_handle);
			$lock_handle = null;

			$zip = new \ZipArchive();
			$open_result = $zip->open($temporary_zip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
			if ($open_result !== true) {
				throw new RuntimeException('No se ha podido crear el archivo ZIP temporal.');
			}

			try {
				$files = $this->listPackageFiles($package_dir);
				foreach ($files as $relative_path => $absolute_path) {
					$this->assertSafePackagePath($relative_path);
					if (!$zip->addFile($absolute_path, $relative_path)) {
						throw new RuntimeException('No se ha podido añadir un archivo al ZIP de migración.');
					}
				}
			}
			finally {
				$zip->close();
			}

			$validation_zip = new \ZipArchive();
			$validation_result = $validation_zip->open($temporary_zip, \ZipArchive::CHECKCONS);
			if ($validation_result !== true) {
				throw new RuntimeException('El ZIP generado no ha superado la comprobación de integridad.');
			}
			try {
<<<<<<< HEAD
				foreach (['manifest.json', 'database.sql', 'app_data.json', 'plugin_config.json', 'export-report.json', 'checksums.json'] as $required_file) {
=======
				foreach (['manifest.json', 'database.sql', 'app_data.json', 'export-report.json', 'checksums.json'] as $required_file) {
>>>>>>> 5839a412eb104a058a66212a692db6c5bb96f5a1
					if ($validation_zip->locateName($required_file) === false) {
						throw new RuntimeException('El paquete no contiene el archivo obligatorio ' . $required_file . '.');
					}
				}
				$this->validatePackageChecksums($validation_zip);
			}
			finally {
				$validation_zip->close();
			}

			if (file_exists($final_path)) {
				throw new RuntimeException('Ya existe un paquete con el nombre final seleccionado.');
			}
			if (!rename($temporary_zip, $final_path)) {
				if (!copy($temporary_zip, $final_path)) {
					throw new RuntimeException('No se ha podido mover el paquete final a /ofw/export.');
				}
				@unlink($temporary_zip);
			}

			if (!is_file($final_path) || !is_readable($final_path)) {
				throw new RuntimeException('El paquete final no se puede leer después de guardarlo.');
			}

			return $final_path;
		}
		finally {
			if (is_resource($lock_handle)) {
				fclose($lock_handle);
			}
			@unlink($lock_path);
		}
	}

	private function validatePackageChecksums(\ZipArchive $zip): void {
		$checksums_content = $zip->getFromName('checksums.json');
		if (!is_string($checksums_content)) {
			throw new RuntimeException('No se puede leer checksums.json desde el ZIP generado.');
		}

		try {
			$checksums = json_decode($checksums_content, true, 512, JSON_THROW_ON_ERROR);
		}
		catch (JsonException) {
			throw new RuntimeException('checksums.json no es válido dentro del ZIP generado.');
		}

		if (($checksums['algorithm'] ?? null) !== 'sha256' || !is_array($checksums['files'] ?? null)) {
			throw new RuntimeException('checksums.json no tiene la estructura esperada.');
		}

		foreach ($checksums['files'] as $relative_path => $expected_hash) {
			if (!is_string($relative_path) || !is_string($expected_hash)) {
				throw new RuntimeException('checksums.json contiene una entrada no válida.');
			}
			$this->assertSafePackagePath($relative_path);

			$stream = $zip->getStream($relative_path);
			if ($stream === false) {
				throw new RuntimeException('El ZIP no contiene uno de los archivos declarados en checksums.json.');
			}

			$hash_context = hash_init('sha256');
			try {
				hash_update_stream($hash_context, $stream);
			}
			finally {
				fclose($stream);
			}
			$actual_hash = hash_final($hash_context);

			if (!hash_equals(strtolower($expected_hash), strtolower($actual_hash))) {
				throw new RuntimeException('Un archivo del ZIP no coincide con su hash SHA-256.');
			}
		}
	}

	private function listPackageFiles(string $package_dir): array {
		$package_real_path = realpath($package_dir);
		if ($package_real_path === false) {
			throw new RuntimeException('No se puede resolver la ruta del contenido del paquete.');
		}

		$files = [];
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($package_real_path, FilesystemIterator::SKIP_DOTS),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($iterator as $file_info) {
			if ($file_info->isLink()) {
				throw new RuntimeException('Se ha detectado un enlace simbólico dentro del contenido del paquete.');
			}
			if (!$file_info->isFile()) {
				continue;
			}

			$absolute_path = $file_info->getPathname();
			$relative_path = substr($absolute_path, strlen($package_real_path) + 1);
			$relative_path = str_replace(DIRECTORY_SEPARATOR, '/', $relative_path);
			$this->assertSafePackagePath($relative_path);
			$files[$relative_path] = $absolute_path;
		}

		ksort($files);
		return $files;
	}

	private function writeJson(string $path, array $data): void {
		try {
			$json = json_encode(
				$data,
				JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
			);
		}
		catch (JsonException) {
			throw new RuntimeException('No se ha podido serializar uno de los archivos JSON del paquete.');
		}

		if (file_put_contents($path, $json . "\n", LOCK_EX) === false) {
			throw new RuntimeException('No se ha podido escribir uno de los archivos JSON del paquete.');
		}
	}

	private function findDumpExecutable(): string {
		$candidates = [
			'/usr/bin/mariadb-dump',
			'/usr/local/bin/mariadb-dump',
			'/usr/bin/mysqldump',
			'/usr/local/bin/mysqldump'
		];

		foreach ($candidates as $candidate) {
			if (is_file($candidate) && is_executable($candidate)) {
				return $candidate;
			}
		}

		$commands = DIRECTORY_SEPARATOR === '\\'
			? ['where mariadb-dump', 'where mysqldump']
			: ['command -v mariadb-dump', 'command -v mysqldump'];

		foreach ($commands as $command) {
			$output = [];
			$exit_code = 0;
			exec($command . ' 2>/dev/null', $output, $exit_code);
			if ($exit_code === 0 && count($output) > 0) {
				$path = trim($output[0]);
				if ($path !== '' && is_file($path) && is_executable($path)) {
					return $path;
				}
			}
		}

		throw new RuntimeException('No se ha encontrado el ejecutable mariadb-dump ni mysqldump.');
	}

	private function getCommandVersion(string $executable): string {
		$output = [];
		$exit_code = 0;
		exec(escapeshellarg($executable) . ' --version 2>&1', $output, $exit_code);
		if ($exit_code !== 0 || count($output) === 0) {
			return 'unknown';
		}

		return trim(implode(' ', $output));
	}

	private function createAvailablePackageFileName(): string {
		$export_dir = $this->withTrailingSeparator((string) $this->getConfig()->getDir('ofw_export'));
		$now = new DateTimeImmutable('now');

		for ($offset = 0; $offset <= 99; $offset++) {
			$date = $offset === 0 ? $now : $now->modify('+' . $offset . ' seconds');
			$file_name = self::PACKAGE_PREFIX . $date->format('Y-m-d-His') . '.otpv';
			$path = $export_dir . $file_name;
			if (!file_exists($path) && !file_exists($path . '.lock')) {
				return $file_name;
			}
		}

		throw new RuntimeException('No se ha podido obtener un nombre libre para el paquete final.');
	}

	private function addWarning(string $code, string $message, array $context = []): void {
		$warning = [
			'code'    => $code,
			'message' => $message
		];

		foreach ($context as $key => $value) {
			$warning[$key] = $value;
		}

		$this->warnings[] = $warning;
	}

	private function getMimeType(string $path): string {
		if (class_exists(\finfo::class)) {
			$finfo = new \finfo(FILEINFO_MIME_TYPE);
			$mime_type = $finfo->file($path);
			if (is_string($mime_type) && $mime_type !== '') {
				return $mime_type;
			}
		}

		return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
			'jpg', 'jpeg' => 'image/jpeg',
			'webp'        => 'image/webp',
			'pdf'         => 'application/pdf',
			'json'        => 'application/json',
			'sql'         => 'application/sql',
			default       => 'application/octet-stream'
		};
	}

	private function sanitizeOriginalName(string $name): string {
		$normalized_name = str_replace('\\', '/', trim($name));
		$base_name = basename($normalized_name);
		return $base_name !== '' ? $base_name : 'document.pdf';
	}

	private function isSafeSlug(string $slug): bool {
		return $slug !== ''
			&& !str_contains($slug, '..')
			&& preg_match('/^[a-zA-Z0-9_-]+$/', $slug) === 1;
	}

	private function assertSafePackagePath(string $path): void {
		if ($path === '' || str_starts_with($path, '/') || str_starts_with($path, '\\')) {
			throw new RuntimeException('Se ha intentado utilizar una ruta absoluta dentro del paquete.');
		}
		if (preg_match('/^[a-zA-Z]:/', $path) === 1) {
			throw new RuntimeException('Se ha intentado utilizar una ruta de unidad dentro del paquete.');
		}
		if (str_contains($path, '\\')) {
			throw new RuntimeException('Las rutas internas del paquete deben utilizar /.');
		}

		$segments = explode('/', $path);
		foreach ($segments as $segment) {
			if ($segment === '' || $segment === '.' || $segment === '..') {
				throw new RuntimeException('Se ha detectado una ruta interna no válida en el paquete.');
			}
		}
	}

	private function isPathInside(string $path, string $root): bool {
		$real_path = realpath($path);
		$real_root = realpath($root);
		if ($real_path === false || $real_root === false) {
			return false;
		}

		$normalized_path = rtrim($real_path, DIRECTORY_SEPARATOR);
		$normalized_root = rtrim($real_root, DIRECTORY_SEPARATOR);
		return str_starts_with($normalized_path, $normalized_root . DIRECTORY_SEPARATOR);
	}

	private function removeDirectory(string $path): void {
		if (is_link($path) || is_file($path)) {
			@unlink($path);
			return;
		}
		if (!is_dir($path)) {
			return;
		}

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($iterator as $file_info) {
			$entry_path = $file_info->getPathname();
			if ($file_info->isLink() || $file_info->isFile()) {
				@unlink($entry_path);
			}
			else {
				@rmdir($entry_path);
			}
		}

		@rmdir($path);
	}

	private function withTrailingSeparator(string $path): string {
		return rtrim($path, '/\\') . DIRECTORY_SEPARATOR;
	}

	private function getUtcDate(): string {
		return (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s\Z');
	}
}
