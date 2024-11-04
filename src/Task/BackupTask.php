<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\Tools\OTools;

class BackupTask extends OTask {
	public function __toString() {
		return "backup: Tarea para realizar backups";
	}

	/**
	 * Función para encriptar un archivo
	 *
	 * @param string $source Ruta del archivo original
	 *
	 * @param string $dest Ruta del archivo encriptado destino
	 *
	 * @param string $key Clave para encriptar el archivo
	 *
	 * @return void
	 */
	private function encryptFile(string $source, string $dest, string $key): void {
		$cipher   = 'aes-256-cbc';
		$ivLenght = openssl_cipher_iv_length($cipher);
		$iv       = openssl_random_pseudo_bytes($ivLenght);

		$fpSource = fopen($source, 'rb');
		$fpDest   = fopen($dest, 'w');

		fwrite($fpDest, $iv);

		while (! feof($fpSource)) {
			$plaintext  = fread($fpSource, $ivLenght * FILE_ENCRYPTION_BLOCKS);
			$ciphertext = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
			$iv         = substr($ciphertext, 0, $ivLenght);

			fwrite($fpDest, $ciphertext);
		}

		fclose($fpSource);
		fclose($fpDest);
	}

	public function run(array $options=[]): void {
		define('FILE_ENCRYPTION_BLOCKS', 10000);
		OTools::checkOfw('export');
		$file_path     = $this->getConfig()->getDir('ofw_export') . $this->getConfig()->getDB('name') . '.sql';
		$file_path_enc = $this->getConfig()->getDir('ofw_export') . $this->getConfig()->getDB('name') . '.enc.sql';
		$enc_key       = $this->getConfig()->getExtra('backup_id_account') . '-' . $this->getConfig()->getExtra('backup_api_key') . '-' . $this->getConfig()->getExtra('backup_id_account');

		if (file_exists($file_path)) {
			unlink($file_path);
		}
		if (file_exists($file_path_enc)) {
			unlink($file_path_enc);
		}

		OTools::runOFWTask('backupDB', [true]);

		$this->encryptFile($file_path, $file_path_enc, $enc_key);

		$backup_save_url = $this->getConfig()->getExtra('backup_api_url').'save-backup';

		$ch    = curl_init($backup_save_url);
		$cfile = curl_file_create($file_path_enc);
		$post  = [
			'api_key'    => $this->getConfig()->getExtra('backup_api_key'),
			'id_account' => $this->getConfig()->getExtra('backup_id_account'),
			'file'       => $cfile
		];
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec ($ch);
		curl_close ($ch);
	}
}
