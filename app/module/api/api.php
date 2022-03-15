<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Model\Caja;
use OsumiFramework\App\Service\generalService;
use OsumiFramework\App\DTO\InstallationDTO;

#[ORoute(
	type: 'json',
	prefix: '/api'
)]
class api extends OModule {
	private ?generalService   $general_service = null;

	function __construct() {
		$this->general_service = new generalService();
	}

	/**
	 * Función para obtener los datos iniciales de configuración y comprobar el cierre de caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/check-start')]
	public function checkStart(ORequest $req): void {
		$status   = 'ok';
		$date     = $req->getParamString('date');
		$opened   = 'false';
		$app_data = 'null';
		$tipos_pago = [];

		if (is_null($date)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$opened     = $this->general_service->getOpened($date) ? 'true' : 'false';
			$app_data   = $this->general_service->getAppData();
			$tipos_pago = $this->general_service->getTiposPago();
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('opened',  $opened);
		$this->getTemplate()->add('appData', $app_data, 'nourlencode');
		$this->getTemplate()->addComponent('tiposPago', 'model/tipo_pago_list', ['list' => $tipos_pago, 'extra' => 'nourlencode']);
	}

	/**
	 * Función guardar los datos iniciales de configuración
	 *
	 * @param InstallationDTO $data Objeto con la información sobre la instalación
	 * @return void
	 */
	#[ORoute('/save-installation')]
	public function saveInstallation(InstallationDTO $data): void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$this->general_service->saveAppData($data);
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para abrir la caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/open-box')]
	public function openBox(ORequest $req): void {
		$status = 'ok';

		$caja = new Caja();
		$caja->set('apertura',         date('Y-m-d H:i:s', time()));
		$caja->set('cierre',           null);
		$caja->set('ventas',           null);
		$caja->set('beneficios',       null);
		$caja->set('venta_efectivo',   null);
		$caja->set('venta_otros',      null);
		$caja->set('importe_apertura', null);
		$caja->set('importe_cierre',   null);

		$caja->save();

		$previous_id = $caja->get('id') -1;
		$previous_caja = new Caja();
		if ($previous_caja->find(['id'=>$previous_id])) {
			// La anterior caja se cierra en el momento en que la nueva se abre
			$previous_caja->set('cierre', $caja->get('apertura', 'Y-m-d H:i:s'));

			// Al cerrar la anterior caja actualizamos los valores comprobando las ventas
			$datos = $this->general_service->getVentasDia($previous_caja);

			$previous_caja->set('ventas', $datos['ventas']);
			$previous_caja->set('beneficios', $datos['beneficios'] - $this->general_service->getPagosCajaDia($previous_caja));
			$previous_caja->set('venta_efectivo', $datos['venta_efectivo']);
			$previous_caja->set('venta_otros', $datos['venta_otros']);
			$previous_caja->set('importe_cierre', $previous_caja->get('importe_apertura') + $datos['venta_efectivo']);

			$previous_caja->save();

			// Al abrir una caja nueva el importe que debería haber en caja es el que había al cerrar la anterior
			$caja->set('importe_apertura', $previous_caja->get('importe_cierre'));
			$caja->save();
		}

		$this->getTemplate()->add('status', $status);
	}
}
