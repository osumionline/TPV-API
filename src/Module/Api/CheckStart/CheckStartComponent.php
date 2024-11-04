<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\CheckStart;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Service\GeneralService;
use Osumi\OsumiFramework\App\Component\Model\TipoPagoList\TipoPagoListComponent;

class CheckStartComponent extends OComponent {
  private ?GeneralService $gs = null;

  public string $opened   = 'false';
  public string $app_data = 'null';
  public ?TipoPagoListComponent $tipos_pago = null;

  public function __construct() {
    parent::__construct();
    $this->gs = inject(GeneralService::class);
    $this->tipos_pago = new TipoPagoListComponent();
  }

	/**
	 * Función para obtener los datos iniciales de configuración y comprobar el cierre de caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->opened   = $this->gs->getOpened() ? 'true' : 'false';
		$this->app_data = $this->gs->getAppData();
		$this->tipos_pago->list = $this->gs->getTiposPago();

		// Limpieza de carpeta tmp
    OTools::checkOfw('tmp');
		foreach (glob($this->getConfig()->getDir('ofw_tmp') . "*.html") as $nombre_fichero) {
    	unlink($nombre_fichero);
		}
		foreach (glob($this->getConfig()->getDir('ofw_tmp') . "*.pdf") as $nombre_fichero) {
    	unlink($nombre_fichero);
		}
		foreach (glob($this->getConfig()->getDir('ofw_tmp') . "*.png") as $nombre_fichero) {
    	unlink($nombre_fichero);
		}
	}
}
