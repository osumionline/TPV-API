<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiAlmacen\GetCaducidades;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\AlmacenService;
use Osumi\OsumiFramework\App\DTO\CaducidadesDTO;
use Osumi\OsumiFramework\App\Component\Model\CaducidadList\CaducidadListComponent;

class GetCaducidadesComponent extends OComponent {
	private ?AlmacenService $as = null;

	public string $status = 'ok';
	public ?CaducidadListComponent $list = null;
	public int $pags = 0;
	public int $total_unidades = 0;
	public float $total_pvp = 0;
	public float $total_puc = 0;

	public function __construct() {
		parent::__construct();
		$this->as = inject(AlmacenService::class);
		$this->list = new CaducidadListComponent(['list' => []]);
	}

	/**
   * Función para obtener el listado de caducidades
	 *
	 * @param CaducidadesDTO $data Filtros para buscar las caducidades
	 * @return void
	 */
	public function run(CaducidadesDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$caducidades = $this->as->getCaducidades($data);

			$this->list->list     = $caducidades['list'];
			$this->pags           = $caducidades['pags'];
			$this->total_unidades = $caducidades['total_unidades'];
			$this->total_pvp      = $caducidades['total_pvp'];
			$this->total_puc      = $caducidades['total_puc'];
		}
	}
}
