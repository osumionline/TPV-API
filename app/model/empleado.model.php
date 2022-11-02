<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\ODB;

class Empleado extends OModel {
	function __construct() {
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
				'nullable' => true,
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
				'type'    => OModel::DATE,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de baja del empleado'
			]
		];

		parent::load($model);
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

	private ?array $roles = null;

	/**
	 * Obtiene el listado de roles de un empleado
	 *
	 * @return array Listado de roles
	 */
	public function getRoles(): array {
		if (is_null($this->roles)) {
			$this->loadRoles();
		}
		return $this->roles;
	}

	/**
	 * Guarda la lista de roles
	 *
	 * @param array $r Lista de roles
	 *
	 * @return void
	 */
	public function setRoles(array $r): void {
		$this->roles = $r;
	}

	/**
	 * Carga la lista de roles de un empleado
	 *
	 * @return void
	 */
	public function loadRoles(): void {
		$db = new ODB();
		$sql = "SELECT `id_rol` FROM `empleado_rol` WHERE `id_empleado` = ?";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			array_push($list, $res['id_rol']);
		}

		$this->setRoles($list);
	}
}
