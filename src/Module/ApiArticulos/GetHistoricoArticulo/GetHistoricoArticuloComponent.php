<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiArticulos\GetHistoricoArticulo;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\ArticulosService;
use Osumi\OsumiFramework\App\DTO\HistoricoArticuloDTO;
use Osumi\OsumiFramework\App\Component\Model\HistoricoArticuloList\HistoricoArticuloListComponent;

class GetHistoricoArticuloComponent extends OComponent {
  private ?ArticulosService $ars = null;

  public string $status = 'ok';
  public int    $pags   = 0;
  public ?HistoricoArticuloListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->ars = inject(ArticulosService::class);
    $this->list = new HistoricoArticuloListComponent();
  }

	/**
	 * Función para obtener los datos históricos de un artículo
	 *
	 * @param HistoricoArticuloDTO $data Objeto con la información del artículo a buscar
	 * @return void
	 */
	public function run(HistoricoArticuloDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
      $this->pags       = $this->ars->getHistoricoArticuloPags($data->id);
			$this->list->list = $this->ars->getHistoricoArticulo($data);
		}
	}
}
