<?php
$scr = "";
$fname = dirname(__FILE__);
$fname = explode("/",$fname);
$cnt = count($fname)-1;
for ($i=0;$i<=$cnt-1;$i++){
	$scr .= $fname[$i]."/";
}
$scr = $scr."config.cfg";
$cfg = trim(file_get_contents($scr));
$cfg = explode("\r\n",$cfg);
$my = mysql_connect($cfg[0],$cfg[1],$cfg[2]);
if (!$my) die("Error reading config file...");
mysql_select_db($cfg[3]);

//Get connection setup//
$sql = "select host,user_name,password,database_name from cput_system_setup where system_name='copies'";
$result = mysql_query($sql,$my);
if (!$result) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population-Notereader Config Error.','%s',now())",mysql_error($my));
	mysql_query($sql);
	die();
}
if (mysql_num_rows($result) == 0) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population-Notereader','%s',now())",mysql_error($my));
	mysql_query($sql,$my);
	die();
}
//Connect to copy system
$row = mysql_fetch_object($result);
$copy = pg_connect("host=".$row->host." dbname=".$row->database_name." user=".$row->user_name." password=".$row->password);
if (!$copy)
{
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population-Notereader','Error connecting to notereader system.',now())");
	mysql_query($sql,$my);
	die();
}


///Get point id
$sql = "select unitid,svalue from notebox where functionid=104";
$presult = pg_query($copy,$sql);
if (!$presult) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population-Notereader','Error getting point id.',now())");
	mysql_query($sql,$my);
	die();
} 

$p = array();
while ($prow = pg_fetch_object($presult)) {
	$p[$prow->unitid] = $prow->svalue;
}


//////////////////////////////////////////////////////////////////////////////////
//GET RESULT SET
$sql = "select a.noteid,a.userref,a.ivalue,a.accountid,a.readerid,a.recstatus from notereader a where a.recstatus = 0";
$result = pg_query($copy,$sql);
if (!$result) { 
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population-Notereader','Error retrieving records.',now())");
	mysql_query($sql,$my);
	die();
}
while ($row = pg_fetch_object($result)) {
			$amt = $row->ivalue;
			settype($amt,"string");
			$len = strlen($amt);
			$cents = substr($amt,$len-2,2);
			$rands = substr($amt,0,$len-2);
			$amt = $rands.".".$cents;
			$amt_int = $row->ivalue;
			$accid = $p[$row->readerid];

		$sql = "select max(cast(receipt_no as signed)) as max from cput_pcounter where rec_type in (224,212,190)";
		$resm = mysql_query($sql);
		$rowm = mysql_fetch_object($resm);
		$rnd = $rowm->max;

		$rnd = $rnd + 1;
	switch ($row->accountid) {
		case "Acc1":
			$sql = sprintf("insert into cput_pcounter (account_no,trans_no,rec_type,amount,status_flag,receipt_no,new_value,aux1,aux2) values ('%s',%d,224,%0.2f,0,'%s','%s','%s','%s')",$row->userref,$rnd,$amt,$rnd,$amt,'RCL  Account ID: '.$row->accountid,'ID: '.$row->readerid);
			$res = mysql_query($sql,$my);
			if (!$res) {
				echo mysql_error($my);
				$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population-Notereader[Acc1]','%s',now())",mysql_error($my));
				mysql_query($sql,$my);
			} else {
				$sql = sprintf("update notereader set recstatus = 1 where noteid = %d",$row->noteid);
                $res = pg_query($copy,$sql);
			}
			break 1;
		case "Acc2":
			$sql = sprintf("insert into cput_pcounter (account_no,trans_no,rec_type,amount,status_flag,receipt_no,new_value,aux1,aux2) values ('%s',%d,212,%0.2f,0,'%s','%s','%s','%s')",$row->userref,$rnd,$amt,$rnd,$amt,'RCL Account ID: '.$row->accountid,'ID: '.$row->readerid);
			$res = mysql_query($sql,$my);
				if (!$res) {
					$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population-Notereader[Acc2]','%s',now())",mysql_error($my));
					mysql_query($sql,$my);
				} else {
					$sql = sprintf("update notereader set recstatus = 1 where noteid = %d",$row->noteid);
					$res = pg_query($copy,$sql);
				}
			break 1;

		default:  break;
	}
}
?>
