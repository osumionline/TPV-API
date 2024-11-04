<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Osumi\OsumiFramework\Core\OCore;

$core = new OCore();
$core->load();

set_exception_handler([$core, 'errorHandler']);

$core->run();
