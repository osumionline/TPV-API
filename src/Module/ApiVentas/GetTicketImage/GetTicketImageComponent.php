<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\GetTicketImage;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ImprimirService;
use Osumi\OsumiFramework\App\Model\Venta;

class GetTicketImageComponent extends OComponent {
	private ?ImprimirService $is = null;

	public function __construct() {
		parent::__construct();
		$this->is = inject(ImprimirService::class);
	}

	/**
   * Función para obtener la imagen de un ticket
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			echo "ERROR: Falta el id de la venta.";
			exit;
		}

		// Compruebo que exista la venta
		$venta = Venta::findOne(['id' => $id]);
		if (is_null($venta)) {
			echo "ERROR: No se encuentra la venta indicada.";
			exit;
		}

		// Compruebo que exista la imagen, sino la genero
		$file = $this->getConfig()->getDir('ofw_tmp') . "venta/{$venta->id}/ticket_{$venta->id}-venta_1.jpg";
		if (!file_exists($file)) {
			$ticket_pdf = $this->is->generateTicket($venta, 'venta');
		}

		// Si se ha generado correctamente la envío, sino error
		if (file_exists($file)) {
			header("Content-Type: image/jpeg");
			header("Content-Length: " . filesize($file));
			readfile($file);
			exit;
		}
		else {
			echo "ERROR: No se pudo generar la imagen del ticket.";
			exit;
		}
	}
}
