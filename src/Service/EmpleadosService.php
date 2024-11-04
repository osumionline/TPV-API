<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Empleado;
use Osumi\OsumiFramework\App\Model\EmpleadoRol;

class EmpleadosService extends OService {
	/**
	 * Comprueba el inicio de sesión de un empleado
	 *
	 * @param int $id Id del usuario
	 *
	 * @param string $pass Contraseña introducida por el empleado
	 *
	 * @return ?Empleado Devuelve el empleado en caso de datos correctos o null en caso contrario
	 */
	public function login(int $id, string $pass): ?Empleado {
		$empleado = Empleado::findOne(['id' => $id]);
		if (!is_null($empleado) && password_verify($pass, $empleado->pass)) {
			return $empleado;
		}
		return null;
	}

	/**
	 * Devuelve la lista completa de empleados
	 *
	 * @return array Lista de empleados
	 */
	public function getEmpleados(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `empleado` WHERE `deleted_at` IS NULL ORDER BY `nombre`";
		$db->query($sql);
		$list = [];

		while ($res = $db->next()) {
			$empleado = Empleado::from($res);
			$empleado->loadRoles();
			$list[] = $empleado;
		}

		return $list;
	}

	/**
	 * Función para actualizar la lista de permisos de un empleado
	 *
	 * @param Empleado Empleado al que hay que actualizar la lista de permisos
	 *
	 * @param array Lista de permisos nueva del empleado
	 *
	 * @return void
	 */
	public function updateRoles(Empleado $empleado, array $roles): void {
		$db = new ODB();
		$sql = "DELETE FROM `empleado_rol` WHERE `id_empleado` = ?";
		$db->query($sql, [$empleado->id]);

		foreach ($roles as $id_rol) {
			$er = EmpleadoRol::create();
			$er->id_empleado = $empleado->id;
			$er->id_rol      = $id_rol;
			$er->save();
		}
	}

	/**
	 * Función para borrar un empleado y limpiar sus ventas asociadas
	 *
	 * @param int $id_empleado Id del empleado a borrar
	 *
	 * @return bool Devuelve si el empleado se ha encontrado y la operación ha sido correcta
	 */
	public function deleteEmpleado(int $id_empleado): bool {
		$empleado = Empleado::findOne(['id' => $id_empleado]);
		if (!is_null($empleado)) {
			$empleado->deleted_at = date('Y-m-d H:i:s', time());
			$empleado->save();
			return true;
		}
		return false;
	}
}
