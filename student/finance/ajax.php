<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',1);

if (isset($_GET['action'])) {

	if ($_GET['action'] == 'fee_account') {
		$dbo->setQuery("select connect_string,user_name,password from #__system_setup where system_name = 'its'");
		$row = $dbo->loadObjectList();
		$cs = $row[0]->connect_string;
		$uname = $row[0]->user_name;
		$pass = $row[0]->password;
		$data = array();
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			  echo "99999";
			exit();
		}
		$sql = "select sum(decode(iamtype,'D',iamamt,-iamamt)) as bfbal from stud.iamlog,finc.fujacd where fujacctyp = iamacctyp and iamstno = ".$_GET['uid']." and  nvl(fujfee,'F') = 'F' and nvl(iamshow,'Y') = 'Y' and iamdate <= to_date('01-01-".date('Y')."','dd-mm-yyyy')";
		$result = oci_parse($con,$sql);
		$x = oci_execute($result);
		//if (!$x) { $e = oci_error($x); echo $e['message']."Error1"; exit(); }
		$row = oci_fetch_object($result);
		if (!$row) {  echo "99999"; exit();	}
		$data[] = $row->BFBAL;
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'transactions') {
		$dbo->setQuery("select connect_string,user_name,password from #__system_setup where system_name = 'its'");
		$row = $dbo->loadObjectList();
		$cs = $row[0]->connect_string;
		$uname = $row[0]->user_name;
		$pass = $row[0]->password;
		$data = array();
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "99999";
			exit();
		}
		$sql = "select to_char(iamdate,'dd-mon-yyyy') as trandate,iamrefno,decode(iamtype,'D',iamamt,-iamamt) as amt, substr(nvl(iamnote,fclname),1,28) as trandesc,'F' as acctype from stud.iamlog,finc.fcltrn,finc.fujacd where iamstno = ".$_GET['uid']." and iamcode = fcltrn(+) and iamacctyp = fujacctyp and nvl(fujfee,'F') = 'F' and nvl(iamshow,'Y') = 'Y' and iamdate >= to_date('01-01-".date('Y')."','dd-mm-yyyy') order by iamdate";
		//echo $sql; exit();
		$result = oci_parse($con,$sql);
		$x = oci_execute($result);
		if (!$x) { $e = oci_error($result); echo $e['message']; exit(); }
		while ($row = oci_fetch_object($result)) {
			$data[] = $row->TRANDATE."*".$row->IAMREFNO."*".$row->TRANDESC."*".$row->AMT;
		}
		echo json_encode($data);
	}

}
exit();

?>