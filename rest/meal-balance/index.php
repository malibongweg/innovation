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

getRoute()->get('/', 'mealBalance');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */

function mealBalance(){
	$data = array();

	$mconnect = getDatabase()->one("select connect_string,user_name,password,host from cput_system_setup where system_name = 'meals'");
	//print "<pre>";print_r($mconnect);print "</pre>";
	$con = ibase_connect($mconnect['host'].$mconnect['connect_string'],$mconnect['username'],$mconnect['password']);
		if (!$con) {
			$data['Result'] = 'ERR';
			$data['Reason'] = 'DB CONNECT';
			//$db = array_to_object($data);
			echo json_encode($db);
			exit();
		}

	$sql = sprintf("select userno,balance,count(*) as cnt from balances where balances.userno=%d group by userno,balance",$_GET['stdno']);
	$result = ibase_query($con,$sql);
	if (!$result) {
		echo ibase_errmsg();
		exit();
	}

	$row = ibase_fetch_object($result);

	if ($row->CNT == 0) {
		//$data['Result'] = 'ERR';
		//$data['Reason'] = 'NO REC';
		//echo json_encode($db);
		echo "NULL";
	} else {

	//$data['Result'] = 'OK';
	//$data['Records'][] = $row;
	echo $row->BALANCE;

	//echo json_encode($data);
	}

}

?>

