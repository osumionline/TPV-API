<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\OFW\Plugins\OTicketBai;
use OsumiFramework\App\Model\Venta;

class ticketbaiTask extends OTask {
	public function __toString() {
		return "ticketbai: Tarea para enviar ventas a TicketBai";
	}

	public function run(array $options=[]): void {
		if (count($options) < 1) {
			echo "\nERROR: Tienes que indicar el id de la venta que quieres enviar a TicketBai.\n\n";
			echo "  ofw ticketbai 1\n\n";
			exit();
		}

		$id = $options[0];
		
		$venta = new Venta();
		if (!$venta->find(['id' => $id])) {
			echo "\nERROR: No se encuentra la venta indicada.\n\n";
			exit();
		}

		$tbai_conf = $this->getConfig()->getPluginConfig('ticketbai');
		if ($tbai_conf['token'] !== '' && $tbai_conf['nif'] !== '') {
			$tbai = new OTicketBai( ($this->getConfig()->getEnvironment()=='prod') );

			if ($tbai->checkStatus()) {
				$this->getLog()->info('TicketBai status OK');
				$response = $tbai->nuevoTbai($venta->getDatosTBai());
				if (is_array($response)) {
					$this->getLog()->info('TicketBai response OK');
					$venta->set('tbai_huella', $response['huella_tbai']);
					$venta->set('tbai_qr',     $response['qr']);
					$venta->set('tbai_url',    $response['url']);
					$venta->save();
				}
				else {
					$this->getLog()->error('Ocurrió un error al generar el TicketBai de la venta '.$venta->get('id'));
					$this->getLog()->error(var_export($response, true));
				}
			}
		}
	}
}