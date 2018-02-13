<?php
$dbo = &JFactory::getDBO();
require_once('scripts/system/functions.php');

function sendMailSubmit($req_Id, $empNo, $empUname, $leaderName, $app_date, $sys_no, $sys_desc, $details, $urge)
{
	//$sub = "(".strtolower($empUname)."@cput.ac.za) AUTHORIZATION FOR CHANGE REQUEST #".$req_Id;
	$sub = "AUTHORIZATION FOR CHANGE REQUEST #".$req_Id;
	$mailmessage = "<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<style type='text/css'>
<!--
body {
	margin-top: 0px;
}
.style2 { 
	font-weight:bold; font-size:10px; color: #000000;	
}
.style3 { 
	font-size: xx-small;
}
.style4 { 
	color: #355770; font-size: 12px; font-weight: bold;
}
.style5 {
	font-size:12px;
}
a:link { 
	text-decoration: none; 
}
a:visited { 
	text-decoration: none; 
}
a:hover { 
	text-decoration: underline; 
}
a:active { 
	text-decoration: none; 
}
body { 
	font-size: 10px; font-family:  Arial, Verdana, Helvetica, sans-serif 
}
td { 
	font-size: 12px; font-family:  Arial, Verdana, Helvetica, sans-serif 
}
-->
</style></head>
<body>
<table border='0' cellpadding='0' cellspacing='0' align='center' width='550'>

<tr><td>&nbsp;</td></tr>
<tr>
	<td style='border-left:1px solid #CCCCCC; border-bottom:1px solid #CCCCCC; border-top: 1px solid #CCCCCC '>
		<table width='100%' border='0' cellpadding='4' cellspacing='4'>
							
		<tr>
			<td colspan='2'>
				<span class='style4'>
				ONLINE CHANGE MANAGEMENT SYSTEM<br><br>
				>>>> This email was sent on ".date('d-M-Y h:m:s')."
				</span>
			</td>			
		<tr>			
		</table>
		<table>
			<tr>
				<td colspan='2'>	
					A Change Request  was  submitted by <b>".$leaderName."</b> on <b>".$app_date."</b> for system 
					<b>".$sys_no." - ".$sys_desc."</b> with the following description details:
				</td>
			</tr>
			<tr>
			<td width='10%'>	
					&nbsp;
				</td>
				<td ></br>	
					<b>".$details."</b>
				</td>
			</tr>";
	
if(($empNo == "30015995")&&($urge == 1))
{//paul and emergency
	$mailmessage .= "<tr>
				<td colspan='2'></br>
					This is an <u><b>EMERGENCY CHANGE</b></u> and requires your urgent approval.</br>
					<b>NOTE:</b> Once you have approved this request, the change will immediately be put into action.
					</br>
					Please click on the following link to be directed to the Change Management Authorization Page:
				</td>
			</tr>
						<tr>
				<td colspan='2'>	
					<!--a href='http://veratech.ctech.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."'>http://veratech.ctech.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."</a> </br-->
					<!--a href='http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&?empKey=".$empNo."'>http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&?empKey=".$empNo."</a> </br-->
					<a href='http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."'>http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."</a> </br>
				</td>
				</tr>";
}
//else if(($empNo != "30081344")&&($empNo != "30005669"))
else if(($empNo != "30006391")&&($empNo != "30005669")&&($empNo != "30013306"))
{//normal
	$mailmessage .= "<tr>
				<td colspan='2'></br>
					<!--b>NOTE:</b> Approval of the request is subject to the decision made by the Change Control Committee and should only be implemented after the decision has been reached.
					</br-->";
					
	if($urge == 1)
		$mailmessage .=	"This is an <u><b>EMERGENCY CHANGE</b></u> and requires your urgent approval.
		<br>Please click on the following link to be directed to the Change Management Authorization Page:";
	
	if($urge != 1)
		$mailmessage .=	"NOTE:</b> Approval of the request is subject to the decision made by the Change Control Committee and should only be implemented after the decision has been reached.</br>
						The relevant Change Control officers have been notified about it's approval.";
	
	$mailmessage .=	"</td>
			</tr>
						<tr>
				<td colspan='2'>	
					<!--a href='http://veratech.ctech.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."'>http://veratech.ctech.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."</a> </br-->
					<!--a href='http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&?empKey=".$empNo."'>http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&?empKey=".$empNo."</a> </br-->";
	if($urge == 1)
		$mailmessage .=	"<!--a href='http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."'>http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."</a> </br -->";
	
	$mailmessage .=	"</td>
				</tr>";
}		
//else if(($empNo == "30081344")||($empNo == "30005669"))
else if(($empNo == "30006391")||($empNo == "30005669")||($empNo == "30013306"))
{//niall
	$mailmessage .= "<tr>
				<td colspan='2'></br>
					<!--b>NOTE:</b> Approval of the request is subject to the decision made by the Change Control Committee and should only be implemented after the decision has been reached.
					</br-->";
					
	if($urge != 1)
		$mailmessage .=	"Please click on the following link to be directed to the Change Management Authorization Page:";
	
	if($urge == 1)
		$mailmessage .=	"This is an <u><b>EMERGENCY CHANGE</b></u> and the departmental HOD and relevant section heads have been sent an e-mail notification regarding it's approval.";
	
	$mailmessage .=	"</td>
			</tr>
						<tr>
				<td colspan='2'>	
					<!--a href='http://veratech.ctech.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."'>http://veratech.ctech.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."</a> </br-->
					<!--a href='http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&?empKey=".$empNo."'>http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&?empKey=".$empNo."</a> </br-->";
	//if($urge != 1) 
	//commented out because section is redund
		$mailmessage .=	"<a href='http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."'>http://opa.cput.ac.za/Change_Control_Online/viewSubmitted.php?reqID=".$req_Id."&empKey=".$empNo."</a> </br>";
	
	$mailmessage .=	"</td>
				</tr>";
}				
	$mailmessage .= "			
		</table>
	</BODY>
	</HTML>";
	$to[] = $empUname."@cput.ac.za";
	echo $empUname."@cput.ac.za";
	sendMail(serialize($to),$sub,$mailmessage);
}//end of function Konica

$dbo->setQuery("select * from change_control.change_request where request_no = ".$_GET['req']);
$row = $dbo->loadObject();

$dbo->setQuery("select * from change_control.cts_systems where system_no = '".$row->system_no."'");
$sys = $dbo->loadObject();

$requestID = $_GET['req'];
$appDate = $row->application_date;
$systemVal = $row->system_no;
$description = $row->change_desc;
$urgency = $row->urgency_code;
$dbo->setQuery("select system_name from change_control.cts_systems where system_no = '".$systemVal."'");
$aux = $dbo->loadObject();
$systemName = $aux->system_name;
$leader = $row->project_leader;
$dbo->setQuery("select concat(fname,' ',sname) as fullname from change_control.cts_technicians where empno = ".$leader);
$aux = $dbo->loadObject();
$leaderName = $aux->fullname;

$auth = explode(",",$sys->auth);
foreach($auth as $empNo) {
	$dbo->setQuery("select uname from change_control.cts_technicians where empno = ".$empNo);
	$aux = $dbo->loadObject();
	$empEmail = $aux->uname;
	sendMailSubmit($requestID,$empNo,$empEmail,$leaderName,$appDate,$systemVal,$systemName,$description,$urgency);
}
?>