<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
require_once("scripts/system/functions.php");

if (isset($_GET)) {

	if ($_GET['action'] == "display_info") {
		$pt = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
		$fp = file($pt);
		$ldap = $fp[4];
		$cnt = preg_match('/^[0-9]+$/',$_GET['uid']);
		if ($cnt >= 1) {
			$username = $_GET['uid']; 
		} else {
			$sql = sprintf("select userid from cput_users_cellular where login='%s'",$_GET['uid']);
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			$username = $row->userid;
		}
		$username = trim($username);
		$sql = sprintf("select a.staff_no,a.staff_title,a.staff_init,a.staff_sname,a.staff_fname,a.fac_name,a.dept_name,ifnull(a.staff_cellno,'UNKNOWN') as cell,
			a.staff_job_title, (select concat(c.staff_sname,', ',c.staff_fname)  from staff.staff c where c.staff_no = a.staff_boss) as hod,
			d.campus_name,e.build_name,ifnull(a.staff_floor,0) as floor_no,ifnull(a.staff_room,' ') as room,a.staff_telext,a.speed_dial,a.staff_email
			from staff.staff a 
			left outer join structure.campus d on (a.staff_campus = d.campus_code)
			left outer join structure.buildings e on (a.staff_building = e.build_code)
			where a.staff_no=%d",$username);
			//echo $sql;exit();
							
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		if (!$row) echo "-1"; else
			echo $row->staff_no.";".$row->staff_title,";".$row->staff_init.";".$row->staff_sname.";".$row->staff_fname.";".$row->fac_name.";".$row->dept_name.";".$row->cell.";".$row->staff_job_title.";".$row->hod.";".$row->campus_name.";".$row->build_name.";".$row->floor_no.";".$row->room.";".$row->staff_telext.";".$row->speed_dial.";".$row->staff_email;
	}
	else if ($_GET['action'] == "display_stud_info") {
		$data = array();
		$username = $_GET['uid']; 
		$sql = sprintf("select a.stud_numb,a.reg_date,a.stud_block,e.off_name as ot,f.qual_desc,
							concat(b.pers_fname,' ',b.pers_sname) as fullname,c.fac_desc,d.dept_desc,g.cell
							from student.student2012 a 
							left outer join student.personal b on (a.stud_numb = b.stud_numb)
							left outer join structure.faculty c on (a.stud_fact = c.fac_code)
							left outer join structure.department d on (a.stud_dept = d.dept_code)
							left outer join structure.offerings e on (a.stud_ot = e.off_code)
							left outer join structure.qualification f on (a.stud_qual = f.qual_code)
							left outer join cput_users_cellular g on (a.stud_numb = g.userid)
							where a.stud_numb = %d",$username);
							
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		if (is_object($row)) {
	echo $row->stud_numb.";".$row->reg_date.";".$row->stud_block.";".$row->ot.";".$row->qual_desc.";".$row->fullname.";".$row->fac_desc.";".$row->dept_desc.";".$row->cell;
		} else {
				echo "-1";
		}
	}

	else if ($_GET['action'] == "display_secondary") {
		$dbo->setQuery("call proc_check_secondary_profile_staff('".$_GET['stfno']."')");
		$dbo->query();
		$dbo->setQuery("select ifnull(fax,' ') as fax,ifnull(speed_dial,' ') as speed_dial,ifnull(line_manager,0) as line_manager,
		ifnull(secretary,0) as secretary,ifnull(secretary_ext,' ') as secretary_ext,ifnull(secretary_fax,' ') as secretary_fax,
		ifnull(secretary_email,' ') as secretary_email,ifnull(secondary_campus,0) as secondary_campus,
		ifnull(secondary_building,0) as secondary_building,ifnull(secondary_floor,' ') as secondary_floor,
		ifnull(secondary_room,' ') as secondary_room,ifnull(secondary_ext,' ') as secondary_ext,ifnull(secondary_fax,' ') as secondary_fax,
		ifnull(aka,'') as aka,ifnull(hod,'') as hod,secretary2,secretary_fax2,secretary_ext2,secretary_email2
		from cput_staff_secondary_profile where staff_no = ".$_GET['stfno']);
		$row = $dbo->loadObject();
			if (!$row) { echo "-1"; exit(); }
		echo $row->fax.";".$row->speed_dial.";".$row->line_manager.";".$row->secretary.";".$row->secretary_ext.";".$row->secretary_fax.";".$row->secretary_email.";".$row->secondary_campus.";".$row->secondary_building.";".$row->secondary_floor.";".$row->secondary_room.";".$row->secondary_ext.";".$row->secondary_fax.";".$row->aka,";".$row->hod.";".$row->secretary2.";".$row->secretary_ext2.";".$row->secretary_fax2.";",$row->secretary_email2;
	}
	else if ($_GET['action'] == "save_profile") {
		$sql = sprintf("update cput_staff_secondary_profile set fax='%s',speed_dial='%s',
		secondary_campus=%d,secondary_building=%d,secondary_floor='%s',secondary_room='%s',secondary_ext='%s',secondary_fax='%s',secretary2=%d,secretary_ext2='%s',secretary_fax2='%s',secretary_email2='%s' where staff_no=%d",$_POST['fax_no'],$_POST['speed_dial'],$_POST['other_campus'],$_POST['other_building'],$_POST['other_floor'],$_POST['other_room'],$_POST['other_ext'],$_POST['other_fax'],$_POST['secretary2'],$_POST['sec_ext2'],$_POST['sec_fax2'],$_POST['sec_email2'],$_POST['hidden_staffno']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) echo "-1"; else echo "1";
	}
	else if ($_GET['action'] == "save_profile_sec") {
		$sql = sprintf("update cput_staff_secondary_profile set secretary=%d,secretary_ext='%s',secretary_fax='%s',secretary_email='%s' where staff_no=%d",$_POST['secretary'],$_POST['sec_ext'],$_POST['sec_fax'],$_POST['sec_email'],$_POST['sec_staff']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) echo "-1"; else echo "1";
	}
	else if ($_GET['action'] == "save_profile_aka") {
		$sql = sprintf("update cput_staff_secondary_profile set aka='%s',hod=%d,line_manager=%d where staff_no = %d",$_POST['n_aka'],$_POST['hod2'],$_POST['line_manager'],$_POST['aka_staff']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) echo "-1"; else echo "1";
	}
	else if ($_GET['action'] == "save_profile_cell") {
		$sql = sprintf("CALL proc_update_cell(%d, '%s', 1, '%s')",$_GET['uid'],$_GET['cell'],$_GET['lg']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) echo "-1"; else echo "1";
	}
	else if ($_GET['action'] == "mail_hr") {
		$dbo->setQuery("select ifnull(hr_email,'@') as hr_email from #__sms_settings where id = 1");
		$row = $dbo->loadObject();
		$hr_email[] = $row->hr_email;
			$dbo->setQuery("select name from cput_users where id = ".$_GET['uid']);
			$row = $dbo->loadObject();
			$uname = $row->name;
				$msg = urldecode($_GET['fac'])." - ".urldecode($_GET['dept'])."<br />";
				$msg .= "The following staff member requested an information update on the ITS system.<br /><br />";
				$msg .= "User name: ".$uname."<br />";
				$msg .= "Staff# ".$_GET['stfno']."<br /><br />";
				$msg .= urldecode($_GET['info'])."<br /><br />";
				$msg .= "This message was generated from the Online Personal Access System.<br />";
				$msg .= "If this message was not intended for you, please delete it now.";

				$res = sendMail(serialize($hr_email),"Staff member profile update request.",$msg);
				echo $res;

	}
	else if ($_GET['action'] == "save_profile_view") {
		$dbo->setQuery("CALL `proc_save_profile_view`('".$_GET['id']."')");
		$dbo->query();
	}
	else if ($_GET['action'] == "display_last_view") {
		$dbo->setQuery("select ifnull(view_date,'NEVER') as view_date from cput_staff_profile_view where stf_no=".$_GET['id']);
		$row = $dbo->loadObject();
		if (strlen($row->view_date) == 0) echo "NEVER"; else echo $row->view_date;
	}
	else if ($_GET['action'] == "check_data_empno") {
		$sql = sprintf("select userid from cput_users_cellular where login='%s'",$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { echo "0"; }
		else { $row = $dbo->loadObject(); echo $row->userid; }
	}
	else if ($_GET['action'] == "check_data") {
		$sql = sprintf("select userid from cput_users_cellular where login='%s'",$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { 
			echo "0"; exit(); 
		} else { 
			$row = $dbo->loadObject(); 
			echo $row->userid;
			exit();
		}
	}
	else if ($_GET['action'] == "student_lookup") {
		$data = array();
		$sql = "select stud_numb,pers_sname,pers_fname,pers_dob,pers_idno from student.personal where 1=1";
			if (strlen($_POST['studno']) > 0) $sql .= " and stud_numb like '".$_POST['studno']."%'";
			if (strlen($_POST['sname']) > 0) $sql .= " and upper(pers_sname) like '".strtoupper($_POST['sname'])."%'";
			if (strlen($_POST['fname']) > 0) $sql .= " and upper(pers_fname) like '".strtoupper($_POST['fname'])."%'";
			if (strlen($_POST['idno']) > 0) $sql .= " and pers_idno like '".$_POST['idno']."%'";
			if (strlen($_POST['dob']) > 0) $sql .= " and pers_dob = '".$_POST['dob']."'";
			$sql .= "order by pers_sname limit 100";
			$dbo->setQuery($sql);
			$dbo->query();
			if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
			$result = $dbo->loadObjectList();
			foreach($result as $row){
				$data[] = $row->stud_numb.";".$row->pers_sname.";".$row->pers_fname.";".$row->pers_idno.";".$row->pers_dob;
			}
			echo json_encode($data);
	}
}
exit();

?>