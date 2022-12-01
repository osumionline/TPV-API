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
			'albaran_factura' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => true,
				'comment' => 'Indica si se trata de un albarán 1 o una factura 0'
			],
			'num_albaran_factura' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 200,
				'comment' => 'Albarán / factura del pedido'
			],
			'importe' => [
				'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => 0,
				'comment'  => 'Importe total del pedido'
			],
			'portes' => [
				'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => 0,
				'comment'  => 'Importe de los portes del pedido'
			],
			'fecha_pago' => [
				'type'    => OModel::DATE,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de pago del pedido'
			],
			'fecha_pedido' => [
				'type'    => OModel::DATE,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha del pedido'
			],
			're' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Indica si el pedido tiene RE 1 o no 0'
			],
			'europeo' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Indica si se rata de un pedido europeo 1 o no 0'
			],
			'faltas' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Indica si hay faltas en el pedido 1 o no 0'
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
	
	private ?array $lineas = null;

	/**
	 * Obtiene el listado de líneas de un pedido
	 *
	 * @return array Listado de líneas
	 */
	public function getLineas(): array {
		if (is_null($this->lineas)) {
			$this->loadLineas();
		}
		return $this->lineas;
	}

	/**
	 * Guarda la lista de líneas
	 *
	 * @param array $l Lista de líneas
	 *
	 * @return void
	 */
	public function setLineas(array $l): void {
		$this->lineas = $l;
	}

	/**
	 * Carga la lista de líneas de un pedido
	 *
	 * @return void
	 */
	public function loadLineas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `linea_pedido` WHERE `id_pedido` = ?";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$lp = new LineaPedido();
			$lp->update($res);
			array_push($list, $lp);
		}

		$this->setLineas($list);
	}
}
