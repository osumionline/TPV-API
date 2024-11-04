<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiProveedores\SaveProveedor;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\ProveedoresService;
use Osumi\OsumiFramework\App\DTO\ProveedorDTO;
use Osumi\OsumiFramework\App\Model\Proveedor;

class SaveProveedorComponent extends OComponent {
  private ?ProveedoresService $ps = null;

  public string       $status = 'ok';
  public string | int $id     = 'null';

  public function __construct() {
    parent::__construct();
    $this->ps = inject(ProveedoresService::class);
  }

	/**
	 * Función para guardar un proveedor
	 *
	 * @param ProveedorDTO $data Objeto con toda la información sobre un proveedor
	 * @return void
	 */
	public function run(ProveedorDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$proveedor = Proveedor::create();
			if (!is_null($data->id)) {
				$proveedor = Proveedor::findOne(['id' => $data->id]);
			}
			else {
				if ($this->ps->checkNombreProveedor(urldecode($data->nombre))) {
					$this->status = 'error-nombre';
				}
			}

			if ($this->status === 'ok') {
        $proveedor->nombre        = urldecode($data->nombre);
				$proveedor->direccion     = urldecode($data->direccion);
				$proveedor->telefono      = urldecode($data->telefono);
				$proveedor->email         = urldecode($data->email);
				$proveedor->web           = urldecode($data->web);
				$proveedor->observaciones = urldecode($data->observaciones);
				$proveedor->save();

				if (!is_null($data->foto) && !str_starts_with($data->foto, 'http') && !str_starts_with($data->foto, '/')) {
					$ruta = $proveedor->getRutaFoto();
					// Si ya tenía una imagen, primero la borro
					if (file_exists($ruta)) {
						unlink($ruta);
					}
					$this->ps->saveFoto($data->foto, $proveedor);
				}

				$this->ps->updateProveedoresMarcas($proveedor->id, $data->marcas);

        $this->id = $proveedor->id;
			}
		}
	}
}
