<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCompras\AutoSavePedido;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\ComprasService;
use Osumi\OsumiFramework\App\DTO\PedidoDTO;
use Osumi\OsumiFramework\App\Model\Pedido;
use Osumi\OsumiFramework\App\Model\LineaPedido;
use Osumi\OsumiFramework\App\Model\VistaPedido;
use \DateTime;

class AutoSavePedidoComponent extends OComponent {
  private ?ComprasService $cs = null;

  public int $id = -1;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ComprasService::class);
  }

	/**
	 * Función para auto-guardar un pedido
	 *
	 * @param PedidoDTO $data Objeto con toda la información sobre un pedido
	 * @return void
	 */
	public function run(PedidoDTO $data): void {
		$pedido = Pedido::create();
		if (!is_null($data->id)) {
			$pedido = Pedido::findOne(['id' => $data->id]);
		}

		// Guardo datos del pedido
		if (!is_null($data->idProveedor) && $data->idProveedor !== -1) {
			$pedido->id_proveedor = $data->idProveedor;
		}
		else {
			$pedido->id_proveedor = null;
		}
		$pedido->metodo_pago = $data->idMetodoPago;
		$pedido->tipo        = $data->tipo;
		$pedido->num         = !is_null($data->num) ? urldecode($data->num) : null;
		$pedido->importe     = $data->importe;
		$pedido->portes      = $data->portes;
		$pedido->descuento   = $data->descuento;
		if (!is_null($data->fechaPago)) {
      $dateTime  = DateTime::createFromFormat('d/m/Y', urldecode($data->fechaPago));
      $timestamp = $dateTime->getTimestamp();
			$pedido->fecha_pago = date('Y-m-d H:i:s', $timestamp);
		}
		else {
			$pedido->fecha_pago = null;
		}
		if (!is_null($data->fechaPedido)) {
      $dateTime  = DateTime::createFromFormat('d/m/Y', urldecode($data->fechaPedido));
      $timestamp = $dateTime->getTimestamp();
			$pedido->fecha_pedido = date('Y-m-d H:i:s', $timestamp);
		}
		else {
			$pedido->fecha_pedido = null;
		}
		$pedido->re           = $data->re;
		$pedido->europeo      = $data->ue;
		$pedido->faltas       = $data->faltas;
		$pedido->recepcionado = $data->recepcionado;
		if (!is_null($data->observaciones)) {
			$pedido->observaciones = urldecode($data->observaciones);
		}

		$pedido->save();
		$data->id = $pedido->id;

		// Borro líneas del pedido
		$this->cs->borrarLineasPedido($pedido->id);

		// Guardo nuevas líneas del pedido
		foreach ($data->lineas as $linea) {
			$lp = LineaPedido::create();
      $lp->id_pedido       = $pedido->id;
			$lp->id_articulo     = $linea['idArticulo'];
			$lp->nombre_articulo = urldecode($linea['nombreArticulo']);
			$lp->codigo_barras   = $linea['codBarras'];
			$lp->unidades        = $linea['unidades'];
			$lp->palb            = $linea['palb'];
			$lp->puc             = $linea['puc'];
			$lp->pvp             = $linea['pvp'];
			$lp->margen          = $linea['margen'];
			$lp->iva             = $linea['iva'];
			$lp->re              = $data->re ? $linea['re'] : null;
			$lp->descuento       = $linea['descuento'];
			$lp->save();
		}

		// Borro todas las líneas de la vista del pedido
		$this->cs->borrarVistaPedido($pedido->id);

		// Guardo nueva vista
		foreach ($data->vista as $vista) {
			$vp = VistaPedido::create();
			$vp->id_pedido = $pedido->id;
			$vp->id_column = $vista['idColumn'];
			$vp->status    = $vista['status'];
			$vp->save();
		}

		// Actualizo PDFs adjuntos
		$this->cs->updatePedidoPDFs($pedido, $data->pdfs);

    $this->id = $data->id;
	}
}
