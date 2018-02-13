#!/usr/bin/php
<?php

// This file is part of a tutorial on the blog of Philipp C. Heckel, July 2013
// http://blog.philippheckel.com/2013/07/07/send-whatsapp-messages-via-php-script-using-whatsapi/

require_once('whatsapp_whatsapi_config.php');

$w = new WhatsProt($userPhone, $userIdentity, $userName, $debug);
$w->Connect();
$w->LoginWithPassword($password);

while (true) {
	$w->PollMessages();
	$msgs = $w->GetMessages();

	foreach ($msgs as $m) {
		$time = date("m/d/Y H:i", $m->_attributeHash['t']);
		$from = str_replace("@s.whatsapp.net", "", $m->_attributeHash['from']);
		$name = "(unknown)";
		$body = "";

		foreach ($m->_children as $child) {
			if ($child->_tag == "body") {
				$body = $child->_data;
			}
			else if ($child->_tag == "notify") {
				if (isset($child->_attributeHash) && isset($child->_attributeHash['name'])) {	
					$name = $child->_attributeHash['name'];
				}
			}
		}

		if (!empty($body)) {
			echo "[$time] From: $from, Name: $name, Message: $body\n";
		}

		if (strtolower($body) == "exit") {
			exit;
		}

		// print_r($m);
	}
}

?>
