<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Empleado;
use OsumiFramework\App\Model\EmpleadoRol;

class empleadosService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
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

		while ($res=$db->next()) {
			$empleado = new Empleado();
			$empleado->update($res);
			$empleado->loadRoles();
			array_push($list, $empleado);
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
		$db->query($sql, [$empleado->get('id')]);

		foreach ($roles as $id_rol) {
			$er = new EmpleadoRol();
			$er->set('id_empleado', $empleado->get('id'));
			$er->set('id_rol', $id_rol);
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
		$empleado = new Empleado();
		if ($empleado->find(['id' => $id_empleado])) {
			$empleado->set('deleted_at', date('Y-m-d H:i:s', time()));
			$empleado->save();
			return true;
		}
		return false;
	}
}
