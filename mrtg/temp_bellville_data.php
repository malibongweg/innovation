<?php

$con = mysql_connect("localhost","opa_cput","hp9000s") or die ("Error connecting.");

mysql_select_db("blackbox");

$sql = "SELECT func_getvalues_001('BLV001') as rec";
$result = mysql_query($sql,$con);
$row = mysql_fetch_object($result);

list($temp,$humi,$t) = explode("-",$row->rec);
$t = time();
$ret = rrd_update("/var/www/html/scripts/mrtg/temp_bellville.rrd", "$t:$temp:$humi");

if ($ret) {
	echo "OK";
} else {
	echo "ERROR: ".rrd_error();
}
?>
