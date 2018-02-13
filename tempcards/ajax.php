<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
$dbo->setQuery("select connect_string,user_name,password from cput_system_setup where system_name='helpdesk'");
$dbo->query();
if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
$row = $dbo->loadObject();

if (isset($_GET['action'])) {

	if ($_GET['action'] == 'list_users') {
		$data = array();
		$yr = getDate('Y');
		if ($_GET['scond'] == 'N') {
		$sql = sprintf("select a.stud_numb,concat(a.pers_fname,' ',a.pers_sname) as fullname from student.personal_curr a where cast(a.stud_numb as char) like '%s%%'",$_GET['id']);
		} else {
		$sql = sprintf("select a.stud_numb,concat(a.pers_sname,', ',a.pers_fname) as fullname from student.personal_curr a where lower(a.pers_sname) like lower('%s%%')",$_GET['id']);
		}
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) {
			$data[] = "-1";
		} else {
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->stud_numb.";".$row->fullname;
		}
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'display_photo') {
		$sql = sprintf("select count(*) as cnt from identity.photos where userid = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$cnt = $dbo->loadResult();
			if ($cnt == 0) {
				echo "-2"; exit();
			}

		$sql = sprintf("select location from identity.photos where userid = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$location = $dbo->loadResult();
		echo $location;
		
	}
	else if ($_GET['action'] == 'get_user_details') {
		$yr = Date("Y");
		$sql = "select A.stud_numb, concat(A.pers_fname,' ',A.pers_sname) as fullname, A.pers_dob, B.qual_desc from student.personal_curr A, structure.qualification B,student.student".$yr." C 
		where A.stud_numb =".$_GET['id']." and A.stud_numb = C.stud_numb and C.stud_qual = B.qual_code and B.qual_year = ".$yr;
		//echo $sql;exit();
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { echo "-2"; exit(); }
		$row = $dbo->loadObject();
		if (!$row) { echo $dbo->getErrorMsg(); exit(); }
		$response = $row->stud_numb.";".$row->fullname.";".$row->qual_desc;
		echo $response;
	}
		
}
exit();

?>
