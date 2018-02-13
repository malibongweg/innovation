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
EpiDatabase::employ('mysql','assets',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->get('/', 'getAsset');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function getAsset()
{
		$data = array();
		$sql = sprintf("select aaabarcode,aaaadesc,aaaser from assets.aaareg where aaabarcode = '%s'",$_GET['barcode']);
		$result = getDatabase()->one($sql);
		$data["aaabarcode"] = $_GET['barcode'];
		$data["aaaadesc"] = $result["aaaadesc"];
		$data["aaaser"] = $result["aaaser"];
		echo json_encode($data);
}

?>

