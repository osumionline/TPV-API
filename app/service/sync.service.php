<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\App\Service\ventasService;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\LineaVenta;

class syncService extends OService {
	private ?ventasService $ventas_service = null;

	function __construct() {
		$this->loadService();
		$this->ventas_service = new ventasService();
	}

	/**
	 * Función para enviar datos a la tienda online
	 *
	 * @param array $data Datos a enviar
	 *
	 * @return void
	 */
	public function send(array $data): void {
		$token = $this->createToken($data);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token' => $token]));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);

		curl_close ($ch);
	}

	/**
	 * Función para codificar los datos a enviar
	 *
	 * @param string $data Datos a codificar
	 *
	 * @return string Datos codificados
	 */
	private function base64urlEncode(string $data): ?string {
		$b64 = base64_encode($data);

		if ($b64 === false) {
			return null;
		}

		$url = strtr($b64, '+/', '-_');

		return rtrim($url, '=');
	}

	/**
	 * Función para descodificar datos recibidos
	 *
	 * @param string $data Datos que hay que descodificar
	 *
	 * @param bool $strict Indica si hay que usar el modo strict, por defecto no
	 *
	 * @return string Datos descodificados
	 */
	public function base64urlDecode(string $data, bool $strict = false): string {
		// Convert Base64URL to Base64 by replacing “-” with “+” and “_” with “/”
		$b64 = strtr($data, '-_', '+/');

		// Decode Base64 string and return the original data
		return base64_decode($b64, $strict);
	}

	/**
	 * Función para crear el token a partir de los datos
	 *
	 * @param array $params Lista de parámetros a enviar
	 *
	 * @return string Token generado
	 */
	public function createToken(array $params): string {
		$header = ['alg'=> 'HS256', 'typ'=>'JWT'];
		$header_64 = $this->base64urlEncode(json_encode($header));
		$payload = $params;
		$payload_64 = $this->base64urlEncode(json_encode($payload));

		$signature = hash_hmac('sha256', $header_64.'.'.$payload_64, $this->secret);

		return $header_64.'.'.$payload_64.'.'.$signature;
	}

	/**
	 * Función para comprobar si el token recibido es correcto
	 *
	 * @param string $token Token recibido
	 *
	 * @return bool Indica si el token recibido es válido o no
	 */
	public function checkToken(string $token): bool {
		$pieces = explode('.', $token);
		$header_64  = $pieces[0];
		$payload_64 = $pieces[1];
		$signature  = $pieces[2];

		$signature_check = hash_hmac('sha256', $header_64.'.'.$payload_64, $this->secret);

		if ($signature === $signature_check) {
			$this->params = json_decode($this->base64urlDecode($payload_64), true);
			return true;
		}
		return false;
	}

	/**
	 * Función para actualizar el stock a partir de un token
	 *
	 * @param string $token Token con información de artículos a actualizar
	 *
	 * @return void
	 */
	public function updateStock(string $token): void {
		if (!$this->checkToken($token)) {
			$this->getLog()->info('Sync - Token error: '.$token);
			return false;
		}

		$this->getLog()->info('Sync - Token ok');

		foreach ($this->params as $data) {
			$venta = new Venta();
			$venta->set('num_venta',      $this->ventas_service->generateNumVenta());
			$venta->set('id_empleado',    null);
			$venta->set('id_cliente',     null);
			$venta->set('total',          $data['amount']);
			$venta->set('entregado',      0);
			$venta->set('pago_mixto',     0);
			$venta->set('id_tipo_pago',   $data['method']);
			$venta->set('entregado_otro', 0);
			$venta->set('saldo',          null);
			$venta->set('facturada',      false);
			$venta->set('tbai_huella',    null);
			$venta->set('tbai_qr',        null);
			$venta->set('tbai_url',       null);
			$venta->save();

			$this->getLog()->info('Sync - Nueva venta '.$venta->get('id'));

			foreach ($data['items'] as $item) {
				$this->getLog()->info('Sync - Item: '.var_export($item, true));

				$art = new Articulo();
				$art->find(['localizador' => $item['localizador']]);
				$id_articulo = $art->get('id');
				$nombre      = $art->get('nombre');
				$puc         = $art->get('puc');
				$pvp         = $art->get('pvp');
				$iva         = $art->get('iva');

				$lv = new LineaVenta();
				$lv->set('id_venta', $venta->get('id'));
				$lv->set('id_articulo', $art->get('id'));
				$lv->set('nombre_articulo', $art->get('nombre'));
				$lv->set('puc', $art->get('puc'));
				$lv->set('pvp', $art->get('pvp'));
				$lv->set('iva', $art->get('iva'));
				$lv->set('descuento', 0);
				$lv->set('importe_descuento', null);
				$lv->set('importe', $item['num'] * $art->get('pvp'));
				$lv->set('devuelto', 0);
				$lv->set('unidades', $item['num']);
				$lv->save();

				$this->getLog()->info('Sync - Linea ticket introducida');

				$articulo->set('stock', $articulo->get('stock') - $item['num']);
				$articulo->save();

				$this->getLog()->info('Sync - Actualizo stock');
			}
		}
	}
}
