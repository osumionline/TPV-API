<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\App\Model\Proveedor;

class Comercial extends OModel {
	#[OPK(
	  comment: 'Id único para cada comercial'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del proveedor para el que trabaja el comercial',
	  nullable: true,
	  ref: 'proveedor.id',
	  default: null
	)]
	public ?int $id_proveedor;

	#[OField(
	  comment: 'Nombre del comercial',
	  nullable: false,
	  max: 50,
	  default: null
	)]
	public ?string $nombre;

	#[OField(
	  comment: 'Teléfono del comercial',
	  nullable: true,
	  max: 15,
	  default: null
	)]
	public ?string $telefono;

	#[OField(
	  comment: 'Dirección de email del comercial',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $email;

	#[OField(
	  comment: 'Observaciones o notas personales del comercial',
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
	  comment: 'Fecha de borrado del cliente',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;

	private ?Proveedor $proveedor = null;

	/**
	 * Obtiene el proveedor al que pertenece el comercial
	 *
	 * @return Proveedor Proveedor al que pertenece el comercial
	 */
	public function getProveedor(): Proveedor {
		if (is_null($this->proveedor)) {
			$this->loadProveedor();
		}
		return $this->proveedor;
	}

	/**
	 * Guarda el proveedor al que pertenece el comercial
	 *
	 * @param Proveedor $p Proveedor al que pertenece el comercial
	 *
	 * @return void
	 */
	public function setProveedor(Proveedor $p): void {
		$this->proveedor = $p;
	}

	/**
	 * Carga el proveedor al que pertenece el comercial
	 *
	 * @return void
	 */
	public function loadProveedor(): void {
		$this->setProveedor(Proveedor::findOne(['id' => $this->id_proveedor]));
	}
}
