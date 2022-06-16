<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Foto extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada foto'
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OModel::UPDATED,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($model);
	}

	/**
	 * Función para borrar una foto, el archivo, y luego su registro
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		global $core;
		$ruta = $core->config->getExtra('fotos').$this->get('id').'.webp';
		if (file_exists($ruta)){
			unlink($ruta);
		}

		$this->delete();
	}
}
