<?php
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::init('route','database','config');
Epi::setSetting('exceptions', true);
Epi::setPath('config', dirname(__FILE__));
getConfig()->load('configuration.ini');
$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','portal',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->post('/', 'feeAccount');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function feeAccount() {
		$data = array();
		$rec = getDatabase()->one("select connect_string,user_name,password from portal.cput_system_setup where system_name = 'its'");
		$cs = $rec['connect_string'];
		$uname = $rec['user_name'];
		$pass = $rec['password'];
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			$data['record'][0]['servercode'] = '-1';
			echo json_encode($data);
			exit();
		}
		$sql = "select sum(decode(iamtype,'D',iamamt,-iamamt)) as bfbal from stud.iamlog,finc.fujacd where fujacctyp = iamacctyp and iamstno = ".$_POST['uname']." and  nvl(fujfee,'F') = 'F' and nvl(iamshow,'Y') = 'Y' and to_date(iamdate,'dd-mm-yyyy') <= to_date('01-01-".date('Y')."','dd-mm-yyyy')";
		$result = oci_parse($con,$sql);
		$x = oci_execute($result);
		if (!$x) {
			$data['record']['servercode'] = '-1';
			echo json_encode($data);
			exit(); 
		}
		$row = oci_fetch_object($result);
		if (!$row) {  
			$data['record']['servercode'] = '-1';
			echo json_encode($data);
			exit();
			}
		if (is_null($row->BFBAL)) { $bfBalance = '0.00'; } else {
		$bfBalance =  number_format($row->BFBAL,2,'.',''); }

		$sql = "select to_char(iamdate,'dd-mm-yyyy') as trandate,iamrefno,decode(iamtype,'D',iamamt,-iamamt) as amt, substr(nvl(iamnote,fclname),1,28) as trandesc,'F' as acctype from stud.iamlog,finc.fcltrn,finc.fujacd where iamstno = ".$_POST['uname']." and iamcode = fcltrn(+) and iamacctyp = fujacctyp and nvl(fujfee,'F') = 'F' and nvl(iamshow,'Y') = 'Y' and iamdate >= to_date('01-01-".date('Y')."','dd-mm-yyyy') order by iamdate";

		$result = oci_parse($con,$sql);
		$x = oci_execute($result);
		if (!$x) { $data['record']['servercode'] = '-1';
			echo json_encode($data);
			exit();
		}
		$cnt = 0;
		while ($row = oci_fetch_object($result)) {
			$data['record'][$cnt]['bfbalance'] = $bfBalance;
			$data['record'][$cnt]['transdate'] = $row->TRANDATE;
			$data['record'][$cnt]['refno'] = $row->IAMREFNO;
			$data['record'][$cnt]['desc'] = $row->TRANDESC;
			$data['record'][$cnt]['amount'] = number_format($row->AMT,2,'.','');
			++$cnt;
		}

		echo json_encode($data);


}
?>

