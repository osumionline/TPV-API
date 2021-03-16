<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\ODB;

class Articulo extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'articulo';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada artículo'
			],
			'localizador' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'comment'  => 'Localizador único de cada artículo'
			],
			'nombre' => [
				'type'     => OModel::TEXT,
				'nullable' => false,
				'default'  => null,
				'size'     => 100,
				'comment'  => 'Nombre del artículo'
			],
			'puc' => [
				'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => '0',
				'comment'  => 'Precio Unitario de Compra del artículo'
			],
			'pvp' => [
				'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => '0',
				'comment'  => 'Precio de Venta al Público del artículo'
			],
			'margen' => [
				'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => '0',
				'comment'  => 'Margen de beneficio del artículo'
			],
			'palb' => [
				'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => '0',
				'comment'  => 'Precio del artículo en el albarán'
			],
			'id_marca' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'ref'      => 'marca.id',
				'comment'  => 'Id de la marca del artículo'
			],
			'id_proveedor' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'ref'      => 'proveedor.id',
				'comment'  => 'Id del proveedor del artículo'
			],
			'stock' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => '0',
				'comment'  => 'Stock actual del artículo'
			],
			'stock_min' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => '0',
				'comment'  => 'Stock mínimo del artículo'
			],
			'stock_max' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => '0',
				'comment'  => 'Stock máximo del artículo'
			],
			'lote_optimo' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => '0',
				'comment'  => 'Lote óptimo para realizar pedidos del artículo'
			],
			'iva' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'comment'  => 'IVA del artículo'
			],
			'fecha_caducidad' => [
				'type'     => OModel::DATE,
				'nullable' => true,
				'default'  => null,
				'comment'  => 'Fecha de caducidad del artículo'
			],
			'mostrar_feccad' => [
				'type'    => OModel::BOOL,
				'comment' => 'Mostrar fecha de caducidad 0 no 1 si'
			],
			'observaciones' => [
				'type'     => OModel::LONGTEXT,
				'nullable' => true,
				'default'  => null,
				'comment'  => 'Observaciones o notas sobre el artículo'
			],
			'mostrar_obs_pedidos' => [
				'type'    => OModel::BOOL,
				'comment' => 'Mostrar observaciones en pedidos 0 no 1 si'
			],
			'mostrar_obs_ventas' => [
				'type'    => OModel::BOOL,
				'comment' => 'Mostrar observaciones en ventas 0 no 1 si'
			],
			'referencia' => [
				'type'     => OModel::TEXT,
				'nullable' => true,
				'default'  => null,
				'size'     => 50,
				'comment'  => 'Referencia original del proveedor'
			],
			'venta_online' => [
				'type'    => OModel::BOOL,
				'comment' => 'Indica si el producto está disponible desde la web 1 o no 0'
			],
			'mostrar_en_web' => [
				'type'    => OModel::BOOL,
				'comment' => 'Indica si debe ser mostrado en la web 1 o no 0'
			],
			'id_categoria' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'comment'  => 'Id de la categoría en la que se engloba el artículo'
			],
			'desc_corta' => [
				'type'     => OModel::TEXT,
				'nullable' => true,
				'default'  => null,
				'size'     => 250,
				'comment'  => 'Descripción corta para la web'
			],
			'desc' => [
				'type'     => OModel::LONGTEXT,
				'nullable' => true,
				'default'  => null,
				'comment'  => 'Descripción larga para la web'
			],
			'activo' => [
				'type'    => OModel::BOOL,
				'default' => true,
				'comment' => 'Indica si el artículo está en alta 1 o dado de baja 0'
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'     => OModel::UPDATED,
				'nullable' => true,
				'default'  => null,
				'comment'  => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
	}

	private ?array $codigos_barras = null;

	/**
	 * Obtiene el listado de códigos de barras de un artículo
	 *
	 * @return array Listado de códigos de barras
	 */
	public function getCodigosBarras(): array {
		if (is_null($this->codigos_barras)) {
			$this->loadCodigosBarras();
		}
		return $this->codigos_barras;
	}

	/**
	 * Guarda la lista de códigos de barras
	 *
	 * @param array $cb Lista de códigos de barras
	 *
	 * @return void
	 */
	public function setCodigosBarras(array $cb): void {
		$this->codigos_barras = $cb;
	}

	/**
	 * Carga la lista de códigos de barras de un artículo
	 *
	 * @return void
	 */
	public function loadCodigosBarras(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `codigo_barras` WHERE `id_articulo` = ? ORDER BY `por_defecto` DESC, `created_at` ASC";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$cb = new CodigoBarras();
			$cb->update($res);
			array_push($list, $cb);
		}

		$this->setCodigosBarras($list);
	}
}