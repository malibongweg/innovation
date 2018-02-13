<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
require_once("scripts/system/functions.php");

if (isset($_GET['action'])) {


	if ($_GET['action'] == 'save_bug') {
		$sql = sprintf("insert into #__bug_track (uid,app,details,status,request_type,email) values ('%s','%s','%s',1,%d,'%s')",$_GET['uid'],$_GET['app'],addslashes($_GET['details']),$_GET['btype'],$_GET['email']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$email = array();
		$email[] = $_GET['email'];
		$email = serialize($email);
		$body = "Thank you for your comments on the ".$_GET['app']." application.<br />";
		$body .= "Our software developers will process your request as soon as possible.<br /><br />";
		$body .= "Please do not reply to this email. This is an automatic response.<br />";
		$body .= "Cape Peninsula University of Technology<br />";
		$body .= "CTS Department";
		sendMail($email,"OPA Application Tracker",$body);
		if (!$result) { echo "-1"; } else { echo "1"; }
	}
	else if ($_GET['action'] == 'save_feedback') {
		$sql = sprintf("update #__bug_track set feedback='%s',status = 2 where id=%d",addslashes($_POST['feedback']),$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) { echo "-1"; } else { echo "1"; }
	}
	else if ($_GET['action'] == 'send_mail') {
		$subj = "OPA Application Tracker Feedback";
		$msg = "Dear user...<br /><br />";
		$msg .= "Please find the latest feedback on your application tracker request:<br />";
		$msg .= "Original Request:<br />";
		$msg .= $_POST['details']."<br /><br />";
		$msg .= "Feedback from technicians:<br />";
		$msg .= $_POST['feedback']."<br /><br />";
		$msg .= "For any further enquiries, please contact the CTS Helpdesk @ 6407.<br /><br />";
		$msg .= "Thank you for your valuable input. CTS department.";
		$to = array();
		$to[] = $_POST['email'];
		sendMail(serialize($to),$subj,$msg);
	}

}
exit();

?>

<!--cbud-->