<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='mas'");
$row = $dbo->loadObject();
$oci_host = $row->host;
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;
$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass;

$con = oci_connect($uname,$pass,$cs);
		if (!$con){
			$return['count'] = 0;
			echo json_encode($return);
			exit();
		}

if (isset($_GET['action'])) {

	if ($_GET['action'] == 'get_errors'){
	
		$sql = sprintf("select distinct x.iagqual,x.iagot,q.iaidesc,q.iaifac,q.iaidept,g.gaename,d.gacname ,count(*) as cnt 
		from stud.iagenr x, stud.iaiqal q, gen.gaefac g, gen.gacdpt d
		where x.iagcyr = %d and q.iaicyr = x.iagcyr and x.iagqual = q.iaiqual
		and g.gaecode = q.iaifac and q.iaidept = d.gaccode and x.iagstno not in (select y.stdno from adstudinfo y where y.stdno = x.iagstno)
		group by x.iagqual,x.iagot,q.iaidesc,q.iaifac,q.iaidept,g.gaename,d.gacname",date('Y'));
		//$sql = sprintf("select distinct x.iagqual,x.iagot,q.iaidesc, count(*) as cnt from stud.iagenr x, stud.iaiqal q where x.iagcyr = %d and q.iaicyr = x.iagcyr and x.iagqual = q.iaiqual
		//				and x.iagstno not in (select y.stdno from adstudinfo y where y.stdno = x.iagstno)
		//				group by x.iagqual,x.iagot,q.iaidesc",date('Y'));
		//$sql = sprintf("select x.iagqual,x.iagot,count(*) as cnt from stud.iagenr x where x.iagcyr = %d
		//				and x.iagstno not in (select y.stdno from adstudinfo y where y.stdno = x.iagstno)
		//				group by x.iagqual,x.iagot",date('Y'));
		//$sql = "select iagqual,iagot,count(*) as cnt from ad_template_test group by iagqual,iagot";
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$arr = oci_fetch_all($result,$rows,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
		$return = array();
		$return['Count'] = $arr;
		$return['Records'] = $rows;
		echo json_encode($return);
	} else if ($_GET['action'] == 'get_template'){
		$sql = sprintf("select qual,off_type,template from stud.novell_templt where qual = '%s'",$_GET['qual']);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$arr = oci_fetch_all($result,$rows,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
		$return = array();
		$return['Count'] = $arr;
		$return['Records'] = $rows;
		$return['Qualification'] = $_GET['qual'];
		$return['Offering'] = $_GET['ot'];
		echo json_encode($return);
	} else if ($_GET['action'] == 'save_template'){
		$sql = sprintf("select count(*) as cnt from stud.novell_templt where qual = '%s' and off_type = '%s' and template = '%s'"
		,$_POST['qual'],$_POST['ot'],$_POST['tmpl']);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$row = oci_fetch_object($result);
		if ($row->CNT == 0) {
			$sql = sprintf("insert into stud.novell_templt (qual,off_type,template) values ('%s','%s','%s')",$_POST['qual'],$_POST['ot'],$_POST['tmpl']);
			$result = oci_parse($con,$sql);
			oci_execute($result);
		}
		echo $row->CNT;
	} else if ($_GET['action'] == 'proc_pending'){
		$sql = "begin refresh_studs(); end;";
		$result = oci_parse($con,$sql);
		oci_execute($result);
		echo "OK";
	}


}

exit();