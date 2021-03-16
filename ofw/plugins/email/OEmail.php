<?php declare(strict_types=1);

namespace OsumiFramework\OFW\Plugins;

use OsumiFramework\OFW\Log\OLog;

/**
 * Utility class to send emails using PHP's mail function
 */
class OEmail {
	private bool    $debug        = false;
	private ?OLog   $l            = null;
	private string  $lang         = 'es';
	private array   $recipients   = [];
	private string  $subject      = '';
	private string  $message      = '';
	private bool    $is_html      = true;
	private string  $from         = '';
	private ?string $from_name    = null;
	private array   $attachments  = [];
	private string  $eol          = "\r\n";
	private array   $result_ok    = [];
	private array   $result_error = [];
	private array   $errors       = [
		'es' => ['NO_RECIPIENTS' => '¡No hay destinatarios!', 'ERROR_SENDING' => 'Error al enviar email a: '],
		'es' => ['NO_RECIPIENTS' => 'There are no recipients!', 'ERROR_SENDING' => 'Error sending the email to: '],
	];

	/**
	 * Load debugger and application language on startup
	 */
	function __construct() {
		global $core;
		$this->debug = ($core->config->getLog('level') == 'ALL');
		if ($this->debug) {
			$this->l = new OLog('OEmail');
		}
		$this->lang = $core->config->getLang();
	}

	/**
	 * Logs internal information of the class
	 *
	 * @param string $str String to be logged
	 *
	 * @return void
	 */
	private function log(string $str): void {
		if ($this->debug) {
			$this->l->debug($str);
		}
	}

	/**
	 * Set email recipient list
	 *
	 * @param array $r Array of recipient emails
	 *
	 * @return void
	 */
	public function setRecipients(array $r): void {
		$this->recipients = $r;
	}

	/**
	 * Get email recipient list
	 *
	 * @return array Array of recipient emails
	 */
	public function getRecipients(): array {
		return $this->recipients;
	}

	/**
	 * Add recipient to the list
	 *
	 * @param string $r New recipients email
	 *
	 * @return void
	 */
	public function addRecipient(string $r): void {
		array_push($this->recipients, $r);
	}

	/**
	 * Set emails subject
	 *
	 * @param string $s Emails subject
	 *
	 * @return void
	 */
	public function setSubject(string $s): void {
		$this->subject = $s;
	}

	/**
	 * Get emails subject
	 *
	 * @return string Emails subject
	 */
	public function getSubject(): string {
		return $this->subject;
	}

	/**
	 * Set emails message content (plain text or HTML)
	 *
	 * @param string $m Emails message content
	 *
	 * @return void
	 */
	public function setMessage(string $m): void {
		$this->message = $m;
	}

	/**
	 * Get emails message content
	 *
	 * @return string Emails message content
	 */
	public function getMessage(): string {
		return $this->message;
	}

	/**
	 * Set if the email content is plain text or HTML
	 *
	 * @param bool $ih Emails message content is HTML
	 *
	 * @return void
	 */
	public function setIsHtml(bool $ih): void {
		$this->is_html = $ih;
	}

	/**
	 * Get if the email content is HTML
	 *
	 * @return bool Emails content is HTML
	 */
	public function getIsHtml(): bool {
		return $this->is_html;
	}

	/**
	 * Set emails sender address and name
	 *
	 * @param string $f Senders email address
	 *
	 * @param string $name Senders full name
	 *
	 * @return void
	 */
	public function setFrom(string $f, ?string $name=null): void {
		$this->from = $f;
		if (!is_null($name)) {
			$this->from_name = $name;
		}
	}

	/**
	 * Get emails sender address
	 *
	 * @return string Senders email address
	 */
	public function getFrom(): string {
		return $this->from;
	}

	/**
	 * Set senders full name
	 *
	 * @param string $n Senders full name
	 *
	 * @return void
	 */
	public function setFromName(string $n): void {
		$this->from_name = $n;
	}

	/**
	 * Get senders full name
	 *
	 * @return string Senders full name
	 */
	public function getFromName(): string {
		return $this->from_name;
	}

	/**
	 * Set list of filenames/paths to be attached to the email
	 *
	 * @param array $a List of filenames/paths to be attached
	 *
	 * @return void
	 */
	public function setAttachments(array $a): void {
		$this->attachments = $a;
	}

	/**
	 * Get list of filenames/paths to be attached to the email
	 *
	 * @return array List of filenames/paths to be attached
	 */
	public function getAttachments(): array {
		return $this->attachments;
	}

	/**
	 * Add filename/path to be attached to the email
	 *
	 * @param string $a Name/path of the file to be attached
	 */
	public function addAttachment(string $a): void {
		array_push($this->attachments, $a);
	}

