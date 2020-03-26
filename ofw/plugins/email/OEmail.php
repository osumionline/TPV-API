<?php
class OEmail {
	private $debug        = false;
	private $l            = null;
	private $lang         = 'es';
	private $recipients   = [];
	private $subject      = '';
	private $message      = '';
	private $is_html      = true;
	private $from         = '';
	private $from_name    = null;
	private $result_ok    = [];
	private $result_error = [];
	private $errors       = [
		'es' => ['NO_RECIPIENTS' => '¡No hay destinatarios!', 'ERROR_SENDING' => 'Error al enviar email a: '],
		'es' => ['NO_RECIPIENTS' => 'There are no recipients!', 'ERROR_SENDING' => 'Error sending the email to: '],
	];
	
	function __construct() {
		global $core;
		$this->debug = ($core->config->getLog('level') == 'ALL');
		if ($this->debug) {
			$this->l = new OLog();
		}
		$this->lang = $core->config->getLang();
	}

	private function log($str) {
		if ($this->debug) {
			$this->l->debug($str);
		}
	}

	public function setRecipients($r) {
		$this->recipients = $r;
	}

	public function getRecipients() {
		return $this->recipients;
	}

	public function addRecipient($r) {
		array_push($this->recipients, $r);
	}

	public function setSubject($s) {
		$this->subject = $s;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function setMessage($m) {
		$this->message = $m;
	}

	public function getMessage() {
		return $this->message;
	}

	public function setIsHtml($ih) {
		$this->is_html = $ih;
	}

	public function getIsHtml() {
		return $this->is_html;
	}

	public function setFrom($f, $name=null) {
		$this->from = $f;
		if (!is_null($name)) {
			$this->from_name = $name;
		}
	}

	public function getFrom() {
		return $this->from;
	}

	public function setFromName($n) {
		$this->from_name = $n;
	}

	public function getFromName() {
		return $this->from_name;
	}

	public function setResultOk($ro) {
		$this->result_ok = $ro;
	}

	public function getResultOk() {
		return $this->result_ok;
	}

	public function addResultOk($ro) {
		array_push($this->result_ok, $ro);
	}

	public function setResultError($re) {
		$this->result_error = $re;
	}

	public function getResultError() {
		return $this->result_error;
	}

	public function addResultError($re) {
		array_push($this->result_error, $re);
	}

	private function getErrorMessage($key) {
		return $this->errors[$this->lang][$key];
	}

	public function send() {
		$ret = ['status'=>'ok','mens'=>''];

		// If there are no recipients, return error
		if (count($this->getRecipients())==0) {
			$ret['status'] = 'error';
			$ret['mens'] = $this->getErrorMessage('NO_RECIPIENTS');
		}
		else {
			$list = $this->getRecipients();
			$this->log('[OEmail] - Sending emails to '.count($list).' addresses');

			foreach ($list as $item) {
				$headers = '';
				// If is html add special headers
				if ($this->getIsHtml()) {
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=utf-8\r\n";
				}
				$headers .= "To: ".$item."\r\n";
				$headers .= "From: ".$this->getFrom().( is_null($this->getFromName()) ? "" : "<".$this->getFromName().">" )."\r\n";

				// Send the email
				if (mail($item, $this->getSubject(), $this->getMessage(), $headers)) {
					$this->addResultOk($item);
					$this->log('Email sent to: '.$item);
				}
				else {
					$this->addResultError($item);
					$ret['status'] = 'error';
					$ret['mens'] .= $this->getErrorMessage('ERROR_SENDING').$item.' - ';
					$this->log('Error sending email to: '.$item);
				}
			}
		}

		return $ret;
	}
}