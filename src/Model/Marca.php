<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Proveedor;

class Marca extends OModel {
	#[OPK(
	  comment: 'Id único para cada marca'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Nombre de la marca',
	  nullable: false,
	  max: 50,
	  default: null
	)]
	public ?string $nombre;

	#[OField(
	  comment: 'Dirección física de la marca',
	  nullable: true,
	  max: 200,
	  default: null
	)]
	public ?string $direccion;

	#[OField(
	  comment: 'Teléfono de la marca',
	  nullable: true,
	  max: 15,
	  default: null
	)]
	public ?string $telefono;

	#[OField(
	  comment: 'Dirección de email de la marca',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $email;

	#[OField(
	  comment: 'Dirección de la página web de la marca',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $web;

	#[OField(
	  comment: 'Observaciones o notas personales de la marca',
	  nullable: true,
	  default: null,
	  type: OField::LONGTEXT
	)]
	public ?string $observaciones;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	#[OField(
	  comment: 'Fecha de borrado de la marca',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;

	private ?Proveedor $proveedor = null;

	/**
	 * Obtiene el proveedor al que pertenece la marca
	 *
	 * @return Proveedor Proveedor al que pertenece la marca
	 */
	public function getProveedor(): ?Proveedor {
		if (is_null($this->proveedor)) {
			$this->loadProveedor();
		}
		return $this->proveedor;
	}

	/**
	 * Guarda el proveedor al que pertenece la marca
	 *
	 * @param Proveedor $p Proveedor al que pertenece la marca
	 *
	 * @return void
	 */
	public function setProveedor(?Proveedor $p): void {
		$this->proveedor = $p;
	}

	/**
	 * Carga el proveedor al que pertenece la marca
	 *
	 * @return void
	 */
	public function loadProveedor(): void {
		$db = new ODB();
		$sql = "SELECT p.* FROM `proveedor` p, `proveedor_marca` pm WHERE p.`id` = pm.`id_proveedor` AND pm.`id_marca` = ?";
		$db->query($sql, [$this->id]);

		$p = null;
		if ($res = $db->next()) {
			$p = Proveedor::from($res);
		}
		$this->setProveedor($p);
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
		return $core->config->getUrl('base') . "/marcas/{$this->id}.webp";
	}

	/**
	 * Obtiene la ruta física a la imagen del logo
	 *
	 * @return string Ruta del archivo de la imagen
	 */
	public function getRutaFoto(): string {
		global $core;
		return $core->config->getDir('public') . "marcas/{$this->id}.webp";
	}

	/**
	 * Función para borrar completamente una marca y sus relaciones
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$db = new ODB();
		$sql = "DELETE FROM `proveedor_marca` WHERE `id_marca` = ?";
		$db->query($sql, [$this->id]);

		$ruta_foto = $this->getRutaFoto();
		if (file_exists($ruta_foto)) {
			unlink($ruta_foto);
		}

		$sql = "UPDATE `articulo` SET `id_marca` = NULL WHERE `id_marca` = ?";
		$db->query($sql, [$this->id]);

		$this->delete();
	}
}
