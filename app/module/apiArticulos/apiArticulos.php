<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Service\articulosService;
use OsumiFramework\App\DTO\ArticuloDTO;

#[ORoute(
	type: 'json',
	prefix: '/api-articulos'
)]
class apiArticulos extends OModule {
  private ?articulosService $articulos_service = null;

  function __construct() {
		$this->articulos_service = new articulosService();
  }

	/**
	 * Función para obtener las estadísticas de ventas o web de un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-statistics')]
	public function getStatistics(ORequest $req): void {

	}

  /**
	 * Función para dar de baja un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/delete-articulo')]
	public function deleteArticulo(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$art = new Articulo();
			if ($art->find(['id' => $id])) {
				$art->set('deleted_at', date('Y-m-d H:i:s', time()));
				$art->save();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}

  /**
	 * Función para guardar un artículo
	 *
	 * @param ArticuloDTO $data Objeto con toda la información sobre un artículo
	 * @return void
	 */
	#[ORoute('/save-articulo')]
	public function saveArticulo(ArticuloDTO $data): void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$art = new Articulo();
			if (!is_null($data->getId())) {
				$art->find(['id' => $data->getId()]);
			}
			else {
				$data->setLocalizador( intval($this->articulos_service->getNewLocalizador()) );
			}
			$fecha_caducidad = null;
			if (!is_null($data->getFechaCaducidad())) {
				$fec_cad_data = explode('-', $data->getFechaCaducidad());
				$time = mktime(0, 0, 0, intval($fec_cad_data[0]), 1, (2000 + intval($fec_cad_data[1])));
				$fecha_caducidad = date('Y-m-d H:i:s', $time);
			}
			$art->set('localizador',         $data->getLocalizador());
			$art->set('nombre',              urldecode($data->getNombre()));
			$art->set('slug',                OTools::slugify(urldecode($data->getNombre())));
			$art->set('id_categoria',        $data->getIdCategoria());
			$art->set('id_marca',            $data->getIdMarca());
			$art->set('id_proveedor',        $data->getIdProveedor());
			$art->set('referencia',          $data->getReferencia());
			$art->set('palb',                $data->getPalb());
			$art->set('puc',                 $data->getPuc());
			$art->set('pvp',                 $data->getPvp());
			$art->set('iva',                 $data->getIva());
			$art->set('re',                  $data->getRe());
			$art->set('margen',              $data->getMargen());
			$art->set('stock',               $data->getStock());
			$art->set('stock_min',           $data->getStockMin());
			$art->set('stock_max',           $data->getStockMax());
			$art->set('lote_optimo',         $data->getLoteOptimo());
			$art->set('venta_online',        $data->getVentaOnline());
			$art->set('fecha_caducidad',     $fecha_caducidad);
			$art->set('mostrar_en_web',      $data->getMostrarEnWeb());
			$art->set('desc_corta',          urldecode($data->getDescCorta()));
			$art->set('descripcion',         urldecode($data->getDescripcion()));
			$art->set('observaciones',       urldecode($data->getObservaciones()));
			$art->set('mostrar_obs_pedidos', $data->getMostrarObsPedidos());
			$art->set('mostrar_obs_ventas',  $data->getMostrarObsVentas());

			$art->save();
			$data->setId( $art->get('id') );

			$cod_barras_por_defecto = false;
			foreach ($data->getCodigosBarras() as $cod) {
				if (!empty($cod['codigoBarras'])) {
					$cb = new CodigoBarras();
					if (!empty($cod['id'])) {
						$cb->find(['id'=>$cod['id']]);
					}
					$cb->set('id_articulo', $data->getId());
					$cb->set('codigo_barras', $cod['codigoBarras']);
					if ($cb->get('por_defecto')) {
						$cod_barras_por_defecto = true;
					}
					$cb->save();
				}
			}

			if (!$cod_barras_por_defecto) {
				$cb = new CodigoBarras();
				$cb->set('id_articulo', $data->getId());
				$cb->set('codigo_barras', $data->getLocalizador());
				$cb->set('por_defecto', true);
				$cb->save();
			}

			$this->articulos_service->updateFotos($art, $data->getFotosList());
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('localizador', $data->getLocalizador());
	}

	/**
	 * Función para obtener los datos de un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/load-articulo')]
	public function loadArticulo(ORequest $req): void {
		$status = 'ok';
		$localizador = $req->getParamInt('localizador');
		$articulo    = null;

		if (is_null($localizador)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cb = new CodigoBarras();
			if ($cb->find(['codigo_barras'=>$localizador])) {
				$articulo = new Articulo();
				$articulo->find(['id' => $cb->get('id_articulo')]);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('articulo', 'model/articulo', ['articulo' => $articulo, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para buscar artículos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/search-articulos')]
	public function searchArticulos(ORequest $req): void {
		$status = 'ok';
		$name = $req->getParamString('name');
		$id_marca = $req->getParamInt('idMarca');
		$list = [];

		if (is_null($name) || is_null($id_marca)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$list = $this->articulos_service->searchArticulos($name, $id_marca);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'model/articulo_list', ['list' => $list, 'extra' => 'nourlencode']);
	}
}
