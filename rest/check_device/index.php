<?php
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::init('config');
Epi::init('route','database');
//Epi::setSetting('exceptions', true);
Epi::setPath('config', dirname(__FILE__));
getConfig()->load('configuration.ini');
$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','mobile',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]


getRoute()->post('/', 'checkDevice');
getRoute()->run();


function checkDevice()
{
	$data = array();
       	$device = getDatabase()->one("SELECT COUNT(*) AS cnt FROM registered_devices WHERE uuid=:id", array(':id' => $_POST['uuid']));
	$data['record'][0]['servercode'] = '10000';
	$data['record'][0]['response'] = $device['cnt'];
	echo json_encode($data);;
}

?>

