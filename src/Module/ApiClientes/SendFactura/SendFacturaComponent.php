<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\SendFactura;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Plugins\OEmailSMTP;
use Osumi\OsumiFramework\App\Service\ImprimirService;
use Osumi\OsumiFramework\App\Model\Factura;
use Osumi\OsumiFramework\App\Utils\AppData;
use Osumi\OsumiFramework\App\Component\Imprimir\FacturaEmail\FacturaEmailComponent;

class SendFacturaComponent extends OComponent {
	private ?ImprimirService $is = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
		$this->is = inject(ImprimirService::class);
  }

	/**
	 * Función para enviar por email una factura a un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$factura = Factura::findOne(['id' => $id]);
			if (!is_null($factura)) {
				$cliente = $factura->getCliente();
				if (!is_null($cliente->email) && $cliente->email !== '') {
					$app_data_file = $this->getConfig()->getDir('ofw_cache') . 'app_data.json';
					$app_data = new AppData($app_data_file);
					if (!$app_data->getLoaded()) {
						echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
						exit();
					}

					$email_conf = $this->getConfig()->getPluginConfig('email_smtp');

					$factura_pdf = $this->is->generateFactura($factura);

					$content = new FacturaEmailComponent(['id' => $factura->id, 'nombre' => $app_data->getNombre()]);
					$email = new OEmailSMTP();
					$email->addRecipient($cliente->email);
					$email->setSubject($app_data->getNombre() . ' - Factura ' . $factura->id);
					$email->setMessage(strval($content));
					$email->setFrom($email_conf['user']);
					$email->addAttachment($factura_pdf);
					$email->send();
				} else {
					$status = 'error';
				}
			} else {
				$status = 'error';
			}
		}
	}
}
