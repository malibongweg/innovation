<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::init('config');
Epi::init('route','database');
Epi::setSetting('exceptions', true);
Epi::setPath('config', dirname(__FILE__));
getConfig()->load('configuration.ini');
$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','portal',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->get('/', 'sendsms');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function sendsms()
{
		$data = array();
		$cell = $_GET['cell'];
		$msg = $_GET['msg'];

		$remote = $_SERVER['REMOTE_ADDR'];
		$sql = sprintf("select count(*) as cnt from portal.cput_sms_servers a where a.IPADDRESS = '%s' and a.STATUSYN = 'Y'",$remote);
		$rec = getDatabase()->one($sql);
		if (intval($rec['cnt']) == 1) {
				mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
							$charid = strtoupper(md5(uniqid(rand(), true)));
							$hyphen = chr(45);// "-"
							$uuid = 
								substr($charid, 0, 8).$hyphen
								.substr($charid, 8, 4).$hyphen
								.substr($charid,12, 4).$hyphen
								.substr($charid,16, 4).$hyphen
								.substr($charid,20,12);
				$sql = sprintf("insert into portal.cput_sms_log_new (session,username,uid,to_cell,to_message,when_to_send) values ('%s','Heat Helpdesk',261,'%s','%s',now())",
				$uuid,urlencode($cell),urldecode(addslashes($msg)));
				//echo $sql;
				$affectedRows = getDatabase()->execute($sql);
				$data['status'] = 'OK';
		} else {
			$data['status'] = 'ERR';
		}
		echo json_encode($data);
}
?>

