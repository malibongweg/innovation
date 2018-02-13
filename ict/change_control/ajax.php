<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',1);

if (isset($_GET['action'])) {


	if ($_GET['action'] == 'save_request') {
		$sql = sprintf("insert into change_control.change_request (system_no, application_date, execute_date, start_time, end_time, office_hours, urgency_code, project_leader, change_desc, user, change_details,change_reason,change_impact,change_rollback,change_comments,request_status) values ('%s',now(),'%s','%s','%s','%s',%d,%d,'%s','%s','%s','%s','%s','%s','%s',1)",$_POST['system_no'],$_POST['pdate'],$POST['sdate'],$_POST['edate'],$_POST['officeHours'],$_POST['urgencyChoice'],
			$_POST['leader'],addslashes($_POST['request_desc']),$_POST['uname'],addslashes($_POST['details_change']),addslashes($_POST['reasons_change']),addslashes($_POST['impact_change']),addslashes($_POST['rollback_change']),addslashes($_POST['comments_change']));
		$dbo->setQuery($sql);
		$result = $dbo->query();
		
		$request_no = $dbo->insertid();
		$sql = sprintf("select auth from change_control.cts_systems where system_no = '%s'",$_POST['system_no']);
		$dbo->setQuery($sql);
		$result = $dbo->loadResult();
		$auth = explode(',',$result);
		foreach($auth as $key=>$value) {
			$sql = sprintf("insert into change_control.systems_auth(system_no, staff_no, request_no) values ('%s',%d,%d)",$_POST['system_no'],$value,$request_no);
			$dbo->setQuery($sql);
			$dbo->query();
		}
		$sql = sprintf("insert into change_control.change_status(request_no, status_code, status_date, status_techie) values (%d,%d,now(),%d)",$request_no,1,$_POST['leader']);
		$dbo->setQuery($sql);
		$dbo->query();

		$sql = sprintf("insert into change_control.docs_received(request_no, received) values (%d,0)",$request_no);
		$dbo->setQuery($sql);
		$dbo->query();

		echo $request_no;
	} 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($_GET['action'] == 'save_affected') {
		$sql = sprintf("insert into change_control.affect_service(request_no, service_code) values (%d,'%s')",$_GET['req'],$_GET['service']);
		$dbo->setQuery($sql);
		$dbo->query();
	}////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($_GET['action'] == 'save_campus') {
	$sql = sprintf("insert into change_control.affect_campus(request_no, campus_code) values (%d,%d)",$_GET['req'],$_GET['cmp']);
	$dbo->setQuery($sql);
	$dbo->query();
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($_GET['action'] == 'save_resources') {
		$res = $_GET['res'];
		$x = explode(';',$res);
		$sql = sprintf("insert into change_control.change_checklist(request_no, step_desc, hw_soft_ready, step_date) values (%d,'%s','%s','%s')",$_GET['req'],$x[0],$x[1],$x[2]);
		$dbo->setQuery($sql);
		$dbo->query();
	}
}
exit();