<?php declare(strict_types=1);

namespace OsumiFramework\App\Utils;

use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\LineaVenta;

class PDF extends \FPDF {
  /**
   * Función para crear la cabecera del ticket
   *
   * @return void
   */
  private function addHeader(): void {

  }

  /**
   * Función para añadir los datos de la venta en el ticket
   *
   * @param Venta $venta Objeto venta con todos los datos de la venta
   *
   * @return void
   */
  private function addData(Venta $venta): void {
    $this->SetFont('Arial','B',16);
    $this->Cell(40,10,iconv('UTF-8', 'windows-1252', 'Venta '.$venta->get('id')));
  }

  /**
   * Función para crear el pie del ticket
   */
  private function addFooter(): void {

  }

  /**
   * Función para crear el PDF del ticket de una venta
   *
   * @param Venta $venta Objeto venta con todos los datos de la venta
   *
   * @param string $route Ruta donde se guardará el archivo PDF resultante
   *
   * @return void
   */
  public function ticket(Venta $venta, string $route): void {
    $this->SetMargins(0,0,0);
		$this->AddPage();
    $this->addHeader();
    $this->addData($venta);
    $this->addFooter();

    $this->Output('F', $route, true);
  }
}
