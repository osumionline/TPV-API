<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Comercial extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada comercial'
			],
			'id_proveedor' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
        'ref' => 'proveedor.id',
				'comment' => 'Id del proveedor para el que trabaja el comercial'
			],
      'nombre' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre del comercial'
			],
			'telefono' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 15,
				'comment' => 'Teléfono del comercial'
			],
			'email' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección de email del comercial'
			],
			'observaciones' => [
				'type'    => OModel::LONGTEXT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Observaciones o notas personales del comercial'
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
				'comment' => 'Fecha de borrado del comercial'
			]
		];

		parent::load($model);
	}

	private ?Proveedor $proveedor = null;

	/**
	 * Obtiene el proveedor al que pertenece el comercial
	 *
	 * @return Proveedor Proveedor al que pertenece el comercial
	 */
	public function getProveedor(): Proveedor {
		if (is_null($this->proveedor)) {
			$this->loadProveedor();
		}
		return $this->proveedor;
	}

	/**
	 * Guarda el proveedor al que pertenece el comercial
	 *
	 * @param Proveedor $p Proveedor al que pertenece el comercial
	 *
	 * @return void
	 */
	public function setProveedor(Proveedor $p): void {
		$this->proveedor = $p;
	}

	/**
	 * Carga el proveedor al que pertenece el comercial
	 *
	 * @return void
	 */
	public function loadProveedor(): void {
		$p = new Proveedor();
		$p->find(['id' => $this->get('id_proveedor')]);
		$this->setProveedor($p);
	}
}
