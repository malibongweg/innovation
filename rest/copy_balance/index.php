<?php
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::init('route','database','config');
Epi::setSetting('exceptions', true);
Epi::setPath('config', dirname(__FILE__));
getConfig()->load('configuration.ini');
$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','portal',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->post('/', 'copyBalance');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function copyBalance() {
		$data = array();
		$username = $_POST['uname'];
        $rec = getDatabase()->one("select database_name,user_name,password,host from portal.cput_system_setup where system_name = 'copies'");
		$host = $rec['host'];
		$uname = $rec['user_name'];
		$pass = $rec['password'];
		$dbname = $rec['database_name'];
		$cstr = "host=".$host." dbname=".$dbname." user=".$uname." password=".$pass;
		$con = pg_connect("host=".$host." dbname=".$dbname." user=".$uname." password=".$pass);
		if (!$con) { $data['record'][0]['servercode'] = '-1'; echo json_encode($data); exit(); }

					$sql = sprintf("select getstatus('%s') as result",$username);
					$res = pg_query($con,$sql);
					$row = pg_fetch_object($res);
					if ($row->result == -1) { $data['record'][0]['servercode'] = '-1'; echo json_encode($data); exit(); }
				$sql = sprintf("select credits.amount as cr from credits,users where users.reference='%s' and credits.userid = users.userid and credits.creditno=1",$username);
				$result = pg_query($con,$sql);
				$row = pg_fetch_object($result);
				$credit = $row->cr;
				$sql = sprintf("select sum(debits.amount) as db from debits,users where users.reference='%s' and debits.userid = users.userid and debits.debitno=1",$username);
				$result = pg_query($con,$sql);
				$row = pg_fetch_object($result);
				$debits = $row->db;
	
				$bal = $credit - $debits;
				$data['record'][0]['servercode'] = '1';
				$data['record'][0]['response'] = number_format(($bal/100),2);
				echo json_encode($data);


}
?>

