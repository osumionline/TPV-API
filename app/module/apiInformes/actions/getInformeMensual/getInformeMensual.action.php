<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Api\InformeMensualItemListComponent;

#[OModuleAction(
	url: '/get-informe-mensual',
	services: ['informes']
)]
class getInformeMensualAction extends OAction {
	/**
	 * Función para obtener los datos del informe mensual
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$month = $req->getParamInt('month');
		$year = $req->getParamInt('year');
		$informe_mensual_item_list_component = new InformeMensualItemListComponent(['list' => []]);

		if (is_null($month) || is_null($year)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$informe_mensual_item_list_component->setValue('list', $this->informes_service->getInformeMensual($month, $year));
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list', $informe_mensual_item_list_component);
	}
}
