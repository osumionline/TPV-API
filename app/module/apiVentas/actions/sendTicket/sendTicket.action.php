<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Plugins\OEmailSMTP;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Component\Ticket\TicketEmailComponent;

#[OModuleAction(
	url: '/send-ticket',
	services: ['ticket']
)]
class sendTicketAction extends OAction {
	/**
	 * Función para enviar un ticket por email a un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$email_address = $req->getParamString('email');

		if (is_null($id) || is_null($email_address)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$venta = new Venta();
			if ($venta->find(['id' => $id])) {
				$ticket_pdf = $this->ticket_service->generateTicket($venta, false);

				$content = new TicketEmailComponent();
				$email = new OEmailSMTP();
				$email->addRecipient(urldecode($email_address));
				$email->setSubject('TIENDA - Ticket venta X');
				$email->setMessage(strval($content));
				$email->setFrom('hola@indomablestore.com');
				$email->addAttachment($ticket_pdf);
				$email->send();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
