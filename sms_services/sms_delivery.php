<?php
$scr = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
$cfg = trim(file_get_contents($scr));
$cfg = explode("\r\n",$cfg);
$my = mysql_connect($cfg[0],$cfg[1],$cfg[2]);
if (!$my) die('Error');
mysql_select_db($cfg[3]);

$status = $_GET['status'];
$ts = $_GET['ts'];
$smsc = $_GET['smsc'];
$from = $_GET['sender'];
$to = $_GET['to'];
$msg = $_GET['msg'];
$id = $_GET['smsID'];

switch ($status) {
	case 1: $status2 = 'Delivered'; case 3: $status2 = 'Delivered'; $sql = sprintf("update cput_smpp status=%d,status2='%s' where id = '%s'",$status,$status2,$id) ; break;
	case 2: $status2 = 'Delivery Failed'; case 3: $status2 = 'Delivered'; $sql = sprintf("update cput_smpp status=%d,status2='%s' where id = '%s'",$status,$status2,$id) ; break;
	case 4: $status2 = 'Pending'; break;
	case 8: $status2 = 'Pending'; $sql = sprintf("insert into cput_smpp (smsc,ts,destination,source,status,status2,msg,id) values ('%s','%s','%s','%s',%d,'%s','%s','%s')",$smsc,$ts,$to,$from,$status,$status2,$msg,$id); break;
	case 16: $status2 = 'Rejected'; case 3: $status2 = 'Delivered'; $sql = sprintf("update cput_smpp status=%d,status2='%s' where id = '%s'",$status,$status2,$id) ; break;
	default: $status2 = 'Unkown'; 
}

	$result = mysql_query($sql,$my);

?>