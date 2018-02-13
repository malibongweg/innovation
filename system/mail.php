<?php
function sendMail($to,$subject,$body,$attach='') {
	$recipients = unserialize($to);
jimport('joomla.mail.mail');
require_once("/var/www/html/configuration.php");
$jconfig = new JConfig();
		$mail =& JMail::getInstance();
		$sender = array();
		$rep = array();
		$rep[] =  $jconfig->mailfrom;
		$rep[] = $jconfig->fromname;
		$sender[] = $jconfig->mailfrom;
		$sender[] = $jconfig->fromname;
		$mail->setsender($sender);
		foreach($recipients as $key=>$value) {
			$mail->addRecipient($value);
		}
		$mail->addReplyTo($rep);
		$mail->setSubject($subject);
		$mail->isHTML(true);
		$mail->Encoding = 'base64';
		$mail->setbody($body);
		if (strlen($attach) > 0)  $mail->addAttachment($attach);
		if ($jconfig->mailer == "sendmail") 
			$mail->useSendMail($jconfig->sendmail);
		else $mail->useSMTP(true,$jconfig->smtphost, $jconfig->smtpuser,$jconfig->smtppass,$jconfig->smtpport);
		return($mail->send());
}

?>
