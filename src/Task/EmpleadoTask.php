<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Model\Empleado;

class EmpleadoTask extends OTask {
	public function __toString() {
		return "empleado: Crea un nuevo empleado";
	}

	public function run(array $options = []): void {
		if (count($options) !== 3) {
			echo "\nERROR: Tienes que indicar el nombre de usuario, su contraseña y su color.\n\n";
			echo "  ofw empleado --nombre nombre --pass contraseña --color ff0000\n\n";
			exit();
		}

		$nombre = $options['nombre'];
		$pass   = $options['pass'];
		$color  = $options['color'];

		if (strlen($color) !== 6) {
			echo "\nERROR: El color debe ser una cadena hexadecimal de 6 caracteres.\n\n";
			echo "  ofw empleado --nombre nombre --pass contraseña --color ff0000\n\n";
			exit();
		}

		$empleado = Empleado::create();
		$empleado->nombre = $nombre;
		$empleado->pass   = password_hash($pass, PASSWORD_BCRYPT);
		$empleado->color  = $color;
		$empleado->save();

		echo "\nNuevo empleado {$nombre} creado con id: {$empleado->id}\n\n";
	}
}
