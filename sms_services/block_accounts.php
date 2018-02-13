<?php

function blockCopy($my,$uid,$type,$cell,$login,$ldap) {
		$sql = "select database_name,user_name,password,host from cput_system_setup where system_name = 'copies'";
		$result = mysql_query($sql,$my);
			if (!$result) { return -1; }
		$row = mysql_fetch_object($result);
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$dbname = $row->database_name;
		$con = pg_connect("host=".$host." dbname=".$dbname." user=".$uname." password=".$pass);
		if (!$con) { return -2; }
		$sql = sprintf("select blockcard('%s') as result",$uid);
		$res = pg_query($con,$sql);
		if (!$res) { return -3; }
		$sql = sprintf("select getstatus('%s') as result",$uid);
		$res = pg_query($con,$sql);
		if (!$res) { return -1; }
		$row = pg_fetch_object($res);
		return $row->result;
}

function blockMeals($my,$uid,$type,$cell,$login,$ldap) {
		$sql = "select connect_string,user_name,password,host from cput_system_setup where system_name = 'meals'";
		$result = mysql_query($sql,$my);
			if (!$result) { return -1; }
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$cs = $row->connect_string;
			$con = ibase_connect($host.$cs,$uname,$pass);
			if (!$con) { return -1; }
		if (preg_match('/^[0-9]+$/',$login) == 0) { return 0; }
		$sql = sprintf("update users set status='N' where userno=%d",$userid);
		$res = ibase_query($con,$sql);
		if (!$res) { return -1; } else { return 1; }
}

$scr = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
$cfg = trim(file_get_contents($scr));
$cfg = explode("\r\n",$cfg);
$my = mysql_connect($cfg[0],$cfg[1],$cfg[2]);
if (!$my) die('Error');
mysql_select_db($cfg[3]);
$ldap_server = $cfg[4];
$ldap_user = $cfg[5];
$ldap_pass = $cfg[6];

$sql = "select cmd_location from cput_sms_settings limit 1";
$result = mysql_query($sql,$my);
if (!$result) {
	$sms_cmd = "/var/www/html/scripts/sendsms";
} else {
	$row = mysql_fetch_object($result);
	$sms_cmd = $row->cmd_location;
}

if (isset($_GET['request'])) {
	if (trim($_GET['request']) == "facilities") {
		$msg = "CPUT SMS Service Request: ";
		$sender = $_GET['sender'];

		$sql = sprintf("select userid,cell,user_type,login from cput_users_cellular where trim(cell) = '%s'",$sender);
		$result = mysql_query($sql,$my);
		if (!$result) {
			exec($sms_cmd." ".$sender." We were unable to verify your cellular number. Please try again at a later stage.");
			die();
		}
		if (mysql_num_rows($result) == 0) {
			exec($sms_cmd." ".$sender." Our records shows that you have not registered your cellular number on the OPA website. Log into OPA and view your user profile.");
			die();
		}
		///////////////Account OK---go eahead//////////////////
		$result = blockCopy($my,$row->userid,$row->user_type,$row->cell,$row->login,$ldap_server);
		//echo $result;
		switch (intval($result)) {
			case -1: $msg .= "System was unable to block photocopy account...";
			case 0: $msg .= "System was unable to locate a photcopy account for ".$row->userid."...";
			case 1: $msg .= "Photocopy account successfully BLOCKED. ";
		}

		$result = blockMeals($my,$row->userid,$row->user_type,$row->cell,$row->login,$ldap_server);
		switch (intval($result)) {
			case -1: $msg .= "System was unable to block meals account...";
			case 0: $msg .= "System was unable to locate a meals account for ".$row->userid."...";
			case 1: $msg .= "Meals account successfully BLOCKED. ";
		}
		$msg .= " Please use OPA website to unblock these accounts.";
		$sql = sprintf("insert into cput_sms_services_log (sender,request) values ('%s','Block Facilities')",$sender);
				$res = mysql_query($sql,$my);

				exec($sms_cmd." ".$sender." ".$msg);
		
		
	}
}

?>