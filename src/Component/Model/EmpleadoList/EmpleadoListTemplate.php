<?php
use Osumi\OsumiFramework\App\Component\Model\Empleado\EmpleadoComponent;

foreach ($list as $i => $empleado) {
  $component = new EmpleadoComponent([ 'empleado' => $empleado ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
