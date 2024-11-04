<?php
use Osumi\OsumiFramework\App\Component\Model\HistoricoArticulo\HistoricoArticuloComponent;

foreach ($list as $i => $historicoarticulo) {
  $component = new HistoricoArticuloComponent([ 'historicoarticulo' => $historicoarticulo ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
