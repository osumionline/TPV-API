<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class Marca extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada marca'
			),
			new OModelField(
				name: 'nombre',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 50,
				comment: 'Nombre de la marca'
			),
			new OModelField(
				name: 'direccion',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 200,
				comment: 'Dirección física de la marca'
			),
			new OModelField(
				name: 'telefono',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 15,
				comment: 'Teléfono de la marca'
			),
			new OModelField(
				name: 'email',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 100,
				comment: 'Dirección de email de la marca'
			),
			new OModelField(
				name: 'web',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 100,
				comment: 'Dirección de la página web de la marca'
			),
			new OModelField(
				name: 'observaciones',
				type: OMODEL_LONGTEXT,
				nullable: true,
				default: null,
				comment: 'Observaciones o notas personales de la marca'
			),
			new OModelField(
				name: 'created_at',
				type: OMODEL_CREATED,
				comment: 'Fecha de creación del registro'
			),
			new OModelField(
				name: 'updated_at',
				type: OMODEL_UPDATED,
				nullable: true,
				default: null,
				comment: 'Fecha de última modificación del registro'
			),
			new OModelField(
				name: 'deleted_at',
				type: OMODEL_DATE,
				nullable: true,
				default: null,
				comment: 'Fecha de borrado de la marca'
			)
		);

		parent::load($model);
	}

	private ?Proveedor $proveedor = null;

	/**
	 * Obtiene el proveedor al que pertenece la marca
	 *
	 * @return Proveedor Proveedor al que pertenece la marca
	 */
	public function getProveedor(): Proveedor {
		if (is_null($this->proveedor)) {
			$this->loadProveedor();
		}
		return $this->proveedor;
	}

	/**
	 * Guarda el proveedor al que pertenece la marca
	 *
	 * @param Proveedor $p Proveedor al que pertenece la marca
	 *
	 * @return void
	 */
	public function setProveedor(Proveedor $p): void {
		$this->proveedor = $p;
	}

	/**
	 * Carga el proveedor al que pertenece la marca
	 *
	 * @return void
	 */
	public function loadProveedor(): void {
		$db = new ODB();
		$sql = "SELECT p.* FROM `proveedor` p, `proveedor_marca` pm WHERE p.`id` = pm.`id_proveedor` AND pm.`id_marca` = ?";
		$db->query($sql, [$this->get('id')]);

		$p = new Proveedor();
		if ($res = $db->next()) {
			$p->update($res);
		}
		$this->setProveedor($p);
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
		return $core->config->getUrl('base').'/marcas/'.$this->get('id').'.webp';
	}

	/**
	 * Obtiene la ruta física a la imagen del logo
	 *
	 * @return string Ruta del archivo de la imagen
	 */
	public function getRutaFoto(): string {
		global $core;
		return $core->config->getDir('web').'marcas/'.$this->get('id').'.webp';
	}
}
