<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\ClienteDTO;
use OsumiFramework\App\Model\Cliente;

#[OModuleAction(
	url: '/save-cliente'
)]
class saveClienteAction extends OAction {
	/**
	 * Función para guardar un cliente
	 *
	 * @param ClienteDTO $data Objeto con toda la información sobre un cliente
	 * @return void
	 */
	public function run(ClienteDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cliente = new Cliente();
			if (!is_null($data->getId())) {
				$cliente->find(['id' => $data->getId()]);
			}

			$cliente->set('nombre_apellidos',      urldecode($data->getNombreApellidos()));
			$cliente->set('dni_cif',               !is_null($data->getDniCif()) ? urldecode($data->getDniCif()) : null);
			$cliente->set('telefono',              !is_null($data->getTelefono()) ? urldecode($data->getTelefono()) : null);
			$cliente->set('email',                 !is_null($data->getEmail()) ? urldecode($data->getEmail()) : null);
			$cliente->set('direccion',             !is_null($data->getDireccion()) ? urldecode($data->getDireccion()) : null);
			$cliente->set('codigo_postal',         !is_null($data->getCodigoPostal()) ? urldecode($data->getCodigoPostal()) : null);
			$cliente->set('poblacion',             !is_null($data->getPoblacion()) ? urldecode($data->getPoblacion()) : null);
			$cliente->set('provincia',             $data->getProvincia());
			$cliente->set('fact_igual',            $data->getFactIgual());
			$cliente->set('fact_nombre_apellidos', !is_null($data->getFactNombreApellidos()) ? urldecode($data->getFactNombreApellidos()) : null);
			$cliente->set('fact_dni_cif',          !is_null($data->getFactDniCif()) ? urldecode($data->getFactDniCif()) : null);
			$cliente->set('fact_telefono',         !is_null($data->getFactTelefono()) ? urldecode($data->getFactTelefono()) : null);
			$cliente->set('fact_email',            !is_null($data->getFactEmail()) ? urldecode($data->getFactEmail()) : null);
			$cliente->set('fact_direccion',        !is_null($data->getFactDireccion()) ? urldecode($data->getFactDireccion()) : null);
			$cliente->set('fact_codigo_postal',    !is_null($data->getFactCodigoPostal()) ? urldecode($data->getFactCodigoPostal()) : null);
			$cliente->set('fact_poblacion',        !is_null($data->getFactPoblacion()) ? urldecode($data->getFactPoblacion()) : null);
			$cliente->set('fact_provincia',        $data->getFactProvincia());
			$cliente->set('observaciones',         !is_null($data->getObservaciones()) ? urldecode($data->getObservaciones()) : null);

			$cliente->save();

			$data->setId( $cliente->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
