<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\PrintTicket;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ImprimirService;
use Osumi\OsumiFramework\App\Model\Venta;

class PrintTicketComponent extends OComponent {
  private ?ImprimirService $is = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->is = inject(ImprimirService::class);
  }

	/**
	 * Función para reimprimir un ticket
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id   = $req->getParamInt('id');
		$tipo = $req->getParamString('tipo');

		if (is_null($id) || is_null($tipo)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$venta = Venta::findOne(['id' => $id]);
			if (!is_null($venta)) {
				$ticket_pdf = $this->is->generateTicket($venta, $tipo);
				if (PHP_OS_FAMILY === 'Windows') {
					$comando =  '"'.$this->getConfig()->getExtra('foxit').'" -t "'.str_ireplace('/', "\\", $ticket_pdf).'" '.$this->getConfig()->getExtra('impresora');
				}
				else {
					$comando = "lpr -P ".$this->getConfig()->getExtra('impresora')." ".$ticket_pdf." &";
				}
				exec($comando, $salida);
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
