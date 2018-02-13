<?php
$dbo =& JFactory::getDBO();

if (isset($_GET['action']))
{
	if ($_GET['action'] == "show_log") {
		$data = array();
		$dbo->setQuery("select id,start_date,publish_date,end_date,event_description from #__events where year(publish_date) = year(now()) and month(publish_date) = ".$_GET['mth']." order by start_date desc");
		$dbo->query();
			if ($dbo->getNumRows() == 0) {
				$data[] = "-1";
				echo json_encode($data);
				exit();
			}
		$result = $dbo->loadObjectList();
		foreach ($result as $row) {
			$data[] = $row->id.";".$row->start_date.";".$row->publish_date.";".$row->end_date.";".$row->event_description;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == "expire_event") {
		$dbo->setQuery("update #__events set end_date = date(now()) where id = ".$_GET['id']);
		$result = $dbo->query();
		if (!$result) {
			echo "-1";
		} else {
			echo "1";
		}
	}
	else if ($_GET['action'] == "save_event") {
		$sql = sprintf("insert into #__events (start_date,publish_date,end_date,event_description,details) values ('%s','%s','%s','%s','%s')",$_POST['sdate'],$_POST['pdate'],$_POST['edate'],$_POST['desc'],$_POST['details']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) {
			echo "-1";
		} else {
			echo "1";
		}
	}
	else if ($_GET['action'] == "edit_entry") {
		$data = array();
		$sql = sprintf("select start_date,publish_date,end_date,event_description,details from #__events where id = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$data[] = $row->start_date.";".$row->publish_date.";".$row->end_date.";".$row->event_description.";".$row->details;
		echo json_encode($data);
	}
	else if ($_GET['action'] == "save_edit") {
		$sql = sprintf("update #__events set start_date='%s',publish_date='%s',end_date='%s',event_description='%s',details='%s' where id = %d",$_POST['sdate'],$_POST['pdate'],$_POST['edate'],$_POST['desc'],$_POST['details'],$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if ($_GET['action'] == "delete_entry") {
		$sql = sprintf("delete from #__events where id = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if ($_GET['action'] == "display_details") {
		$dbo->setQuery("select details from #__events where id=".$_GET['id']);
		$row = $dbo->loadObject();
		echo $row->details;
	}
	
}
exit;
?>