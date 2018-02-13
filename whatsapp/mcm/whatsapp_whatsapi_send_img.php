<?php

// This file is part of a tutorial on the blog of Philipp C. Heckel, July 2013
// http://blog.philippheckel.com/2013/07/07/send-whatsapp-messages-via-php-script-using-whatsapi/

require_once('/var/www/html/script/whatsapp/mcm/whatsapp_whatsapi_config.php');


$w = new WhatsProt($userPhone, $userIdentity, $userName, $debug);
$w->Connect();
$w->LoginWithPassword($password);
$id = $w->sendMessageImage($argv[1], $argv[2]);
echo "Message ID# ".$id."\n";
$w->pollMessage();
?>
