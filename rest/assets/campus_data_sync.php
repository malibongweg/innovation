<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::init('config');
Epi::init('route','database');
Epi::init('route');
Epi::setSetting('exceptions', true);
Epi::setPath('config', dirname(__FILE__));
getConfig()->load('configuration.ini');
$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','structure',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->get('/', 'syncCampus');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function syncCampus()
{
		$data = array();
		$sql = sprintf("select campus_code,campus_name from structure.campus");
		$result = getDatabase()->execute($sql);
        	$records = getDatabase()->all($sql);
		$data = $records;
		echo json_encode($data);
}

?>

