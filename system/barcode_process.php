<?php
//Get Barcode Changes
$sql = "select connect_string,user_name,password from cput_system_setup where system_name='its'";
$result = mysql_query($sql,$my);
if (!$result) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,ipdate_date) values ('PCounter Barcode Population','%s',now())",mysql_error($my));
	mysql_query($sql);
	unlink($proc);
	die();
}
//$row = mysql_fetch_object($result); echo $row->connect_string;
if (mysql_num_rows($result) == 0) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Barcode Population','%s',now())",mysql_error($my));
	mysql_query($sql,$my);
	unlink($proc);
	die();
}

$row = mysql_fetch_object($result);
$oci = oci_connect($row->user_name,$row->password,$row->connect_string);
	if (!$oci) {
		$sql = sprintf("insert into cput_cron_log(system_name,msg,update_date) values('PCounter Barcode Population','Error connecting to Oracle.',now())");
		mysql_query($sql,$my);
		unlink($proc);
		die();
	}

$sql = "select max(cast(receipt_no as signed)) as max from cput_pcounter where rec_type in (11111,11112)";
$resultm = mysql_query($sql,$my);
$rowm = mysql_fetch_object($resultm);
$br = $rowm->max;

$sql = "select stdno,nvl(old_magno,new_magno) old_magno,new_magno,iadsurn||' '||iadnames as usrname from iadbio,barcode_upd where stdno is not null and status = 0 and stdno = iadstno";
$result = oci_parse($oci,$sql);
oci_execute($result);
while ($row = oci_fetch_object($result)) {
	$br = $br + 1;
	$sql = sprintf("insert into cput_pcounter (account_no,trans_no,rec_type,old_value,new_value,aux1,receipt_no,aux2) values ('%s','%s',11111,'%s','%s','PHOTO COPY BARCODE UPDATE','%s','%s')",$row->STDNO,$br,$row->OLD_MAGNO,$row->NEW_MAGNO,$br,$row->USRNAME);
	mysql_query($sql,$my);
	$br = $br + 1;
	$sql = sprintf("insert into cput_pcounter (account_no,trans_no,rec_type,old_value,new_value,aux1,receipt_no,aux2) values ('%s','%s',11112,'%s','%s','MEALS BARCODE UPDATE','%s','%s')",$row->STDNO,$br,$row->OLD_MAGNO,$row->NEW_MAGNO,$br,$row->USRNAME);
	mysql_query($sql,$my);
	$sql = sprintf("update barcode_upd set status=1 where stdno=%d",$row->STDNO);
	$res = oci_parse($oci,$sql);
	oci_execute($res);
}
?>