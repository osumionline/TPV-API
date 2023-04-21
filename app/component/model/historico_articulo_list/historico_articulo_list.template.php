<?php
use OsumiFramework\App\Component\Model\HistoricoArticuloComponent;

foreach ($values['list'] as $i => $historico_articulo) {
  $component = new HistoricoArticuloComponent([ 'historico_articulo' => $historico_articulo ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
