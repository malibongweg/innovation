<?php
defined('_JEXEC') or die('Restricted access');
$dbo =& JFactory::getDBO();
$doc = & JFactory::getDocument();
$root = $_SERVER['DOCUMENT_ROOT'];
$incl = $root."/scripts/system/functions.php";
require_once($incl);

if (isset($_GET['action'])) {

	if ($_GET['action'] == 'get_entries') {
		$data = array();
		$dbo->setQuery("select cell,cell_name from #__sms_address_entries where bookid = ".$_GET['bookid']." order by cell_name");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[0] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach ($result as $item) {
			$data[$item->cell] = $item->cell_name.'['.$item->cell.']';
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'mail_error') {
		$dbo->setQuery("select notify_email,last_notify from #__sms_settings where id = 1");
		$dbo->query();
		if ($dbo->getNumRows() == 0) {
			exit();
		}
		$row = $dbo->loadObject();
		$td = strtotime(date('Y-m-d'));
		$sd = strtotime($row->last_notify);
		if ($td != $sd) {
			$to = array();
			$to[] = $row->notify_email;
			$to = serialize($to);
			$msg = "Date: ".Date('Y-m-d G:m')."<br />";
			$msg .= "SMS Gateway is currently unavailable.<br /><br />";
			$msg .= "Please check status and restart.";
			sendMail($to,'SMS Gateway Error',$msg);
			$dbo->setQuery("update #__sms_settings set last_notify = date(now()) where id = 1");
			$dbo->query();
			$dbo->setQuery("call proc_update_audit('SMS GATEWAY','NOT AVAILABLE',NULL,NULL,'System',NULL)");
			$dbo->query();
		}
	}

	else if ($_GET['action'] == 'new_group') {
		$sql = sprintf("insert into #__sms_address_books (userid,book_name) values (%d,'%s')",$_GET['uid'],urldecode($_GET['grp']));
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) echo "-1"; else echo "1";
	}

	else if($_GET['action'] == 'list_books') {
		$data=array();
		$dbo->setQuery("select id,book_name from #__sms_address_books where userid=".$_GET['uid']." order by id");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[0] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach ($result as $item) {
			$data[$item->id] = $item->book_name;
		}
		echo json_encode($data);
	}

	else if($_GET['action'] == 'del_grp') {
		$dbo->setQuery("delete from #__sms_address_books where id = ".$_GET['bookid']);
		$result = $dbo->query();
		if (!$result) echo "-1"; else echo "1";
	}

	else if($_GET['action'] == 'del_entry') {
		$dbo->setQuery("delete from #__sms_address_entries where cell = '".$_GET['cell']."'");
		$result = $dbo->query();
		if (!$result) echo "-1"; else echo "1";
	}

	else if($_GET['action'] == 'new_entry') {
		$sql = sprintf("insert into #__sms_address_entries (bookid,cell,cell_name) values (%d,'%s','%s')",$_GET['bookid'],urldecode($_GET['cell']),urldecode($_GET['nme']));
		$dbo->setQuery($sql);
		$result = $dbo->query();
		echo $sql;
		if (!$result) echo "-1"; else echo "1";
	}

	else if($_GET['action'] == 'send_single') {
		$dbo->setQuery("select system_status from #__sms_settings where id=1");
		$result = $dbo->loadResult();
			if ($result == 0) { echo "0"; exit; }
		$sql = sprintf("insert into cput_sms_log (username,to_cell,to_message) values ('%s','%s','%s')",$_POST['single_user'],$_POST['single_number'],addslashes($_POST['single_message']));
		//echo $sql;
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) echo "-1"; else echo "1";
	}

	else if($_GET['action'] == 'send_bulk') {
		$dbo->setQuery("select system_status from #__sms_settings where id=1");
		$result = $dbo->loadResult();
			if ($result == 0) { echo "0"; exit; }
		$sql = sprintf("insert into #__sms_log (username,to_cell,to_message) values ('%s','%s','%s')",$_POST['bulk_user'],$_GET['cell'],addslashes($_POST['bulk_message']));
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) echo "-1"; else echo "1";
	}

	else if($_GET['action'] == 'get_balance') {
		$dbo->setQuery("select current_balance from #__sms_accounts where username = '".$_GET['uid']."'");
		$result = $dbo->loadResult();
		if (!$result) echo "-1"; else echo $result;
	}

	else if($_GET['action'] == 'system_status') {
		$dbo->setQuery("select system_status from #__sms_settings where id=1");
		$result = $dbo->loadResult();
		echo $result;
	}

	else if($_GET['action'] == 'del_reports') {
		$data = array();
		$sql = sprintf("select concat(a.date_sent,' ',a.to_cell,' ',b.description,' [',substring(a.to_message,1,15),']') as del_entry from #__sms_log a, #__sms_delivery_codes b where a.delivery_status = b.id and month(a.date_sent) = %d and year(a.date_sent) = year(now()) and a.username = '%s' order by a.date_sent desc",$_GET['mth'],$_GET['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) { $data[] = "-1"; echo json_encode($data); exit(); }
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach ($result as $item) {
			$data[] = $item->del_entry;
		}
		echo json_encode($data);
	}

	else if($_GET['action'] == 'msg_types') {
		$data = array();
		$dbo->setQuery("select id,msg_description from #__sms_standard_msg where username='".$_GET['uid']."'");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[0] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[$row->id] = $row->msg_description;
		}
		echo json_encode($data);
	}
	else if($_GET['action'] == 'msg_types_sel') {
		$data = array();
		$dbo->setQuery("select id,msg_description from #__sms_standard_msg where username='".$_GET['uid']."' and id=".$_GET['id']);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[0] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[$row->id] = $row->msg_description;
		}
		echo json_encode($data);
	}
	else if($_GET['action'] == 'msg_std') {
		$data = array();
		$dbo->setQuery("select msg_id,message_text from #__sms_standard_messages where msg_id =".$_GET['id']);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[0] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[$row->msg_id] = $row->message_text;
		}
		echo json_encode($data);
	}
	else if($_GET['action'] == 'save_msg_type') {
		$data = array();
		$sql = sprintf("insert into #__sms_standard_msg (username,msg_description) values ('%s','%s')",$_GET['uid'],addslashes(urldecode($_GET['desc'])));
		$dbo->setQuery($sql);
		$dbo->query();
		$dbo->setQuery("select id,msg_description from #__sms_standard_msg where username='".$_GET['uid']."'");
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$data[$row->id] = $row->msg_description;
		}
		echo json_encode($data);
	}
	else if($_GET['action'] == 'save_std_msg') {
		$sql = sprintf("update #__sms_standard_messages set message_text = '%s' where msg_id = %d",addslashes(urldecode($_GET['msg'])),$_GET['id']);
		$dbo->setQuery($sql);
		echo $sql;
		$dbo->query();
	}
	else if($_GET['action'] == 'del_std_msg') {
		$dbo->setQuery("delete from #__sms_standard_msg where id = ".$_GET['id']);
		$dbo->query();
	}
}

///Finally exit
exit();