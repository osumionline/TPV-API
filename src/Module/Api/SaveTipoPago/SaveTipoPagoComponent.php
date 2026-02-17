<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveTipoPago;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Service\GeneralService;
use Osumi\OsumiFramework\App\DTO\TipoPagoDTO;
use Osumi\OsumiFramework\App\Model\TipoPago;

class SaveTipoPagoComponent extends OComponent {
	private ?GeneralService $gs = null;

  public string              $status = 'ok';
	public string | int | null $id     = 'null';

  public function __construct() {
    parent::__construct();
    $this->gs = inject(GeneralService::class);
  }

	/**
	 * Función para guardar un tipo de pago
	 *
	 * @param TipoPagoDTO $data Objeto con toda la información sobre un tipo de pago
	 * @return void
	 */
	public function run(TipoPagoDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$tp = TipoPago::create();
			if (!is_null($data->id)) {
				$tp = TipoPago::findOne(['id' => $data->id]);
			}
			$orden = $data->orden;
			if (is_null($orden)) {
				$orden = $this->gs->getNewTipoPagoOrden();
			}
			$tp->nombre      = urldecode($data->nombre);
			$tp->slug        = OTools::slugify(urldecode($data->nombre));
			$tp->afecta_caja = $data->afectaCaja;
			$tp->orden       = $orden;
			$tp->fisico      = $data->fisico;

			$tp->save();

			if (!is_null($data->foto) && !str_starts_with($data->foto, 'http') && !str_starts_with($data->foto, '/img')) {
				$ruta = $tp->getRutaFoto();
				// Si ya tenía una imagen, primero la borro
				if (file_exists($ruta)) {
					unlink($ruta);
				}
				$this->gs->saveFoto($data->foto, $tp);
			}

			$this->id = $tp->id;
		}
	}
}
