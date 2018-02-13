<html>
<head>
<script type="text/javascript" src="mootools.js"></script>
</head>
<?php
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
if (!$my) die();
mysql_select_db($cfg[3]);

////Get notereader transactions
///Include credit printing
$scr = "";
$fname = dirname(__FILE__);
$fname = explode("/",$fname);
$cnt = count($fname);
for ($i=0;$i<=$cnt-1;$i++){
	$scr .= $fname[$i]."/";
}
$scr = $scr."notereader.php";
require_once($scr);

//////////////////////
//Get Oracle connection strings
$sql = "select connect_string,user_name,password from cput_system_setup where system_name='pcounter'";
$result = mysql_query($sql);
if (!$result) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg) values ('PCounter Population','%s')",mysql_error($my));
	mysql_query($sql);
	die();
}
//$row = mysql_fetch_object($result); echo $row->connect_string;
if (mysql_num_rows($result) == 0) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg) values ('PCounter Population','%s')",mysql_error($my));
	mysql_query($sql);
	die();
}

$row = mysql_fetch_object($result);
$oci = oci_connect($row->user_name,$row->password,$row->connect_string);
	if (!$oci) {
		$sql = sprintf("insert into cput_cron_log(system_name,msg) values('%s','%s')",'PCounter Population','Error connecting to Oracle.');
		mysql_query($sql);
		die();
	}
//Get todays transaction numbers///
$sql = "select trans_no,rec_type from cput_pcounter where date(trans_date) = date(now())";
$result = mysql_query($sql);
$codes = "(";
while ($row = mysql_fetch_object($result)) {
	$codes .= $row->trans_no.",";
}
if (strlen($codes) > 2) {
	$codes = substr($codes,0,-1);
} else { $codes = "(0"; } 
$codes .= ")";
/////////////////////////////////////

//PARSE & EXECUTE SQL
$sql = "select substr(facnote1,1,least(decode(instr(facnote1,' ')-1,0,length(facnote1),-1,length(facnote1),instr(facnote1,' ')-1),9)) stdno,factnum rnum,to_char(faedate,'DD-MM-YYYY') rdate,facrtype rtype,facamount ramount,facrnum receipt_no from facrec,faetrn where to_date(to_char(faedate,'DD-MON-YYYY'),'DD-MM-YYYY') = to_date(to_char(sysdate,'DD-MON-YYYY'),'DD-MM-YYYY') and facrtype in (212,190,28,30,33,272,224,21,37) and facamount between -90000 and 90000 and factnum = faetnum and factnum not in ".$codes;
//$sql = "select substr(facnote1,1,least(decode(instr(facnote1,' ')-1,0,length(facnote1),-1,length(facnote1),instr(facnote1,' ')-1),9)) stdno,factnum rnum,to_char(faedate,'DD-MM-YYYY') rdate,facrtype rtype,facamount ramount,facrnum receipt_no from facrec,faetrn where to_date(to_char(faedate,'DD-MON-YYYY'),'DD-MM-YYYY') = to_date('02-12-2011','dd-mm-yyyy') and facrtype in (212,190,28,30,33,272,224,21,37) and facamount between -90000 and 90000 and factnum = faetnum and factnum not in ".$codes;


	$oresult = oci_parse($oci,$sql);
	if (!$oresult) {
		$sql = "insert into cput_cron_log (system_name,msg) values ('PCounter Population','Error parsing Oracle SQL.')";
		mysql_query($sql);
		die();
	}
		
	$parse = oci_execute($oresult);
	if (!$parse) {
		$sql = "insert into cput_cron_log (system_name,msg) values ('PCounter Population','Error executing Oracle SQL.')";
		mysql_query($sql);
		die();
	}

//echo "exit"; exit();
//GET RESULT SET
while ($row = oci_fetch_object($oresult))
{
	$mysql = sprintf("insert into cput_pcounter (account_no,trans_no,rec_type,amount,receipt_no) values('%s','%s',%d,%0.2f,'%s')",$row->STDNO,$row->RNUM,$row->RTYPE,$row->RAMOUNT,$row->RECEIPT_NO);
	$mresult = mysql_query($mysql);
		if (!$mresult) {
			$sql = sprintf("insert into cput_cron_log (system_name,msg) values ('PCounter Population','%s')",mysql_error($my));
			mysql_query($sql);
		}
}
oci_close($oci);
?>
</html>