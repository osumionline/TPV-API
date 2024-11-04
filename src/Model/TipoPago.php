<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Venta;

class TipoPago extends OModel {
	#[OPK(
	  comment: 'Id único para cada tipo de pago'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Nombre del tipo de pago',
	  nullable: false,
	  max: 50,
	  default: null
	)]
	public ?string $nombre;

	#[OField(
	  comment: 'Slug del nombre del tipo de pago',
	  nullable: false,
	  max: 50,
	  default: null
	)]
	public ?string $slug;

	#[OField(
	  comment: 'Indica si el tipo de pago afecta a la caja',
	  nullable: false,
	  default: false
	)]
	public ?bool $afecta_caja;

	#[OField(
	  comment: 'Orden del tipo de pago en la lista completa',
	  nullable: false,
	  default: null
	)]
	public ?int $orden;

	#[OField(
	  comment: 'Indica si el tipo de pago es para tienda física',
	  nullable: false,
	  default: true
	)]
	public ?bool $fisico;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	#[OField(
	  comment: 'Fecha de borrado del proveedor',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;

  private ?array $ventas = null;

	/**
	 * Obtiene el listado de ventas de un tipo de pago
	 *
	 * @return array Listado de ventas
	 */
	public function getVentas(): array {
		if (is_null($this->ventas)) {
			$this->loadVentas();
		}
		return $this->ventas;
	}

	/**
	 * Guarda la lista de ventas
	 *
	 * @param array $v Lista de ventas
	 *
	 * @return void
	 */
	public function setVentas(array $v): void {
		$this->ventas = $v;
	}

	/**
	 * Carga la lista de ventas de un tipo de pago
	 *
	 * @return void
	 */
	public function loadVentas(): void {
		$this->setVentas(Venta::where(['id_tipo_pago' => $this->id]));
	}

	/**
	 * Función para obtener la url de la imagen del logo
	 *
	 * @return string Url de la imagen o null si no tiene
	 */
	public function getFoto(): ?string {
		global $core;
		$ruta_foto = $this->getRutaFoto();
		if (!file_exists($ruta_foto)) {
			return null;
		}
		return $core->config->getUrl('base') . "/tipos-pago/icon-{$this->slug}.webp";
	}

	/**
	 * Obtiene la ruta física a la imagen del logo
	 *
	 * @return string Ruta del archivo de la imagen
	 */
	public function getRutaFoto(): string {
		global $core;
		return $core->config->getDir('public') . "tipos-pago/icon-{$this->slug}.webp";
	}
}
