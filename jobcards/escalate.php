<?php
require_once("scripts/system/functions.php");

$con = mysql_connect("localhost","root","") or die("DB Error...");
mysql_select_db("jobcards");

$sql = "select escalation_hours from general_settings where id = 1";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);
$eh = $row->escalation_hours;

$sql = sprintf("select id from jobcards where timestampdiff(HOUR,capture_date,current_timestamp) >= %d and assigned_date is null and job_status = 1",$eh);
$result = mysql_query($sql);
$jobs = array();
while($row = mysql_fetch_object($result)){
	$sql = sprintf("update jobcards set job_status=5,escalated = 1 where id=%d",$row->id);
	$upd = mysql_query($sql);
	$sql = sprintf("insert into jobcard_escalations (jobcard_id) values (%d)",$row->id);
	$esc = mysql_query($sql);
	$jobs[] = $row->id;
}

$sql = "select send_escalation,escalation_email,escalation_email2 from general_settings where id = 1";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);
if ($row->send_escalation == 1){
		$sendTo = array();
		$sendTo[] = $row->escalation_email;
		$sendTo[] = $row->escalation_email2;
		$addresses = serialize($sendTo);
		$details = "Cape Peninsula University of Technology.<br />";
		$details .= "<hr>";
		$details .= "Maintenance Jobcard Request<br />";
		$details .= "Date: ".date('Y-m-d')."<br />";
		$details .= "<hr />";
		$details .= "The following jobcards were not attended to within the escalation period:<br />";
		foreach($job as $key=>$value){
			$details .= "Jobcard# ".$value."<br />";
		}
		$details .= "<hr>";
		$details .= "Please acknowledge jobcards as soon as possible.<br />";
		$details .= "Details of work to be performed:<br />";
		$details .= "<b>".$row->job_details."</b>";
		$sql = sprintf("update jobcards.jobcards set notification_sent=1 where id = %d",$jid);
		$dbo->setQuery($sql);
		$dbo->query();
		sendMail($addresses,'Maintenance Jobcard Escalation',$details);
}

?>
