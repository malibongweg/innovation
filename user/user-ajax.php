<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',1);
$option = array(); //prevent problems

$option['driver']   = 'mysqli';            // Database driver name
$option['host']     = '10.47.2.231';    // Database host name
$option['user']     = 'root';       // User for database authentication
$option['password'] = 'hp9000s';   // Password for database authentication
$option['database'] = 'squid';      // Database name
$option['prefix']   = '';             // Database prefix (may be empty)

$db = & JDatabase::getInstance( $option );

if (isset($_GET['function'])) {

	if ($_GET['function'] == 'ibal') {
		$dbo->setQuery("select database_name,user_name,password,host from #__system_setup where system_name = 'sushi'");
		$row = $dbo->loadObjectList();
		$host = $row[0]->host;
		$uname = $row[0]->user_name;
		$pass = $row[0]->password;
		$dbname = $row[0]->database_name;
		$data = array();

		$con = pg_connect("host=".$host." dbname=".$dbname." user=".$uname." password=".$pass);
			if (!$con) {
				echo "99999"; exit();
			} else {
				echo '20.00';
			}
		//echo json_encode($data);
	}

	else if ($_GET['function'] == 'proxy') {
		$sql = sprintf("SELECT ifnull(func_get_user_balance('%s'),-5000) as pbal",$_GET['uid']);
		$db->setQuery($sql);
		$db->query();
		$row = $db->loadObject();
		if (($row->pbal) <= -5000) {
			echo "-1";
		} else {
			echo number_format($row->pbal,2);
		}

	}

	else if($_GET['function'] == 'smsbal') {
		$dbo->setQuery("select current_balance from #__sms_accounts where username='".$_GET['username']."'");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
		$result = $dbo->loadResult();
		echo number_format($result,2);
	}

	else if($_GET['function'] == 'mbal') {
		$data = array();
		$dbo->setQuery("select connect_string,user_name,password,host from #__system_setup where system_name = 'meals'");
		$row = $dbo->loadObjectList();
		$host = $row[0]->host;
		$uname = $row[0]->user_name;
		$pass = $row[0]->password;
		$cs = $row[0]->connect_string;
			$con = ibase_connect($host.$cs,$uname,$pass);
			if (!$con) { echo "-1"; exit(); }
			$username = $_GET['uid'];
				$sql = sprintf("select balance from balances where balances.userno=%d",$username);
				$result = ibase_query($con,$sql);
				$row = ibase_fetch_object($result);
				if (!is_object($row) || is_null($row))
					$bal = number_format(0,2);
				else
					$bal = number_format($row->BALANCE,2);
				echo $bal;
	}

else if($_GET['function'] == 'pbal') {
		$pt = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
		$fp = file($pt);
		$ldap = $fp[4];
		$lcon = @ldap_connect($ldap);
		if (!$lcon) { echo "-1"; exit(); }
		$lbind = @ldap_bind($lcon);
		$sr = @ldap_search($lcon,"o=cput","cn=".$_GET['uid']);
		$en=ldap_get_entries($lcon,$sr);
		$dn = $en[0]['dn'];
		$entry = ldap_first_entry($lcon,$sr);
			if (!$entry) { echo "-2"; exit(); }
		$value = ldap_get_values($lcon,$entry,"accountbalance");
			if (!$value) {
				$value_credit = 0;
			} else {
				$value_credit = $value[0];
			}
		echo number_format(($value_credit/100),2);

}
else if($_GET['function'] == 'cbal') {
		$pt = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
		$fp = file($pt);
		$ldap = $fp[4];
		$cnt = preg_match('/^[0-9]+$/',$_GET['uid']);
		if ($cnt >= 1) {
			$username = $_GET['uid'];
		} else {
		$sql = sprintf("select userid from cput_users_cellular where login='%s'",$_GET['uid']);
			$dbo->setQuery($sql);
			$dbo->query();
			if ($dbo->getNumRows() == 0) { echo "-1"; }
			else {
				$row = $dbo->loadObject();
				$username = $row->userid;
			}

		$username = strtoupper($username);
		}
		$dbo->setQuery("select database_name,user_name,password,host from #__system_setup where system_name = 'copies'");
		$row = $dbo->loadObjectList();
		$host = $row[0]->host;
		$uname = $row[0]->user_name;
		$pass = $row[0]->password;
		$dbname = $row[0]->database_name;
		$cstr = "host=".$host." dbname=".$dbname." user=".$uname." password=".$pass;
		$con = pg_connect("host=".$host." dbname=".$dbname." user=".$uname." password=".$pass);
		if (!$con) { echo $cstr; exit(); }
					$sql = sprintf("select getstatus('%s') as result",$username);
					$res = pg_query($con,$sql);
					$row = pg_fetch_object($res);
					//echo $row->result;exit();
					if ($row->result == -1) { echo "-1"; exit(); }
				$sql = sprintf("select credits.amount as cr from credits,users where users.reference='%s' and credits.userid = users.userid and credits.creditno=1",$username);
				$result = pg_query($con,$sql);
				$row = pg_fetch_object($result);
				$credit = $row->cr;
				$sql = sprintf("select sum(debits.amount) as db from debits,users where users.reference='%s' and debits.userid = users.userid and debits.debitno=1",$username);
				$result = pg_query($con,$sql);
				$row = pg_fetch_object($result);
				$debits = $row->db;

				$bal = $credit - $debits;
				$balance = number_format(($bal/100),2);
				echo $balance;
	}
	else if($_GET['function'] == 'verify_staffno') {
		$sql = sprintf("select a.staff_no from staff.staff a where a.staff_no = %d and a.staff_idno = '%s'",$_GET['stf'],$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if ($dbo->getNumRows() == 0) { echo "0"; } else { echo "1"; }
	}
	else if($_GET['function'] == 'verify_save') {
		$sql = sprintf("insert into cput_users_cellular (userid,user_type,login) values (%d,1,'%s')",$_GET['stf'],$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
		echo "1";
	}
	else if($_GET['function'] == 'fs') {
		$sql = sprintf("select count(*) as cnt from cput_users_cellular where lower(login) = lower('%s')",$_GET['lg']);
		$dbo->setQuery($sql);
		$result = $dbo->loadResult();
		if (intval($result) == 0) {
			echo "0";
		} else {
			echo "1";
		}
	}



}
exit();

?>
