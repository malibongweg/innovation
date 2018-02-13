<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

$con = mysql_connect("localhost","sms","hp9000s") or die("Error...");
mysql_select_db("dlr");

$session = 1;//$_GET['session'];
$uid = 43;//$_GET['uid'];
$time_sent = $_GET['ts'];
$originator = "";//$_GET['originator'];
$recipient = $_GET['recipient'];
$stat_int = $_GET['stat_int'];
if (intval($stat_int) == 8) { $stat_char = "DELIVERED"; }
else if (intval($stat_int) == 1) { $stat_char = "SENT"; }
$msg = $_GET['msg'];

$sql = sprintf("insert into dlr (session,uid,time_sent,originator,destination,`status`,status_values,msg) values ('%s',%d,'%s','%s','%s',%d,'%s','%s')",
$session,$uid,$time_sent,urldecode($originator),urldecode($recipient),$stat_int,$stat_char,urldecode($msg));
$fp = fopen("/var/www/html/scripts/sms_services/sql.txt","a");
fwrite($fp,$sql);
fclose($fp);
mysql_query($sql);

mysql_close($con);
