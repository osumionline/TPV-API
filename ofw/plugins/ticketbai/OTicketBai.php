<?php declare(strict_types=1);

namespace OsumiFramework\OFW\Plugins;

use OsumiFramework\OFW\Tools\OTools;

/**
 * Clase para realizar llamadas al servicio TicketBaiWS de Berein
 */
class OTicketBai {
	private string $url              = 'http://api.ticketbaiws.eus/';
	private string $token            = '';
	private string $nif              = '';
	private bool   $ssl_verification = false;
	private bool   $debug            = false;

	function __construct() {
		global $core;
		$conf = $core->config->getPluginConfig('ticketbai');
		$this->token = $conf['token'];
		$this->nif   = $conf['nif'];
	}

	/**
	 * Este método permite ver el estado del sistema y sirve para comprobar la conectividad.
	 * En caso de que el certificado electrónico haya expirado, la licencia de ticketbai WS haya expirado o haya algún otro problema notificará un estado de ERROR.
	 *
	 * @return bool Estado del sistema
	 */
	public function check_status(): bool {
		$response = $this->callService('status', 'GET');		
		return ($respuesta['result'] == 'OK');
	}

	/**
	 * Este método permite enviar una factura a la hacienda foral correspondiente y devolverá la huella TBAI, la imagen código QR en base64
	 * y la URL de validación de la factura de la hacienda foral que contiene el QR. El entorno de test permite generar TBAIs en el entorno
	 * de pruebas de la hacienda correspondiente.
	 *
	 * ZUZENDU: El servicio de Zuzendu para poder modificar facturas enviadas erróneamente o poder reenviar facturas que previamente habían
	 * dado error, se ha de hacer reenviando de nuevo la factura que se desea corregir con el parámetro zuzendu a true. La serie y el número
	 * de factura no pueden ser modificados, deben ser los mismos que contenía la factura original
	 *
	 * @param array $info_factura Parámetros de la factura
	 *
	 * @return string | array Array con información (huella, QR y url) en caso de éxito o mensaje de error
	 */
	public function nuevo_tbai(array $info_factura): string | array {
		$response = $this->callService('tbai', 'POST', $info_factura);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Este método permite convertir una o varias facturas simplificadas en una factura completa.
	 * La factura completa deberá llevar su propio número. No se trata de una factura rectificativa aunque se trate como si fuera una de sustitución.
	 *
	 * @param array $info_factura Parámetros de la factura
	 *
	 * @return string | array Array con información (huella, QR y url) en caso de éxito o mensaje de error
	 */
	public function tbai_completar(array $info_factura): string | array {
		$response = $this->callService('tbai-completar', 'POST', $info_factura);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Este método permite anular un TicketBAI enviado por error PERO no se podrá enviar otra factura con el mismo número.
	 * Consulta las preguntas frecuentes de Batuz aquí: https://www.batuz.eus/es/preguntas-frecuentes?p_p_id=net_bizkaia_iybzwpfc_IYBZWPFCPortlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_resource_id=%2Fdescargar%2FpregFrec&p_p_cacheability=cacheLevelPage
	 * @param array $info_factura Parámetros de la factura (Serie y número)
	 *
	 * @return string | array Array vacío en caso de éxito o mensaje de error
	 */
	public function anula_tbai(array $info_factura): string | array {
		$response = $this->callService('tbai', 'DELETE', $info_factura);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Este método permite consultar la huella TBAI, el código QR y la URL de validación de una factura TicketBAI previamente generada.
	 *
	 * @param array $info_factura Parámetros de la factura (Serie y número)
	 *
	 * @return string | array Array con información (huella, QR y url) en caso de éxito o mensaje de error
	 */
	public function check_tbai(array $info_factura): string | array {
		$response = $this->callService('tbai', 'GET', $info_factura);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Este método descargarse los ficheros XML generados en la solicitud y la respuesta a la diputación correspondiente. Los ficheros
	 * XML se devuelven encapsulados mediante Base64.
	 *
	 * @param array $info_factura Parámetros de la factura (Serie y número)
	 *
	 * @return string | array Array con información (xml_request y xml_response) en caso de éxito o mensaje de error
	 */
	public function xml_tbai(array $info_factura): string | array {
		$response = $this->callService('tbai-xml', 'GET', $info_factura);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * ?
	 */
	public function get_licencias($datos) {
		$response = $this->callService('licencias', 'GET', $datos);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * ?
	 */
	public function add_licencias($datos) {
		$response = $this->callService('licencias', 'POST', $datos);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * ?
	 */
	public function get_empresas($datos) {
		$response = $this->callService('empresas', 'GET', $datos);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * ?
	 */
	public function add_empresa($datos) {
		$response = $this->callService('empresas', 'POST', $datos);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * ?
	 */
	public function get_epigrafes() {
		$response = $this->callService('epigrafes', 'GET', $datos);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Función para realizar las llamadas al servicio
	 *
	 * @param string $service_name Nombre del método al que hacer la llamada
	 *
	 * @param string $method Tipo de método GET / POST / PUT / DEL / DELETE
	 *
	 * @param array $parameters Lista de parametros
	 *
	 * @return array Resultado de la llamada
	 */
	private function callService(string $service_name, string $method, array $parameters = []): array {
		$request = json_encode($parameters);
		$service_url = $this->url.$service_name.'/';

		$headers = [
			"Content-Type: application/json",
			"Accept: application/json;charset=UTF-8",
			"Token: ".$this->token,
			"Nif: ".$this->nif
		];

		$ch = curl_init();

		switch ($method) {
			case 'POST': {
				curl_setopt($ch, CURLOPT_POST, true);
				if ($request !== '') {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
				}
			}
			break;
			case 'PUT': {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				if ($request !== '') {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
				}
			}
			break;
			case 'GET': {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
				if ($request !== '') {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
				}
			}
			break;
			case 'DEL':
			case 'DELETE': {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if ($request !== ''){
					curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
				}
			}
			break;
		}

		curl_setopt($ch, CURLOPT_URL, $service_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verification );
		
		if ($this->debug) {
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			$stream_verbose_handle = fopen('php://temp', 'w+');
			curl_setopt($ch, CURLOPT_STDERR, $stream_verbose_handle);
		}

		$response = curl_exec($ch);

		if ($this->debug) {
			echo "SERVICE URL: ".$service_url."\n";
			echo "METHOD: ".$method."\n";
			echo "HEADERS: \n";
			var_export($headers);
			echo "\n";
			echo "REQUEST: \n";
			var_export($request);
			echo "\n";
			echo "RESPONSE: \n";
			var_export($response);
			echo "\n";

			if ($response === false) {
				printf(
					"cUrl error (#%d): %s<br>\n",
					curl_errno($ch),
					htmlspecialchars(curl_error($ch))
				);
			}

			rewind($stream_verbose_handle);
			$verbose_log = stream_get_contents($stream_verbose_handle);

			echo "cUrl verbose information:\n";
			echo "<pre>";
			echo htmlspecialchars($verbose_log);
			echo "</pre>\n";

			exit;
		}

		$network_err = curl_errno($ch);
		if ($network_err) {
			error_log('curl_err: ' . $network_err);
		}
		else {
			$httpStatus = intval( curl_getinfo($ch, CURLINFO_HTTP_CODE) );
			curl_close($ch);
			if ($httpStatus == 200) {
				$response_decoded = json_decode($response, true);
				$business_err = [];
				if ($response_decoded == null) {
					$business_err = $response;
				}
				else {
					$response = $response_decoded;
				}
			}
		}

		return $response;
	}
}