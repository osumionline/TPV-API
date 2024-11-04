<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\SendTBai;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Plugins\OTicketBai;
use Osumi\OsumiFramework\App\Model\Venta;

class SendTBaiComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Acción para enviar una venta a TicketBai
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');

		$venta = Venta::findOne(['id' => $id]);
		if (is_null($venta)) {
			$this->status = 'id-error';
		}

		$tbai_conf = $this->getConfig()->getPluginConfig('ticketbai');
		if ($this->status === 'ok' && $tbai_conf['token'] !== '' && $tbai_conf['nif'] !== '') {
			$tbai = new OTicketBai( ($this->getConfig()->getEnvironment() === 'prod') );
			$this->getLog()->info(var_export($tbai, true));

			if ($tbai->checkStatus()) {
				$this->getLog()->info('TicketBai status OK');
				$response = $tbai->nuevoTbai($venta->getDatosTBai());
				if (is_array($response)) {
					$this->getLog()->info('TicketBai response OK');
					$venta->tbai_huella = $response['huella_tbai'];
					$venta->tbai_qr     = $response['qr'];
					$venta->tbai_url    = $response['url'];
					$venta->save();
				}
				else {
					$this->getLog()->error('Ocurrió un error al generar el TicketBai de la venta '.$venta->id);
					$this->getLog()->error(var_export($response, true));
					$this->status = 'error';
				}
			}
			else {
				$this->status = 'check-status-error';
			}
		}
	}
}
