<?php
include_once ('/var/www/html/scripts/rest/src/Epi.php');
Epi::setPath('base', '/var/www/html/scripts/rest/src');
Epi::init('route','database','config');
Epi::setSetting('exceptions', true);
Epi::setPath('config', dirname(__FILE__));
getConfig()->load('configuration.ini');


$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','portal',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]


getRoute()->post('/', 'register');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function register() {
		
		$ip = $_POST['ip'];
		$secret = $_POST['secret_key'];
		$mac = $_POST['mac'];

		$sql = sprintf("select secret_key from ups.sys_config where id = 1");
		$record = getDatabase()->one($sql);
		$key = $record['secret_key'];

		if ($secret == $key){

			$sql = sprintf("delete from ups.devices where mac = '%s'",$mac);
			$record = getDatabase()->execute($sql);

			$sql = sprintf("insert into ups.devices (ip,mac) values ('%s','%s')",$ip,$mac);
			$record = getDatabase()->execute($sql);

			$sql = sprintf("select cell from ups.supervisors");
			$record = getDatabase()->all($sql);
			$msg = 'EATON Raspberry device online at IP: '.$ip.'. Please set hostname of device at http://'.$ip.' for identification. [MAC: '.$mac.']';
			foreach($record as $row){
				$sql = sprintf("insert into portal.cput_sms_log (username,to_cell,to_message) values ('facilities','%s','%s')",$row['cell'],$msg);
				//echo $sql.'<br />';
				$sms = getDatabase()->execute($sql);
				sleep(3);
			}
			echo 'OK';
		} else {
			echo 'Secret Key ERR.';
		}
}
?>

