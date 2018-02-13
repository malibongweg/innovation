<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
require_once("scripts/system/functions.php");
$sql = "select user_name,password,database_name,host from cput_system_setup where system_name='internet'";
$dbo->setQuery($sql);
$row = $dbo->loadObject();

$option = array(); //prevent problems
$option['driver']   = 'mysqli';            // Database driver name
$option['host']     = $row->host;
$option['user']     = $row->user_name;
$option['password'] = $row->password;
$option['database'] = $row->database_name;
$option['prefix']   = '';             // Database prefix (may be empty)
 
$db = & JDatabase::getInstance( $option );


if (isset($_GET)) {

	if ($_GET['action'] == "display_log") {
		$data = array();
		$sql = sprintf("select a.usage_date,a.domain_name,sum(a.bytes_used) as bytes_used,round(sum(a.amount_used),2) as amount_used from user_billing_%s_%s a where a.user_login = '%s' and year(a.usage_date) = year(now()) and month(a.usage_date) = %d and day(a.usage_date) = %d group by a.user_login,a.domain_name 
		union
		select b.usage_date,b.domain_name,sum(b.bytes_used) as bytes_used,round(sum(b.amount_used),2) as amount_used from user_billing_unknown b where b.user_login = '%s' and year(b.usage_date) = year(now()) and month(b.usage_date) = %d and day(b.usage_date) = %d group by b.user_login,b.domain_name",$_GET['mth'],date("Y"),$_GET['user'],intval($_GET['mth']),$_GET['day'],$_GET['user'],intval($_GET['mth']),$_GET['day']);
		//echo $sql;exit();
		$db->setQuery($sql);
		$db->query();
			if ($db->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $db->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->usage_date.";".$row->domain_name.";".$row->bytes_used.";".$row->amount_used;
		}
		echo json_encode($data);
	}
}
exit();

?>