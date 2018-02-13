<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

if (isset($_GET)) {
	if ($_GET['action'] == 'verify_staffno'){
		$sql = sprintf("select a.staff_no from staff.staff a where a.staff_no = %d and a.staff_idno = '%s'",$_GET['stf'],$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if ($dbo->getNumRows() == 0) { echo "0"; } else { echo "1"; }
	}
	else if ($_GET['action'] == 'verify_save'){
		$sql = sprintf("insert into cput_users_cellular (userid,user_type,login) values (%d,1,'%s')",$_GET['stf'],$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
		echo "1";
	}
	else if ($_GET['action'] == 'fs'){
	$sql = sprintf("select id from cput_users_cellular where lower(login) = lower('%s')",$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			echo "0";
		} else {
			echo "1";
		}

	}

exit();
}