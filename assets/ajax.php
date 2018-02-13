<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();
$user = & JFactory::getUser();

if (isset($_GET['action'])) {

	if ($_GET['action'] == "listForms") {
		$return = array();
		$sql = sprintf("select count(*) as cnt from portal.cput_assets_renewal");
		$dbo->setQuery($sql);
		$cnt = $dbo->loadResult();
			$sql = sprintf("select a.id,a.date_requested,a.requester,a.campus,a.building,a.faculty,a.dept,a.location,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as staffmember,
				c.campus_name,d.fac_desc,e.dept_desc
				from portal.cput_assets_renewal a left outer join staff.staff b on (a.requester = b.staff_no)
				left outer join structure.campus c on (a.campus = c.campus_code)
				left outer join structure.faculty d on (a.faculty = d.fac_code)
				left outer join structure.department e on (a.dept = e.dept_code)
				order by %s limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
			$dbo->setQuery($sql);
			$result = $dbo->loadObjectList();
			$return['TotalRecordCount'] = $cnt;
			$return['Records'] = $result;
			$return['Result'] = "OK";
			echo json_encode($return);
		}
		else if ($_GET['action'] == "listRequesters") {
			$return = array();
			//$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname,' ',a.staff_title) as staff from staff.staff a");
			$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname,' ',a.staff_title) as staff from staff.staff a 
					where date(a.staff_resign) >= current_date order by a.staff_sname,a.staff_fname");
			$result = $dbo->loadObjectList();
			$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->staff;
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
			$sql = sprintf("insert into portal.cput_assets_renewal(date_requested,requester,campus,building,faculty,dept) values ('%s',%d,%d,%d,%d,%d)",
				$_POST['date_requested'],$_POST['requester'],$_POST['campus'],$_POST['building'],$_POST['faculty'],$_POST['dept']);
			$dbo->setQuery($sql);
			$dbo->query();

			$id = $dbo->insertid();
			$sql = sprintf("select a.id,a.date_requested,a.requester,a.campus,a.building,a.faculty,a.dept,a.location,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as staffmember,
				c.campus_name,d.fac_desc,e.dept_desc
				from portal.cput_assets_renewal a left outer join staff.staff b on (a.requester = b.staff_no)
				left outer join structure.campus c on (a.campus = c.campus_code)
				left outer join structure.faculty d on (a.faculty = d.fac_code)
				left outer join structure.department e on (a.dept = e.dept_code)
				where a.id = LAST_INSERT_ID()");
			$dbo->setQuery($sql);
			$result = $dbo->loadObject();
			//$return['TotalRecordCount'] = $cnt;
			$return['Record'] = $result;
			$return['Result'] = "OK";
			echo json_encode($return);
		}
		else if ($_GET['action'] == "deleteForms") {
			$return = array();
			$sql = sprintf("delete from portal.cput_assets_renewal where id = %d",$_POST['id']);
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
}
exit();
?>
