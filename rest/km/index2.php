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

getRoute()->get('/', 'kmBalance');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function kmBalance()
{
$username='safeq';
$password='password';
$URL="https://10.18.8.99:8443/payment-system/api/v2/customer/".$_GET['uid']."/";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$URL);
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$result=curl_exec ($ch);
curl_close ($ch);
echo $result;
}
?>

