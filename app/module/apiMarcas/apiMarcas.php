<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Model\Marca;
use OsumiFramework\App\Service\marcasService;
use OsumiFramework\App\DTO\MarcaDTO;

#[ORoute(
	type: 'json',
	prefix: '/api-marcas'
)]
class apiMarcas extends OModule {
  private ?marcasService $marcas_service = null;

  function __construct() {
		$this->marcas_service = new marcasService();
  }

  /**
	 * Función para obtener la lista de marcas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-marcas')]
	public function getMarcas(ORequest $req): void {
		$list = $this->marcas_service->getMarcas();

		$this->getTemplate()->addComponent('list', 'model/marca_list', ['list' => $list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para guardar una marca
	 *
	 * @param MarcaDTO $data Objeto con toda la información sobre una marca
	 * @return void
	 */
	#[ORoute('/save-marca')]
	public function saveMarca(MarcaDTO $data): void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$marca = new Marca();
			if (!is_null($data->getId())) {
				$marca->find(['id' => $data->getId()]);
			}

			$marca->set('nombre',        urldecode($data->getNombre()));
			$marca->set('direccion',     urldecode($data->getDireccion()));
			$marca->set('telefono',      urldecode($data->getTelefono()));
			$marca->set('email',         urldecode($data->getEmail()));
			$marca->set('web',           urldecode($data->getWeb()));
			$marca->set('observaciones', urldecode($data->getObservaciones()));

			$marca->save();

			$data->setId( $marca->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
