<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class ArticuloDTO implements ODTO {
	public ?int    $id                  = null;
	public ?int    $localizador         = null;
	public string  $nombre              = '';
	public ?int    $id_categoria        = null;
	public ?int    $id_marca            = null;
	public ?int    $id_proveedor        = null;
	public string  $referencia          = '';
	public float   $palb                = 0;
	public float   $puc                 = 0;
	public float   $pvp                 = 0;
	public ?float  $pvp_descuento       = 0;
	public int     $iva                 = 0;
	public float   $re                  = 0;
	public float   $margen              = 0;
	public ?float  $margen_descuento    = 0;
	public int     $stock               = 0;
	public int     $stock_min           = 0;
	public int     $stock_max           = 0;
	public int     $lote_optimo         = 0;
	public bool    $venta_online        = false;
	public ?string $fecha_caducidad     = null;
	public bool    $mostrar_en_web      = false;
	public string  $desc_corta          = '';
	public string  $descripcion         = '';
	public string  $observaciones       = '';
	public bool    $mostrar_obs_pedidos = false;
	public bool    $mostrar_obs_ventas  = false;
	public array   $codigos_barras      = [];
	public array   $fotos_list          = [];
	public string  $nombre_status       = 'ok';

	public function isValid(): bool {
		return (
			!is_null($this->nombre) &&
			!is_null($this->id_marca) &&
			!is_null($this->iva) &&
			!is_null($this->puc) &&
			!is_null($this->pvp) &&
			!is_null($this->stock)
		);
	}

	public function load(ORequest $req): void {
		$this->id                  = $req->getParamInt('id');
		$this->localizador         = $req->getParamInt('localizador');
		$this->nombre              = $req->getParamString('nombre');
		$this->id_categoria        = $req->getParamInt('idCategoria');
		$this->id_marca            = $req->getParamInt('idMarca');
		$this->id_proveedor        = $req->getParamInt('idProveedor');
		$this->referencia          = $req->getParamString('referencia');
		$this->palb                = $req->getParamFloat('palb');
		$this->puc                 = $req->getParamFloat('puc');
		$this->pvp                 = $req->getParamFloat('pvp');
		$this->pvp_descuento       = $req->getParamFloat('pvpDescuento');
		$this->iva                 = $req->getParamInt('iva');
		$this->re                  = $req->getParamFloat('re');
		$this->margen              = $req->getParamFloat('margen');
		$this->margen_descuento    = $req->getParamFloat('margenDescuento');
		$this->stock               = $req->getParamInt('stock');
		$this->stock_min           = $req->getParamInt('stockMin');
		$this->stock_max           = $req->getParamInt('stockMax');
		$this->lote_optimo         = $req->getParamInt('loteOptimo');
		$this->venta_online        = $req->getParamBool('ventaOnline');
		$this->fecha_caducidad     = $req->getParamString('fechaCaducidad');
		$this->mostrar_en_web      = $req->getParamBool('mostrarEnWeb');
		$this->desc_corta          = $req->getParamString('descCorta');
		$this->descripcion         = $req->getParamString('descripcion');
		$this->observaciones       = $req->getParamString('observaciones');
		$this->mostrar_obs_pedidos = $req->getParamBool('mostrarObsPedidos');
		$this->mostrar_obs_ventas  = $req->getParamBool('mostrarObsVentas');
		$this->codigos_barras      = $req->getParam('codigosBarras');
		$this->fotos_list          = $req->getParam('fotosList');
		$this->nombre_status       = $req->getParamString('nombreStatus');
	}
}
