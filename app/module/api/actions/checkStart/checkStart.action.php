<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\TipoPagoListComponent;

#[OModuleAction(
	url: '/check-start',
	services: ['general']
)]
class checkStartAction extends OAction {
	/**
	 * Función para obtener los datos iniciales de configuración y comprobar el cierre de caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$opened   = 'false';
		$app_data = 'null';
		$tipos_pago_component = new TipoPagoListComponent(['list' => []]);

		$opened   = $this->general_service->getOpened() ? 'true' : 'false';
		$app_data = $this->general_service->getAppData();
		$tipos_pago_component->setValue('list', $this->general_service->getTiposPago());

		// Limpieza de carpeta tmp
		foreach (glob($this->getConfig()->getDir('ofw_tmp')."*.html") as $nombre_fichero) {
    	unlink($nombre_fichero);
		}
		foreach (glob($this->getConfig()->getDir('ofw_tmp')."*.pdf") as $nombre_fichero) {
    	unlink($nombre_fichero);
		}
		foreach (glob($this->getConfig()->getDir('ofw_tmp')."*.png") as $nombre_fichero) {
    	unlink($nombre_fichero);
		}

		$this->getTemplate()->add('opened',    $opened);
		$this->getTemplate()->add('appData',   $app_data, 'nourlencode');
		$this->getTemplate()->add('tiposPago', $tipos_pago_component);
	}
}
