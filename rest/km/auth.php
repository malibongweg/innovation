<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$username='safeq';
$password='password';
$URL='https://10.18.8.99:8443/payment-system/api/v2/customer/davidsel/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$URL);
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$result=curl_exec($ch);
print "<pre>";print_r($result); print"</pre>";
curl_close ($ch);

?>
