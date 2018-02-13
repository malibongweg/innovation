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


getRoute()->post('/', 'alert');
getRoute()->run();

function alert() {
		$ip = $_POST['ip'];
		$secret = $_POST['secret_key'];
		$host = $_POST['host_name'];
		$ups_status = $_POST['ups_status'];
		$ups_charge = $_POST['ups_charge'];
		$ups_load = $_POST['ups_load'];
		$msg_type = $_POST['msg_type'];

		$sql = sprintf("select secret_key from ups.sys_config where id = 1");
		$record = getDatabase()->one($sql);
		$key = $record['secret_key'];

		if ($secret == $key){
			if (intval($msg_type) == 1) {
				$sql = sprintf("select cell from ups.supervisors");
				$record = getDatabase()->all($sql);
				if (intval($msg_type) == 1){
					$msg = 'EATON UPS ['.$host.']: Power Status Warning: '.$ups_status.' Batt: '.$ups_charge.' Load: '.$ups_load;
				} else {
					$msg = 'EATON UPS ['.$host.']: Battery Warning: '.$ups_status.' Batt: '.$ups_charge.' Load: '.$ups_load;
				}
				foreach($record as $row){
					$sql = sprintf("insert into portal.cput_sms_log (username,to_cell,to_message) values ('facilities','%s','%s')",$row['cell'],$msg);
					$sms = getDatabase()->execute($sql);
					sleep(3);
				}
				$sql = sprintf("insert into ups.ups_log (ups_name,ups_status,ups_charge,ups_load) values ('%s','%s','%s','%s')",$host,$ups_status,$ups_charge,$ups_load);
				$recid = getDatabase()->execute($sql);
				echo 'OK';
			} else if (intval($msg_type) == 2){
				$sql = sprintf("insert into ups.ups_heartbeat (ups_name,ups_charge,ups_load) values ('%s','%s','%s')",$host,$ups_charge,$ups_load);
				$recid = getDatabase()->execute($sql);	
				echo 'OK';
			}
		} else {
			echo 'Secret Key ERR.';
		}

}
?>

