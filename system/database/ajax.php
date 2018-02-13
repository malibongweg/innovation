<?php
$dbo =& JFactory::getDBO();
//require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");

if (isset($_GET)) {

	if ($_GET['action'] == 'displaySystems') {
		$dbo->setQuery('select id,system_name,host,connect_string,user_name,`password`,database_name,system_type,system_mode,log_only,
						disaster_host,disaster_connect_string,disaster_user_name,disaster_password,disaster_database_name from cput_system_setup');
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		//$return['TotalRecordCount'] = $nr;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateSystems') {
		$sql = sprintf("update cput_system_setup set system_name='%s',host='%s',connect_string='%s',user_name='%s',`password`='%s',database_name='%s',system_type=%d,system_mode=%d,
						log_only=%d,disaster_host='%s',disaster_connect_string='%s',disaster_user_name='%s',disaster_password = '%s',disaster_database_name='%s'
						where id=%d",$_POST['system_name'],$_POST['host'],$_POST['connect_string'],$_POST['user_name'],$_POST['password'],
						$_POST['database_name'],$_POST['system_type'],$_POST['system_mode'],$_POST['log_only'],
						$_POST['disaster_host'],$_POST['disaster_connect_string'],$_POST['disaster_user_name'],$_POST['disaster_password'],$_POST['disaster_database_name'],
						$_POST['id']);
						//$fp = fopen('c:\Temp\sql.txt');fwrite($fp,$sql);fclose($fp);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listSystemTypes') {
		$return = array();
		$dbo->setQuery('select id,system_name from cput_system_types');
		$result = $dbo->loadObjectList();
		$return['Result'] = 'OK';
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->system_name;
			$return['Options'][$i]['Value'] = $row->id;
			++$i;
		}
		echo json_encode($return);
	}
}

exit();



?>
