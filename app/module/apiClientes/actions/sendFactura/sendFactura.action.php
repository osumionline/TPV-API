<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Plugins\OEmailSMTP;
use OsumiFramework\App\Model\Factura;
use OsumiFramework\App\Utils\AppData;
use OsumiFramework\App\Component\Imprimir\FacturaEmailComponent;

#[OModuleAction(
	url: '/send-factura',
	services: ['imprimir']
)]
class sendFacturaAction extends OAction {
	/**
	 * Función para enviar por email una factura a un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';
		$status = 'ok';
		$id     = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$factura = new Factura();
			if ($factura->find(['id' => $id])) {
				$cliente = $factura->getCliente();
				if (!is_null($cliente->get('email')) && $cliente->get('email') != '') {
					$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
					$app_data = new AppData($app_data_file);
					if (!$app_data->getLoaded()) {
						echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
						exit();
					}

					$email_conf = $this->getConfig()->getPluginConfig('email_smtp');

					$factura_pdf = $this->imprimir_service->generateFactura($factura);

					$content = new FacturaEmailComponent(['id' => $factura->get('id'), 'nombre' => $app_data->getNombre()]);
					$email = new OEmailSMTP();
					$email->addRecipient($cliente->get('email'));
					$email->setSubject($app_data->getNombre().' - Factura '.$factura->get('id'));
					$email->setMessage(strval($content));
					$email->setFrom($email_conf['user']);
					$email->addAttachment($factura_pdf);
					$email->send();
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
