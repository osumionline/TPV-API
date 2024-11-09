<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Venta;

class Empleado extends OModel {
	#[OPK(
	  comment: 'Id único para cada empleado'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Nombre del empleado',
	  nullable: false,
	  max: 50,
	  default: null
	)]
	public ?string $nombre;

	#[OField(
	  comment: 'Contraseña cifrada del empleado',
	  nullable: true,
	  max: 200,
	  default: null
	)]
	public ?string $pass;

	#[OField(
	  comment: 'Código de color hexadecimal para distinguir a cada empleado',
	  nullable: false,
	  max: 6,
	  default: null
	)]
	public ?string $color;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	#[OField(
	  comment: 'Fecha de borrado del cliente',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;

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
		$this->setVentas(Venta::where(['id_empleado' => $this->id]));
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
		$roles = EmpleadoRol::where(['id_empleado' => $this->id]);
		$list = [];

		foreach ($roles as $role) {
			$list[] = $role->id_rol;
		}

		$this->setRoles($list);
	}
}
