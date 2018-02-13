<?php
    function CreateGUID()
    /*  CreateGUID is used to return a unique ID to use as an index
        in the database.  It is a maximum length string of 35.  It uses
        the PHP command uniqid to create a 23 character string appended
        to the IP address of the client without periods.                */
        {
        //Append the IP address w/o periods to the front of the unique ID
        $varGUID = uniqid(date('ymdis'), TRUE);

        //Return the GUID as the function value
        return $varGUID;
        }


function sendSMS($cell,$msg) {
		$resp = array();
		$cmd = $_SERVER['DOCUMENT_ROOT']."/scripts/sendsms";
		$sms_cmd = $cmd." ".trim($cell)." ".trim($msg);
		exec($sms_cmd,$resp);
		$resp[0] = 'Ok';
		return $resp[0];
}

function sendMail($to,$subject,$body,$attach='') {
	$recipients = unserialize($to);
jimport('joomla.mail.mail');
require_once($_SERVER['DOCUMENT_ROOT']."configuration.php");
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

function joomlaPassword($password) {
//generate salt----------------------------------->
$salt = '';
   for ($i=0; $i<=32; $i++) {
      $d=rand(1,30)%2;
      $salt .= $d ? chr(rand(65,90)) : chr(rand(48,57));
   }
//end generate salt--------------------------------|

//hash password with salt-->
$hashed = md5($password . $salt);

//here is your new encrypted password, ready to store in the database table,  `jos_users`
$encrypted = $hashed . ':' . $salt;
return($encrypted);
}
?>