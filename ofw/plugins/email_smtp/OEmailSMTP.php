<?php declare(strict_types=1);

namespace OsumiFramework\OFW\Plugins;

use OsumiFramework\OFW\Log\OLog;

/**
 * Utility class to send emails using SMTP
 */
class OEmailSMTP {
	private bool    $debug        = false;
	private ?Olog   $l            = null;
	private string  $lang         = 'es';
	private array   $smtp_data    = [];
	private array   $recipients   = [];
	private ?string $cc           = null;
	private ?string $bcc          = null;
	private string  $subject      = '';
	private string  $message      = '';
	private bool    $is_html      = true;
	private string  $from         = '';
	private ?string $from_name    = null;
	private array   $attachments  = [];
	private array   $result_ok    = [];
	private array   $result_error = [];
	private array   $errors       = [
		'es' => ['NO_RECIPIENTS' => '¡No hay destinatarios!', 'ERROR_SENDING' => 'Error al enviar email a: '],
		'es' => ['NO_RECIPIENTS' => 'There are no recipients!', 'ERROR_SENDING' => 'Error sending the email to: '],
	];

	/**
	 * Load debugger, SMTP configuration and application language on startup
	 */
	function __construct() {
		global $core;
		$this->debug = ($core->config->getLog('level') == 'ALL');
		if ($this->debug) {
			$this->l = new OLog();
		}
		$this->lang = $core->config->getLang();
		$this->smtp_data = $core->config->getPluginConfig('email_smtp');
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
	 * Set emails CC copy recipient
	 *
	 * @param string $cc Emails copy recipient
	 *
	 * @return void
	 */
	public function setCC(string $cc): void {
		$this->cc = $cc;
	}

	/**
	 * Get emails CC copy recipient
	 *
	 * @return string Emails CC copy recipient
	 */
	public function getCC(): ?string {
		return $this->cc;
	}

	/**
	 * Set emails BCC copy recipient
	 *
	 * @param string $bcc Emails copy recipient
	 *
	 * @return void
	 */
	public function setBCC(string $bcc): void {
		$this->bcc = $bcc;
	}

	/**
	 * Get emails BCC copy recipient
	 *
	 * @return string Emails BCC copy recipient
	 */
	public function getBCC(): ?string {
		return $this->bcc;
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
	public function getFromName(): ?string {
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
	 * Send email
	 *
	 * @return array Status information array (ok/error) and error message (if any)
	 */
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
				try {
					$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
					$mail->isSMTP();

					$mail->CharSet = 'UTF-8';
					$mail->Host = $this->smtp_data['host'];
					$mail->Port = $this->smtp_data['port'];
					$mail->SMTPSecure = $this->smtp_data['secure'];
					$mail->SMTPAuth = true;
					$mail->Username = $this->smtp_data['user'];
					$mail->Password = $this->smtp_data['pass'];
					if (is_null($this->getFromName())) {
						$mail->setFrom($this->getFrom());
					}
					else {
						$mail->setFrom($this->getFrom(), $this->getFromName());
					}
					if (!is_null($this->getCC())) {
						$mail->addCC($this->getCC());
					}
					if (!is_null($this->getBCC())) {
						$mail->addBCC($this->getBCC());
					}
					$mail->addAddress($item);
					$mail->Subject = $this->getSubject();
					$mail->msgHTML($this->getMessage());

					if (count($this->attachments)>0) {
						foreach ($this->attachments as $attachment) {
							$mail->addAttachment($attachment);
						}
					}

					if ($mail->send()) {
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
				catch (Exception $e) {
					$this->addResultError($item);
					$ret['status'] = 'error';
					$ret['mens'] .= $this->getErrorMessage('ERROR_SENDING').$item.' - Error: '.$e->errorMessage();
					$this->log('Error sending email to: "'.$item.'": '.$e->errorMessage());
				}

				$mail = null;
			}
		}

		return $ret;
	}
}