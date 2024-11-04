<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class PdfPedido extends OModel {
	#[OPK(
	  comment: 'Id único para cada PDF'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del pedido al que pertenece el PDF',
	  nullable: false,
	  ref: 'pedido.id',
	  default: null
	)]
	public ?int $id_pedido;

	#[OField(
	  comment: 'Nombre del archivo PDF',
	  nullable: true,
	  max: 200,
	  default: null
	)]
	public ?string $nombre;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Función para obtener la ruta al archivo físico
	 *
	 * @return string Ruta al archivo
	 */
	public function getFileRoute(): string {
		return $this->getFileFolder() . '/' . $this->id . '.pdf';
	}

	/**
	 * Función para obtener la ruta a la carpeta del archivo físico
	 *
	 * @return string Ruta a la carpeta del archivo
	 */
	public function getFileFolder(): string {
		global $core;
		return $core->config->getDir('public') . 'pdf/' . $this->id_pedido;
	}

	/**
	 * Función para obtener la URL del archivo PDF
	 *
	 * @return string URL del archivo PDF
	 */
	public function getURL(): string {
		global $core;
		return $core->config->getUrl('base') . 'pdf/' . $this->id_pedido . '/' . $this->id . '.pdf';
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
