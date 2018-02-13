<?php
$dbo = &JFactory::getDBO();
$user = & JFactory::getUser();
ini_set('error_reporting',0);

if (isset($_GET['action'])) {


	if ($_GET['action'] == 'get_receipt') {
		$data = array();
		$dbo->setquery("select account_no,trans_date,processed_date,rec_type,amount,status_flag,receipt_no,old_value,new_value
		from #__pcounter where receipt_no = '".$_GET['receipt']."'");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$data[] = $row->account_no.";".$row->trans_date.";".$row->processed_date.";".$row->rec_type.";".$row->amount.";".$row->status_flag.";".$row->receipt_no.";".$user->username;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'fix_receipt') {
		$data = array();
		$dbo->setquery("update #__pcounter set status_flag = 0, retry_seq = 0, account_no = '".$_GET['usr']."' where receipt_no = '".$_GET['receipt']."'");
		$result = $dbo->query();
		if (!$result) { echo "-1"; } else { echo "1"; }
	}

	else if ($_GET['action'] == 'show_activity') {
		$data = array();
		$dbo->setquery("select id,account_no,trans_date,processed_date,rec_type,amount,status_flag,receipt_no,old_value,new_value
		from #__pcounter where account_no ='".$_GET['id']."' and month(trans_date) = ".$_GET['mth']." and year(trans_date) = year(now()) order by trans_date desc");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$data[] = $row->account_no.";".$row->trans_date.";".$row->processed_date.";".$row->rec_type.";".$row->amount.";".$row->status_flag.";".$row->receipt_no.";".$row->id;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'rst') {
		$dbo->setQuery("update #__pcounter set seq_reset = 1 where id = ".$_GET['id']);
		$result = $dbo->query();
		if (!$result) { echo "-1"; } else { echo "1"; }
	}
	

}
exit();

?>