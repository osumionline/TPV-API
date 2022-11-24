<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\TipoPago;
use OsumiFramework\App\Component\Api\DatosCierreComponent;

#[OModuleAction(
	url: '/get-cierre-caja',
	services: ['general']
)]
class getCierreCajaAction extends OAction {
	/**
	 * Función para obtener los datos de cierre de una caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status   = 'ok';
		$date     = $req->getParamString('date');
		$datos_cierre_component = new DatosCierreComponent(['datos' => null]);

		if (is_null($date)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$c = $this->general_service->getCaja($date);
			if (!is_null($c)) {
				$datos = $this->general_service->getVentasDia($c);
				$pagos_caja = $this->general_service->getPagosCajaDia($c);
				$datos_cierre = [];

				$datos_cierre['saldo_inicial']    = $c->get('importe_apertura');
				$datos_cierre['importe_efectivo'] = $datos['venta_efectivo'];
				$datos_cierre['salidas_caja']     = $pagos_caja['importe'];
				$datos_cierre['saldo_final']      = $datos['ventas'] - $pagos_caja['importe'];
				$datos_cierre['tipos']            = [];

				foreach ($datos['tipos_pago'] as $tipo) {
					$tp = new TipoPago();
					$tp->find(['id' => $tipo['id']]);
					$tipo_datos = [];
					$tipo_datos['id']          = $tp->get('id');
					$tipo_datos['nombre']      = $tp->get('nombre');
					$tipo_datos['ventas']      = $tipo['importe_total'];
					$tipo_datos['operaciones'] = $tipo['operaciones'];

					array_push($datos_cierre['tipos'], $tipo_datos);
				}
				$datos_cierre_component->setValue('datos', $datos_cierre);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('datos',  $datos_cierre_component);
	}
}
