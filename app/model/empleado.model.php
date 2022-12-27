<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;

class Empleado extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada empleado'
			),
			new OModelField(
				name: 'nombre',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 50,
				comment: 'Nombre del empleado'
			),
			new OModelField(
				name: 'pass',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 200,
				comment: 'Contraseña cifrada del empleado'
			),
			new OModelField(
				name: 'color',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 6,
				comment: 'Código de color hexadecimal para distinguir a cada empleado'
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
				comment: 'Fecha de borrado del cliente'
			)
		);

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
