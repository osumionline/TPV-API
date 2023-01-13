<?php declare(strict_types=1);

namespace OsumiFramework\OFW\Plugins;

/**
 * Clase para realizar llamadas al servicio TicketBaiWS de Berein ( https://ticketbaiws.eus/ )
 */
class OTicketBai {
	private string $url              = 'https://api.ticketbaiws.eus/';
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
	 * Método para activar / desactivar la verificación SSL
	 *
	 * @param bool $mode Modo de verificación SSL (activado / desactivado)
	 *
	 * @return void
	 */
	public function setSslVerification(bool $mode): void {
		$this->ssl_verification = $mode;
	}

	/**
	 * Método para activar / desactivar el modo debug
	 *
	 * @param bool $mode Modo debug (activado / desactivado)
	 *
	 * @return void
	 */
	public function setDebug(bool $mode): void {
		$this->debug = $mode;
	}

	/**
	 * Este método permite ver el estado del sistema y sirve para comprobar la conectividad.
	 * En caso de que el certificado electrónico haya expirado, la licencia de ticketbai WS haya expirado o haya algún otro problema notificará un estado de ERROR.
	 *
	 * https://ticketbaiws.eus/es/documentacion-api/status-get/
	 *
	 * @return bool Estado del sistema
	 */
	public function checkStatus(): bool {
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
	 * https://ticketbaiws.eus/es/documentacion-api/ticketbai-post/
	 *
	 * @param array $info_factura Parámetros de la factura
	 *
	 * @return string | array Array con información (huella, QR y url) en caso de éxito o mensaje de error
	 */
	public function nuevoTbai(array $info_factura): string | array {
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
	 * https://ticketbaiws.eus/es/documentacion-api/tbai-completar-post/
	 *
	 * @param array $info_factura Parámetros de la factura
	 *
	 * @return string | array Array con información (huella, QR y url) en caso de éxito o mensaje de error
	 */
	public function tbaiCompletar(array $info_factura): string | array {
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
	 *
	 * https://ticketbaiws.eus/es/documentacion-api/ticketbai-del/
	 *
	 * @param array $info_factura Parámetros de la factura (Serie y número)
	 *
	 * @return string | array Array vacío en caso de éxito o mensaje de error
	 */
	public function anulaTbai(array $info_factura): string | array {
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
	 * https://ticketbaiws.eus/es/documentacion-api/ticketbai-get/
	 *
	 * @param array $info_factura Parámetros de la factura (Serie y número)
	 *
	 * @return string | array Array con información (huella, QR y url) en caso de éxito o mensaje de error
	 */
	public function checkTbai(array $info_factura): string | array {
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
	 * https://ticketbaiws.eus/es/documentacion-api/ticketbai-xml-get/
	 *
	 * @param array $info_factura Parámetros de la factura (Serie y número)
	 *
	 * @return string | array Array con información (xml_request y xml_response) en caso de éxito o mensaje de error
	 */
	public function xmlTbai(array $info_factura): string | array {
		$response = $this->callService('tbai-xml', 'GET', $info_factura);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Este método devuelve el listado de licencias contratadas
	 *
	 * https://ticketbaiws.eus/documentacion-api/licencias-get/
	 *
	 * @param array Array con el número de licencia
	 *
	 * @return string | array Array con el listado de licencias o mensaje de error
	 */
	public function getLicencias(array $datos): string | array {
		$response = $this->callService('licencias', 'GET', $datos);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Este método permite crear una nueva licencia
	 *
	 * https://ticketbaiws.eus/documentacion-api/licencias-post/
	 *
	 * @param array $datos Datos para crear una nueva licencia
	 *
	 * @return string | array Array con el listado de ids de licencias o mensaje de error
	 */
	public function addLicencias(array $datos): string | array {
		$response = $this->callService('licencias', 'POST', $datos);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Este método devuelve el listado de las empresas dadas de alta
	 *
	 * https://ticketbaiws.eus/documentacion-api/empresas-get/
	 *
	 * @param array $datos Datos con los que obtener el listado de empresas (id licencia, nif)
	 *
	 * @return string | array Array con el listado de empresas o mensaje de error
	 */
	public function getEmpresas(array $datos): string | array {
		$response = $this->callService('empresas', 'GET', $datos);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Este método permite crear una nueva ficha de empresa para el envío de ticketbai
	 *
	 * https://ticketbaiws.eus/documentacion-api/empresas-post/
	 *
	 * @param array $datos Datos necesarios para crear una nueva empresa
	 *
	 * @return string | array Array con los datos de la nueva empresa o mensaje de error
	 */
	public function addEmpresa(array $datos): string | array {
		$response = $this->callService('empresas', 'POST', $datos);

		if (!isset($response['result']) || $response['result'] == 'ERROR') {
			return $response['msg'];
		}
		else {
			return $response['return']);
		}
	}

	/**
	 * Este método devuelve el listado de los epígrafes de IAE para dar de alta cuentas de Ticketbai WS para
	 * el envío de facturas de autónomos, comunidades de bienes o sociedades civiles
	 *
	 * https://ticketbaiws.eus/documentacion-api/epigrafes-get/
	 *
	 * @return string | array Array con los epígrafes o mensaje de error
	 */
	public function getEpigrafes() {
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