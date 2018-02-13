<?php
$dbo =& JFactory::getDBO();
require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");

$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='pin'");
$row = $dbo->loadObject();
$oci_host = $row->host;
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;
$oci_host_dr = $row->disaster_host;
$oci_connect_dr = $row->disaster_connect_string;
$oci_user_dr = $row->disaster_user_name;
$oci_pass_dr = $row->disaster_password;
$oci_system_mode = intval($row->system_mode);
$oci_log = intval($row->log_only);

if (isset($_GET)) {

	if ($_GET['action'] == 'checkAccount') {
		$data = array();
		//$dbo->setQuery("select connect_string,user_name,password from #__system_setup where system_name = 'mas'");
		//$row = $dbo->loadObjectList();
		//$cs = $row[0]->connect_string;
		//$uname = $row[0]->user_name;
		//$pass = $row[0]->password;
		if ($oci_system_mode == 0){
			$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
		} else {
			$cs = $oci_connect_dr; $uname = $oci_user_dr; $pass = $oci_pass_dr; 
		}
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			$e = oci_error();   // For oci_connect errors do not pass a handle
			echo $e['message'];
			echo "-1";
			exit();
		}
		$sql = sprintf("select staffno from view_ldap_users where lower(loginid) = lower('%s')",$_GET['login']);
		$res = oci_parse($con,$sql);
		oci_execute($res);
		$row = oci_fetch_object($res);
		if (strlen($row->STAFFNO) <= 0){
			echo "0";
		} else {
			echo $row->STAFFNO;
		}
		oci_close($con);
	}
	else if($_GET['function'] == 'checkDBStatus') {
		$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='pin'");
		$row = $dbo->loadObject();
		$oci_host_dr = $row->disaster_host;
		$oci_system_mode = intval($row->system_mode);
		$oci_log = intval($row->log_only);
		echo $oci_system_mode.";".$oci_host_dr.";".$oci_log;
	}
	else if ($_GET['action'] == 'getPin') {
		$data = array();
		//$dbo->setQuery("select connect_string,user_name,password from #__system_setup where system_name = 'mas'");
		//$row = $dbo->loadObjectList();
		//$cs = $row[0]->connect_string;
		//$uname = $row[0]->user_name;
		//$pass = $row[0]->password;
		if ($oci_system_mode == 0){
			$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
		} else {
			$cs = $oci_connect_dr; $uname = $oci_user_dr; $pass = $oci_pass_dr; 
		}
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "-1";
			exit();
		}
		$sql = sprintf("select staffpin from staffpins where staffnum=%d",$_GET['staffno']);
		$res = oci_parse($con,$sql);
		oci_execute($res);
		$row = oci_fetch_object($res);
		if (strlen($row->STAFFPIN) <= 0){
			echo "0";
		} else {
			echo $row->STAFFPIN;
		}
		oci_close($con);
	}
	else if ($_GET['action'] == 'newPin') {
		$data = array();
		//$dbo->setQuery("select connect_string,user_name,password from #__system_setup where system_name = 'mas'");
		//$row = $dbo->loadObjectList();
		//$cs = $row[0]->connect_string;
		//$uname = $row[0]->user_name;
		//$pass = $row[0]->password;
		if ($oci_system_mode == 0){
			$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
		} else {
			$cs = $oci_connect_dr; $uname = $oci_user_dr; $pass = $oci_pass_dr; 
		}
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "-1";
			exit();
		}
		$p1=$_POST['staff_no'];
		$sql = sprintf("BEGIN STAFFPINGEN(%d); END;",$p1);
		$res = oci_parse($con, $sql);
		oci_execute($res) or die("Error");
		oci_close($con);
	}
	else if ($_GET['action'] == 'sendMail') {
		$sendTo = array();
		$sendTo[] = $_POST['user_email'];
		$addresses = serialize($sendTo);
		$details .= 'This is an automatic generated message from the OPA system. Please do not reply to this message as it is an unattended Mailbox.<br /><br />';
		$details .= 'Below please find your pin that you will need to operate the Uniflow system on the newly acquired Canon Multifunctions.<br /><br />';
		$details .= 'Please NOTE this PIN REPLACES all PINS issued previously .....<br /><br />';
		$details .= 'Keep you PIN safe to prevent unauthorised of your credentials ...<br /><br />';
		$details .= '<br /><br />';
		$details .= 'User Name: '.$_POST['user_fname'].'<br />';
		$details .= 'Login: '.$_POST['user_name'].'<br />';
		$details .= 'User Pin: '.$_POST['staff_pin'].'<br />';
		$details .= 'Email: '.$_POST['user_email'].'<br />';
		sendMail($addresses,'Uniflow Pin Request',$details);
	}
	
}

exit();



?>
