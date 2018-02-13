<?php
function getCopyBal($my,$uid,$login,$utype,$ldap) {
		$sql = "select database_name,user_name,password,host from cput_system_setup where system_name = 'copies'";
		$res = mysql_query($sql,$my);
			if (!$res) { return -1; }
		$row = mysql_fetch_object($res);
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$dbname = $row->database_name;
		$con = pg_connect("host=".$host." dbname=".$dbname." user=".$uname." password=".$pass);
		if (!$con) { return -1; }
					$sql = sprintf("select getstatus('%s') as result",$uid);
					$res = pg_query($con,$sql);
					$row = pg_fetch_object($res);
					if ($row->result == -1) { return -2; }

				$sql = sprintf("select credits.amount as cr from credits,users where users.reference='%s' and credits.userid = users.userid and credits.creditno=1",$uid);
				$result = pg_query($con,$sql);
				$row = pg_fetch_object($result);
				$credit = $row->cr;
				$sql = sprintf("select sum(debits.amount) as db from debits,users where users.reference='%s' and debits.userid = users.userid and debits.debitno=1",$uid);
				$result = pg_query($con,$sql);
				$row = pg_fetch_object($result);
				$debits = $row->db;
	
				$bal = $credit - $debits;
				$balance = number_format(($bal/100),2);
				return $balance;
	}	

function getPrintBal($my,$uid,$login,$utype,$ldap) {
		$lcon = @ldap_connect($ldap);
		if (!$lcon) { return -1; }
		$lbind = @ldap_bind($lcon);
		$sr = @ldap_search($lcon,"o=cput","cn=".$login);
			if (!$sr) { return -2; }
		$en=ldap_get_entries($lcon,$sr);
		$entry = ldap_first_entry($lcon,$sr);
			if (!$entry) { return -3; }
		$value = ldap_get_values($lcon,$entry,"accountbalance");
			if (!$value) {
				$value_credit = 0;
			} else {
				$value_credit = $value[0];
			}
		return number_format(($value_credit/100),2);
}

function getMealsBal($my,$uid,$login,$utype) {
		$sql = "select connect_string,user_name,password,host from #__system_setup where system_name = 'meals'";
		$res = mysql_query($sql,$my);
			if (!$res) { return -1; }
		$row = mysql_fetch_object($res);
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$cs = $row->connect_string;
			$con = ibase_connect($host.$cs,$uname,$pass);
			if (!$con) { return -2; }
				$sql = sprintf("select balance from balances where balances.userno=%d",$login);
				$res2 = ibase_query($con,$sql);
					if (!$res2) { return -3; }
				$row = ibase_fetch_object($result);
				if (!is_object($row) || is_null($row))
					$bal = number_format(0,2);
				else
					$bal = number_format($row->BALANCE,2);
				return $bal;
}

function getSMSbal($my,$uid,$login,$utype) {
	    $sql = "select current_balance from cput_sms_accounts where username='".trim($login)."'";
		$res = mysql_query($sql,$my);
			if (!$res) { return -1; }
		if (intval(mysql_num_rows($res)) == 0) { $bal = 0; }
		else {
			$row = mysql_fetch_object($res);
			$bal = $row->current_balance;
		}
		return number_format($bal,2);
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
			//////Get copy balance///////////
			$msg = "CPUT SMS Service Request.";
			$row = mysql_fetch_object($result);
			$result = getCopyBal($my,$row->userid,$row->login,$row->user_type,$ldap_server);
			if (intval($result) == -1) { $msg .= "PHOTOCOPY: Unavailable.[code:-1]  "; }
			else if (intval($result) == -2) { $msg .= "PHOTOCOPY: Unavailable.[code:-2]  "; }
			else { $msg .= "PhotoCopy: R".$result."  "; }

	
				$result = getPrintBal($my,$row->userid,$row->login,$row->user_type,$ldap_server);
				if (intval($result) == -1) { $msg .= "PRINTING: Unavailable.[code:-1]  "; }
				else if (intval($result) == -2) { $msg .= "PRINTING: Unavailable.[code:-2]  "; }
				else if (intval($result) == -3) { $msg .= "PRINTING: Unavailable.[code:-3]  "; }
				else { $msg .= "Printing: R".$result."  "; }

				if ($row->user_type == 2) {
					$result = getMealsBal($my,$row->userid,$row->login,$row->user_type);
					if (intval($result) == -1) { $msg .= "MEALS: Unavailable.[code:-1]  "; }
					else if (intval($result) == -2) { $msg .= "MEALS: Unavailable.[code:-2]  "; }
					else if (intval($result) == -3) { $msg .= "MEALS: Unavailable.[code:-3]  "; }
					else { $msg .= "Meals: R".$result."  "; }
				}

				if ($row->user_type == 1) {
					$result = getSMSbal($my,$row->userid,$row->login,$row->user_type);
					if (intval($result) == -1) { $msg .= "SMS: Unavailable.[code:-1]  "; }
					else { $msg .= "SMS: R".$result; }
				}

				$sql = sprintf("insert into cput_sms_services_log (sender,request) values ('%s','Balance Facilities')",$sender);
				$res = mysql_query($sql,$my);

				exec($sms_cmd." ".$sender." ".$msg);
		}
	}


?>
