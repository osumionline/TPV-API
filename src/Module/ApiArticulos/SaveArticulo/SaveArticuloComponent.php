<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiArticulos\SaveArticulo;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Service\ArticulosService;
use Osumi\OsumiFramework\App\DTO\ArticuloDTO;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\CodigoBarras;
use Osumi\OsumiFramework\App\Model\HistoricoArticulo;

class SaveArticuloComponent extends OComponent {
	private ?ArticulosService $ars = null;

  public string       $status      = 'ok';
	public string | int $localizador = 'null';
	public string       $message     = '';

  public function __construct() {
    parent::__construct();
		$this->ars = inject(ArticulosService::class);
  }

	/**
	 * Función para guardar un artículo
	 *
	 * @param ArticuloDTO $data Objeto con toda la información sobre un artículo
	 * @return void
	 */
	public function run(ArticuloDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$a = Articulo::create();
			if (!is_null($data->id)) {
				$a = Articulo::findOne(['id' => $data->id]);
			} else {
				$data->localizador = intval($this->ars->getNewLocalizador());
			}
			// Validaciones
			// Comprobar nombre ya usado
			if ($data->nombre_status === 'ok') {
				$check = $this->ars->checkNombre(urldecode($data->nombre), $data->id);
				if ($check['status'] !== 'ok') {
					$this->status  = $check['status'];
					$this->message = $check['message'];
				}
			}
			// Comprobar referencia ya usada
			if ($this->status === 'ok') {
				$check = $this->ars->checkReferencia($data->referencia, $data->id);
				if ($check['status'] !== 'ok') {
					$this->status  = $check['status'];
					$this->message = $check['message'];
				}
			}
			// Comprobar código de barras ya usado
			if ($this->status === 'ok') {
				$check = $this->ars->checkCodigosBarras($data->codigos_barras, $data->id);
				if ($check['status'] !== 'ok') {
					$this->status  = $check['status'];
					$this->message = $check['message'];
				}
			}

			if ($this->status === 'ok') {
				$fecha_caducidad = null;
				if (!is_null($data->fecha_caducidad)) {
					$fec_cad_data = explode('/', $data->fecha_caducidad);
					$time = mktime(0, 0, 0, intval($fec_cad_data[0]), 1, (2000 + intval($fec_cad_data[1])));
					$fecha_caducidad = date('Y-m-d H:i:s', $time);
				}

				$stock_previo = $a->stock;
				$stock_final  = $data->stock;
				if (is_null($stock_previo)) {
					$stock_previo = 0;
				}
				$diferencia = $stock_final - $stock_previo;
				$pvp_previo = $a->pvp;

				// Guardo datos del artículo
				$a->localizador         = intval($data->localizador);
				$a->nombre              = urldecode($data->nombre);
				$a->slug                = OTools::slugify(urldecode($data->nombre));
				$a->id_categoria        = $data->id_categoria;
				$a->id_marca            = $data->id_marca;
				$a->id_proveedor        = $data->id_proveedor;
				$a->referencia          = $data->referencia;
				$a->palb                = $data->palb;
				$a->puc                 = $data->puc;
				$a->pvp                 = $data->pvp;
				$a->pvp_descuento       = $data->pvp_descuento;
				$a->iva                 = $data->iva;
				$a->re                  = $data->re;
				$a->margen              = $data->margen;
				$a->margen_descuento    = $data->margen_descuento;
				$a->stock               = $data->stock;
				$a->stock_min           = $data->stock_min;
				$a->stock_max           = $data->stock_max;
				$a->lote_optimo         = $data->lote_optimo;
				$a->venta_online        = $data->venta_online;
				$a->fecha_caducidad     = $fecha_caducidad;
				$a->mostrar_en_web      = $data->mostrar_en_web;
				$a->desc_corta          = urldecode($data->desc_corta);
				$a->descripcion         = urldecode($data->descripcion);
				$a->observaciones       = urldecode($data->observaciones);
				$a->mostrar_obs_pedidos = $data->mostrar_obs_pedidos;
				$a->mostrar_obs_ventas  = $data->mostrar_obs_ventas;

				$a->save();
				$data->id = $a->id;

				// Guardo los códigos de barras
				$cod_barras_por_defecto = false;
				$cb_checked = [];
				foreach ($data->codigos_barras as $cod) {
					if (!empty($cod['codigoBarras'])) {
						$cb = CodigoBarras::create();
						if (!empty($cod['id'])) {
							$cb = CodigoBarras::findOne(['id' => $cod['id']]);
						}
						$cb->id_articulo   = $data->id;
						$cb->codigo_barras = $cod['codigoBarras'];
						if ($cb->por_defecto) {
							$cod_barras_por_defecto = true;
						} else {
							$cb->por_defecto = false;
						}
						$cb->save();
						$cb_checked[] = $cb->id;
					}
				}

				// Si no tiene código de barras por defecto se lo creo
				if (!$cod_barras_por_defecto) {
					$cb = CodigoBarras::create();
					$cb->id_articulo   = $data->id;
					$cb->codigo_barras = strval($data->localizador);
					$cb->por_defecto   = true;
					$cb->save();
					$cb_checked[] = $cb->id;
				}

				//Limpio códigos de barras que hayan sido marcados para borrar
				$this->ars->cleanCodigosDeBarras($a->id, $cb_checked);

				// Actualizo las fotos del artículo
				$this->ars->updateFotos($a, $data->fotos_list);

				// Obtengo el localizador del artículo, o el que se le ha asignado
				$this->localizador = $data->localizador;

				// Histórico
				if ($diferencia !== 0 || $pvp_previo !== $a->pvp) {
					$ha = HistoricoArticulo::create();
					$ha->id_articulo  = $a->id;
					$ha->tipo         = HistoricoArticulo::FROM_ARTICULO;
					$ha->stock_previo = $stock_previo;
					$ha->diferencia   = $diferencia;
					$ha->stock_final  = $stock_final;
					$ha->id_venta     = null;
					$ha->id_pedido    = null;
					$ha->puc          = $a->puc;
					$ha->pvp          = $a->pvp;
					$ha->save();
				}
			}
		}
	}
}
