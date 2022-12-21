<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;

class Pedido extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada pedido'
			),
			new OModelField(
				name: 'id_proveedor',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'proveedor.id',
				comment: 'Id del proveedor del pedido'
			),
			new OModelField(
				name: 'tipo',
				type: OMODEL_TEXT,
				nullable: true,
				size: 10,
				comment: 'Indica si se trata de un albarán, una factura o un abono'
			),
			new OModelField(
				name: 'num',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 200,
				comment: 'Albarán / factura / abono del pedido'
			),
			new OModelField(
				name: 'importe',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total del pedido'
			),
			new OModelField(
				name: 'portes',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe de los portes del pedido'
			),
			new OModelField(
				name: 'fecha_pago',
				type: OMODEL_DATE,
				nullable: true,
				default: null,
				comment: 'Fecha de pago del pedido'
			),
			new OModelField(
				name: 'fecha_pedido',
				type: OMODEL_DATE,
				nullable: true,
				default: null,
				comment: 'Fecha del pedido'
			),
			new OModelField(
				name: 'fecha_recepcionado',
				type: OMODEL_DATE,
				nullable: true,
				default: null,
				comment: 'Fecha del momento de la recepcion del pedido'
			),
			new OModelField(
				name: 're',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si el pedido tiene RE 1 o no 0'
			),
			new OModelField(
				name: 'europeo',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si se rata de un pedido europeo 1 o no 0'
			),
			new OModelField(
				name: 'faltas',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si hay faltas en el pedido 1 o no 0'
			),
			new OModelField(
				name: 'recepcionado',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si se ha recepcionado el pedido 1 o si está pendiente 0'
			),
			new OModelField(
				name: 'observaciones',
				type: OMODEL_LONGTEXT,
				nullable: true,
				default: null,
				comment: 'Observaciones o notas sobre el pedido'
			),
			new OModelField(
				name: 'created_at',
				type: OMODEL_CREATED,
				comment: 'Fecha de creación del registro'
			),
			new OModelField(
				name: 'updated_at',
				type: OMODEL_UPDATED,
				nullable: true,
				default: null,
				comment: 'Fecha de última modificación del registro'
			)
		);

		parent::load($model);
	}

	private ?Proveedor $proveedor = null;

	/**
	 * Obtiene el proveedor al que pertenece el pedido
	 *
	 * @return Proveedor Proveedor al que pertenece el pedido, a no ser que se haya borrado
	 */
	public function getProveedor(): ?Proveedor {
		if (is_null($this->proveedor)) {
			$this->loadProveedor();
		}
		if (!is_null($this->proveedor->get('deleted_at'))){
			return null;
		}
		return $this->proveedor;
	}

	/**
	 * Guarda el proveedor al que pertenece el pedido
	 *
	 * @param Proveedor $p Proveedor al que pertenece el pedido
	 *
	 * @return void
	 */
	public function setProveedor(Proveedor $p): void {
		$this->proveedor = $p;
	}

	/**
	 * Carga el proveedor al que pertenece el pedido
	 *
	 * @return void
	 */
	public function loadProveedor(): void {
		$p = new Proveedor();
		$p->find(['id' => $this->get('id_proveedor')]);
		$this->setProveedor($p);
	}

	private ?array $lineas = null;

	/**
	 * Obtiene el listado de líneas de un pedido
	 *
	 * @return array Listado de líneas
	 */
	public function getLineas(): array {
		if (is_null($this->lineas)) {
			$this->loadLineas();
		}
		return $this->lineas;
	}

	/**
	 * Guarda la lista de líneas
	 *
	 * @param array $l Lista de líneas
	 *
	 * @return void
	 */
	public function setLineas(array $l): void {
		$this->lineas = $l;
	}

	/**
	 * Carga la lista de líneas de un pedido
	 *
	 * @return void
	 */
	public function loadLineas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `linea_pedido` WHERE `id_pedido` = ?";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$lp = new LineaPedido();
			$lp->update($res);
			array_push($list, $lp);
		}

		$this->setLineas($list);
	}

	private ?array $pdfs = null;

	/**
	 * Obtiene el listado de PDFs de un pedido
	 *
	 * @return array Listado de PDFs
	 */
	public function getPdfs(): array {
		if (is_null($this->pdfs)) {
			$this->loadPdfs();
		}
		return $this->pdfs;
	}

	/**
	 * Guarda la lista de PDFs
	 *
	 * @param array $l Lista de PDFs
	 *
	 * @return void
	 */
	public function setPdfs(array $l): void {
		$this->pdfs = $l;
	}

	/**
	 * Carga la lista de PDFs de un pedido
	 *
	 * @return void
	 */
	public function loadPdfs(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `pdf_pedido` WHERE `id_pedido` = ?";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$pdf = new PdfPedido();
			$pdf->update($res);
			array_push($list, $pdf);
		}

		$this->setPdfs($list);
	}

	private ?array $vista = null;

	/**
	 * Obtiene el listado de opciones para la vista de un pedido
	 *
	 * @return array Listado de opciones
	 */
	public function getVista(): array {
		if (is_null($this->vista)) {
			$this->loadVista();
		}
		return $this->vista;
	}

	/**
	 * Guarda la lista de opciones para la vista de un pedido
	 *
	 * @param array $l Lista de opciones
	 *
	 * @return void
	 */
	public function setVista(array $v): void {
		$this->vista = $v;
	}

	/**
	 * Carga la lista de opciones para la vista de un pedido
	 *
	 * @return void
	 */
	public function loadVista(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `vista_pedido` WHERE `id_pedido` = ? ORDER BY `id_column` ASC";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$v = new VistaPedido();
			$v->update($res);
			array_push($list, $v);
		}

		$this->setVista($list);
	}

	/**
	 * Función para borrar un pedido, sus líneas, sus pdfs y su vista
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$lineas = $this->getLineas();
		foreach ($lineas as $linea) {
			$linea->delete();
		}

		$pdfs = $this->getPdfs();
		foreach ($pdfs as $pdf) {
			$pdf->deleteFull();
		}

		$vista = $this->getVista();
		foreach ($vista as $v) {
			$v->delete();
		}

		$this->delete();
	}
}
