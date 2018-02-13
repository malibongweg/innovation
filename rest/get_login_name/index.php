<?php
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::setPath('config', dirname(__FILE__));
Epi::init('config');
Epi::init('route','database');
getConfig()->load('configuration.ini');
$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','mobile',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->post('/','getLoginName');
getRoute()->run();


function getLoginName() {

		$data = array();
        $device = getDatabase()->one("SELECT count(*) as cnt, login, confirmed FROM registered_devices WHERE uuid=:id", array(':id' => $_POST['uuid']));
		$data['record'][0]['login'] = $device['login'];
		$data['record'][0]['response'] = $device['cnt'];
		$data['record'][0]['confirmed'] = $device['confirmed'];
		echo json_encode($data);
	
	}
?>