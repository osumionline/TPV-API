<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Utils\AppData;

#[OModuleAction(
	url: '/stock',
	services: ['sync']
)]
class syncStockAction extends OAction {
	/**
	 * Función para sincronizar el stock del TPV con la web
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}

		$data = $this->sync_service->getSyncStock();

		$header = array('alg'=> 'HS256', 'typ'=>'JWT');
		$header_64 = base64_encode(json_encode($header));
		$payload = array('data'=>implode('#', $data));
		$payload_64 = base64_encode(json_encode($payload));

		$signature = hash_hmac('sha256', $header_64.'.'.$payload_64, $app_data->getSecretApi());

		$token = $header_64.'.'.$payload_64.'.'.$signature;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $app_data->getUrlApi()."syncStock");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('token' => $token)));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);

		curl_close ($ch);

		echo $server_output;
		exit;
	}
}
