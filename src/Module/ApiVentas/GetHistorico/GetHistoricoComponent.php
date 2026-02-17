<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\GetHistorico;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\VentasService;
use Osumi\OsumiFramework\App\DTO\HistoricoDTO;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Component\Model\VentaList\VentaListComponent;

class GetHistoricoComponent extends OComponent {
	private ?VentasService $vs = null;

  public string $status = 'ok';
	public ?VentaListComponent $list  = null;
	public string   $ventas_otros     = '';
	public float    $total_dia        = 0;
	public float    $ventas_efectivo  = 0;
	public float    $ventas_web       = 0;
	public float    $ventas_beneficio = 0;

  public function __construct() {
    parent::__construct();
    $this->vs = inject(VentasService::class);
		$this->list = new VentaListComponent();
  }

	/**
	 * Función para obtener el listado de ventas o detalle de una venta concreta
	 *
	 * @param HistoricoDTO $data Filtros usados para buscar ventas
	 * @return void
	 */
	public function run(HistoricoDTO $data): void {
		$ventas_otros_list = [];
		$otros_list = [];

		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok' && !(
			($data->modo === 'id'    && !is_null($data->id)) ||
			($data->modo === 'fecha' && !is_null($data->fecha)) ||
			($data->modo === 'rango' && !is_null($data->desde) && !is_null($data->hasta))
		)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$ventas_list = [];
			if ($data->modo !== 'id') {
				$ventas_list = $this->vs->getHistoricoVentas($data);
			} else {
				$venta = Venta::findOne(['id' => $data->id]);
				if (!is_null($venta)) {
					$ventas_list = [$venta];
				} else {
					$this->status = 'error';
				}
			}
			$this->list->list = $ventas_list;
			foreach ($ventas_list as $venta) {
				$this->total_dia       += $venta->total;
				$this->ventas_efectivo += $venta->getVentaEfectivo();
				if (!is_null($venta->id_tipo_pago)) {
					$tipo_pago = $venta->getTipoPago();
					if (!array_key_exists($tipo_pago->nombre, $otros_list)) {
						$otros_list[$tipo_pago->nombre] = 0;
					}
					if ($tipo_pago->fisico) {
						$otros_list[$tipo_pago->nombre] += $venta->getVentaOtros();
					} else {
						$this->ventas_web += $venta->getVentaOtros();
					}
				}
				$this->ventas_beneficio += $venta->getBeneficio();
			}
			foreach ($otros_list as $key => $value) {
				if ($value !== 0) {
					$ventas_otros_list[] = ['nombre' => $key, 'valor' => $value];
				}
			}
			$this->ventas_otros = json_encode($ventas_otros_list);
		}
	}
}
