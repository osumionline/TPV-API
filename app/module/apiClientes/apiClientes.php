<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Model\Cliente;
use OsumiFramework\App\Service\clientesService;
use OsumiFramework\App\DTO\ClienteDTO;

#[ORoute(
	type: 'json',
	prefix: '/api-clientes'
)]
class apiclientes extends OModule {
  private ?clientesService $clientes_service = null;

  function __construct() {
		$this->clientes_service = new clientesService();
  }

  /**
	 * Función para buscar clientes
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/search-clientes')]
	public function searchClientes(ORequest $req): void {
		$status = 'ok';
		$name = $req->getParamString('name');
		$list = [];

		if (is_null($name)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$list = $this->clientes_service->searchClientes($name);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'model/cliente_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para guardar un cliente
	 *
	 * @param ClienteDTO $data Objeto con toda la información sobre un cliente
	 * @return void
	 */
	#[ORoute('/save-cliente')]
	public function saveCliente(ClienteDTO $data): void {
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

	/**
	 * Función para obtener las estadísticas de un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-estadisticas-cliente')]
	public function getEstadisticasCliente(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$ultimas_ventas = [];
		$top_ventas = [];

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$ultimas_ventas = $this->clientes_service->getUltimasVentas($id);
			$top_ventas = $this->clientes_service->getTopVentas($id);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('ultimas_ventas', 'api/ultimas_ventas', ['list' => $ultimas_ventas, 'extra' => 'nourlencode']);
		$this->getTemplate()->addComponent('top_ventas',     'api/top_ventas',     ['list' => $top_ventas,     'extra' => 'nourlencode']);
	}

	/**
	 * Función para obtener la lista de clientes
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-clientes')]
	public function getClientes(ORequest $req): void {
		$list = $this->clientes_service->getClientes();

		$this->getTemplate()->add('status', 'ok');
		$this->getTemplate()->addComponent('list', 'model/cliente_list', ['list' => $list, 'extra'=>'nourlencode']);
	}
}
