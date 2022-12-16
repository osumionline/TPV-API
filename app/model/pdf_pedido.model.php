<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class PdfPedido extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada PDF'
			),
			new OModelField(
				name: 'id_pedido',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'pedido.id',
				comment: 'Id del pedido al que pertenece el PDF'
			),
			new OModelField(
				name: 'nombre',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 200,
				comment: 'Nombre del archivo PDF'
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

	/**
	 * Función para obtener la ruta al archivo físico
	 *
	 * @return string Ruta al archivo
	 */
	public function getFileRoute(): string {
		global $core;
		return $core->config->getDir('web').'pdf/'.$this->get('id_pedido').'/'.$this->get('id').'.pdf';
	}

	/**
	 * Función para obtener la URL del archivo PDF
	 *
	 * @return string URL del archivo PDF
	 */
	public function getURL(): string {
		global $core;
		return $core->config->getUrl('base').'pdf/'.$this->get('id_pedido').'/'.$this->get('id').'.pdf';
	}
	
	/**
	 * Función para borrar un  pdf, el archivo, y luego su registro
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$ruta = $this->getFileRoute();
		if (file_exists($ruta)){
			unlink($ruta);
		}

		$this->delete();
	}
}
