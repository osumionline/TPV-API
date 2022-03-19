<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Empleado extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'empleado';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada empleado'
			],
			'nombre' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre del empleado'
			],
      'pass' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 200,
				'comment' => 'Contraseña cifrada del empleado'
			],
      'color' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 6,
				'comment' => 'Código de color hexadecimal para distinguir a cada empleado'
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
				'type'    => OModel::UPDATED,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de baja del empleado'
			]
		];

		parent::load($table_name, $model);
	}

  private ?array $ventas = null;

	/**
	 * Obtiene el listado de ventas de un empleado
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
	 * Carga la lista de ventas de un empleado
	 *
	 * @return void
	 */
	public function loadVentas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `venta` WHERE `id_empleado` = ?";
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
	 * Comprueba el inicio de sesión de un empleado
	 *
	 * @param int $id Id del usuario
	 *
	 * @param string $pass Contraseña introducida por el empleado
	 *
	 * @return bool Comprobación de contraseña correcta o incorrecta
	 */
	public function login(int $id, string $pass): bool {
		if ($this->find(['id'=>$id])) {
			if (password_verify($pass, $this->get('pass'))) {
				return true;
			}
		}
		return false;
	}
}