	/**
	 * Set list of recipients that got the email correctly
	 *
	 * @param array $ro List of recipients
	 *
	 * @return void
	 */
	public function setResultOk(array $ro): void {
		$this->result_ok = $ro;
	}

	/**
	 * Get list of recipients that got the email correctly
	 *
	 * @return array List of recipients
	 */
	public function getResultOk(): array {
		return $this->result_ok;
	}

	/**
	 * Add recipient to the list that got the email correctly
	 *
	 * @param string $ro Email address of the recipient
	 *
	 * @return void
	 */
	public function addResultOk(string $ro): void {
		array_push($this->result_ok, $ro);
	}

	/**
	 * Set list of recipients that didn't get the email because of an error
	 *
	 * @param array List of recipients
	 *
	 * @return void
	 */
	public function setResultError(array $re): void {
		$this->result_error = $re;
	}

	/**
	 * Get list of recipients that didn't get the email because of an error
	 *
	 * @return array List of recipients
	 */
	public function getResultError(): array {
		return $this->result_error;
	}

	/**
	 * Add recipient to the list that didn't get the email because of an error
	 *
	 * @param string $ro Email address of the recipient
	 *
	 * @return void
	 */
	public function addResultError(string $re): void {
		array_push($this->result_error, $re);
	}

	/**
	 * Get localized error message
	 *
	 * @param string $key Key code of the requested message
	 *
	 * @return string Requested localized error message
	 */
	private function getErrorMessage(string $key): string {
		return $this->errors[$this->lang][$key];
	}

	/**
	 * Get email headers
	 *
	 * @param string $recipient Recipients email address
	 *
	 * @param string $separator String used to separate message content from the attachments
	 *
	 * @return string String of the required headers
	 */
	private function getHeaders(string $recipient, string $separator): string {
		$headers = '';
		if (count($this->attachments)==0){
			// If is html add special headers
			if ($this->is_html) {
				$headers .= "Content-type: text/html; charset=\"utf-8\"".$this->eol;
			}
			else{
				$headers .= "Content-Type: text/plain; charset=\"utf-8\"".$this->eol;
			}
		}
		else{
			$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$this->eol;
		}
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "To: ".$recipient."\r\n";
		$headers .= "From: ".$this->from.( is_null($this->from_name) ? "" : "<".$this->from_name.">" ).$this->eol;

		return $headers;
	}

	/**
	 * Get email body
	 *
	 * @param string $separator String used to separate message content from the attachments
	 *
	 * @return string String with the bodys contentx
	 */
	private function getBody(string $separator): string {
		$body = '';
		if (count($this->attachments)>0){
			$body .= "--" . $separator . $this->eol;
			if ($this->is_html) {
	    		$body .= "Content-Type: text/html; charset=\"utf-8\"" . $this->eol;
	    	}
	    	else{
		    	$body .= "Content-Type: text/plain; charset=\"utf-8\"" . $this->eol;
	    	}
	    	$body .= "Content-Transfer-Encoding: 8bit" . $this->eol;
	    }
	    $body .= $this->message . $this->eol;

	    return $body;
	}

	/**
	 * Get attachment ready to be inserted into the emails body
	 *
	 * @param string $filename Name/path of the file to be attached
	 *
	 * @param string $separator String to separate email message content from the attachments
	 *
	 * @return string Attachment as string ready to be inserted into the email
	 */
	public function getAttachment(string $filename, string $separator): string {
		$content = file_get_contents($filename);
		$content = chunk_split(base64_encode($content));

		$attachment = "--" . $separator . $this->eol;
		$attachment .= "Content-Type: application/octet-stream; name=\"".basename($filename)."\"".$this->eol;
		$attachment .= "Content-Transfer-Encoding: base64".$this->eol;
		$attachment .= "Content-Disposition: attachment".$this->eol;
		$attachment .= $content.$this->eol;
		$attachment .= "--".$separator."--";

		return $attachment;
	}

	/**
	 * Send email
	 *
	 * @return array Status information array (ok/error) and error message (if any)
	 */
	public function send(): array {
		$ret = ['status'=>'ok','mens'=>''];

		// If there are no recipients, return error
		if (count($this->recipients)==0) {
			$ret['status'] = 'error';
			$ret['mens'] = $this->getErrorMessage('NO_RECIPIENTS');
		}
		else {
			$this->log('Sending emails to '.count($this->recipients).' addresses');

			foreach ($this->recipients as $item) {
				// A random hash will be necessary to send mixed content
				$separator = md5(uniqid());

				// Headers
				$headers = $this->getHeaders($item, $separator);

				// Body
				$body = $this->getBody($separator);

				// Attachments
				foreach ($this->attachments as $attachment) {
					$body .= $this->getAttachment($attachment, $separator);
				}

				// Send the email
				if (mail($item, $this->subject, $body, $headers)) {
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