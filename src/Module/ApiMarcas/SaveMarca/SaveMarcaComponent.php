<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiMarcas\SaveMarca;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\MarcasService;
use Osumi\OsumiFramework\App\DTO\MarcaDTO;
use Osumi\OsumiFramework\App\Model\Marca;
use Osumi\OsumiFramework\App\Model\Proveedor;
use Osumi\OsumiFramework\App\Model\ProveedorMarca;

class SaveMarcaComponent extends OComponent {
  private ?MarcasService $ms = null;

  public string       $status = 'ok';
  public string | int $id     = 'null';

  public function __construct() {
    parent::__construct();
		$this->ms = inject(MarcasService::class);
  }

	/**
	 * Función para guardar una marca
	 *
	 * @param MarcaDTO $data Objeto con toda la información sobre una marca
	 * @return void
	 */
	public function run(MarcaDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$marca = Marca::create();
			if (!is_null($data->id)) {
				$marca = Marca::findOne(['id' => $data->id]);
			}
			else {
				if ($this->ms->checkNombreMarca(urldecode($data->nombre))) {
					$this->status = 'error-nombre';
				}
			}

			if ($this->status === 'ok') {
        $marca->nombre        = urldecode($data->nombre);
				$marca->direccion     = is_null($data->direccion)     ? null : urldecode($data->direccion);
				$marca->telefono      = is_null($data->telefono)      ? null : urldecode($data->telefono);
				$marca->email         = is_null($data->email)         ? null : urldecode($data->email);
				$marca->web           = is_null($data->web)           ? null : urldecode($data->web);
				$marca->observaciones = is_null($data->observaciones) ? null : urldecode($data->observaciones);
				$marca->save();

				if (!is_null($data->foto) && !str_starts_with($data->foto, 'http') && !str_starts_with($data->foto, '/img')) {
					$ruta = $marca->getRutaFoto();
					// Si ya tenía una imagen, primero la borro
					if (file_exists($ruta)) {
						unlink($ruta);
					}
					$this->ms->saveFoto($data->foto, $marca);
				}

				// Si tiene el check de crear proveedor, creo uno nuevo con los mismos datos de la marca
				if ($data->crear_proveedor) {
					$proveedor = Proveedor::create();
          $proveedor->nombre        = urldecode($data->nombre);
					$proveedor->direccion     = is_null($data->direccion)     ? null : urldecode($data->direccion);
					$proveedor->telefono      = is_null($data->telefono)      ? null : urldecode($data->telefono);
					$proveedor->email         = is_null($data->email)         ? null : urldecode($data->email);
					$proveedor->web           = is_null($data->web)           ? null : urldecode($data->web);
					$proveedor->observaciones = is_null($data->observaciones) ? null : urldecode($data->observaciones);
					$proveedor->save();

					// Si la marca tiene foto, se la copio al proveedor
					if (file_exists($marca->getRutaFoto())) {
						if (file_exists($proveedor->getRutaFoto())) {
							unlink($proveedor->getRutaFoto());
						}
						copy($marca->getRutaFoto(), $proveedor->getRutaFoto());
					}

					// Asocio la marca al proveedor recien creaddo
					$pm = ProveedorMarca::create();
					$pm->id_proveedor = $proveedor->id;
					$pm->id_marca     = $marca->id;
					$pm->save();
				}

        $this->id = $marca->id;
			}
		}
	}
}
