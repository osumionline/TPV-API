<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\SendTicket;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Plugins\OEmailSMTP;
use Osumi\OsumiFramework\App\Service\ImprimirService;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Utils\AppData;
use Osumi\OsumiFramework\App\Component\Imprimir\TicketEmail\TicketEmailComponent;

class SendTicketComponent extends OComponent {
  private ?ImprimirService $is = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->is = inject(ImprimirService::class);
  }

	/**
	 * Función para enviar un ticket por email a un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id            = $req->getParamInt('id');
		$email_address = $req->getParamString('email');

		if (is_null($id) || is_null($email_address)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$venta = Venta::findOne(['id' => $id]);
			if (!is_null($venta)) {
				$app_data_file = $this->getConfig()->getDir('ofw_cache') . 'app_data.json';
				$app_data = new AppData($app_data_file);
				if (!$app_data->getLoaded()) {
					echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
					exit();
				}

				$email_conf = $this->getConfig()->getPluginConfig('email_smtp');

				$ticket_pdf = $this->is->generateTicket($venta, 'venta');

        try {
  				$content = new TicketEmailComponent(['id' => $venta->id, 'nombre' => $app_data->getNombre()]);
  				$email = new OEmailSMTP();
  				$email->addRecipient(urldecode($email_address));
  				$email->setSubject($app_data->getNombre().' - Ticket venta '.$venta->id);
  				$email->setMessage(strval($content));
  				$email->setFrom($email_conf['user']);
  				$email->addAttachment($ticket_pdf);
  				$email->send();
        }
        catch (Throwable $t) {
          $this->getLog()->error("Error enviando email: " . $t->getMessage());
          $this->status = 'ok-email-error';
        }
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
