<?php
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::init('config','route');
Epi::setSetting('exceptions', true);
Epi::setPath('config', dirname(__FILE__));
getConfig()->load('configuration.ini');

getRoute()->post('/','printBalance');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function printBalance() {

		$data = array();
		$ldap_server = getConfig()->get('ldap_server');
		$ldap_domain = getConfig()->get('domain');
		$lcon = ldap_connect($ldap_server);
		if (!$lcon) {
			$data['record'][0]['response'] = '1';
			echo json_encode($data);
			exit();
		}

		ldap_set_option($lcon, LDAP_OPT_TIMELIMIT, 5);

		$lbind = @ldap_bind($lcon);
		if (!$lbind) {
			$data['record'][0]['response'] = '2';
			echo json_encode($data);
			exit();
		}

		$sr = ldap_search($lcon,'o='.$ldap_domain,'cn='.$_POST['uname']);
		if (!$sr) {
			ldap_close($lcon);
			$data['record'][0]['response'] = '3';
			echo json_encode($data);
			exit();
		}

		$en=ldap_get_entries($lcon,$sr);
		$dn = $en[0]['dn'];
		if (strlen($dn) <= 0) {
			ldap_close($lcon);
			$data['record'][0]['response'] = '4';
			echo json_encode($data);
			exit();
		}

		$entry = ldap_first_entry($lcon,$sr);
		if (!$entry) {
			ldap_close($lcon);
			$data['record'][0]['response'] = '5';
			echo json_encode($data);
			exit();
		}
		
	$value = ldap_get_values($lcon,$entry,"accountbalance");
		if (!$value) {
				$value_credit = 0;
			} else {
				$value_credit = $value[0];
			}
		$data['record'][0]['response'] = '6';
		$data['record'][0]['balance'] = number_format(($value_credit/100),2);
		echo json_encode($data);
	
}

?>