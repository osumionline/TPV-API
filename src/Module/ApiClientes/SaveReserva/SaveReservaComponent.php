<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\SaveReserva;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\ImprimirService;
use Osumi\OsumiFramework\App\Service\VentasService;
use Osumi\OsumiFramework\App\DTO\VentaDTO;
use Osumi\OsumiFramework\App\Model\Reserva;
use Osumi\OsumiFramework\App\Model\LineaReserva;
use Osumi\OsumiFramework\App\Model\Articulo;

class SaveReservaComponent extends OComponent {
	private ?ImprimirService $is = null;
	private ?VentasService $vs = null;

  public string         $status  = 'ok';
	public string | int   $id      = 'null';
	public string | float $importe = 'null';

  public function __construct() {
    parent::__construct();
		$this->is = inject(ImprimirService::class);
		$this->vs = inject(VentasService::class);
  }

	/**
	 * Función para guardar una reserva
	 *
	 * @param VentaDTO Datos de la reserva
	 * @return void
	 */
	public function run(VentaDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$reserva = Reserva::create();
			$reserva->id_cliente = ($data->getIdCliente() !== -1) ? $data->getIdCliente() : null;
			$reserva->total      = $data->getTotal();
			$reserva->save();

			foreach ($data->getLineas() as $linea) {
				$nombre = $linea['descripcion'];
				$puc = 0;
				$pvp = $linea['pvp'];
				$iva = $linea['iva'];

				if ($linea['idArticulo'] !== 0) {
					$art = Articulo::findOne(['id' => $linea['idArticulo']]);
					$nombre = $art->nombre;
					$puc    = $art->puc;
					$pvp    = $art->pvp;
					$iva    = $art->iva;
				}

				$lr = LineaReserva::create();
				$lr->id_reserva      = $reserva->id;
				$lr->id_articulo     = $linea['idArticulo'] !== 0 ? $linea['idArticulo'] : null;
				$lr->nombre_articulo = $nombre;
				$lr->puc             = $puc;
				$lr->pvp             = $pvp;
				$lr->iva             = $iva;
				$this->importe = $linea['importe'];

				if (!$linea['descuentoManual']) {
					$lr->descuento         = $linea['descuento'];
					$lr->importe_descuento = null;
					$this->importe = ($this->importe * (1 - ($linea['descuento'] / 100)));
				} else {
					$lr->descuento         = null;
					$lr->importe_descuento = $linea['descuento'];
					$this->importe = $this->importe - $linea['descuento'];
				}

				$lr->importe  = $this->importe;
				$lr->unidades = $linea['cantidad'];
				$lr->save();

				// Reduzco el stock
				if ($linea['idArticulo'] !== 0) {
					$art->stock = $art->stock - $linea['cantidad'];
					$art->save();
				}
			}

			if ($data->imprimir === 'reserva') {
				$venta      = $this->vs->getVentaFromReserva($reserva);
				$ticket_pdf = $this->is->generateTicket($venta, 'reserva');
				$this->is->imprimirTicket($ticket_pdf);
			}

			$this->id      = $reserva->id;
			$this->importe = $reserva->total;
		}
	}
}
