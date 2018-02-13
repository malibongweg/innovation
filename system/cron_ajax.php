<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);

if (isset($_GET['action'])) {


	if ($_GET['action'] == 'show_log') {
		$data = array();
		$dbo->setQuery("select entry_date,system_name,msg,object_id from #__cron_log where year(entry_date) = year(now()) and month(entry_date) = ".$_GET['mth']." order by entry_date desc");
		$dbo->query();
		if ($dbo->getNumRows() ==  0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->entry_date.";".$row->system_name.";".$row->msg.";".$row->object_id;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'show_log_filter') {
		$data = array();
		$dbo->setQuery("select entry_date,system_name,msg,object_id from #__cron_log where object_id = '".$_GET['filter']."' and year(entry_date) = year(now()) and month(entry_date) = ".$_GET['mth']." order by entry_date desc");
		$dbo->query();
		if ($dbo->getNumRows() ==  0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->entry_date.";".$row->system_name.";".$row->msg.";".$row->object_id;
		}
		echo json_encode($data);
	}
}
exit();