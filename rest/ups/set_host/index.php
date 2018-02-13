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


getRoute()->post('/', 'sethostname');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function sethostname() {
		
		if (isset($_POST['hostname'])) $hostname = $_POST['hostname'];
		$mac = $_POST['mac'];
		$code = $_POST['code'];

		if (intval($code) == 1){
			$sql = sprintf("update ups.devices set host_name = '%s' where mac = '%s'",$hostname,$mac);
			$record = getDatabase()->execute($sql);
			$msg = 'EATON Raspberry device hostname set successfully.';
		} else {
			$msg = 'EATON Raspberry...Error setting hostname!';
		}
			$sql = sprintf("select cell from ups.supervisors");
			$record = getDatabase()->all($sql);
			
			foreach($record as $row){
				$sql = sprintf("insert into portal.cput_sms_log (username,to_cell,to_message) values ('facilities','%s','%s')",$row['cell'],$msg);
				$sms = getDatabase()->execute($sql);
				sleep(3);
			}

}
?>

