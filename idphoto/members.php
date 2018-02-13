<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

if (isset($_GET)) {

	if ($_GET['action'] == "search_member") {
			$sql = sprintf("select a.id,a.stdno,a.branch,b.id as org_id,b.org_name from identity.membership a left outer join identity.org b on (a.org = b.id) where a.stdno = %d",$_GET['uid']);
			$dbo->setQuery($sql);
			$dbo->query();
			$return = array();
			if ($dbo->getNumRows() == 0){
				$return["code"] = -1;
			} else {
				$row = $dbo->loadObject();
				$return["code"] = 1;
				$return["Record"] = $row;
			}
			echo json_encode($return);
	} else if ($_GET['action'] == "lookup_member") {
			$sql = sprintf("select a.stud_numb,concat(a.pers_title,' ',a.pers_init,' ',a.pers_sname) as membername from student.personal_curr a where a.stud_numb = %d",$_GET['uid']);
			$dbo->setQuery($sql);
			$dbo->query();
			$return = array();
			if ($dbo->getNumRows() == 0){
				$return["code"] = -1;
			} else {
				$row = $dbo->loadObject();
				$return["code"] = 1;
				$return["Record"] = $row;
			}
			echo json_encode($return);
	} else if ($_GET['action'] == "load_orgs") {
			$sql = sprintf("select id,org_name from identity.org order by org_name");
			$dbo->setQuery($sql);
			$dbo->query();
			$return = array();
			if ($dbo->getNumRows() == 0){
				$return["code"] = -1;
			} else {
				$row = $dbo->loadObjectList();
				$return["code"] = 1;
				$return["Record"] = $row;
			}
			echo json_encode($return);
	} else if ($_GET['action'] == "save_mem") {
			$sql = sprintf("insert into identity.membership (stdno,mem_name,branch,org) values (%d,'%s','%s',%d)",$_POST['mem_stdno'],$_POST['mem_name'],$_POST['mem_branch'],$_POST['mem_org']);
			$dbo->setQuery($sql);
			$dbo->query();
	}  else if ($_GET['action'] == "del_mem") {
			$sql = sprintf("delete from identity.membership where stdno = %d",$_POST['mem_stdno']);
			$dbo->setQuery($sql);
			$dbo->query();
	} else if ($_GET['action'] == "prn_search") {
			$return = array();
			$sql = sprintf("select distinct a.location from identity.photos a where a.userid = %d",$_GET['uid']);
			$dbo->setQuery($sql);
			$dbo->query();
			if ($dbo->getNumRows() == 0){
				$return["pic"] = -1;
			} else {
				$row = $dbo->loadObject();
				$return["pic"] = 1;
				$return["source"] = $row->location;
			}

			$sql = sprintf("select a.stdno,a.mem_name,a.branch,year(now()) as expire from identity.membership a where a.stdno = %d",$_GET['uid']);
			$dbo->setQuery($sql);
			$dbo->query();

			$row = $dbo->loadObject();
			$return["Record"] = $row;
			
			echo json_encode($return);
	}


}
exit();
