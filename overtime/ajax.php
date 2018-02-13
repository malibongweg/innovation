<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
require_once ( JPATH_BASE .'/scripts/system/functions.php' );
$dbo = &JFactory::getDBO();
$user = & JFactory::getUser();

if (isset($_GET['action'])) {

	if ($_GET['action'] == "checkForManager") {
		$result = array();
		$sql = sprintf("select a.section_code,staff_no from over_time.managers a left outer join portal.cput_users_cellular b on (a.staff_no=b.userid) where lower(b.login) = lower('%s')",$_GET['uname']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) {
			$sql = sprintf("select a.userid from portal.cput_users_cellular a where lower(a.login) = lower('%s')",$_GET['uname']);
			$dbo->setQuery($sql);
			$dbo->query();
				if ($dbo->getNumRows() == 0){
					$result['Manager'] = -1;
					$result['StaffNumber'] = 0;
					$result['Section'] = 0;
				} else {
					$result['Manager'] = 0;
					$result['StaffNumber'] = $dbo->loadResult();
					$result['Section'] = 0;
				}
		} else {
			$rec = $dbo->loadObject();
			$result['Manager'] = 1;
			$result['StaffNumber'] = $rec->staff_no;
			$result['Section'] = $rec->section_code;
		}
		$sql = sprintf("select a.cutoff_date from over_time.cutoffdates a where a.year = YEAR(current_date) and a.month_code = MONTH(current_date)");
		$dbo->setQuery($sql);
		$cd = $dbo->loadResult();
		$result['Cutoff'] = $cd;
		echo json_encode($result);
	}
	else if ($_GET['action'] == "listForms") {
		$return = array();
		if ($_GET['manager'] == 0) {
			$sql = sprintf("select count(*) as cnt from over_time.authorization a where a.staff_no = %d",$_GET['staffno']);
			$dbo->setQuery($sql);
			$cnt = $dbo->loadResult();
			$sql= sprintf("select a.staff_no,a.form_no as form_img,a.form_no,a.form_date,concat(b.staff_title,' ',b.staff_fname,' ',b.staff_sname) as applicant,stat,stat as stat_img
				from over_time.authorization a left outer join staff.staff b on (a.staff_no=b.staff_no)
				where a.staff_no = %d order by '%s' limit %d,%d",$_GET['staffno'],$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		} else {
			$sql = sprintf("select count(*) as cnt from over_time.authorization a where a.section = %d",$_GET['section']);
			$dbo->setQuery($sql);
			$cnt = $dbo->loadResult();
			$sql= sprintf("select a.staff_no,a.form_no as form_img,a.form_no,a.form_date,concat(b.staff_title,' ',b.staff_fname,' ',b.staff_sname) as applicant,stat,stat as stat_img
				from over_time.authorization a left outer join staff.staff b on (a.staff_no=b.staff_no)
				where a.section = %d order by '%s' limit %d,%d",$_GET['section'],$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		}
			$dbo->setQuery($sql);
			$result = $dbo->loadObjectList();
			$return['TotalRecordCount'] = $cnt;
			$return['Records'] = $result;
			$return['Result'] = "OK";
			echo json_encode($return);
		}
		else if ($_GET['action'] == "listManagers") {
			$return = array();
			//$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname,' ',a.staff_title) as staff from staff.staff a");
			$sql = sprintf("select a.staff_no,a.section_code,concat(b.staff_sname,' ',b.staff_fname) as manager
						from over_time.managers a left outer join staff.staff b on (a.staff_no=b.staff_no) order by b.staff_sname");
			$dbo->setQuery($sql);
			$result = $dbo->loadObjectList();
			$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->manager;
				$return['Options'][$i]['Value'] = $row->staff_no;
				++$i;
			}
			$return['Result'] = 'OK';
			echo json_encode($return);
		}
		else if ($_GET['action'] == "listCampus") {
			$return = array();
			$dbo->setquery("select a.campus_code,a.campus_name from structure.campus a order by a.campus_name");
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
		else if ($_GET['action'] == "listBuildings") {
			$return = array();
			if ($_GET['campus'] == 0) {
				$dbo->setQuery("select a.build_code,a.build_name from structure.buildings a order by a.build_name");
			} else {
				$sql = sprintf("select a.build_code,a.build_name from structure.buildings a where a.campus_code = %d order by a.build_name",$_GET['campus']);
				$dbo->setQuery($sql);
			}
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
		else if ($_GET['action'] == "listFaculties") {
			$return = array();
			$dbo->setQuery("select a.fac_code,a.fac_desc from structure.faculty a where a.fac_active = 'Y' order by a.fac_desc");
			$result = $dbo->loadObjectList();
			$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->fac_desc;
				$return['Options'][$i]['Value'] = $row->fac_code;
				++$i;
			}
			$return['Result'] = 'OK';
			echo json_encode($return);
		}
		else if ($_GET['action'] == "listDepartments") {
			$return = array();
			if ($_GET['faculty'] == 0) {
				$dbo->setQuery("select a.dept_code,a.dept_desc from structure.department a order by a.dept_desc");
			} else {
				$sql = sprintf("select a.dept_code,a.dept_desc from structure.department a where a.fac_code = %d order by a.dept_desc",$_GET['faculty']);
				$dbo->setQuery($sql);
			}
			$result = $dbo->loadObjectList();
			$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->dept_desc;
				$return['Options'][$i]['Value'] = $row->dept_code;
				++$i;
			}
			$return['Result'] = 'OK';
			echo json_encode($return);
		}
		else if ($_GET['action'] == "createForms") {
			$return = array();
			$sql = sprintf("insert into over_time.authorization (form_date,staff_no,manager,stat) values ('%s',%d,%d,'%s')",$_POST['form_date'],$_POST['staff_no'],$_POST['manager'],$_POST['stat']);
			$dbo->setQuery($sql);
			$dbo->query();

			$sql= sprintf("select a.staff_no,a.form_no as form_img,a.form_no,a.form_date,concat(b.staff_title,' ',b.staff_fname,' ',b.staff_sname) as applicant,stat,stat as stat_img
				from over_time.authorization a left outer join staff.staff b on (a.staff_no=b.staff_no)
				where a.form_no = LAST_INSERT_ID()");
			
			$dbo->setQuery($sql);
			$result = $dbo->loadObject();
			$return['Record'] = $result;
			$return['Result'] = "OK";
			echo json_encode($return);
		}
		else if ($_GET['action'] == "editForms") {
			$return = array();
			$return['Email'] = 0;
			$email = '';
				if (($_POST['stat'] == 'A') || ($_POST['stat'] == 'D')){
					if ($_POST['stat'] == 'A') {
						$status = 'accepted';
					} else {
						$status = 'declined';
					}
					$sql = sprintf("select lower(a.login) from portal.cput_users_cellular a where a.userid = %d",$_POST['staff_no']);
					$dbo->setQuery($sql);
					$dbo->query();
					if ($dbo->getNumRows() > 0) {
						$login = $dbo->loadResult();
						$sql = sprintf("select lower(a.email) from portal.cput_users a where lower(a.username) = '%s'",$login);
						$dbo->setQuery($sql);
						$email = $dbo->loadResult();
						$sendTo = array();
						$sendTo[] = $email;
						$addresses = serialize($sendTo);
						$details = 'Your request for overtime authorization has been '.$status.'.<br /><br />';
						$details = $details . 'Authorization ID#: '.$_POST['form_date'].'<br />';
						$details = $details . 'Please consult with your supervisor.<br /><br />Thank you.';
						//sendMail($addresses,'ICTS Overtime authorization request.',$details);
						$return['Email'] = 1;
					}
				}
			$sql= sprintf("update over_time.authorization set staff_no = %d,manager=%d,stat='%s', form_date='%s' where form_no=%d",$_POST['staff_no'],$_POST['manager'],
				$_POST['stat'],$_POST['form_date'],$_POST['form_no']);
			$dbo->setQuery($sql);
			$dbo->query();
			$return['Result'] = 'OK';
			echo json_encode($return);
		}
		else if ($_GET['action'] == "deleteForms") {
			$return = array();
			$sql = sprintf("delete from over_time.authorization where form_no = %d",$_POST['form_no']);
			$dbo->setQuery($sql);
			$result = $dbo->query();
			$return['Result'] = 'OK';
			echo json_encode($return);
		}
		else if ($_GET['action'] == "getEmail") {
			$sql = sprintf("select a.login from portal.cput_users_cellular a where a.userid = %d",$_GET['user']);
			$dbo->setQuery($sql);
			$login = $dbo->loadResult();
			if (strlen($login) == 0) {
				echo "ERR";
			} else {
				$sql = sprintf("select lower(a.email) from cput_users a where a.username = '%s'",$login);
				$dbo->setQuery($sql);
				$email = $dbo->loadResult();
				echo $email;
			}
		}
		else if ($_GET['action'] == "listFormItems") {
		$return = array();
			$sql = sprintf("select count(*) as cnt from over_time.authdate a where a.form_no = %d",$_GET['formno']);
			$dbo->setQuery($sql);
			$cnt = $dbo->loadResult();
			$sql= sprintf("select a.id,a.start_date,a.end_date,a.day_type,a.duties,a.auth_hours
				from over_time.authdate a where a.form_no = %d",$_GET['formno']);
			$dbo->setQuery($sql);
			$result = $dbo->loadObjectList();
			$return['TotalRecordCount'] = $cnt;
			$return['Records'] = $result;
			$return['Result'] = "OK";
			echo json_encode($return);
		}
		else if ($_GET['action'] == "createFormItems") {
			$return = array();
			$sql = sprintf("insert into over_time.authdate (start_date,end_date,day_type,duties,auth_hours,form_no)
				values('%s','%s',%d,'%s',%0.2f,%d)",$_POST['start_date'],$_POST['end_date'],$_POST['day_type'],$_POST['duties'],$_POST['auth_hours'],$_POST['form_number']);
			$dbo->setQuery($sql);
			$dbo->query();

			$sql= sprintf("select a.id,a.start_date,a.end_date,a.day_type,a.duties,a.auth_hours
				from over_time.authdate a where a.id = LAST_INSERT_ID()");
			
			$dbo->setQuery($sql);
			$result = $dbo->loadObject();
			$return['Record'] = $result;
			$return['Result'] = "OK";
			echo json_encode($return);
		}
		else if ($_GET['action'] == "checkStatus") {
			$sql = sprintf("select a.stat from over_time.authorization a where a.form_no = %d",$_GET['id']);
			$dbo->setQuery($sql);
			$dbo->query();
			if ($dbo->getNumRows() == 0){
				echo "0";
			} else {
				$status = $dbo->loadResult();
				if ($status != 'A') {
					echo "0";
				} else {
					echo "1";
				}
			}
		}
		else if ($_GET['action'] == "calcHours") {
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
			$sql = sprintf("select subtime('%s','%s')",$etime,$stime);
			$dbo->setQuery($sql);
			$hours = $dbo->loadResult();
			echo $hours;
		}
		else if ($_GET['action'] == "listClaims") {
		$return = array();
		if ($_GET['manager'] == 0) {
			$sql = sprintf("select count(*) as cnt from over_time.claims a where a.staff_no = %d",$_GET['staffno']);
			$dbo->setQuery($sql);
			$cnt = $dbo->loadResult();
			$sql= sprintf("select a.staff_no,a.form_no as form_img,a.form_no,a.claim_date,concat(b.staff_title,' ',b.staff_fname,' ',b.staff_sname) as applicant, a.auth_no,a.stat
				from over_time.claims a left outer join staff.staff b on (a.staff_no=b.staff_no)
				where a.staff_no = %d order by '%s' limit %d,%d",$_GET['staffno'],$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		} else {
			$sql = sprintf("select count(*) as cnt from over_time.claims a where a.section = %d",$_GET['section']);
			$dbo->setQuery($sql);
			$cnt = $dbo->loadResult();
			$sql= sprintf("select a.staff_no,a.form_no as form_img,a.form_no,a.claim_date,concat(b.staff_title,' ',b.staff_fname,' ',b.staff_sname) as applicant, a.auth_no,a.stat
				from over_time.claims a left outer join staff.staff b on (a.staff_no=b.staff_no)
				where a.section = %d order by '%s' limit %d,%d",$_GET['section'],$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		}
			$dbo->setQuery($sql);
			$result = $dbo->loadObjectList();
			$return['TotalRecordCount'] = $cnt;
			$return['Records'] = $result;
			$return['Result'] = "OK";
			echo json_encode($return);
		}
		else if ($_GET['action'] == "listClaimItems") {
		$return = array();
			$sql = sprintf("select count(*) as cnt from over_time.claimdates a where a.form_no = %d",$_GET['formno']);
			$dbo->setQuery($sql);
			$cnt = $dbo->loadResult();
			$sql= sprintf("select a.id,a.form_no,a.claim_date_item,a.start_time,a.start_time,end_time,upper(dayname(a.claim_date_item)) as day_of_week,a.hours,a.duties
					from over_time.claimdates a where a.form_no = %d",$_GET['formno']);
			$dbo->setQuery($sql);
			$result = $dbo->loadObjectList();
			$return['TotalRecordCount'] = $cnt;
			$return['Records'] = $result;
			$return['Result'] = "OK";
			echo json_encode($return);
		}
		else if ($_GET['action'] == "updateClaimItems") {
			$return = array();
			$sql= sprintf("update over_time.claimdates set claim_date_item = '%s',start_time = '%s',end_time='%s',hours = subtime('%s','%s'),duties = '%s'
				where id = %d",$_POST['claim_date_item'],$_POST['start_time'],$_POST['end_time'],$_POST['end_time'],$_POST['start_time'],$_POST['duties'],$_POST['id']);
			$dbo->setQuery($sql);
			$dbo->query();
			$return['Result'] = 'OK';
			echo json_encode($return);
		}
		else if ($_GET['action'] == "deleteClaimItems") {
			$return = array();
			$sql= sprintf("delete from over_time.claimdates where id = %d",$_POST['id']);
			$dbo->setQuery($sql);
			$dbo->query();
			$return['Result'] = 'OK';
			echo json_encode($return);
		}
		else if ($_GET['action'] == "createClaimItems") {
			$return = array();
			$sql = sprintf("insert into over_time.claimdates (form_no,claim_date_item,start_time,end_time,hours,duties) values (%d,'%s','%s','%s',subtime('%s','%s'),'%s')",
				$_POST['form_number'],$_POST['claim_date_item'],$_POST['start_time'],$_POST['end_time'],$_POST['end_time'],$_POST['start_time'],$_POST['duties']);

			$dbo->setQuery($sql);
			$dbo->query();

			$sql= sprintf("select a.id,a.form_no,a.claim_date_item,a.start_time,a.start_time,end_time,upper(dayname(a.claim_date_item)) as day_of_week,a.hours,a.duties
					from over_time.claimdates a where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$result = $dbo->loadObject();
			$return['Record'] = $result;
			$return['Result'] = "OK";
			echo json_encode($return);
		}
		else if ($_GET['action'] == "freezeClaim") {
			$sql = sprintf("update over_time.claims set stat = 'F' where form_no = %d",$_GET['id']);
			$dbo->setQuery($sql);
			$dbo->query();
			echo "1";
		}

		
}
exit();
?>
