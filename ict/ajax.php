<?php
defined('_JEXEC') or die('Restricted access');

if (isset($_GET['request']))
$dbo =& JFactory::getDBO();
{
	/////Get step order for status///////
	if ($_GET['request'] == 1) {
		$response = array();
		$sql = sprintf("select ict_acquisitions_status.step_order
						from ict_acquisitions_status
						left join ict_acquisitions on (ict_acquisitions_status.step_order = ict_acquisitions.status)
						where ict_acquisitions.id = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$row = mysqli_fetch_object($result);
		$response['code'] = $row->step_order;
		echo json_encode($response);
	}
	////////Cancel Acquisition///////////////
	else if ($_GET['request'] == 2) {
		$sql = sprintf("update ict_acquisitions set status = 7 where ict_acquisitions.id = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
	}
	else if ($_GET['request'] == 3) {
	$sql = sprintf("select connect_string,user_name,password from system_setup where system_name = 'helpdesk'");
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$row = mysqli_fetch_object($result);
		$ora = oci_connect($row->user_name,$row->password,$row->connect_string);
		//$ora = oci_connect('helpdesk','deskhelp',$row->connect_string);
		if (!$ora) {
			echo "\n<script type='text/javascript'>";
			echo "\nsetMessages('Error Connecting To Oracle Helpdesk System.');";
			echo "\n</script>";
			exit;
		}
			$sql = sprintf("select a.caseid,a.details,a.logged_by,b.empname from requests a,requester b
			 where a.callerid = b.empno and a.caseid = %d",$_GET['id']);
			$result = oci_parse($ora,$sql);
			oci_execute($result);
			$row = oci_fetch_object($result);
			$json = array();
			$json['callerid'] = $row->EMPNAME;
			$json['details'] = $row->DETAILS;
			$json['loggedby'] = $row->LOGGED_BY;
			echo json_encode($json);
	}
	else if ($_GET['request'] == 4) {
		$json = array();
		$sql = sprintf("select item_code,qty,description from ict_acquisition_items where id = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		while ($row = mysqli_fetch_object($result)) {
			$json[$row->item_code] = $row->qty." X ".$row->description;
		}
		echo json_encode($json);
	}

	else if ($_GET['request'] == 5) {
		$sql = sprintf("insert into ict_acquisition_items (id,item_code,description,qty) values (%d,'%s','%s',%d)",
			$_GET['id'],$_GET['sc'],$_GET['sd'],$_GET['qty']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
	}
	else if ($_GET['request'] == 6) {
		$sql = sprintf("delete from ict_acquisition_items where id = %d and item_code = '%s'",$_GET['id'],$_GET['sc']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
	}
	else if ($_GET['request'] == 7) {
		$sql = sprintf("delete from ict_acquisitions where id = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		echo $sql;
	}
}
exit;
?>