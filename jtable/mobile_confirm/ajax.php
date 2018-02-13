<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

$dbo->setQuery("select connect_string,user_name,password from portal.cput_system_setup where system_name='dirxml'");
$row = $dbo->loadObject();
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;

if (isset($_GET)) {
	if ($_GET['action'] == 'checkPrevious'){
		$return = array();
		$con = oci_connect($oci_user,$oci_pass,$oci_connect_string);
		if (!$con){
			$return['Result'] = 0;
			echo json_encode($return);
			exit();
		}
		//$sql = sprintf("")
	}
	if ($_GET['action'] == 'loadData'){
		$return = array();
		$con = oci_connect($oci_user,$oci_pass,$oci_connect_string);
		if (!$con){
			$e = oci_error();
			$return['Result'] = 'ERR';
			echo json_encode($return);
			exit();
		}
		$sql = sprintf("select stdno,barcode,mobileno from otphashdata where hashdata = '%s'",$_GET['hash']);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$row = oci_fetch_object($result);
		if (strlen($row->STDNO) == 0){
			$return['Result'] = 0;
			echo json_encode($return);
			oci_close($con);
			exit();
		} else {
			$return['Result'] = 1;
			$return['Record'] = $row;
			echo json_encode($return);
			oci_close($con);
			exit();
		}
	}
	if ($_GET['action'] == 'confirmUser'){
		$return = array();
		$con = oci_connect($oci_user,$oci_pass,$oci_connect_string);
		if (!$con){
			$e = oci_error();
			$return['Result'] = 0;
			echo json_encode($return);
			exit();
		}
		$stud = $_POST['stud'];
		$newCell = $_POST['confirm_cell'];
		$bc = $_POST['current_barcode'];
		$sql = sprintf("select count(*) as cnt from otphashdata where stdno=%d and barcode='%s'",$stud,$bc);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$row = oci_fetch_object($result);
		if (intval($row->CNT) == 0){
			$return['Result'] = 1;
			echo json_encode($return);
			exit();
		} else {
			$sql = sprintf("update gen.getadr set getadr1='%s' where GEN.GETADR.GETUNUM=%d and GETADDRTYPE = 'CE'",$newCell,$stud);
			$result = oci_parse($con,$sql);
			oci_execute($result);
			$return['Result'] = 2;
			echo json_encode($return);
			exit();
		}
	}
}
exit();
?>