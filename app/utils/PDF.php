<?php declare(strict_types=1);

namespace OsumiFramework\App\Utils;

use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\LineaVenta;
use OsumiFramework\App\Utils\AppData;

class PDF extends \FPDF {
	private ?Venta $venta = null;
	private ?AppData $app_data = null;
	private string $logo = '';
	private string $ruta_iconos = '';

	/**
	 * Función para guardar los datos de la venta
	 *
	 * @param Venta $venta Objeto con los datos de la venta
	 *
	 * @return void
	 */
	public function setVenta(Venta $venta): void {
		$this->venta = $venta;
	}

	/**
	 * Función para obtener los datos de la venta
	 *
	 * @return Venta Datos de la venta
	 */
	public function getVenta(): Venta {
		return $this->venta;
	}

	/**
	 * Función para guardar la configuración de la aplicación
	 *
	 * @param AppData $app_data Objeto con la configuración de la aplicación
	 *
	 * @return void
	 */
	public function setAppData(AppData $app_data): void {
		$this->app_data = $app_data;
	}

	/**
	 * Función para obtener la configuración de la aplicación
	 *
	 * @return AppData Configuración de la aplicación
	 */
	public function getAppData(): AppData {
		return $this->app_data;
	}

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
	 * Función para dar formato a un importe
	 *
	 * @param float $num Número al que dar formato
	 *
	 * @return string Número formateado y con el símbolo del euro
	 */
	public function formatNumber(float $num): string {
		return str_ireplace('.', ',', strval($num)).chr(128);
	}

	/**
	 * Función para crear la cabecera del ticket
	 *
	 * @return void
	 */
	private function addHeader(): void {
		$this->Image($this->getLogo(), 7, 7, 60);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', '', 8);
		$this->SetY(22);
		$this->SetX(0);
		$this->Cell(0, 6, $this->getAppData()->getDireccion(), 0, 0, 'C');
		$this->SetY(26);
		$this->SetX(0);
		$this->Cell(0, 6, 'Tel: '.$this->getAppData()->getTelefono().' - NIF: '.$this->getAppData()->getCif(), 0, 0, 'C');
		$this->SetFont('Arial', '', 7);
		$this->SetY(31);
		$this->SetX(0);

		$redes = [];
		if ($this->getAppData()->getTwitter() != '') {
			array_push($redes, ['twitter', $this->getAppData()->getTwitter()]);
		}
		if ($this->getAppData()->getFacebook() != '') {
			array_push($redes, ['facebook', $this->getAppData()->getFacebook()]);
		}
		if ($this->getAppData()->getInstagram() != '') {
			array_push($redes, ['instagram', $this->getAppData()->getInstagram()]);
		}
		if ($this->getAppData()->getWeb() != '') {
			array_push($redes, ['web', $this->getAppData()->getWeb()]);
		}

		foreach ($redes as $i => $red) {
			$this->SetX($i * 20);
			$this->Cell(20, 6, '      '.$red[1], 0, 0, 'L');
			$this->Image($this->getRutaIconos().'icono_'.$red[0].'.png', (1 + ($i * 20)), 32, 3.5);
		}

		$this->SetY(28);
		$this->SetX(0);

		$this->Ln(12);
		$this->SetFont('Arial', '', 10);

		$this->SetX(0);
		$this->Cell(0, 4, '', 'TB');
		$this->SetX(2);
		$this->Cell(22, 4, $this->getVenta()->get('id'));
		$this->Cell(20, 4, '');
		$this->Cell(34, 4, $this->getVenta()->get('created_at', 'd/m/Y  H:i'), 0, 0, 'R');

		$this->SetY(48);
		$this->SetFont('Arial', '', 7);
		$this->SetX(0);
	}

	/**
	 * Función para añadir los datos de la venta en el ticket
	 *
	 * @return void
	 */
	private function addData(): void {
		$lineas = $this->getVenta()->getLineas();
		foreach ($lineas as $linea) {
			$this->SetX(0);
			$this->Cell(12, 3, 'Uds: '.$linea->get('unidades'), 0);
			$this->Cell(19, 3, 'Loc: '.$linea->getArticulo()->get('localizador'), 0);
			$this->Cell(9, 3, 'PVP:', 0);
			$this->Cell(13, 3, $this->formatNumber($linea->get('pvp')), 0, '', 'R');
			$this->Cell(9, 3, 'TOT:', 0);
			$this->Cell(13, 3, $this->formatNumber($linea->get('importe')), 0, '', 'R');
			$this->Ln(4);
			$this->Cell(47, 3, ucfirst(mb_strtolower(utf8_decode(mb_substr($linea->getArticulo()->get('nombre'), 0, 45, 'utf-8')))), 0);
			$this->Ln(7);
		}

		$this->SetX(0);
		$this->Cell(0, 1, '', 'T');
		$this->Ln(2);
		$this->SetX(3);
		$this->Cell(32, 2, '', 0);
		$this->SetFont('Arial', 'B', 11);
		$this->Cell(14, 2, 'T0TAL:', 0);
		$this->Cell(22, 2, $this->formatNumber($this->getVenta()->get('total')), 0, '', 'R');
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
	 * @param AppData $app_data Objeto con la configuración de la tienda
	 *
	 * @return void
	 */
	public function ticket(Venta $venta, string $route, AppData $app_data): void {
		$this->setVenta($venta);
		$this->setAppData($app_data);

		$this->SetMargins(0,0,0);
		$this->AddPage();
		$this->addHeader();
		$this->addData();
		$this->addFooter();

		$this->Output('F', $route, true);
	}
}
