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
		return str_ireplace('.', ',', strval($num)).' '.chr(128);
	}

	/**
	 * Función para añadir el apartado de redes sociales / web
	 *
	 * @return void
	 */
	private function addSocial(): void {
		$this->SetFont('Arial', '', 7);
		$this->SetY(31);
		$this->SetX(0);

		$social = [];
		if ($this->getAppData()->getTwitter() != '') {
			array_push($social, ['twitter', $this->getAppData()->getTwitter()]);
		}
		if ($this->getAppData()->getFacebook() != '') {
			array_push($social, ['facebook', $this->getAppData()->getFacebook()]);
		}
		if ($this->getAppData()->getInstagram() != '') {
			array_push($social, ['instagram', $this->getAppData()->getInstagram()]);
		}
		if ($this->getAppData()->getWeb() != '') {
			array_push($social, ['web', $this->getAppData()->getWeb()]);
		}

		$num_char = 0;
		$gaps = 0;
		switch (count($social)) {
			case 4: {
				$num_char = 11.5;
				$gaps = 5;
			}
			break;
			case 3: {
				$num_char = 16.3;
				$gaps = 4;
			}
			break;
			case 2: {
				$num_char = 26;
				$gaps = 3;
			}
			break;
			case 1: {
				$num_char = 55;
				$gaps = 2;
			}
			break;
		}

		$free_gap = 0;
		foreach ($social as $item) {
		  if (strlen($item[1]) < $num_char) {
			$free_gap += $num_char - strlen($item[1]);
			}
		}

		$space_in_gaps = ceil($free_gap / $gaps);
		$data = [];
		foreach ($social as $item) {
			array_push($data, ['gap']);
			array_push($data, ['item', $item[0], $item[1]]);
		}

		$x = 0;

		foreach ($data as $item) {
		  if ($item[0] == 'gap') {
			$x += $space_in_gaps;
			}
		  if ($item[0] == 'item') {
			$this->SetX($x+2);
			$this->Image($this->getRutaIconos().'icono_'.$item[1].'.png', null, 32.5, 3);
			$this->SetX($x);
			$this->SetFillColor(183, 183, ($x*5));
			//$this->Cell(20, 6, '_______'.$item[2], 0, 0, 'C', true);
			$this->Write(6, '____'.$item[2]);
			$x += strlen('____'.$item[2]);
			}
		}
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

//$this->SetFont('Courier', '', 8);
//$this->SetX(0);
//$this->Cell(0, 6, '', 0, 0, 'C');
		$this->addSocial();

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
		// Líneas de venta
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
			$this->Cell(47, 3, utf8_decode(mb_substr($linea->getArticulo()->get('nombre'), 0, 45, 'utf-8')), 0);
			$this->Ln(7);
		}

		// Total
		$this->SetX(0);
		$this->Cell(0, 1, '', 'T');
		$this->Ln(2);
		$this->SetX(3);
		$this->Cell(32, 2, '', 0);
		$this->SetFont('Arial', 'B', 11);
		$this->Cell(14, 2, 'T0TAL:', 0);
		$this->Cell(22, 2, $this->formatNumber($this->getVenta()->get('total')), 0, '', 'R');
		$this->Ln(6);
		$this->SetFont('Arial','',8);
		$this->SetX(34);

		// Tipo de pago
		if (!$this->getVenta()->get('pago_mixto')) {
			if (is_null($this->getVenta()->get('id_tipo_pago'))) {
				$this->Cell(16, 2, 'Efectivo:', 0, '', 'R');
				$this->Cell(21, 2, $this->formatNumber($this->getVenta()->get('entregado')), 0, '', 'R');
				$this->Ln(3);
				$this->SetX(34);
				$this->Cell(16, 2, 'CAMBIO:', 0, '', 'R');
				$this->Cell(21, 2, $this->formatNumber($this->getVenta()->get('total') - $this->getVenta()->get('efectivo')), 0, '', 'R');
			}
			else {
				$this->Cell(16, 2, 'Forma Pago:', 0, '', 'R');
				$this->Cell(21, 2, 'Tarjeta', 0, '', 'R');
			}
		}
		else {
			$this->Cell(16, 2, 'Forma Pago:', 0, '', 'R');
			$this->Cell(21, 2, 'Mixto', 0, '', 'R');
		}

		// Cliente
		if (!is_null($this->getVenta()->get('id_cliente'))) {
			$this->Ln(8);
			$this->SetFont('Arial', 'B', 8);
			$this->Cell(70, 3, 'Cliente: '.mb_convert_encoding($this->getVenta()->getCliente()->get('nombre_apellidos'), 'iso-8859-1', 'utf-8'), 0, '', 'C');
		}
	}

	/**
	 * Función para crear el pie del ticket
	 */
	private function addFooter(): void {
		$this->SetFont('Arial', '', 7);
		$this->Ln(6);
		$this->SetX(0);
		$this->Cell(0, 2, utf8_decode('I.V.A Incluído'), 0, '', 'C');
		$this->Ln(5);
		$this->SetX(0);
		$this->Cell(0, 2, utf8_decode('No se admitirán cambios ni devoluciones sin ticket o sin caja'), 0, '', 'C');
		$this->Ln(3);
		$this->SetX(0);
		$this->Cell(0, 2, utf8_decode('Plazo máximo de 15 días para devoluciones'), 0, '', 'C');
		$this->Ln(3);
		$this->SetX(0);
		$this->Cell(0, 2, utf8_decode('No se admitirán devoluciones de complementos'), 0, '', 'C');
		$this->Ln(3);
		$this->SetX(0);
		$this->Cell(0, 2, utf8_decode('Resto de condiciones en tienda'), 0, '', 'C');
		$this->Ln(4);
		$this->SetX(0);
		// 14 Diciembre - 7 Enero
		if (
			(date('n', time()) == 12 && date('j', time())>14) ||
			(date('n', time()) == 1  && date('j', time())<7)
		) {
			$this->Cell(0, 2, utf8_decode('¡FELICES FIESTAS!'), 0, '', 'C');
			$this->Ln(4);
			$this->Cell(0, 2, utf8_decode('GABON ZORIONTSUAK!'), 0, '', 'C');
		}
		else {
			$this->Cell(0, 2, utf8_decode('GRACIAS POR SU VISITA'), 0, '', 'C');
			$this->Ln(4);
			$this->Cell(0, 2, utf8_decode('ESKERRIK ASKO ETORTZEAGATIK'), 0, '', 'C');
		}
		$this->Ln(2);
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
