<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\SaveCliente;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\ClienteDTO;
use Osumi\OsumiFramework\App\Model\Cliente;

class SaveClienteComponent extends OComponent {
  public string       $status = 'ok';
  public string | int $id     = 'null';

	/**
	 * Función para guardar un cliente
	 *
	 * @param ClienteDTO $data Objeto con toda la información sobre un cliente
	 * @return void
	 */
	public function run(ClienteDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$cliente = Cliente::create();
			if (!is_null($data->id)) {
				$cliente = Cliente::findOne(['id' => $data->id]);
			}

      $cliente->nombre_apellidos      = urldecode($data->nombre_apellidos);
			$cliente->dni_cif               = !is_null($data->dni_cif) ? urldecode($data->dni_cif) : null;
			$cliente->telefono              = !is_null($data->telefono) ? urldecode($data->telefono) : null;
			$cliente->email                 = !is_null($data->email) ? urldecode($data->email) : null;
			$cliente->direccion             = !is_null($data->direccion) ? urldecode($data->direccion) : null;
			$cliente->codigo_postal         = !is_null($data->codigo_postal) ? urldecode($data->codigo_postal) : null;
			$cliente->poblacion             = !is_null($data->poblacion) ? urldecode($data->poblacion) : null;
			$cliente->provincia             = $data->provincia;
			$cliente->fact_igual            = $data->fact_igual;
			$cliente->fact_nombre_apellidos = !is_null($data->fact_nombre_apellidos) ? urldecode($data->fact_nombre_apellidos) : null;
			$cliente->fact_dni_cif          = !is_null($data->fact_dni_cif) ? urldecode($data->fact_dni_cif) : null;
			$cliente->fact_telefono         = !is_null($data->fact_telefono) ? urldecode($data->fact_telefono) : null;
			$cliente->fact_email            = !is_null($data->fact_email) ? urldecode($data->fact_email) : null;
			$cliente->fact_direccion        = !is_null($data->fact_direccion) ? urldecode($data->fact_direccion) : null;
			$cliente->fact_codigo_postal    = !is_null($data->fact_codigo_postal) ? urldecode($data->fact_codigo_postal) : null;
			$cliente->fact_poblacion        = !is_null($data->fact_poblacion) ? urldecode($data->fact_poblacion) : null;
			$cliente->fact_provincia        = $data->fact_provincia;
			$cliente->observaciones         = !is_null($data->observaciones) ? urldecode($data->observaciones) : null;
			$cliente->descuento             = $data->descuento;

			$cliente->save();
      $this->id = $cliente->id;
		}
	}
}
