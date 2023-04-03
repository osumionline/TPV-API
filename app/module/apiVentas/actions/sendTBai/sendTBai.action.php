<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Plugins\OTicketBai;
use OsumiFramework\App\Model\Venta;

#[OModuleAction(
	url: '/send-tbai/:id'
)]
class sendTBaiAction extends OAction {
	/**
	 * Acción para enviar una venta a TicketBai
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');

		$venta = new Venta();
		if (!$venta->find(['id' => $id])) {
			$status = 'id-error';
		}

		$tbai_conf = $this->getConfig()->getPluginConfig('ticketbai');
		if ($status == 'ok' && $tbai_conf['token'] !== '' && $tbai_conf['nif'] !== '') {
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
					$status = 'error';
				}
			}
			else {
				$status = 'check-status-error';
			}
		}

		$this->getTemplate()->add('status',  $status);
	}
}