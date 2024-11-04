<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\OpenBox;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\GeneralService;
use Osumi\OsumiFramework\App\Model\Caja;
use Osumi\OsumiFramework\App\Model\CajaTipo;

class OpenBoxComponent extends OComponent {
  private ?GeneralService $gs = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->gs = inject(GeneralService::class);
  }

	/**
	 * Función para abrir la caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$caja = Caja::create();
		$caja->apertura             = date('Y-m-d H:i:s', time());
    $caja->cierre               = null;
		$caja->ventas               = 0;
		$caja->beneficios           = 0;
		$caja->venta_efectivo       = 0;
		$caja->operaciones_efectivo = 0;
		$caja->descuento_efectivo   = 0;
		$caja->venta_otros          = 0;
		$caja->operaciones_otros    = 0;
		$caja->descuento_otros      = 0;
		$caja->importe_pagos_caja   = 0;
		$caja->num_pagos_caja       = 0;
		$caja->importe_apertura     = 0;
		$caja->importe_cierre       = 0;
		$caja->importe_cierre_real  = 0;
		$caja->importe1c            = 0;
		$caja->importe2c            = 0;
		$caja->importe5c            = 0;
		$caja->importe10c           = 0;
		$caja->importe20c           = 0;
		$caja->importe50c           = 0;
		$caja->importe1             = 0;
		$caja->importe2             = 0;
		$caja->importe5             = 0;
		$caja->importe10            = 0;
		$caja->importe20            = 0;
		$caja->importe50            = 0;
		$caja->importe100           = 0;
		$caja->importe200           = 0;
		$caja->importe500           = 0;
		$caja->importe_retirado     = 0;
		$caja->importe_entrada      = 0;

		$caja->save();

		$previous_id = $caja->id - 1;
		$previous_caja = Caja::findOne(['id' => $previous_id]);
		if (!is_null($previous_caja)) {
			// Si la caja anterior no fue cerrada manualmente, se cierra automáticamente
			if (is_null($previous_caja->cierre)) {
				// La anterior caja se cierra en el momento en que la nueva se abre
				$previous_caja->cierre = $caja->get('apertura', 'Y-m-d H:i:s');

				// Al cerrar la anterior caja actualizamos los valores comprobando las ventas
				$datos      = $this->gs->getVentasDia($previous_caja);
				$pagos_caja = $this->gs->getPagosCajaDia($previous_caja);

        $previous_caja->ventas               = $datos['ventas'];
				$previous_caja->beneficios           = $datos['beneficios'] - $pagos_caja['importe'];
				$previous_caja->venta_efectivo       = $datos['venta_efectivo'];
				$previous_caja->operaciones_efectivo = $datos['operaciones_efectivo'];
				$previous_caja->descuento_efectivo   = $datos['descuento_efectivo'];
				$previous_caja->venta_otros          = $datos['venta_otros'];
				$previous_caja->operaciones_otros    = $datos['operaciones_otros'];
				$previous_caja->descuento_otros      = $datos['descuento_otros'];

				$previous_caja->importe_pagos_caja = $pagos_caja['importe'];
				$previous_caja->num_pagos_caja     = $pagos_caja['num'];

				$previous_caja->importe_cierre = $previous_caja->importe_apertura + $datos['venta_efectivo'];
				// Si se cierra automáticamente no tenemos forma de saber el importe de cierre real, por lo que asumimos que es correcto
				$previous_caja->importe_cierre_real = $previous_caja->importe_cierre;
				// Si se cierra automáticamente no tenemos forma de saber si se ha retirado o añadido dinero de la caja, por lo que asumimos que no se ha retirado nada
				$previous_caja->importe_retirado = 0;
				$previous_caja->importe_entrada  = 0;

				$previous_caja->save();

				// Guardamos el desglosado de tipos de pago
				foreach ($datos['tipos_pago'] as $tp) {
					$ct = CajaTipo::create();
					$ct->id_caja           = $previous_caja->id;
					$ct->id_tipo_pago      = $tp['id'];
					$ct->operaciones       = $tp['operaciones'];
					$ct->importe_total     = $tp['importe_total'];
					$ct->importe_descuento = $tp['importe_descuento'];
					// Al ser un cierre de caja automático asumimos que el importe real es correcto
					$ct->importe_real      = $tp['importe_total'];

					$ct->save();
				}
			}

			// Al abrir una caja nueva el importe que debería haber en caja es el que había al cerrar la anterior
			$caja->importe_apertura = $previous_caja->importe_cierre_real + $previous_caja->importe_entrada;
			$caja->save();
		}
	}
}
