<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\DTO\ArticuloDTO;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\CodigoBarras;

#[OModuleAction(
	url: '/save-articulo',
	services: ['articulos']
)]
class saveArticuloAction extends OAction {
	/**
	 * Función para guardar un artículo
	 *
	 * @param ArticuloDTO $data Objeto con toda la información sobre un artículo
	 * @return void
	 */
	public function run(ArticuloDTO $data):void {
		$status = 'ok';
		$localizador = 'null';
		$message = '';

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
			// Validaciones
			// Comprobar nombre ya usado
			if ($data->getNombreStatus() == 'ok') {
				$check = $this->articulos_service->checkNombre(urldecode($data->getNombre()), $data->getId());
				if ($check['status'] != 'ok') {
					$status = $check['status'];
					$message = $check['message'];
				}
			}
			// Comprobar referencia ya usada
			if ($status == 'ok') {
				$check = $this->articulos_service->checkReferencia($data->getReferencia(), $data->getId());
				if ($check['status'] != 'ok') {
					$status = $check['status'];
					$message = $check['message'];
				}
			}
			// Comprobar código de barras ya usado
			if ($status == 'ok') {
				$check = $this->articulos_service->checkCodigosBarras($data->getCodigosBarras(), $data->getId());
				if ($check['status'] != 'ok') {
					$status = $check['status'];
					$message = $check['message'];
				}
			}

			if ($status == 'ok') {
				$fecha_caducidad = null;
				if (!is_null($data->getFechaCaducidad())) {
					$fec_cad_data = explode('/', $data->getFechaCaducidad());
					$time = mktime(0, 0, 0, intval($fec_cad_data[0]), 1, (2000 + intval($fec_cad_data[1])));
					$fecha_caducidad = date('Y-m-d H:i:s', $time);
				}
				// Guardo datos del artículo
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

				// Guardo los códigos de barras
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
						else {
							$cb->set('por_defecto', false);
						}
						$cb->save();
					}
				}

				// Si no tiene código de barras por defecto se lo creo
				if (!$cod_barras_por_defecto) {
					$cb = new CodigoBarras();
					$cb->set('id_articulo', $data->getId());
					$cb->set('codigo_barras', strval($data->getLocalizador()));
					$cb->set('por_defecto', true);
					$cb->save();
				}

				// Actualizo las fotos del artículo
				$this->articulos_service->updateFotos($art, $data->getFotosList());

				// Obtengo el localizador del artículo, o el que se le ha asignado
				$localizador = $data->getLocalizador();
			}
		}

		$this->getTemplate()->add('status',      $status);
		$this->getTemplate()->add('localizador', $localizador);
		$this->getTemplate()->add('message',     $message);
	}
}
