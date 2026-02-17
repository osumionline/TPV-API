<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiProveedores\SaveComercial;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\ComercialDTO;
use Osumi\OsumiFramework\App\Model\Comercial;

class SaveComercialComponent extends OComponent {
  public string       $status = 'ok';
  public int | string $id     = 'null';

	/**
	 * Función para guardar un comercial de un proveedor
	 *
	 * @param ComercialDTO $data Objeto con toda la información sobre un comercial
	 * @return void
	 */
	public function run(ComercialDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$comercial = Comercial::create();
			if (!is_null($data->id)) {
				$comercial = Comercial::findOne(['id' => $data->id]);
			}

      $comercial->id_proveedor  = $data->idProveedor;
			$comercial->nombre        = urldecode($data->nombre);
			$comercial->telefono      = !is_null($data->telefono)      ? urldecode($data->telefono)      : null;
			$comercial->email         = !is_null($data->email)         ? urldecode($data->email)         : null;
			$comercial->observaciones = !is_null($data->observaciones) ? urldecode($data->observaciones) : null;
			$comercial->save();

      $this->id = $comercial->id;
		}
	}
}
