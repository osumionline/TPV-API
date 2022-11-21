<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Pedido extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada pedido'
			],
			'id_proveedor' => [
        'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'ref'      => 'proveedor.id',
				'comment'  => 'Id del proveedor del pedido'
			],
			'albaran' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 200,
				'comment' => 'Albarán del pedido'
			],
			'importe' => [
        'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => 0,
				'comment'  => 'Importe total del pedido'
			],
			'recepcionado' => [
        'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Indica si se ha recepcionado el pedido 1 o si está pendiente 0'
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
				'comment' => 'Fecha de borrado de la marca'
			]
		];

		parent::load($model);
	}

  private ?Proveedor $proveedor = null;

	/**
	 * Obtiene el proveedor al que pertenece el pedido
	 *
	 * @return Proveedor Proveedor al que pertenece el pedido, a no ser que se haya borrado
	 */
	public function getProveedor(): ?Proveedor {
		if (is_null($this->proveedor)) {
			$this->loadProveedor();
		}
		if (!is_null($this->proveedor->get('deleted_at'))){
			return null;
		}
		return $this->proveedor;
	}

	/**
	 * Guarda el proveedor al que pertenece el pedido
	 *
	 * @param Proveedor $p Proveedor al que pertenece el pedido
	 *
	 * @return void
	 */
	public function setProveedor(Proveedor $p): void {
		$this->proveedor = $p;
	}

	/**
	 * Carga el proveedor al que pertenece el pedido
	 *
	 * @return void
	 */
	public function loadProveedor(): void {
		$p = new Proveedor();
		$p->find(['id' => $this->get('id_proveedor')]);
		$this->setProveedor($p);
	}
}
