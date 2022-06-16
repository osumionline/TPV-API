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
			$cliente->set('dni_cif',               urldecode($data->getDniCif()));
			$cliente->set('telefono',              urldecode($data->getTelefono()));
			$cliente->set('email',                 urldecode($data->getEmail()));
			$cliente->set('direccion',             urldecode($data->getDireccion()));
			$cliente->set('poblacion',             urldecode($data->getPoblacion()));
			$cliente->set('provincia',             $data->getProvincia());
			$cliente->set('fact_igual',            $data->getFactIgual());
			$cliente->set('fact_nombre_apellidos', urldecode($data->getFactNombreApellidos()));
			$cliente->set('fact_dni_cif',          urldecode($data->getFactDniCif()));
			$cliente->set('fact_telefono',         urldecode($data->getFactTelefono()));
			$cliente->set('fact_email',            urldecode($data->getFactEmail()));
			$cliente->set('fact_direccion',        urldecode($data->getFactDireccion()));
			$cliente->set('fact_poblacion',        urldecode($data->getFactPoblacion()));
			$cliente->set('fact_provincia',        $data->getFactProvincia());
			$cliente->set('observaciones',         urldecode($data->getObservaciones()));

			$cliente->save();

			$data->setId( $cliente->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}