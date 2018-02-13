<?php
require_once("scripts/system/functions.php");
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);

if (isset($_GET['action'])) {


	if ($_GET['action'] == 'listManagers') {///List campus managers
		$sql = sprintf("select a.id, a.staffno, a.campus, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,
		' [',b.staff_no,']') as fullname,c.campus_name from jobcards.campus_managers a
		left outer join staff.staff b on (a.staffno = b.staff_no) left outer join structure.campus c on (a.campus = c.campus_code)
		order by b.staff_sname");

		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		//$return['TotalRecordCount'] = $count;
		echo json_encode($return);
	}
	else if ($_GET['action'] == "listStaff"){
		$sql = sprintf("select a.staff_no, concat(a.staff_sname,', ',a.staff_title,' ',a.staff_fname,
		' [',a.staff_no,']') as fullname from staff.staff a where date(a.staff_resign) > date(now()) order by a.staff_sname,a.staff_fname");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->fullname;
			$return['Options'][$i]['Value'] = $row->staff_no;
			++$i;
		}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'getStaffNo') {
		$sql = sprintf("select userid from portal.cput_users_cellular where lower(login) = lower('%s')",$_GET['login']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			echo "0";
		} else {
			$row = $dbo->loadResult();
			echo $row;
		}
	}
	else if ($_GET['action'] == "listArtisanTrades"){
		$sql = sprintf("select a.id,a.trade_description from jobcards.artisan_trades a order by a.trade_description");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->trade_description;
			$return['Options'][$i]['Value'] = $row->id;
			++$i;
		}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == "listBuildings"){
		if (isset($_GET['cid'])) {
			$sql = sprintf("select a.build_code,a.build_name from structure.buildings a where a.campus_code=%d order by a.build_name",$_GET['cid']);
		} else {
			$sql = sprintf("select a.build_code,a.build_name from structure.buildings a order by a.build_name");
		}
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->build_name;
			$return['Options'][$i]['Value'] = $row->build_code;
			++$i;
		}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == "listCampus"){
		if (isset($_GET['cid'])) {
			$sql = sprintf("select a.campus_code, a.campus_name from structure.campus a where a.campus_code = %d",$_GET['cid']);
		} else {
			$sql = sprintf("select a.campus_code, a.campus_name from structure.campus a order by a.campus_name");
		}
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->campus_name;
			$return['Options'][$i]['Value'] = $row->campus_code;
			++$i;
		}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createManager') {
		$sql = sprintf("select lower(login) as login from portal.cput_users_cellular where userid = %d",$_POST['staffno']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'No user account validation found on OPA.';
			echo json_encode($return);
			exit();
		}

		$login = $dbo->loadResult();
		$sql = sprintf("select id from portal.cput_users where lower(username) = '%s'",$login);
		$dbo->setQuery($sql);
		$id = $dbo->loadResult();

		$sql = sprintf("insert into jobcards.campus_managers (staffno,campus,uid) values (%d,%d,%d)",$_POST['staffno'],$_POST['campus'],$id);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'An error occured.';
		} else {
			$sql = sprintf("select a.id, a.staffno, a.campus, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,
			' [',b.staff_no,']') as fullname,c.campus_name from jobcards.campus_managers a
			left outer join staff.staff b on (a.staffno = b.staff_no) left outer join structure.campus c on (a.campus = c.campus_code)
			where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateManager') {
		$sql = sprintf("update jobcards.campus_managers set staffno=%d, campus=%d where id=%d",$_POST['staffno'],$_POST['campus'],$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteManager') {
		$sql = sprintf("delete from jobcards.campus_managers where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listForeman') {///List campus managers
		$sql = sprintf("select a.id, a.staffno, a.campus, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,
		' [',b.staff_no,']') as fullname,c.campus_name
		from jobcards.campus_foremans a
		left outer join staff.staff b on (a.staffno = b.staff_no)
		left outer join structure.campus c on (a.campus = c.campus_code)
		order by b.staff_sname");

		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		//$return['TotalRecordCount'] = $count;
		echo json_encode($return);
	}
else if ($_GET['action'] == 'createForeman') {
		$sql = sprintf("select lower(login) as login from portal.cput_users_cellular where userid = %d",$_POST['staffno']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'No user account validation found on OPA.';
			echo json_encode($return);
			exit();
		}

		$login = $dbo->loadResult();
		$sql = sprintf("select id from portal.cput_users where lower(username) = '%s'",$login);
		$dbo->setQuery($sql);
		$id = $dbo->loadResult();

		$i = count($_POST['campus']);
		if ($i > 1){
				$sql = sprintf("delete from jobcards.foreman_campuses where staffno=%d",$_POST['staffno']);
				$dbo->setQuery($sql);
				$dbo->query();

				$sql = sprintf("insert into jobcards.campus_foremans (staffno,campus,uid) values (%d,%d,%d)",$_POST['staffno'],$_POST['campus'][0],$id);
				$dbo->setQuery($sql);
				$result = $dbo->query();
				if (!$result){
					$return = array();
					$return['Result'] = 'ERROR';
					$return['Message'] = 'An error occured.';
				} else {
					$sql = sprintf("select a.id, a.staffno, a.campus, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,
					' [',b.staff_no,']') as fullname,c.campus_name from jobcards.campus_foremans a
					left outer join staff.staff b on (a.staffno = b.staff_no) left outer join structure.campus c on (a.campus = c.campus_code)
					where a.id = LAST_INSERT_ID()");
					$dbo->setQuery($sql);
					$row = $dbo->loadAssoc();
					$return = array();
					$return['Result'] = 'OK';
					$return['Record'] = $row;
				}
			foreach($_POST['campus'] as $key=>$value){
				if ($key > 0){
					$sql = sprintf("insert into jobcards.foreman_campuses (staffno,campus) values (%d,%d)",$_POST['staffno'],$value);
					$dbo->setQuery($sql);
					$dbo->query();
				}
			}
		} else {

		$sql = sprintf("insert into jobcards.campus_foremans (staffno,campus,uid) values (%d,%d,%d)",$_POST['staffno'],$_POST['campus'],$id);
		$dbo->setQuery($sql);
		$result = $dbo->query();
			if (!$result){
				$return = array();
				$return['Result'] = 'ERROR';
				$return['Message'] = 'An error occured.';
			} else {
				$sql = sprintf("select a.id, a.staffno, a.campus, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,
				' [',b.staff_no,']') as fullname,c.campus_name from jobcards.campus_foremans a
				left outer join staff.staff b on (a.staffno = b.staff_no) left outer join structure.campus c on (a.campus = c.campus_code)
				where a.id = LAST_INSERT_ID()");
				$dbo->setQuery($sql);
				$row = $dbo->loadAssoc();
				$return = array();
				$return['Result'] = 'OK';
				$return['Record'] = $row;
			}
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateForeman') {
		$sql = sprintf("update jobcards.campus_foremans set staffno=%d, campus=%d where id=%d",$_POST['staffno'],$_POST['campus'],$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteForeman') {
		$sql = sprintf("delete from jobcards.campus_foremans where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listTrades') {///List trades
		$sql = sprintf("select count(*) from jobcards.artisan_trades");
		$dbo->setQuery($sql);
		$count = $dbo->loadResult();
		$sql = sprintf("select a.id, a.trade_description, a.labour_cost, a.display_web
		from jobcards.artisan_trades a order by %s limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $count;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createTrades') {
		$sql = sprintf("insert into jobcards.artisan_trades (trade_description,labour_cost,display_web) values ('%s',%0.2f,%d)",
		$_POST['trade_description'],$_POST['labour_cost'],$_POST['display_web']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'An error occured. Possible duplication.';
		} else {
			$sql = sprintf("select a.id, a.trade_description, a.labour_cost, a.display_web
			from jobcards.artisan_trades a where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
		}
		echo json_encode($return);
	}
else if ($_GET['action'] == 'updateTrades') {
		$sql = sprintf("update jobcards.artisan_trades set trade_description='%s',labour_cost=%0.2f where id = %d",
		$_POST['trade_description'],$_POST['labour_cost'],$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteTrades') {
		$sql = sprintf("delete from jobcards.artisan_trades where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
else if ($_GET['action'] == 'listArtisan') {///List
		$dbo->setQuery("select count(*) from jobcards.artisans");
		$count = $dbo->loadResult();
		$sql = sprintf("select a.id, a.staffno, a.trade_code, a.campus, d.trade_description, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,
		' [',b.staff_no,']') as fullname,c.campus_name from jobcards.artisans a
		left outer join staff.staff b on (a.staffno = b.staff_no) left outer join structure.campus c on (a.campus = c.campus_code)
		left outer join jobcards.artisan_trades d on (a.trade_code = d.id)
		 order by %s limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $count;
		echo json_encode($return);
	}
else if ($_GET['action'] == 'createArtisan') {
		$sql = sprintf("insert into jobcards.artisans (staffno,trade_code,campus) values (%d,%d,%d)",$_POST['staffno'],$_POST['trade_code'],$_POST['campus']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'An error occured.';
		} else {
			$sql = sprintf("select a.id, a.staffno, a.trade_code, a.campus, d.trade_description, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,
			' [',b.staff_no,']') as fullname,c.campus_name from jobcards.artisans a
			left outer join staff.staff b on (a.staffno = b.staff_no) left outer join structure.campus c on (a.campus = c.campus_code)
			left outer join jobcards.artisan_trades d on (a.trade_code = d.id)
			where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateArtisan') {
		$sql = sprintf("update jobcards.artisans set staffno=%d,trade_code=%d,campus=%d where id=%d",
		$_POST['staffno'],$_POST['trade_code'],$_POST['campus'],$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteArtisan') {
		$sql = sprintf("delete from jobcards.artisans where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'get_esc_hours') {
		$sql = sprintf("select escalation_hours,escalation_email,escalation_email2,send_escalation from jobcards.general_settings where id=1");
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			echo "0";
		} else {
			$row = $dbo->loadObject();
			echo $row->escalation_hours.";".$row->escalation_email.";".$row->escalation_email2.";".$row->send_escalation;
		}
	}
	else if ($_GET['action'] == 'save_general') {
		$sql = sprintf("update jobcards.general_settings set escalation_hours = %d,escalation_email='%s',escalation_email2='%s',send_escalation=%d where id=1",$_POST['esc'],$_POST['email'],$_POST['email2'],$_POST['send']);
		$dbo->setQuery($sql);
		$dbo->query();
	}
	//////////////Actual jobcard queries start here////////////////
	else if ($_GET['action'] == 'displayCampus') {
		$sql = sprintf("select * from jobcards.campus_managers where staffno = %d",$_GET['staffno']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			$sql = sprintf("select a.campus_code, a.campus_name
			from structure.campus a
			where a.campus_code = (select b.campus from jobcards.campus_foremans b
			where b.staffno = %d)
			union
			select d.campus_code,d.campus_name from jobcards.foreman_campuses c left outer join structure.campus d on (c.campus=d.campus_code)
			where c.staffno = %d order by campus_name",$_GET['staffno'],$_GET['staffno']);
		} else {
			$sql = sprintf("select a.campus_code, a.campus_name from structure.campus a order by a.campus_name");
		}

		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$data = array();
		foreach($result as $row){
			$data[] = $row->campus_code.";".$row->campus_name;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'setDefaultCampus') {
		$sql = sprintf("select campus from jobcards.campus_managers where staffno = %d",$_GET['staffno']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
				$sql = sprintf("select b.campus from jobcards.campus_foremans b	where b.staffno = %d",$_GET['staffno']);
				$dbo->setQuery($sql);
				$dbo->query();
				if ($dbo->getNumRows() == 0){
				 echo "0";
				} else {
					$cmp = $dbo->loadResult();
					echo $cmp;
				}
		} else {
			$cmp = $dbo->loadResult();
			echo $cmp;
		}
	}
	else if ($_GET['action'] == 'getEmail') {
		$sql = sprintf("select lower(login) as login from portal.cput_users_cellular where userid = %d",$_GET['staffno']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			echo "0";
		} else {
		$login = $dbo->loadResult();
		$sql = sprintf("select email from portal.cput_users where lower(username) = '%s'",$login);
		$dbo->setQuery($sql);
		$email = $dbo->loadResult();
		echo $email;
		}
	}
	else if ($_GET['action'] == 'listJobcards') {
		if (strlen($_GET['jid']) == 0) {
		$sql = sprintf("select count(*) from jobcards.jobcards a where a.campus = %d",$_GET['cid']);
		$dbo->setQuery($sql);
		$count = $dbo->loadResult();
			$sql = sprintf("select a.id,a.capture_date,a.applicant,a.contact_time,a.contact_no,a.email,a.campus,a.building,a.roomno,a.job_status,a.vandalism,a.job_details,
			concat(b.staff_sname,' ,',b.staff_title,' ',b.staff_fname) as fullname,c.campus_name,d.build_name,e.status_desc,a.urgent
			from jobcards.jobcards a left outer join staff.staff b on (a.applicant=b.staff_no)
			left outer join structure.campus c on (a.campus=c.campus_code) left outer join structure.buildings d on (a.building=d.build_code)
			left outer join jobcards.jobcard_status e on (a.job_status=e.id)
			where a.campus = %d order by %s limit %d,%d",$_GET['cid'],$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		} else {
			$sql = sprintf("select a.id,a.capture_date,a.applicant,a.contact_time,a.contact_no,a.email,a.campus,a.building,a.roomno,a.job_status,a.vandalism,a.job_details,
			concat(b.staff_sname,' ,',b.staff_title,' ',b.staff_fname) as fullname,c.campus_name,d.build_name,e.status_desc,a.urgent
			from jobcards.jobcards a left outer join staff.staff b on (a.applicant=b.staff_no)
			left outer join structure.campus c on (a.campus=c.campus_code) left outer join structure.buildings d on (a.building=d.build_code)
			left outer join jobcards.jobcard_status e on (a.job_status=e.id)
			where a.id=%d order by %s limit %d,%d",$_GET['jid'],$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
			$count = 1;
		}
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $count;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createJobcards') {
		$jobstatus = 1;
		$vandalism = 0;
		$urgent = 0;
		if (isset($_POST['vandalism'])){
			$vandalism = 1;
		}
		if (isset($_POST['urgent'])){
			$urgent = 1;
		}
		$sql = sprintf("insert into jobcards.jobcards (applicant,contact_no,email,campus,building,roomno,job_status,vandalism,job_details,contact_time,creator,urgent)
		values (%d,'%s','%s',%d,%d,'%s',%d,%d,'%s','%s',%d,%d)",$_POST['applicant'],$_POST['contact_no'],$_POST['email'],$_POST['campus'],
		$_POST['building'],$_POST['roomno'],$jobstatus,$vandalism,$_POST['job_details'],$_POST['contact_time'],$_POST['creator'],$urgent);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'An error occured.';
		} else {
			$sql = sprintf("select a.id,a.capture_date,a.applicant,a.contact_no,a.email,a.contact_time,a.campus,a.building,a.roomno,a.job_status,a.vandalism,a.job_details,
			concat(b.staff_sname,' ,',b.staff_title,' ',b.staff_fname) as fullname,c.campus_name,d.build_name,e.status_desc
			from jobcards.jobcards a left outer join staff.staff b on (a.applicant=b.staff_no)
			left outer join structure.campus c on (a.campus=c.campus_code) left outer join structure.buildings d on (a.building=d.build_code)
			left outer join jobcards.jobcard_status e on (a.job_status=e.id)
			where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateJobcards') {
		$vandalism = 0;
		$urgent = 0;
		if (isset($_POST['vandalism'])){
			$vandalism = 1;
		}
		if (isset($_POST['urgent'])){
			$urgent = 1;
		}
		$sql = sprintf("update jobcards.jobcards set applicant=%d,contact_no='%s',email='%s',campus=%d,building=%d,roomno=%d,vandalism=%d,job_details='%s',
		contact_time='%s',urgent=%d where id = %d",$_POST['applicant'],$_POST['contact_no'],$_POST['email'],$_POST['campus'],$_POST['building'],$_POST['roomno'],
		$vandalism,$_POST['job_details'],$_POST['contact_time'],$urgent,$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'jobcardDetails') {
		$sql = sprintf("select a.id,a.capture_date,a.contact_time,a.assigned_date,a.applicant,a.contact_no,a.email,a.campus,a.building,a.roomno,a.job_status,a.vandalism,a.job_details,
		concat(b.staff_sname,' ,',b.staff_title,' ',b.staff_fname) as fullname,c.campus_name,d.build_name,e.status_desc
		from jobcards.jobcards a left outer join staff.staff b on (a.applicant=b.staff_no)
		left outer join structure.campus c on (a.campus=c.campus_code) left outer join structure.buildings d on (a.building=d.build_code)
		left outer join jobcards.jobcard_status e on (a.job_status=e.id)
		where a.id = %d",$_GET['jid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObject();
		$return = array();
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'cancelJobcard') {
		$sql = sprintf("update jobcards.jobcards set job_status = 6 where id = %d",$_GET['jid']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		echo "1";
	}
	else if ($_GET['action'] == "listArtisans"){
		$sql = sprintf("select campus from jobcards.jobcards where id = %d",$_GET['jid']);
		$dbo->setQuery($sql);
		$cmp = $dbo->loadResult();

		/*$sql = sprintf("select a.staffno, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,' [',c.trade_description,']') as fullname
			from jobcards.artisans a left outer join staff.staff b on (a.staffno = b.staff_no)
			left outer join jobcards.artisan_trades c on (a.trade_code = c.id)
			where a.campus = %d",$cmp);*/
		$sql = sprintf("select a.staffno,a.staffno as listord, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,' [',c.trade_description,']') as fullname
		from jobcards.artisans a left outer join staff.staff b on (a.staffno = b.staff_no)
		left outer join jobcards.artisan_trades c on (a.trade_code = c.id)
		where a.campus = %d
		union
		select d.id, d.id as listord, d.supplier_name as fullname
		from jobcards.suppliers d
		order by listord",$cmp);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->fullname;
			$return['Options'][$i]['Value'] = $row->staffno;
			++$i;
		}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listAssignedArtisans') {
		/*$sql = sprintf("select a.id, a.jobcard, a.artisan ,a.labour_cost,a.hours, concat(c.staff_sname,', ',c.staff_title,' ',c.staff_fname) as fullname,d.trade_description
		from jobcards.jobcard_artisans a left outer join jobcards.artisans b on (a.artisan = b.staffno)
		left outer join staff.staff c on (b.staffno = c.staff_no) left outer join jobcards.artisan_trades d on (b.trade_code = d.id)
		where a.jobcard = %d order by c.staff_sname",$_GET['jid']);*/
		$sql = sprintf("select a.id, a.jobcard, a.artisan ,a.labour_cost,a.hours, ifnull(concat(c.staff_sname,', ',c.staff_title,' ',c.staff_fname),
		(select e.supplier_name from jobcards.suppliers e where a.artisan = e.id)) as fullname,
		ifnull(d.trade_description,'OUTSIDE CONTRACTOR') as trade_description, f.material_cost
		from jobcards.jobcard_artisans a left outer join jobcards.artisans b on (a.artisan = b.staffno)
		left outer join staff.staff c on (b.staffno = c.staff_no) left outer join jobcards.artisan_trades d on (b.trade_code = d.id)
		left outer join jobcards.jobcards f on (a.jobcard = f.id)
		where a.jobcard = %d order by c.staff_sname",$_GET['jid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createAssignedArtisans') {
		$sql = sprintf("select b.labour_cost from jobcards.artisans a left outer join jobcards.artisan_trades b on (a.trade_code = b.id)
		where a.staffno = %d",$_POST['artisan']);
		$dbo->setQuery($sql);
		$labour = $dbo->loadResult();

		$sql = sprintf("insert into jobcards.jobcard_artisans (jobcard,artisan,labour_cost) values (%d,%d,%0.2f)",$_POST['jobcard'],$_POST['artisan'],$labour);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'An error occured. Possible duplication.';
		} else {
			$sql = sprintf("update jobcards.jobcards set job_status = 2,assigned_date = current_timestamp where id = %d",$_POST['jobcard']);
			$dbo->setQuery($sql);
			$dbo->query();

			/*$sql = sprintf("select a.id, a.jobcard, a.artisan ,a.labour_cost, concat(c.staff_sname,', ',c.staff_title,' ',c.staff_fname) as fullname,d.trade_description
			from jobcards.jobcard_artisans a left outer join jobcards.artisans b on (a.artisan = b.staffno)
			left outer join staff.staff c on (b.staffno = c.staff_no) left outer join jobcards.artisan_trades d on (b.trade_code = d.id)
			where a.id = LAST_INSERT_ID()");*/
			$sql = sprintf("select a.id, a.jobcard, a.artisan ,a.labour_cost,a.hours, ifnull(concat(c.staff_sname,', ',c.staff_title,' ',c.staff_fname),
			(select e.supplier_name from jobcards.suppliers e where a.artisan = e.id)) as fullname,ifnull(d.trade_description,'OUTSIDE CONTRACTTOR') as trade_description
			from jobcards.jobcard_artisans a left outer join jobcards.artisans b on (a.artisan = b.staffno)
			left outer join staff.staff c on (b.staffno = c.staff_no) left outer join jobcards.artisan_trades d on (b.trade_code = d.id)
			where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteAssignedArtisans') {
		$sql = sprintf("delete from jobcards.jobcard_artisans where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();

		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listDelayed') {
		$sql = sprintf("select a.id, a.start_time as starting_time, a.end_time as ending_time, a.reason
					from jobcards.jobcard_delays a where a.jobcard = %d",$_GET['jid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'undoDelayed') {
		$sql = sprintf("update jobcards.jobcard_delays set end_time = current_time where end_time is null");
		$dbo->setQuery($sql);
		$dbo->query();

		$sql = sprintf("update jobcards.jobcards set job_status = 2 where id = %d",$_GET['jid']);
		$dbo->setQuery($sql);
		$dbo->query();
		echo "1";
	}
	else if ($_GET['action'] == 'createDelayed') {
		$sql = sprintf("update jobcards.jobcard_delays set end_time = now() where end_time is null and jobcard = %d",$_POST['jobcard']);
		$dbo->setQuery($sql);
		$dbo->query();
		$sql = sprintf("insert into jobcards.jobcard_delays (jobcard,reason) values (%d,'%s')",$_POST['jobcard'],$_POST['reason']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'An error occured.';
		} else {
			$sql = sprintf("update jobcards.jobcards set job_status = 4 where id = %d",$_POST['jobcard']);
			$dbo->setQuery($sql);
			$dbo->query();

			$sql = sprintf("select a.id, a.start_time as starting_time, a.end_time as ending_time, a.reason
					from jobcards.jobcard_delays a where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteDelayed') {
		$sql = sprintf("delete from jobcards.jobcard_delays where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();

		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'showDelays') {
		$sql = sprintf("select a.start_time,a.end_time,a.reason from jobcards.jobcard_delays a
			where a.jobcard=%d order by a.start_time",$_GET['jid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'checkExist') {
		$sql = sprintf("select id from jobcards.jobcards where id = %d",$_GET['jid']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() > 0){
			$j = $dbo->loadResult();
			echo $j;
		} else {
			echo "0";
		}
	}
	else if ($_GET['action'] == 'checkAck') {
		$sql = sprintf("select notification_sent from jobcards.jobcards where id=%d",$_GET['jid']);
		$dbo->setQuery($sql);
		$count = $dbo->loadResult();
		echo $count;
	}
	else if ($_GET['action'] == 'popArtisanComplete') {
		$sql = sprintf("select a.artisan,concat(b.staff_sname,' ',b.staff_title,' ',b.staff_fname,' [',d.trade_description,']') as fullname,a.labour_cost
		from jobcards.jobcard_artisans a left outer join staff.staff b on (a.artisan = b.staff_no)
		left outer join jobcards.artisans c on (a.artisan = c.staffno) left outer join jobcards.artisan_trades d on (c.trade_code = d.id)
		where a.jobcard = %d limit 3",$_GET['jid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'sendAck') {
		$jid = $_GET['jid'];
		$sql = sprintf("select a.id,concat (b.staff_sname,', ',b.staff_title,' ',b.staff_fname) as fullname,a.email,a.job_details
		from jobcards.jobcards a left outer join staff.staff b on (a.applicant=b.staff_no)
		where id=%d",$jid);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$sendTo = array();
		$sendTo[] = $row->email;
		$addresses = serialize($sendTo);
		$details = "Cape Peninsula University of Technology.<br />";
		$details .= "<hr>";
		$details .= "Maintenance Jobcard Request<br />";
		$details .= "Date: ".date('Y-m-d')."<br />";
		$details .= "Requester: ".$row->fullname."<br />";
		$details .= "E-Mail: ".$row->email."<br />";
		$details .= "Jobcard# ".$row->id."<br />";
		$details .= "<hr>";
		$details .= "This is an acknowledgement email verifying that we have received your request for maintenance.<br />";
		$details .= "You will be contacted by one of our staff members as soon as possible.<br />";
		$details .= "Thank you.<br /><br />";
		$details .= "Details of work to be performed:<br />";
		$details .= "<b>".$row->job_details."</b>";
		$sql = sprintf("update jobcards.jobcards set notification_sent=1 where id = %d",$jid);
		$dbo->setQuery($sql);
		$dbo->query();
		sendMail($addresses,'Maintenance Request Acknowledgement.',$details);
	}
	else if ($_GET['action'] == 'editCompletion') {
		$sql = sprintf("update jobcards.jobcard_artisans set hours = %d where id = %d",$_POST['hours'],$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();

		$sql = sprintf("select jobcard from jobcards.jobcard_artisans where id=%d",$_POST['id']);
		$dbo->setQuery($sql);
		$jid = $dbo->loadResult();

		$sql = sprintf("select ifnull(sum(a.labour_cost * a.hours),0) from jobcards.jobcard_artisans a where a.jobcard=%d",$jid);
		$dbo->setQuery($sql);
		$lc = $dbo->loadResult();

		$sql = sprintf("update jobcards.jobcards set material_cost = %0.2f,labour_cost=%0.2f where id = %d",$_POST['material_cost'],$lc,$_POST['jobcard']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'saveCompletion') {
		$total = 0;
		$sql = sprintf("select labour_cost,hours from jobcards.jobcard_artisans where jobcard = %d",$_GET['jid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$total = $total + ($row->hours * $row->labour_cost);
		}
		$sql = sprintf("update jobcards.jobcards set labour_cost = %0.2f, job_status=3,cde = md5('%s') where id = %d",$total,$_GET['jid'],$_GET['jid']);
		$dbo->setQuery($sql);
		$dbo->query();

		$sql = sprintf("select cde from jobcard.jobcards where id = %d",$_GET['jid']);
		$dbo->setQuery($sql);
		$cde = $dbo->loadResult();
		echo $cde;
	}
else if ($_GET['action'] == 'emailCompletion') {
		$jid = $_GET['jid'];
		$sql = sprintf("select a.id,concat (b.staff_sname,', ',b.staff_title,' ',b.staff_fname) as fullname,a.email,a.job_details,a.cde
		from jobcards.jobcards a left outer join staff.staff b on (a.applicant=b.staff_no)
		where id=%d",$jid);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$sendTo = array();
		$sendTo[] = $row->email;
		$addresses = serialize($sendTo);
		$details = "Cape Peninsula University of Technology.<br />";
		$details .= "<hr>";
		$details .= "Maintenance Jobcard Request<br />";
		$details .= "Date: ".date('Y-m-d')."<br />";
		$details .= "Requester: ".$row->fullname."<br />";
		$details .= "E-Mail: ".$row->email."<br />";
		$details .= "Jobcard# ".$row->id."<br />";
		$details .= "<hr>";
		$details .= "Details of work to be performed:<br />";
		$details .= "<b>".$row->job_details."</b><br /><br />";
		$details .= "Your request for maintenance has been completed. In order to improve our service delivery to our clients, we would<br />";
		$details .= "like you to rate the service that was provided to you. All information will be kept confidential and will only be used to improve our performance.<br />";
		$details .= "Please click on the url below and you will be redirected to our rating webpage.<br />";
		$details .= "http://opa.cput.ac.za/scripts/jobcards/rating.php?cde=".$row->cde."<br /><br />";
		$details .= "Thank you.";
		sendMail($addresses,'Maintenance Request Completion.',$details);
	}
	else if ($_GET['action'] == 'listSuppliers') {
		$sql = sprintf("select id,supplier_name from jobcards.suppliers order by supplier_name");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createSuppliers') {
			$sql = sprintf("insert into jobcards.suppliers (supplier_name) values ('%s')",$_POST['supplier_name']);
			$dbo->setQuery($sql);
			$dbo->query();
			$sql = sprintf("select id,supplier_name from jobcards.suppliers a where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
			echo json_encode($return);
		}
	else if ($_GET['action'] == 'deleteSuppliers') {
		$sql = sprintf("delete from jobcards.suppliers where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();

		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateSuppliers') {
		$sql = sprintf("update jobcards.suppliers set supplier_name='%s' where id=%d",$_POST['supplier_name'],$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'checkValidation') {
		$uname = $_GET['uname'];
		$sql = sprintf("select userid from portal.cput_users_cellular where lower(login) = lower('%s')",$uname);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			echo "0";
		} else {
			$result = $dbo->loadResult();
			echo $result;
		}
	}
	else if ($_GET['action'] == 'listUserMaintenance') {
		$sql = sprintf("select a.id, a.capture_date, a.applicant, a.contact_no, a.contact_time, a.email, a.campus, a.building,
		a.roomno,a.job_details, a.creator, c.campus_name, b.build_name, d.status_desc, a.job_status
		from jobcards.jobcards a left outer join structure.buildings b on (a.building=b.build_code)
		left outer join structure.campus c on (a.campus=c.campus_code) left outer join jobcards.jobcard_status d on (a.job_status = d.id)
		where a.applicant=%d order by a.capture_date desc",$_GET['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $count;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createUserMaintenance') {
		$sql = sprintf("insert into jobcards.jobcards (applicant,contact_no,email,campus,building,roomno,job_details,contact_time,creator)
		values (%d,'%s','%s',%d,%d,'%s','%s','%s',%d)",$_POST['applicant'],$_POST['contact_no'],$_POST['email'],$_POST['campus'],
		$_POST['building'],$_POST['roomno'],$_POST['job_details'],$_POST['contact_time'],$_POST['creator']);
			$dbo->setQuery($sql);
			$dbo->query();
			$sql = sprintf("select a.id, a.capture_date, a.applicant, a.contact_no, a.contact_time, a.email, a.campus, a.building,
			a.roomno,a.job_details, a.creator, c.campus_name, b.build_name, d.status_desc, a.job_status
			from jobcards.jobcards a left outer join structure.buildings b on (a.building=b.build_code)
			left outer join structure.campus c on (a.campus=c.campus_code) left outer join jobcards.jobcard_status d on (a.job_status = d.id)
			where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
			echo json_encode($return);
	}
	else if ($_GET['action'] == 'saveRating') {
		$sql = sprintf("update jobcards.jobcards set job_raring=%d,rating_details='%s' where cde='%s'",$_POST['job_rating'],
		$_POST['rating_details'],$_POST['rating_cde']);
		$dbo->setQuery();
		$dbo->query();
		echo "1";
	}
	else if ($_GET['action'] == 'repArtisansList') {
		$sql = sprintf("select a.staffno, concat(b.staff_sname,', ',b.staff_title,' ',b.staff_fname,' [',c.trade_description,']') as fullname
		from jobcards.artisans a left outer join staff.staff b on (a.staffno = b.staff_no)
		left outer join jobcards.artisan_trades c on (a.trade_code = c.id)
		where a.campus=%d",$_GET['cmp']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displayAllCampus') {
		$sql = sprintf("select a.campus_code, a.campus_name from structure.campus a order by a.campus_name");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$data = array();
		foreach($result as $row){
			$data[] = $row->campus_code.";".$row->campus_name;
		}
		echo json_encode($data);
	}


}
exit();