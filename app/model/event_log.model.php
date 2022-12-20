<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

/*
ventas:
  empleado que ha hecho la venta
articulos:
  articulo creado: empleado que ha creado el articulo, marca, proveedor, stock, pvp
  articulo modificado: empleado, marca, proveedor, stock, pvp (valores originales y nuevos)
*/

class EventLog extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada empleado'
			),
			new OModelField(
				name: 'nombre',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 50,
				comment: 'Nombre del empleado'
			),
			new OModelField(
				name: 'pass',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 200,
				comment: 'Contraseña cifrada del empleado'
			),
			new OModelField(
				name: 'color',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 6,
				comment: 'Código de color hexadecimal para distinguir a cada empleado'
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
			),
			new OModelField(
				name: 'deleted_at',
				type: OMODEL_DATE,
				nullable: true,
				default: null,
				comment: 'Fecha de borrado del cliente'
			)
		);

		parent::load($model);
	}
}
