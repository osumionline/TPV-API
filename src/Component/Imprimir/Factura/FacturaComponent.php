<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Component\Imprimir\Factura;

use Osumi\OsumiFramework\Core\OComponent;

class FacturaComponent extends OComponent {
  public ?int $id = null;
  public ?string $logo = null;
  public ?string $fecha = null;
  public ?int $num_factura = null;
  public ?string $factura_year = null;
  public ?string $nombre_comercial = null;
  public ?string $cif = null;
  public ?string $cliente_nombre_apellidos = null;
  public ?string $cliente_dni_cif = null;
  public ?string $direccion = null;
  public ?string $telefono = null;
  public ?string $email = null;
  public ?string $cliente_direccion = null;
  public ?string $cliente_codigo_postal = null;
  public ?string $cliente_poblacion = null;
  public array $list = [];
  public ?float $subtotal = null;
  public array $ivas = [];
  public ?float $descuento = null;
  public ?float $total = null;
}
