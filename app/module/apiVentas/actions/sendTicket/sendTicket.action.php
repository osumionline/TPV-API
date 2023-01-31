<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Plugins\OEmailSMTP;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Utils\AppData;
use OsumiFramework\App\Component\Imprimir\TicketEmailComponent;

#[OModuleAction(
	url: '/send-ticket',
	services: ['imprimir']
)]
class sendTicketAction extends OAction {
	/**
	 * Función para enviar un ticket por email a un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';
		$status = 'ok';
		$id = $req->getParamInt('id');
		$email_address = $req->getParamString('email');

		if (is_null($id) || is_null($email_address)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$venta = new Venta();
			if ($venta->find(['id' => $id])) {
				$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
				$app_data = new AppData($app_data_file);
				if (!$app_data->getLoaded()) {
					echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
					exit();
				}

				$email_conf = $this->getConfig()->getPluginConfig('email_smtp');

				$ticket_pdf = $this->imprimir_service->generateTicket($venta, false);

				$content = new TicketEmailComponent(['id' => $venta->get('id'), 'nombre' => $app_data->getNombre()]);
				$email = new OEmailSMTP();
				$email->addRecipient(urldecode($email_address));
				$email->setSubject('TIENDA - Ticket venta '.$venta->get('id'));
				$email->setMessage(strval($content));
				$email->setFrom($email_conf['user']);
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
