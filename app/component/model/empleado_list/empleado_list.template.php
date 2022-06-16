<?php
use OsumiFramework\App\Component\EmpleadoComponent;

foreach ($values['list'] as $i => $empleado) {
	$empleado_component = new EmpleadoComponent([ 'empleado' => $empleado ]);
	echo strval($empleado_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
