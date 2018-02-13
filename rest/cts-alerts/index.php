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
EpiDatabase::employ('mysql','portal',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->get('/', 'currentAlerts');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */

function currentAlerts(){
	$alerts = getDatabase()->all("select a.failure_date as Date, b.`description` as Reason, a.failure_message as Description, d.`description` as Severity from portal.cput_service_failure a
		left outer join portal.cput_service_failure_severity c on (a.severity_code = c.id)
		left outer join portal.cput_service_failure_codes b on (a.`failure_reason` = b.`id`)
		left outer join portal.cput_service_failure_severity d on (a.`severity_code` = d.`id`)
		where year(a.failure_date) = year(now()) and a.current_state = 0 order by failure_date desc");

	//$db = array_to_object($alerts);
	$data = array();
	foreach ($alerts as $row) {
		$data['Records'][] = $row;
		//print "<pre>";print_r($row);print "</pre>";
		//echo $row['failure_date'].";".$row['failure_message'];
	}
	echo json_encode($data);

}

?>

