<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\CerrarCaja;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\GeneralService;
use Osumi\OsumiFramework\App\DTO\CierreCajaDTO;
use Osumi\OsumiFramework\App\Model\CajaTipo;

class CerrarCajaComponent extends OComponent {
  private ?GeneralService $gs = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->gs = inject(GeneralService::class);
  }

	/**
	 * Función para realizar un cierre de caja
	 *
	 * @param CierreCajaDTO $data Objeto con toda la información para cerrar una caja
	 * @return void
	 */
	public function run(CierreCajaDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$c = $this->gs->getCaja($data->date);
			if (!is_null($c)) {
				$datos      = $this->gs->getVentasDia($c);
				$pagos_caja = $this->gs->getPagosCajaDia($c);

				$c->cierre               = date('Y-m-d H:i:s', time());
				$c->ventas               = $datos['ventas'];
				$c->beneficios           = $datos['beneficios'] - $pagos_caja['importe'];
				$c->venta_efectivo       = $datos['venta_efectivo'];
				$c->operaciones_efectivo = $datos['operaciones_efectivo'];
				$c->descuento_efectivo   = $datos['descuento_efectivo'];
				$c->venta_otros          = $datos['venta_otros'];
				$c->operaciones_otros    = $datos['operaciones_otros'];
				$c->descuento_otros      = $datos['descuento_otros'];

				$c->importe_pagos_caja   = $pagos_caja['importe'];
				$c->num_pagos_caja       = $pagos_caja['num'];

				$c->importe1c            = $data->importe1c;
				$c->importe2c            = $data->importe2c;
				$c->importe5c            = $data->importe5c;
				$c->importe10c           = $data->importe10c;
				$c->importe20c           = $data->importe20c;
				$c->importe50c           = $data->importe50c;
				$c->importe1             = $data->importe1;
				$c->importe2             = $data->importe2;
				$c->importe5             = $data->importe5;
				$c->importe10            = $data->importe10;
				$c->importe20            = $data->importe20;
				$c->importe50            = $data->importe50;
				$c->importe100           = $data->importe100;
				$c->importe200           = $data->importe200;
				$c->importe500           = $data->importe500;

				$c->importe_cierre       = $c->importe_apertura + $datos['venta_efectivo'];
				$c->importe_cierre_real  = $data->real;
				$c->importe_retirado     = $data->retirado;
				$c->importe_entrada      = $data->entrada;

				$c->save();

				// Guardamos el desglosado de tipos de pago
				foreach ($data->tipos as $tipo) {
					$ct = CajaTipo::create();
					$ct->id_caja           = $c->id;
					$ct->id_tipo_pago      = $tipo['id'];
					$ct->operaciones       = $datos['tipos_pago']['tipo_pago_' . $tipo['id']]['operaciones'];
					$ct->importe_total     = $datos['tipos_pago']['tipo_pago_' . $tipo['id']]['importe_total'];
					$ct->importe_descuento = $datos['tipos_pago']['tipo_pago_' . $tipo['id']]['importe_descuento'];
					$ct->importe_real      = $tipo['real'];

					$ct->save();
				}
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
