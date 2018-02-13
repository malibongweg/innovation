<?php
$scr = "";
$fname = dirname(__FILE__);
$proc = $fname . "/bursary.proc";
if (file_exists($proc)) {
echo "Process is running...";
exit();
} else {
$fp = fopen($proc,"w");
fwrite($fp,"Running");
fclose($fp);
}

//MAKE DB CONNECTION
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
if (!$my) { unlink($proc); die(); }
mysql_select_db($cfg[3]);

//Get Oracle connection strings
$sql = "select connect_string,user_name,password from cput_system_setup where system_name='its'";
$result = mysql_query($sql);
if (!$result) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population','%s',now())",mysql_error($my));
	mysql_query($sql,$my);
	unlink($proc);
	die();
}
//$row = mysql_fetch_object($result); echo $row->connect_string;
if (mysql_num_rows($result) == 0) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population','%s',now())",mysql_error($my));
	mysql_query($sql,$my);
	unlink($proc);
	die();
}

$row = mysql_fetch_object($result);
$oci = oci_connect($row->user_name,$row->password,$row->connect_string);
	if (!$oci) {
		$sql = sprintf("insert into cput_cron_log(system_name,msg,update_date) values('%s','%s')",'PCounter Population','Error connecting to Oracle.',now());
		mysql_query($sql,$my);
		unlink($proc);
		die();
	}

//Get todays transaction numbers///
$sql = "select trans_no from cput_pcounter where rec_type not in (11111,11112) and date(trans_date) = date(now())";
$result = mysql_query($sql,$my);
$codes = "(";
while ($row = mysql_fetch_object($result)) {
	$codes .= $row->trans_no.",";
}
if (strlen($codes) > 2) {
	$codes = substr($codes,0,-1);
} else { $codes = "(0"; } 
$codes .= ")";

//2222
$sql = "select iamstno,iamtseq,iamamt,iamtseq from iamlog where iamcode = 2222 and to_date(to_char(iamdate,'DD-MM-YYYY'),'DD-MM-YYYY') = to_date(to_char(sysdate,'DD-MM-YYYY'),'DD-MM-YYYY') and iamtseq not in ".$codes;

	$result = oci_parse($oci,$sql);
	if (!$result)
	{
		$sqle = sprintf("insert into cput_cron_log(system_name,msg,update_date) values('%s','%s',now())",'PCounter Population','Error getting bursary transactions.');
		mysql_query($sqle,$my);
		unlink($proc);
		die();	
	}
	oci_execute($result);
	while ($orow = oci_fetch_object($result)) {
		echo $orow->IAMSTNO."   ".$orow->IAMAMT."\n";
		$sql = sprintf("insert into cput_pcounter (account_no,trans_no,rec_type,amount,status_flag,receipt_no) values ('%s','%s',2222,%0.2f,0,'%s')",
			$orow->IAMSTNO,$orow->IAMTSEQ,$orow->IAMAMT,$orow->IAMTSEQ);
		$result = mysql_query($sql,$my);
		if ($result) {
			$sqlm = sprintf("insert into cput_cron_log(system_name,msg,update_date) values('%s','%s',now())",'Bursary population for 2222','Error getting bursary transactions.');
			mysql_query($sqlm,$my);
		}
	}

//2192
$sql = "select iamstno,iamtseq,iamamt,iamtseq from iamlog where iamcode = 2192 and to_date(to_char(iamdate,'DD-MM-YYYY'),'DD-MM-YYYY') = to_date(to_char(sysdate,'DD-MM-YYYY'),'DD-MM-YYYY') and iamtseq not in ".$codes;

	$result = oci_parse($oci,$sql);
	if (!$result)
	{
		$sqle = sprintf("insert into cput_cron_log(system_name,msg,update_date) values('%s','%s',now())",'PCounter Population','Error getting bursary transactions.');
		mysql_query($sqle,$my);
		unlink($proc);
		die();	
	}
	oci_execute($result);
	while ($orow = oci_fetch_object($result)) {
		echo $orow->IAMSTNO."   ".$orow->IAMAMT."\n";
		$sql = sprintf("insert into cput_pcounter (account_no,trans_no,rec_type,amount,status_flag,receipt_no) values ('%s','%s',2192,%0.2f,0,'%s')",
			$orow->IAMSTNO,$orow->IAMTSEQ,$orow->IAMAMT,$orow->IAMTSEQ);
		$result = mysql_query($sql,$my);
		if ($result) {
			$sqlm = sprintf("insert into cput_cron_log(system_name,msg,update_date) values('%s','%s',now())",'Bursary population for 2222','Error getting bursary transactions.');
			mysql_query($sqlm,$my);
		}
	}


//2191
$sql = "select iamstno,iamtseq,iamamt,iamtseq from iamlog where iamcode = 2191 and to_date(to_char(iamdate,'DD-MM-YYYY'),'DD-MM-YYYY') = to_date(to_char(sysdate,'DD-MM-YYYY'),'DD-MM-YYYY') and iamtseq not in ".$codes;

	$result = oci_parse($oci,$sql);
	if (!$result)
	{
		$sqle = sprintf("insert into cput_cron_log(system_name,msg,update_date) values('%s','%s',now())",'PCounter Population','Error getting bursary transactions.');
		mysql_query($sqle,$my);
		unlink($proc);
		die();	
	}
	oci_execute($result);
	while ($orow = oci_fetch_object($result)) {
		echo $orow->IAMSTNO."   ".$orow->IAMAMT."\n";
		$sql = sprintf("insert into cput_pcounter (account_no,trans_no,rec_type,amount,status_flag,receipt_no) values ('%s','%s',2191,%0.2f,0,'%s')",
			$orow->IAMSTNO,$orow->IAMTSEQ,$orow->IAMAMT,$orow->IAMTSEQ);
		$result = mysql_query($sql,$my);
		if ($result) {
			$sqlc = sprintf("insert into cput_cron_log(system_name,msg,update_date) values('%s','%s',now())",'Bursary population for 2191','Error getting bursary transactions.');
			mysql_query($sqlc,$my);
		}
	}


unlink($proc);
echo "Done!!!\n";
?>
