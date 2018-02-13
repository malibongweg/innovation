<?php

function auth($lgn,$pwd,$svr) {

					$lcon = @ldap_connect($svr);
					if (!$lcon) { return -1; }
					$lbind = @ldap_bind($lcon);
					$sr = @ldap_search($lcon,"o=cput","cn=".$lgn);
					if (!$sr) { return -2; }
					$en=@ldap_get_entries($lcon,$sr);		
				$dn = $en[0]['dn'];
				if (strlen($pwd) == 0)
                        {
                             $pwd = crypt("CPUT");
                        }
                        $auth = @ldap_bind($lcon,$dn,$pwd);
                        if (!$auth)
                        {
							return -3;
                        } else { return 1; }
}

/////////////////////////////////////////////////////
$user_type = 1;
$scr = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
$cfg = trim(file_get_contents($scr));
$cfg = explode("\r\n",$cfg);
$my = mysql_connect($cfg[0],$cfg[1],$cfg[2]);
if (!$my) die();
mysql_select_db($cfg[3]);
$ldap_server = $cfg[4];
$ldap_user = $cfg[5];
$ldap_pass = $cfg[6];

////Get sms command locations
$sql = "select cmd_location from cput_sms_settings limit 1";
$result = mysql_query($sql,$my);
if (!$result) {
	$sms_cmd = "/var/www/html/scripts/sendsms";
} else {
	$srow = mysql_fetch_object($result);
	$sms_cmd = $srow->cmd_location;
}

$error = 0;
if (isset($_GET['login'])) {
		$sender = $_GET['sender'];
		$login = $_GET['login'];
		$pass = $_GET['pass'];
				if (preg_match('/^[0-9]+$/',$login) >= 1) { $user_type = 2; }
		$ret = auth($login,$pass,$ldap_server);
		//echo $ret;
		$msg = "";
		switch ($ret) {
			case -1: $msg = "CPUT Cellular De-Registration: Unable to connect to authorative server [-1]. Please try again later or report code in square brackets to CTS Helpdesk."; break;
			case -2: $msg = "CPUT Cellular De-Registration: Unable to find user object [-2]. Please try again later."; break;
			case -3: $msg = "CPUT Cellular De-Registration: Could not authenticate your login with your password [-3]. Please try again later."; break;
			case -4: $msg = "CPUT Cellular De-Registration: Unable to find user attribute [-4]. Please try again later."; break;
		}
		if (intval($ret) > 0) {
			$msg = "CPUT Cellular De-Registration: Your cellular number was successfully de-registered.";
			$sql = sprintf("CALL proc_deregister_sms('%s','%s')",trim($sender),$login);
			$result = mysql_query($sql,$my);
		}
		exec($sms_cmd." ".$sender." ".$msg);
}



?>
