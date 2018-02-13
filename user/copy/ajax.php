<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
if (isset($_GET)) {

	if ($_GET['action'] == "display_data") {
		$data = array();
		$pt = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
		$fp = file($pt);
		$ldap = $fp[4];
		//$x = '212083279';
		$cnt = preg_match('/^[0-9]+$/',$_GET['id']);
		if ($cnt >= 1) {
			$username = $_GET['id']; 
		} else {
			$sql = sprintf("select userid from cput_users_cellular where login='%s'",$_GET['id']);
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			$username = $row->userid;
		}
		//$username = '210131470';
		$dbo->setQuery("select database_name,user_name,password,host from #__system_setup where system_name = 'copies'");
		$row = $dbo->loadObject();
		$host = $row->host;
		$uname = $row->user_name;
		$pass = $row->password;
		$dbname = $row->database_name;
		//$cstr = "host=".$host." dbname=".$dbname." user=".$uname." password=".$pass;
		$con = pg_connect("host=".$host." dbname=".$dbname." user=".$uname." password=".$pass);
		if (!$con) { $data[] = "-1"; echo json_encode($data); exit(); }
					$sql = sprintf("select getstatus('%s') as result",$username);
					$res = pg_query($con,$sql);
					if (!$res) { $data[] = "-2"; echo json_encode($data); exit(); }
					$row = pg_fetch_object($res);
					//echo $row->result;exit();
					if ($row->result == -1) { $data[] = "-2"; echo json_encode($data); exit(); }
				$sql = sprintf("select to_char(log.logdate,'YYYY-MM-DD HH:MI') as logdate,log.ivalue,log.svalue,points.pointname from log,points where log.pointid=points.pointid and log.userid='%s' and to_number(to_char(log.logdate,'YYYY'),'9999') = to_number(to_char(now(),'YYYY'),'9999') and to_number(to_char(log.logdate,'mm'),'99') = %d order by log.logdate desc",$username,$_GET['mth']);
				//$data[] = $sql; echo json_encode($data);exit();
				$result = pg_query($con,$sql);
					if (!$result) { $data[] = "-1"; echo json_encode($data); exit(); }
				if (pg_num_rows($result) == 0) { $data[] = "-3"; echo json_encode($data); exit(); }
				while($row = pg_fetch_object($result)){
					$data[] = $row->logdate.";"."R".number_format(($row->ivalue/100),2).";".$row->svalue.";".$row->pointname;
				}
				echo json_encode($data);
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