<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetCierreCaja;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\GeneralService;
use Osumi\OsumiFramework\App\Model\TipoPago;
use Osumi\OsumiFramework\App\Component\Api\DatosCierre\DatosCierreComponent;

class GetCierreCajaComponent extends OComponent {
  private ?GeneralService $gs = null;

  public string $status = 'ok';
  public ?DatosCierreComponent $datos = null;

  public function __construct() {
    parent::__construct();
    $this->gs = inject(GeneralService::class);
    $this->datos = new DatosCierreComponent();
  }

	/**
	 * Función para obtener los datos de cierre de una caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$date = $req->getParamString('date');

		if (is_null($date)) {
			$this->status = 'error-date';
		}

		if ($this->status === 'ok') {
			$c = $this->gs->getCaja($date);
			if (!is_null($c)) {
				$datos_dia    = $this->gs->getVentasDia($c);
				$pagos_caja   = $this->gs->getPagosCajaDia($c);
				$datos_cierre = [];

				$datos_cierre['date']             = $date;
				$datos_cierre['saldo_inicial']    = $c->importe_apertura;
				$datos_cierre['importe_efectivo'] = $datos_dia['venta_efectivo'];
				$datos_cierre['salidas_caja']     = $pagos_caja['importe'];
				$datos_cierre['saldo_final']      = ($datos_cierre['saldo_inicial'] + $datos_cierre['importe_efectivo']) - $pagos_caja['importe'];
				$datos_cierre['real']             = 0;
				$datos_cierre['importe1c']        = $c->importe1c;
				$datos_cierre['importe2c']        = $c->importe2c;
				$datos_cierre['importe5c']        = $c->importe5c;
				$datos_cierre['importe10c']       = $c->importe10c;
				$datos_cierre['importe20c']       = $c->importe20c;
				$datos_cierre['importe50c']       = $c->importe50c;
				$datos_cierre['importe1']         = $c->importe1;
				$datos_cierre['importe2']         = $c->importe2;
				$datos_cierre['importe5']         = $c->importe5;
				$datos_cierre['importe10']        = $c->importe10;
				$datos_cierre['importe20']        = $c->importe20;
				$datos_cierre['importe50']        = $c->importe50;
				$datos_cierre['importe100']       = $c->importe100;
				$datos_cierre['importe200']       = $c->importe200;
				$datos_cierre['importe500']       = $c->importe500;
				$datos_cierre['retirado']         = 0;
				$datos_cierre['entrada']          = 0;
				$datos_cierre['tipos']            = [];

				foreach ($datos_dia['tipos_pago'] as $tipo) {
					$tp = TipoPago::findOne(['id' => $tipo['id']]);
					$tipo_datos = [];
					$tipo_datos['id']          = $tp->id;
					$tipo_datos['nombre']      = $tp->nombre;
					$tipo_datos['ventas']      = $tipo['importe_total'];
					$tipo_datos['operaciones'] = $tipo['operaciones'];

					$datos_cierre['tipos'][] = $tipo_datos;
				}
				$this->datos->datos = $datos_cierre;
			}
			else {
				$this->status = 'error-null';
			}
		}
	}
}
