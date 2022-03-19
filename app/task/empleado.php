<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\App\Model\Empleado;

class empleadoTask extends OTask {
	public function __toString() {
		return "empleado: Crea un nuevo empleado";
	}

	public function run(array $options=[]): void {
		if (count($options) != 3) {
			echo "\nERROR: Tienes que indicar el nombre de usuario, su contraseña y su color.\n\n";
			echo "  ofw empleado nombre contraseña ff0000\n\n";
			exit();
		}

		$nombre = $options[0];
		$pass = $options[1];
		$color = $options[2];

		if (strlen($color) != 6) {
			echo "\nERROR: El color debe ser una cadena hexadecimal de 6 caracteres.\n\n";
			echo "  ofw empleado nombre contraseña ff0000\n\n";
			exit();
		}

		$empleado = new Empleado();
		$empleado->set('nombre', $nombre);
		$empleado->set('pass', password_hash($pass, PASSWORD_BCRYPT));
		$empleado->set('color', $color);
		$empleado->save();
		
		echo "\nNuevo empleado ".$nombre." creado con id: ".$empleado->get('id')."\n\n";
	}
}