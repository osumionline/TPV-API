<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class TipoPago extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada tipo de pago'
			],
			'nombre' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre del tipo de pago'
			],
      'slug' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Slug del nombre del tipo de pago'
			],
			'afecta_caja' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Indica si el tipo de pago afecta a la caja'
			],
			'orden' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'comment'  => 'Orden del tipo de pago en la lista completa'
			],
			'fisico' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => true,
				'comment' => 'Indica si el tipo de pago es para tienda física'
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
			],
      'deleted_at' => [
				'type'    => OModel::DATE,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de borrado del tipo de pago'
			]
		];

		parent::load($model);
	}

  private ?array $ventas = null;

	/**
	 * Obtiene el listado de ventas de un tipo de pago
	 *
	 * @return array Listado de ventas
	 */
	public function getVentas(): array {
		if (is_null($this->ventas)) {
			$this->loadVentas();
		}
		return $this->ventas;
	}

	/**
	 * Guarda la lista de ventas
	 *
	 * @param array $v Lista de ventas
	 *
	 * @return void
	 */
	public function setVentas(array $v): void {
		$this->ventas = $v;
	}

	/**
	 * Carga la lista de ventas de un tipo de pago
	 *
	 * @return void
	 */
	public function loadVentas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `venta` WHERE `id_tipo_pago` = ?";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$v = new Venta();
			$v->update($res);
			array_push($list, $v);
		}

		$this->setVentas($list);
	}

	/**
	 * Función para obtener la url de la imagen del logo
	 *
	 * @return string Url de la imagen o null si no tiene
	 */
	public function getFoto(): ?string {
		global $core;
		$ruta_foto = $this->getRutaFoto();
		if (!file_exists($ruta_foto)) {
			return null;
		}
		return $core->config->getUrl('base').'/tipos-pago/icon-'.$this->get('slug').'.webp';
	}

	/**
	 * Obtiene la ruta física a la imagen del logo
	 *
	 * @return string Ruta del archivo de la imagen
	 */
	public function getRutaFoto(): string {
		global $core;
		return $core->config->getDir('web').'tipos-pago/icon-'.$this->get('slug').'.webp';
	}
}
