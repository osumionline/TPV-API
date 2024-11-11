<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\Plugins\OTicketBai;
use Osumi\OsumiFramework\App\Service\VentasService;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\LineaVenta;
use Osumi\OsumiFramework\App\Model\HistoricoArticulo;
use Osumi\OsumiFramework\App\Utils\AppData;

class SyncService extends OService {
	private ?VentasService $ventas_service = null;
	private ?string $url    = null;
	private ?string $secret = null;
	private array   $params = [];

	function __construct() {
		$this->ventas_service = inject(VentasService::class);

		OTools::checkOfw('cache');
		$app_data_file = $this->getConfig()->getDir('ofw_cache') . 'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}
		$this->url    = $app_data->getUrlApi();
		$this->secret = $app_data->getSecretApi();
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
		$header = ['alg '=> 'HS256', 'typ' => 'JWT'];
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

		$signature_check = hash_hmac('sha256', $header_64 . '.' . $payload_64, $this->secret);

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
	 * @return array Estado de la operación
	 */
	public function updateStock(string $token): array {
		if (!$this->checkToken($token)) {
			$this->getLog()->info('Sync - Token error: '.$token);
			return [];
		}

		$this->getLog()->info('Sync - Token ok');
		$status = [];

		foreach ($this->params as $data) {
			$venta_status = ['id' => $data['id'], 'status' => 'ok'];

			// Primero compruebo que existan todos los artículos
			$articulos = [];
			foreach ($data['items'] as $item) {
				$art = Articulo::findOne(['localizador' => $item['localizador']]);
				if (!is_null($art)) {
					$articulos['loc_' . $item['localizador']] = $art;
				}
				else {
					$venta_status['status'] = 'error';
					break;
				}
			}

			if ($venta_status['status'] == 'ok') {
				$venta = Venta::create();
				$venta->num_venta      = $this->ventas_service->generateNumVenta();
				$venta->id_empleado    = 1;
				$venta->id_cliente     = null;
				$venta->total          = $data['amount'];
				$venta->entregado      = 0;
				$venta->pago_mixto     = 0;
				$venta->id_tipo_pago   = $data['method'];
				$venta->entregado_otro = 0;
				$venta->saldo          = null;
				$venta->facturada      = false;
				$venta->tbai_huella    = null;
				$venta->tbai_qr        = null;
				$venta->tbai_url       = null;
				$venta->save();

				$this->getLog()->info('Sync - Nueva venta ' . $venta->id);

				foreach ($data['items'] as $item) {
					$this->getLog()->info('Sync - Item: ' . var_export($item, true));

					$art = $articulos['loc_' . $item['localizador']];

					$lv = LineaVenta::create();
					$lv->id_venta          = $venta->id;
					$lv->id_articulo       = $art->id;
					$lv->nombre_articulo   = $art->nombre;
					$lv->puc               = $art->puc;
					$lv->pvp               = $art->pvp;
					$lv->iva               = $art->iva;
					$lv->descuento         = 0;
					$lv->importe_descuento = null;
					$lv->importe           = $item['num'] * $art->pvp;
					$lv->devuelto          = 0;
					$lv->unidades          = $item['num'];
					$lv->regalo            = false;
					$lv->save();

					$this->getLog()->info('Sync - Linea ticket introducida');

					$stock_previo = $art->stock;

					$art->stock = $art->stock - $item['num'];
					$art->save();

					// Histórico
					$ha = HistoricoArticulo::create();
					$ha->id_articulo  = $art->id;
					$ha->tipo         = HistoricoArticulo::FROM_VENTA_SYNC;
					$ha->stock_previo = $stock_previo;
					$ha->diferencia   = $item['num'];
					$ha->stock_final  = $art->stock;
					$ha->id_venta     = $venta->id;
					$ha->id_pedido    = null;
					$ha->puc          = $art->puc;
					$ha->pvp          = $art->pvp;
					$ha->save();

					$this->getLog()->info('Sync - Actualizo stock');
				}

				$tbai_conf = $this->getConfig()->getPluginConfig('ticketbai');
				if ($tbai_conf['token'] !== '' && $tbai_conf['nif'] !== '') {
					$tbai = new OTicketBai( ($this->getConfig()->getEnvironment() === 'prod') );

					if ($tbai->checkStatus()) {
						$this->getLog()->info('TicketBai status OK');
						$response = $tbai->nuevoTbai($venta->getDatosTBai());
						if (is_array($response)) {
							$this->getLog()->info('TicketBai response OK');
							$venta->tbai_huella = $response['huella_tbai'];
							$venta->tbai_qr     = $response['qr'];
							$venta->tbai_url    = $response['url'];
							$venta->save();
						}
						else {
							$this->getLog()->error('Ocurrió un error al generar el TicketBai de la venta ' . $venta->id);
							$this->getLog()->error(var_export($response, true));
						}
					}
				}
			}

			$status[] = $venta_status;
		}
		return $status;
	}

	/**
	 * Función para obtener los datos con los que sincronizar el stock
	 *
	 * @return array Lista de localizadores, stock y pvp
	 */
	public function getSyncStock(): array {
		$db = new ODB();
		$sql = "SELECT `localizador`, `stock`, `pvp`, `pvp_descuento` FROM `articulo`";
		$db->query($sql);
		$ret = [];

		while ($res = $db->next()) {
			$cad = $res['localizador'] . '_' . $res['stock'] . '_' . $res['pvp'] . '_';
			if (!is_null($res['pvp_descuento']) && $res['pvp_descuento'] !== '' && $res['pvp_descuento'] !== 0) {
				$cad .= $res['pvp_descuento'];
			}
			else {
				$cad .= '0';
			}
			$ret[] = $cad;
		}

		return $ret;
	}

	/**
	 * Función para llamar a la web con la actualización del stock
	 *
	 * @return string Resultado de la sincronización
	 */
	public function syncStock(): string {
		OTools::checkOfw('cache');
		$app_data_file = $this->getConfig()->getDir('ofw_cache') . 'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}

		$data = $this->getSyncStock();

		$header     = array('alg' => 'HS256', 'typ' => 'JWT');
		$header_64  = base64_encode(json_encode($header));
		$payload    = array('data' => implode('#', $data));
		$payload_64 = base64_encode(json_encode($payload));

		$signature = hash_hmac('sha256', $header_64 . '.' . $payload_64, $app_data->getSecretApi());

		$token = $header_64 . '.' . $payload_64 . '.' . $signature;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $app_data->getUrlApi() . 'syncStock');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token' => $token]));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);

		curl_close ($ch);

		return $server_output;
	}
}
