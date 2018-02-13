<?php
defined('_JEXEC') or die('Restricted access');
$dbo =& JFactory::getDBO();
$doc = & JFactory::getDocument();

if (isset($_GET['action'])) {
	if ($_GET['action'] == "save_budget_config"){
		switch ($_GET['status']){
			case "true": $stat = 1; break;
			case "false": $stat = 0; break;
			default: $stat = 0; break;
		}
		$sql = sprintf("update cput_system_setup set allow_budget=%d, budget_year=%d,budget_cutoff='%s' where system_name='budgets'",$stat,$_POST['budget_year'],$_POST['budget_cutoff']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) { echo "0"; } else { echo "1"; }
	}
	else if ($_GET['action'] == "get_budget_config"){
		$dbo->setQuery("select allow_budget,ifnull(budget_cutoff,date(now())) as budget_cutoff,ifnull(budget_year,year(now())) as budget_year from cput_system_setup where system_name = 'budgets'");
		$row = $dbo->loadObject();
		echo $row->allow_budget.";".$row->budget_cutoff.";".$row->budget_year;
	}
	else if ($_GET['action'] == "check_user_faculty"){
		$dbo->setQuery("select fac_code,fac_name from staff.staff where staff_no=".$_GET['empno']);
		$dbo->query();
			if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
		$row = $dbo->loadObject();
		echo $row->fac_code.";".$row->fac_name;
	}
	else if ($_GET['action'] == "load_centres"){
		$data = array();
		$dbo->setQuery("select distinct a.fac_code,a.detail_cc,a.cc_name from budgets.costcodes a  where a.fac_code = '".$_GET['fac']."' and a.active='Y'");
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$data[] = $row->fac_code.";".$row->detail_cc.";".$row->cc_name;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == "load_assignment"){
		$data = array();
		$sql = sprintf("select ccode from budgets.cc_users where cname = '%s'",$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
			if ($dbo->getNumRows() == 0) { $data[] = 0; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$data[] = $row->ccode;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == "clear_assignment"){
		$sql = sprintf("delete from budgets.cc_users where cname = '%s'",$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if ($_GET['action'] == "save_assignment"){
		$sql = sprintf("insert into budgets.cc_users (ccode,cname) values ('%s','%s')",$_GET['cc'],$_GET['lg']);
		echo $sql;
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if ($_GET['action'] == "load_cost_centres"){
		$data = array();
		$sql = sprintf("select distinct a.detail_cc,a.cc_name from budgets.costcodes a left join budgets.cc_users b on (a.detail_cc = b.ccode) where b.cname = '%s' order by a.detail_cc",$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = 0; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$data[] = $row->detail_cc.";".$row->cc_name;
		}
		echo json_encode($data);
	}

exit();
}