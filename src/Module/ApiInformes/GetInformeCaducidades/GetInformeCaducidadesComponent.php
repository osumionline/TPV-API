<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiInformes\GetInformeCaducidades;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\InformesService;
use Osumi\OsumiFramework\App\DTO\CaducidadesDTO;

class GetInformeCaducidadesComponent extends OComponent {
	private ?InformesService $is = null;

	public string $data = '[]';

	public function __construct() {
		parent::__construct();
		$this->as = inject(InformesService::class);
	}

	/**
   * Función para obtener los datos para generar el informe de caducidades
	 *
	 * @param CaducidadesDTO $data Filtros para buscar las caducidades
	 * @return void
	 */
	public function run(CaducidadesDTO $data): void {
		$this->data = $this->is->getInformeCaducidades($data);
	}
}
