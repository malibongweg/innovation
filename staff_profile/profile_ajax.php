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
	else if ($_GET['action'] == 'saveChangesDB') {
		$wid = md5($_GET['stno'].$_GET['field_name']);
		$sql = sprintf("update cput_staff_profile_amendments set status = 5 where staffno=%d and field_name = '%s' and status = 1",$_GET['stno'],$_GET['field_name']);
		$dbo->setQuery($sql);
		$dbo->query();

		$sql = sprintf("insert into cput_staff_profile_amendments (staffno,field_name,old_field_value,new_field_value,webid,status)
					values (%d,'%s','%s','%s','%s',1)",$_GET['stno'],$_GET['field_name'],$_GET['old_value'],$_GET['new_value'],$wid);
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if ($_GET['action'] == 'display_submitted') {
		$data = array();
		$sql = sprintf("select id,field_name,new_field_value,status
			from cput_staff_profile_amendments where staffno = %d and status in (1,3)
			union
			select id,field_name,new_field_value,status from cput_staff_profile_amendments
			where staffno = %d and status=2 and datediff(current_date,entry_date) <= 14",$_GET['uid'],$_GET['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		foreach ($result as $row) {
			$data[$row->field_name] = $row->new_field_value.";".$row->status;
		}
		echo json_encode($data);
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($_GET['action'] == 'displayAmendmentsList') {
		$sql = sprintf("select count(distinct(a.staffno)) from cput_staff_profile_amendments a
			where a.`status` = 1 group by a.staffno");
		$dbo->setQuery($sql);
		$cnt = $dbo->loadResult();

		$sql = sprintf("select distinct a.staffno as staffindex,a.staffno,b.staff_sname,concat(b.staff_sname,', ',b.staff_title,' ',b.staff_init) as staff_member
						from cput_staff_profile_amendments a
						left outer join staff.staff b on (a.staffno=b.staff_no)
						where a.`status` = 1 and b.dept_code in %s order by b.staff_sname",$_GET['dept']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();

		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);

	}
	else if ($_GET['action'] == 'displayAmendments') {
		$sql = sprintf("select a.id,a.staffno,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as staff_memeber,
						a.field_name,a.old_field_value,a.new_field_value,c.`desc`,a.status from cput_staff_profile_amendments a
						left outer join staff.staff b on (a.staffno=b.staff_no)
						left outer join cput_amend_status c on (a.`status`=c.id)
						where a.staffno = %d and a.`status` <= 3 
						order by a.status",$_GET['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listStatus') {
		$return = array();
		$dbo->setQuery("select id,`desc` from cput_amend_status");
		$result = $dbo->loadObjectList();
		$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->desc;
				$return['Options'][$i]['Value'] = $row->id;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateAmendments') {
		$sql = sprintf("update cput_staff_profile_amendments set status = %d where id = %d",$_POST['status'],$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'getStaffNo') {
		$sql = sprintf("select userid from cput_users_cellular where login='%s'",$_GET['login']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { 
			echo "0";
		} else { 
			$row = $dbo->loadObject(); 
			echo $row->userid;
		}
	}
	else if ($_GET['action'] == 'getHRdept') {
		$sql = sprintf("select a.dept_code from cput_staff_profile_allocations a where a.uid = %d",$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { 
			echo "0";
		} else { 
			$data = array();
			$result = $dbo->loadObjectList();
			foreach ($result as $row) {
				$data[] = $row->dept_code;
			}
			echo json_encode($data);
		}
	}
	else if ($_GET['action'] == 'hrAlloc') {
		$sql = sprintf("select b.id,b.uid,b.dept_code,concat(a.staff_title,' ',a.staff_init,' ',a.staff_sname) as staff_name,c.dept_desc
						from cput_staff_profile_allocations b left outer join staff.staff a on (b.uid=a.staff_no)
						left outer join structure.department c on (b.dept_code=c.dept_code)
						order by a.staff_sname");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listStaffMembers') {
		$sql = sprintf("select a.staff_no,concat(a.staff_title,' ',a.staff_init,' ',a.staff_sname) as staff from staff.staff a");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return['Result'] = 'OK';
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->staff.' ['.$row->staff_no.']';
			$return['Options'][$i]['Value'] = $row->staff_no;
			++$i;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listDepartments') {
		$sql = sprintf("select a.dept_code,a.dept_desc from structure.department a");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return['Result'] = 'OK';
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->dept_desc;
			$return['Options'][$i]['Value'] = $row->dept_code;
			++$i;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'hrDel') {
		$sql = sprintf("delete from cput_staff_profile_allocations where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'hrNew') {
		$sql = sprintf("insert into cput_staff_profile_allocations (uid,dept_code) values (%d,%d)",$_POST['uid'],$_POST['dept_code']);
		$dbo->setQuery($sql);
		$dbo->query();
		$lstid = $dbo->insertid();
		$sql = sprintf("select b.id,b.uid,b.dept_code,concat(a.staff_title,' ',a.staff_init,' ',a.staff_sname) as staff_name,c.dept_desc
						from cput_staff_profile_allocations b left outer join staff.staff a on (b.uid=a.staff_no)
						left outer join structure.department c on (b.dept_code=c.dept_code)
						where b.id = %d",$lstid);
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}

}
exit();

?>