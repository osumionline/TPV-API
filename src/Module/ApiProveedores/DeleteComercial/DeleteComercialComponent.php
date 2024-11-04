<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiProveedores\DeleteComercial;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ProveedoresService;

class DeleteComercialComponent extends OComponent {
  private ?ProveedoresService $ps = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->ps = inject(ProveedoresService::class);
  }

	/**
	 * Función para borrar un comercial
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
			if (!$this->ps->deleteComercial($id)) {
				$this->status = 'error';
			}
		}
	}
}
