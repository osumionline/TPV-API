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

      $cliente->nombre_apellidos      = urldecode($data->nombreApellidos);
			$cliente->dni_cif               = !is_null($data->dniCif)              ? urldecode($data->dniCif)              : null;
			$cliente->telefono              = !is_null($data->telefono)            ? urldecode($data->telefono)            : null;
			$cliente->email                 = !is_null($data->email)               ? urldecode($data->email)               : null;
			$cliente->direccion             = !is_null($data->direccion)           ? urldecode($data->direccion)           : null;
			$cliente->codigo_postal         = !is_null($data->codigoPostal)        ? urldecode($data->codigoPostal)        : null;
			$cliente->poblacion             = !is_null($data->poblacion)           ? urldecode($data->poblacion)           : null;
			$cliente->provincia             = $data->provincia;
			$cliente->fact_igual            = $data->factIgual;
			$cliente->fact_nombre_apellidos = !is_null($data->factNombreApellidos) ? urldecode($data->factNombreApellidos) : null;
			$cliente->fact_dni_cif          = !is_null($data->factDniCif)          ? urldecode($data->factDniCif)          : null;
			$cliente->fact_telefono         = !is_null($data->factTelefono)        ? urldecode($data->factTelefono)        : null;
			$cliente->fact_email            = !is_null($data->factEmail)           ? urldecode($data->factEmail)           : null;
			$cliente->fact_direccion        = !is_null($data->factDireccion)       ? urldecode($data->factDireccion)       : null;
			$cliente->fact_codigo_postal    = !is_null($data->factCodigoPostal)    ? urldecode($data->factCodigoPostal)    : null;
			$cliente->fact_poblacion        = !is_null($data->factPoblacion)       ? urldecode($data->factPoblacion)       : null;
			$cliente->fact_provincia        = $data->factProvincia;
			$cliente->observaciones         = !is_null($data->observaciones)       ? urldecode($data->observaciones)       : null;
			$cliente->descuento             = $data->descuento;

			$cliente->save();
      $this->id = $cliente->id;
		}
	}
}
