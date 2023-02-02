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
			$status = 'error-date';
		}

		if ($status=='ok') {
			$c = $this->general_service->getCaja($date);
			if (!is_null($c)) {
				$datos = $this->general_service->getVentasDia($c);
				$pagos_caja = $this->general_service->getPagosCajaDia($c);
				$datos_cierre = [];

				$datos_cierre['date']             = $date;
				$datos_cierre['saldo_inicial']    = $c->get('importe_apertura');
				$datos_cierre['importe_efectivo'] = $datos['venta_efectivo'];
				$datos_cierre['salidas_caja']     = $pagos_caja['importe'];
				$datos_cierre['saldo_final']      = ($datos_cierre['saldo_inicial'] + $datos_cierre['importe_efectivo']) - $pagos_caja['importe'];
				$datos_cierre['real']             = 0;
				$datos_cierre['importe1c']        = $c->get('importe1c');
				$datos_cierre['importe2c']        = $c->get('importe2c');
				$datos_cierre['importe5c']        = $c->get('importe5c');
				$datos_cierre['importe10c']       = $c->get('importe10c');
				$datos_cierre['importe20c']       = $c->get('importe20c');
				$datos_cierre['importe50c']       = $c->get('importe50c');
				$datos_cierre['importe1']         = $c->get('importe1');
				$datos_cierre['importe2']         = $c->get('importe2');
				$datos_cierre['importe5']         = $c->get('importe5');
				$datos_cierre['importe10']        = $c->get('importe10');
				$datos_cierre['importe20']        = $c->get('importe20');
				$datos_cierre['importe50']        = $c->get('importe50');
				$datos_cierre['importe100']       = $c->get('importe100');
				$datos_cierre['importe200']       = $c->get('importe200');
				$datos_cierre['importe500']       = $c->get('importe500');
				$datos_cierre['retirado']         = 0;
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
				$status = 'error-null';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('datos',  $datos_cierre_component);
	}
}
