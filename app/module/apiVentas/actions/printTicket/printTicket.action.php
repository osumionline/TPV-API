<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Venta;

#[OModuleAction(
	url: '/print-ticket',
	services: ['imprimir']
)]
class printTicketAction extends OAction {
	/**
	 * Función para reimprimir un ticket
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$regalo = $req->getParamBool('regalo');

		if (is_null($id) || is_null($regalo)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$venta = new Venta();
			if ($venta->find(['id' => $id])) {
				$ticket_pdf = $this->imprimir_service->generateTicket($venta, $regalo);
				if (PHP_OS_FAMILY == 'Windows') {
					$comando =  '"'.$this->getConfig()->getExtra('foxit').'" -t "'.str_ireplace('/', "\\", $ticket_pdf).'" '.$this->getConfig()->getExtra('impresora');
				}
				else {
					$comando = "lpr -P ".$this->getConfig()->getExtra('impresora')." ".$ticket_pdf." &";
				}
				$this->getLog()->debug($comando);
				exec($comando, $salida);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
