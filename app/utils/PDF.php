<?php declare(strict_types=1);

namespace OsumiFramework\App\Utils;

use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\LineaVenta;

class PDF extends \FPDF {
	private string $logo = '';
	private string $ruta_iconos = '';

	/**
	 * Función para guardar la ubicación del logo
	 *
	 * @param string $logo Ubicación del logo
	 *
	 * @return void
	 */
	public function setLogo(string $logo): void {
		$this->logo = $logo;
	}

	/**
	 * Función para obtener la ubicación del logo
	 *
	 * @return string Ubicación del logo
	 */
	public function getLogo(): string {
		return $this->logo;
	}

	/**
	 * Función para guardar la ruta de los iconos
	 *
	 * @param string $ruta_iconos Ruta de los iconos
	 *
	 * @return void
	 */
	public function setRutaIconos(string $ruta_iconos): void {
		$this->ruta_iconos = $ruta_iconos;
	}

	/**
	 * Función para obtener la ruta de los iconos
	 *
	 * @return string Ruta de los iconos
	 */
	public function getRutaIconos(): string {
		return $this->ruta_iconos;
	}

	/**
	 * Función para crear la cabecera del ticket
	 *
	 * @param Venta $venta Objeto venta con todos los datos de la venta
	 *
	 * @return void
	 */
	private function addHeader(Venta $venta): void {
		$this->Image($this->getLogo(), 7, 7, 60);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', '', 8);
		$this->SetY(22);
		$this->SetX(0);
		$this->Cell(68, 6, 'Santiago Brouard 5. Bajo 1D. 48012. Bilbao', 0, '', 'C');
		$this->SetY(26);
		$this->SetX(0);
		$this->Cell(68, 6, 'Tel: 946087042 - NIF: E95895843', 0, '', 'C');
		$this->SetFont('Arial', '', 7);
		$this->SetY(31);
		$this->SetX(0);
		$this->Cell(70, 6, '      indomable          indomable          www.indomablestore.com', 0, '', 'L');
		$this->Image($this->getRutaIconos().'icono_instagram.png', 1, 32, 3.5);
		$this->Image($this->getRutaIconos().'icono_facebook.png', 19, 32, 3.5);
		$this->Image($this->getRutaIconos().'icono_web.png', 37, 32, 3.5);

		$this->SetY(28);
		$this->SetX(0);

		$this->Ln(12);
		$this->SetFont('Arial', '', 10);

		$this->SetX(0);
		$this->Cell(24, 4, $venta->get('created_at', 'd/m/Y  H:i')." - ".$venta->get('id'), 'TB', '', 'L');
		$this->Cell(20, 4, '', 'TB');
		$this->Cell(28, 4, $venta->get('created_at', 'd/m/Y  H:i'), 'TB', '', 'R');

		$this->SetY(48);
		$this->SetFont('Arial', '', 7);
		$this->SetX(0);
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
		$this->addHeader($venta);
		$this->addData($venta);
		$this->addFooter();

		$this->Output('F', $route, true);
	}
}
