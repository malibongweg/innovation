<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);

if (isset($_GET['action'])) {


	if ($_GET['action'] == 'show_log') {
		$data = array();
		$dbo->setQuery("select a.id,b.description as failure_reason,c.description as severity,c.color,d.system_name,a.failure_date,a.current_state
		from cput_service_failure a 
		left outer join #__service_failure_codes b on (a.failure_reason = b.id)
		left outer join #__service_failure_severity c on (a.severity_code = c.id)
		left outer join change_control.cts_systems d on (a.system_id = d.system_no)
		where year(a.failure_date) = year(now()) and month(a.failure_date) = ".$_GET['mth']." order by failure_date desc");
		$dbo->query();
		if ($dbo->getNumRows() ==  0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->failure_date.";".$row->system_name.";".$row->failure_reason.";".$row->severity.";".$row->id.";".$row->color.";".$row->current_state;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'pop_old_record') {
		$dbo->setQuery("select a.id,b.description as failure_reason,c.description as severity,c.color,d.system_name,a.failure_date,failure_message
		from cput_service_failure a 
		left outer join #__service_failure_codes b on (a.failure_reason = b.id)
		left outer join #__service_failure_severity c on (a.severity_code = c.id)
		left outer join change_control.cts_systems d on (a.system_id = d.system_no)
		where a.id = ".$_GET['id']);
		$row = $dbo->loadObject();
		echo $row->failure_date.";".$row->system_name.";".$row->failure_reason.";".$row->severity.";".$row->failure_message;
	}
	else if ($_GET['action'] == 'pop_history') {
		$data = array();
		$sql = sprintf("select action_date,action_taken from cput_service_failure_resolve where id = %d order by action_date desc",$_GET['id']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() > 0) {
			$result = $dbo->loadObjectList();
			foreach ($result as  $row){
				$data[] = $row->action_date.";".$row->action_taken;
			}
		} else { $data[] = "-1"; }
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'pop_systems') {
		$data = array();
		$dbo->setQuery("select a.system_no,a.system_name from change_control.cts_systems a order by a.system_name");
		$dbo->query();
			if ($dbo->getNumRows() == 0) { $data[] = "-1"; exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->system_no.";".$row->system_name;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'pop_failure') {
		$data = array();
		$dbo->setQuery("select id,description from #__service_failure_codes order by id");
		$dbo->query();
			if ($dbo->getNumRows() == 0) { $data[] = "-1"; exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[$row->id] = $row->description;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'pop_severity') {
		$data = array();
		$dbo->setQuery("select id,description from #__service_failure_severity order by id");
		$dbo->query();
			if ($dbo->getNumRows() == 0) { $data[] = "-1"; exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[$row->id] = $row->description;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'save_record') {
		$sql = sprintf("insert into #__service_failure (system_id,failure_reason,severity_code,failure_message,failure_date)
		 values ('%s',%d,%d,'%s','%s')",$_POST['sname'],$_POST['fcode'],$_POST['scode'],$_POST['fmsg'],$_POST['sdate']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) { echo "-1"; exit(); } else { echo "1"; exit(); }
	}
	else if ($_GET['action'] == 'show_failures') {
		$data = array();
		$dbo->setQuery("select a.id,a.system_id,b.description as failure_reason,c.description as severity,d.system_name,a.failure_message,a.resolve_message
		from cput_service_failure a 
		left outer join #__service_failure_codes b on (a.failure_reason = b.id)
		left outer join #__service_failure_severity c on (a.severity_code = c.id)
		left outer join change_control.cts_systems d on (a.system_id = d.system_no)
		where a.end_date <= date(now())");
		$result = $dbo->query();
			if ($dbo->getNumRows() == 0) {
				echo "-1"; exit(); 
			}
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = "[".$row->system_id."] ".$row->system_name.";".$row->failure_message.";".$row->resolve_message.";".$row->failure_reason.";".$row->severity;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'save_action') {
		$srec = explode(";",$_POST['current_status']);
		$rstate = $srec[1];
		$state_id = $srec[0];
		$sql = sprintf("insert into cput_service_failure_resolve (id,action_date,action_taken,current_state) values (%d,'%s','%s',%d)",
			$_POST['act_id'],$_POST['action_date'],addslashes($_POST['action_taken']),$state_id);
		$dbo->setQuery($sql);
		$result = $dbo->query();
			if (!$result) { echo "-1"; exit(); }
				if (intval($rstate == 1)) {
					$sql = sprintf("update cput_service_failure set current_state = %d where id = %d",$rstate,$_POST['act_id']);
					$dbo->setQuery($sql);
					$result = $dbo->query();
					if (!$result) { echo "-2"; exit(); }
				}
		echo "1";
	}
}
exit();