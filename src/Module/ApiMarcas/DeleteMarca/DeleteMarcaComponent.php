<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiMarcas\DeleteMarca;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\MarcasService;

class DeleteMarcaComponent extends OComponent {
  private ?MarcasService $ms = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
		$this->ms = inject(MarcasService::class);
  }

	/**
	 * Función para borrar una marca
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			if (!$this->ms->deleteMarca($id)) {
				$this->status = 'error';
			}
		}
	}
}
