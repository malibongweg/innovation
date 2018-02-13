<?php
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::init('route','database','config');
Epi::setSetting('exceptions', true);
Epi::setPath('config', dirname(__FILE__));
getConfig()->load('configuration.ini');
$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','squid',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->post('/','proxyBalance');
getRoute()->run();


function proxyBalance() {

			$data = array();
		
			$record = getDatabase()->one("SELECT ifnull(func_get_user_balance(:login),-5000) as pbal", array(':login' => $_POST['uname']));
			if (intval($record['pbal']) == -5000) {
				$data['record'][0]['servercode'] = '-1';
			} else {
				$data['record'][0]['servercode'] = '1';
				$data['record'][0]['response'] = $record['pbal'];
			}
			echo json_encode($data);
}
?>