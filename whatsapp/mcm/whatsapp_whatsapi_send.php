<?php


require_once('/var/www/html/scripts/whatsapp/mcm/whatsapp_whatsapi_config.php');

$destinationPhone = '27822020567';

$w = new WhatsProt($userPhone, $userIdentity, $userName, $debug);
$w->Connect();
$w->LoginWithPassword($password);
$id = $w->sendMessage($argv[1], $argv[2]);
echo "Message ID# ".$id."\n";
$w->pollMessage();
?>
