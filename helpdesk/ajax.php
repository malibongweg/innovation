<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
$dbo->setQuery("select connect_string,user_name,password from cput_system_setup where system_name='helpdesk'");
$dbo->query();
if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
$row = $dbo->loadObject();

if (isset($_GET['action'])) {

	if ($_GET['action'] == 'get_number') {
		$cnt = preg_match('/^\d+$/',$_GET['uid']);
		if ($cnt >= 1) {
			echo $_GET['uid']; exit(); 
		}
		$sql = sprintf("select userid from cput_users_cellular where login='%s'",$_GET['uid']);
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			$username = $row->userid;
	}

	else if ($_GET['action'] == 'list_services') {
		$oci = oci_connect($row->user_name,$row->password,$row->connect_string);
		if (!$oci) { echo "-1"; exit(); }
		$sql = "select substr(servicename,1,40) as servicename,servcode from service where web='Y' order by servicename";
		$res = oci_parse($oci,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		$data = array();
		while ($o = oci_fetch_object($res)) {
			$data[] = $o->SERVCODE.";".$o->SERVICENAME;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'list_campus') {
		$oci = oci_connect($row->user_name,$row->password,$row->connect_string);
		if (!$oci) { echo "-1"; exit(); }
		$sql = "select campus from campus order by campus";
		$res = oci_parse($oci,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		$data = array();
		while ($o = oci_fetch_object($res)) {
			$data[] = $o->CAMPUS;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'list_subservice') {
		$oci = oci_connect($row->user_name,$row->password,$row->connect_string);
		if (!$oci) { echo "-1"; exit(); }
		$sql = "select substr(subservname,1,40) as subservname,subservcode from subservice where linkcode = ".$_GET['primary']." and web='Y' order by subservcode";
		$res = oci_parse($oci,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		$data = array();
		while ($o = oci_fetch_object($res)) {
			$data[] = $o->SUBSERVCODE.";".$o->SUBSERVNAME;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'save_data') {
		$oci = oci_connect($row->user_name,$row->password,$row->connect_string);
		if (!$oci) { echo "-1"; exit(); }

		$sql = "select callid.nextval as ci from dual";
		$res = oci_parse($oci,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		$row = oci_fetch_object($res);
		$ci = $row->CI;

		$sql = sprintf("select count(*) as cnt from requests where service = %d and subservice = %d and callerid = %d and  to_number(to_date(to_char(sysdate,'dd-mm-yyyy'),'dd-mm-yyyy') - to_date(to_char(logdatetime,'dd-mm-yyyy'),'dd-mm-yyyy')) < 15",
		$_POST['service_name'],$_POST['sub_service'],$_POST['user_number']);
		$res = oci_parse($oci,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		$c = oci_fetch_object($res);
		if ($c->CNT > 0) { $recur = 1; } else { $recur = 0; }

		$sql = sprintf("select nvl(actual_group,'FACILITIES') actual_group,NVL(actual_campus,'BELLVILLE') actual_campus from webtrans where web_campus = '%s' and service_code = %d",$_POST['campus'],$_POST['service']);
		$res = oci_parse($oci,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		$r = oci_fetch_object($res);
		$groupname = $r->ACTUAL_GROUP; 
		$actcampus = $r->ACTUAL_CAMPUS;
		if (strlen($groupname) == 0) { $groupname = 'FACILITIES'; }
		if (strlen($actcampus) == 0) { $actcampus = 'BELLVILLE'; }
		$details = $_POST['details'];
		$details = str_replace("'", "''", $details);

		$sql = sprintf("insert into requests (empno,service,details,caseid,callerid,casestatus,subservice,groupname,logdatetime,campus,recur) values (%d,%d,'%s',%d,%d,'%s',%d,'%s',sysdate,'%s',%d)",$_POST['user_number'],$_POST['service_name'],$details,$ci,
		$_POST['user_number'],"WEB LOGGED",$_POST['sub_service'],$groupname,$actcampus,$recur);
		$res = oci_parse($oci,$sql);
		if (!$res) { echo "-1"; exit(); } else { oci_execute($res); echo "1"; }
	}
	else if ($_GET['action'] == "check_data") {
		$sql = sprintf("select userid from cput_users_cellular where login='%s'",$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { echo "0"; }
		else {
			$row = $dbo->loadObject();
			echo $row->userid;
		}
	}
		
}
exit();

?>