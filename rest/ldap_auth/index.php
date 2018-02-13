<?php
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
EpiDatabase::employ('mysql','mobile',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->post('/','ldapValidate');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function ldapValidate() {

		$data = array();
		$ldap_server = getConfig()->get('ldap_server');
		$ldap_domain = getConfig()->get('domain');
		$lcon = ldap_connect($ldap_server);
		if (!$lcon) {
			$data['record'][0]['servercode'] = '30000';
			$data['record'][0]['response'] = '1';
			echo json_encode($data);;
			exit();
		}

		ldap_set_option($lcon, LDAP_OPT_TIMELIMIT, 5);

		$lbind = @ldap_bind($lcon);
		if (!$lbind) {
			$data['record'][0]['servercode'] = '30000';
			$data['record'][0]['response'] = '2';
			echo json_encode($data);;
			exit();
		}

		$sr = ldap_search($lcon,'o='.$ldap_domain,'cn='.$_POST['uname']);
		if (!$sr) {
			ldap_close($lcon);
			$data['record'][0]['servercode'] = '30000';
			$data['record'][0]['response'] = '3';
			echo json_encode($data);;
			exit();
		}

		$en=ldap_get_entries($lcon,$sr);
		$dn = $en[0]['dn'];
		if (strlen($dn) <= 0) {
			ldap_close($lcon);
			$data['record'][0]['servercode'] = '30000';
			$data['record'][0]['response'] = '4';
			echo json_encode($data);;
			exit();
		}

		$lbind = ldap_bind($lcon,$dn,$_POST['upass']);
		if (!$lbind) {
			ldap_close($lcon);
			$data['record'][0]['servercode'] = '30000';
			$data['record'][0]['response'] = '5';
			echo json_encode($data);;
			exit();
		} else {
			$session = md5($_POST['uname'].$_POST['upass'].date('GHs'));
			ldap_close($lcon);
			getDatabase()->execute('DELETE FROM user_sessions WHERE userid=:id', array(':id' => $_POST['uname']));
			getDatabase()->execute('INSERT INTO user_sessions (id,userid) VALUES (:id,:userid)', array(':id' => $session,'userid' => $_POST['uname']));
			$data['record'][0]['servercode'] = '90000';
			$data['record'][0]['response'] = $session;
			echo json_encode($data);
			exit();
		}
}

?>