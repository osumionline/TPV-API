<?php declare(strict_types=1);
class OToken {
	private ?string $secret = null;
	private array $params = [];
	private ?string $token = null;

	/**
	 * Sets signature secret string on startup
	 *
	 * @param string $secret String used to sign the resulting token or check a tokens validity
	 */
	function __construct(string $secret) {
		$this->secret = $secret;
	}

	/**
	 * Set parameter array to be added to the token
	 *
	 * @param array $params Parameter array to be added to the token
	 *
	 * @return void
	 */
	public function setParams(array $params): void {
		$this->params = $params;
	}

	/**
	 * Adds a parameter to the tokens parameters
	 *
	 * @param string $key Key code of the parameter
	 *
	 * @param string|int|float|bool $value Value of the parameter to be added
	 *
	 * @return void
	 */
	public function addParam(string $key, $value): void {
		$this->params[$key] = $value;
	}

	/**
	 * Gets list of tokens parameters
	 *
	 * @return array Array of tokens parameters
	 */
	public function getParams(): array {
		return $this->params;
	}

	/**
	 * Gets a single parameter of the token
	 *
	 * @param string $key Key code of the parameter to be retrieved
	 *
	 * @return string|int|float|bool Value of the requested parameter or null if not found
	 */
	public function getParam(string $key) {
		return array_key_exists($key, $this->params) ? $this->params[$key] : null;
	}

	/**
	 * Generates a new JWT token based on given parameters and signed with the given secret key
	 *
	 * @return string Generated JWT token
	 */
	public function getToken(): string {
		if (!is_null($this->token)) {
			return $this->token;
		}
		$header = ['alg'=> 'HS256', 'typ'=>'JWT'];
		$header_64 = OTools::base64urlEncode(json_encode($header));
		$payload = $this->params;
		$payload_64 = OTools::base64urlEncode(json_encode($payload));

		$signature = hash_hmac('sha256', $header_64.'.'.$payload_64, $this->secret);

		$this->token = $header_64.'.'.$payload_64.'.'.$signature;

		return $this->token;
	}

	/**
	 * Checks if a given token is valid
	 *
	 * @param string $token Token to be checked
	 *
	 * @return bool Token is valid or not
	 */
	public function checkToken(string $token): bool {
		$pieces = explode('.', $token);
		$header_64  = $pieces[0];
		$payload_64 = $pieces[1];
		$signature  = $pieces[2];

		$signature_check = hash_hmac('sha256', $header_64.'.'.$payload_64, $this->secret);

		if ($signature === $signature_check) {
			$this->params = json_decode(OTools::base64urlDecode($payload_64), true);
			return true;
		}
		return false;
	}
}