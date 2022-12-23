<?php

	//=======================================
	//   mail sending start
	//=======================================
		$to = 'vibha@domainname.com';
		$fromName = 'Administrator';
		$fromEmail = 'admin@domainname.com';
		$subject = 'Subjest is here';
		$message = 'Message is here..Message is here..Message is here......Message is here';
		$template = 'demo.tpl';
		$files = array('demo.txt','demo.xlsx');
		if(sendEmail($to,$fromName,$fromEmail,$subject,$message,$cc='',$bcc='',$files))
			echo 'Mail successfully sent.';
		else
			echo 'Mail sending failed.';
	//=======================================
	//   mail sending end
	//=======================================


function sendEmail($to,$fromName,$fromEmail,$subject,$message,$cc='',$bcc='',$files=array(),$debug=false)
{
	// starting of headers
	$headers = 'From: '.$fromName.' <'.$fromEmail.'>';
	if($cc != '')
		$headers .= "\r\nCc: ". $cc;
	if($bcc != '')
		$headers .= "\r\nBcc: ". $bcc;

	// boundary
	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

	// headers for attachment
	$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

	// multipart boundary
	$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
	if(count($files))
		$message .= "--{$mime_boundary}\n";

	// preparing attachments
	for($x=0;$x<count($files);$x++)
	{
		$file = fopen($files[$x],"rb");
		$data = fread($file,filesize($files[$x]));
		fclose($file);
		$data = chunk_split(base64_encode($data));
		$message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
		"Content-Disposition: attachment;\n" . " filename=\"$files[$x]\"\n" .
		"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
		if($x < (count($files)-1))
			$message .= "--{$mime_boundary}\n";
		else
			$message .= "--{$mime_boundary}--\n";

	}

	if($debug)
	{
		echo '<br />$to: '.$to;
		echo '<br />$fromName: '.$fromName;
		echo '<br />$fromEmail: '.$fromEmail;
		echo '<br />$subject: '.$subject;
		echo '<br />$message: '.$message;
		echo '<br />$cc: '.$cc;
		echo '<br />$bcc: '.$bcc;
		echo '<br />$file: '.print_r($file);
	}

	if(mail($to, $subject, $message, $headers))
	{
		return true;
	}
	else
	{
		return false;
	}
}

?>
