<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
//$dbo->setQuery("call proc_pop_application('My Tools')");
//$dbo->query();

if (isset($_GET['func'])) {

	if ($_GET['func'] == 'cstat') {
		$data = array();
		$pt = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
		$fp = file($pt);
		$ldap = $fp[4];
		$cnt = preg_match('/^[0-9]+$/',$_GET['uid']);
		if ($cnt >= 1) {
			$username = $_GET['uid']; 
		} else {
			$lcon = @ldap_connect($ldap);
			if (!$lcon) { echo "-1"; exit(); }
			$lbind = @ldap_bind($lcon);
			if (!$lbind) { echo "-1"; exit(); }
			$sr = @ldap_search($lcon,"o=cput","cn=".$_GET['uid']);
			$en=ldap_get_entries($lcon,$sr);
			if (!$en) { echo "-1"; exit(); }
			if (isset($en[0]['employeenumber'][0])) {
				$username = $en[0]['employeenumber'][0];
				if (strlen($username) == 0) { echo "-1"; exit(); }
			}
		}
		$dbo->setQuery("select database_name,user_name,password,host from #__system_setup where system_name = 'copies'");
		$row = $dbo->loadObject();
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$dbname = $row->database_name;
		$con = pg_connect("host=".$host." dbname=".$dbname." user=".$uname." password=".$pass);
		if (!$con) { echo "-1"; exit(); }
		$sql = sprintf("select getstatus('%s') as result",$username);
		$res = pg_query($con,$sql);
		if (!$res) { echo '-9' ; exit(); }
		$row = pg_fetch_object($res);
		echo $row->result;
	}

	else if ($_GET['func'] == 'bcopy') {
		$data = array();
		$pt = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
		$fp = file($pt);
		$ldap = $fp[4];
		$cnt = preg_match('/^[0-9]+$/',$_GET['uid']);
		if ($cnt >= 1) {
			$username = $_GET['uid']; 
		} else {
			$lcon = @ldap_connect($ldap);
			if (!$lcon) { echo "-1"; exit(); }
			$lbind = @ldap_bind($lcon);
			if (!$lbind) { echo "-1"; exit(); }
			$sr = @ldap_search($lcon,"o=cput","cn=".$_GET['uid']);
			$en=ldap_get_entries($lcon,$sr);
			if (!$en) { echo "-1"; exit(); }
			if (isset($en[0]['employeenumber'][0])) {
				$username = $en[0]['employeenumber'][0];
				if (strlen($username) == 0) { echo "-1"; exit(); }
			}
		}
		$dbo->setQuery("select database_name,user_name,password,host from #__system_setup where system_name = 'copies'");
		$row = $dbo->loadObject();
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$dbname = $row->database_name;
		$con = pg_connect("host=".$host." dbname=".$dbname." user=".$uname." password=".$pass);
		if (!$con) { echo '-1'; exit(); }
		$sql = sprintf("select blockcard('%s') as result",$username);
		$res = pg_query($con,$sql);
		if (!$res) { echo "-1" ;exit(); }
		$row = pg_fetch_object($res);
		echo $row->result;
	}

else if ($_GET['func'] == 'ubcopy') {
	$data = array();
		$pt = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
		$fp = file($pt);
		$ldap = $fp[4];
		$cnt = preg_match('/^[0-9]+$/',$_GET['uid']);
		if ($cnt >= 1) {
			$username = $_GET['uid']; 
		} else {
			$lcon = @ldap_connect($ldap);
			if (!$lcon) { echo "-1"; exit(); }
			$lbind = @ldap_bind($lcon);
			if (!$lbind) { echo "-1"; exit(); }
			$sr = @ldap_search($lcon,"o=cput","cn=".$_GET['uid']);
			$en=ldap_get_entries($lcon,$sr);
			if (!$en) { echo "-1"; exit(); }
			if (isset($en[0]['employeenumber'][0])) {
				$username = $en[0]['employeenumber'][0];
				if (strlen($username) == 0) { echo "-1"; exit(); }
			}
		}
		$dbo->setQuery("select database_name,user_name,password,host from #__system_setup where system_name = 'copies'");
		$row = $dbo->loadObject();
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$dbname = $row->database_name;
		$con = pg_connect("host=".$host." dbname=".$dbname." user=".$uname." password=".$pass);
		if (!$con) { echo '-1'; exit(); }
		$sql = sprintf("select unblockcard('%s') as result",$username);
		$res = pg_query($con,$sql);
		if (!$res) { echo "-1" ;exit(); }
		$row = pg_fetch_object($res);
		echo $row->result;
	}

	else if ($_GET['func'] == 'mstat') {
		$data = array();
		$dbo->setQuery("select connect_string,user_name,password,host from #__system_setup where system_name = 'meals'");
		$row = $dbo->loadObject();
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$cs = $row->connect_string;
			$con = ibase_connect($host.$cs,$uname,$pass);
			if (!$con) { echo "-1"; exit(); }
		if (preg_match('/^[0-9]+$/',$_GET['uid']) > 0) {
			$sql = sprintf("select count(*) as cnt from users where userno=%d",$_GET['uid']);
			$result = ibase_query($con,$sql);
			if (!$result) { echo "-1"; exit(); }
			$row = ibase_fetch_object($result);
				if ($row->CNT != 1) { echo "-2"; exit(); }
			$sql = sprintf("select status from users where userno=%d",$_GET['uid']);
			$result = ibase_query($con,$sql);
			if (!$result) { echo "-1"; exit(); }
			$row = ibase_fetch_object($result);
			switch ($row->STATUS) {
				case 'Y': echo "0"; break;
				case 'N': echo "1"; break;
			}
		} else { echo "-2"; exit(); }
	
	}

	else if ($_GET['func'] == 'bmeals') {
		$data = array();
		$dbo->setQuery("select connect_string,user_name,password,host from #__system_setup where system_name = 'meals'");
		$row = $dbo->loadObject();
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$cs = $row->connect_string;
			$con = ibase_connect($host.$cs,$uname,$pass);
			if (!$con) { echo "-1"; exit(); }
		$sql = sprintf("update users set status='N' where userno=%d",$_GET['uid']);
		$res = ibase_query($con,$sql);
		if (!$res) { echo "-1"; } else { echo "0"; }
		
	}
	else if ($_GET['func'] == 'ubmeals') {
		$data = array();
		$dbo->setQuery("select connect_string,user_name,password,host from #__system_setup where system_name = 'meals'");
		$row = $dbo->loadObject();
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$cs = $row->connect_string;
			$con = ibase_connect($host.$cs,$uname,$pass);
			if (!$con) { echo "-1"; exit(); }
		$sql = sprintf("update users set status='Y' where userno=%d",$_GET['uid']);
		$res = ibase_query($con,$sql);
		if (!$res) { echo "-1"; } else { echo "0"; }
		
	}

}
exit();

?>
