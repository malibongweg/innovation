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
EpiDatabase::employ('mysql','assets',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->get('/', 'deviceRegistration');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function deviceRegistration()
{
		$data = array();
		$sql = sprintf("update assets.devices set aux = now() where uuid = '%s'",$_GET['deviceToken']);
		$affectedRows = getDatabase()->execute($sql);
		if ($affectedRows == 0){
			$sql = sprintf("insert into assets.devices (uuid) values ('%s')",$_GET['deviceToken']);
			$result = getDatabase()->execute($sql);
		}
		$sql = sprintf("select assets.check_device('%s') as stat",$_GET['deviceToken']);
        	$rec = getDatabase()->one($sql);
		$data['status'] = $rec['stat'];
		echo json_encode($data);
}
?>

