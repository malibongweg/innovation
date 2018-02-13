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
		$sql = sprintf("select a.stud_numb,concat(a.pers_fname,' ',a.pers_sname) as fullname from student.personal_history a where cast(a.stud_numb as char) like '%s%%'",$_GET['id']);
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
		$dbo->setQuery("select connect_string,user_name,password from #__system_setup where system_name = 'helpdesk'");
		$row = $dbo->loadObject();
		$cs = $row->connect_string;
		$uname = $row->user_name;
		$pass = $row->password;
		$con = oci_connect($uname,$pass,$cs);
			if (!$con) {
				echo "-1";
				exit();
			}
		$sql = sprintf("select studfoto,studno from fototab where studno = %d",$_GET['id']);
		$result = oci_parse($con,$sql);
		if (!$result) { echo "-1"; exit(); } 
		$p = oci_execute($result);
		if (!$p) { echo "-1"; exit(); } 
		$row = oci_fetch_assoc($result);
		$img = $row['STUDFOTO']->load();
		$fp = fopen("c:/temp/img.jpg","w");
		fwrite($fp,$img);
		fclose($fp);
		echo $row->STUDNO;
		file_put_contents("c:/temp/img2.jpg",$img);
		
	}
		
}
exit();

?>