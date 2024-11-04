<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Foto extends OModel {
	#[OPK(
	  comment: 'Id único para cada foto'
	)]
	public ?int $id;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Función para borrar una foto, el archivo, y luego su registro
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		global $core;
		$ruta = $core->config->getExtra('fotos') . $this->id . '.webp';
		if (file_exists($ruta)){
			unlink($ruta);
		}

		$this->delete();
	}
}
