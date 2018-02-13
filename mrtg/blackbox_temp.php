<?php
$con = mysql_connect("localhost","opa_cput","hp9000s") or die ("Error");
mysql_select_db("blackbox");
$temp = $_GET['temp'];
$humi = $_GET['humi'];
$thres = $_GET['threshold'];
$unit = $_GET['unit'];
$sql = sprintf("insert into temp (device,temp,humi,thres) values ('%s',%0.2f,%0.2f,%0.2f)",$unit,floatval($temp),floatval($humi),floatval($thres));
$res = mysql_query($sql,$con);
mysql_close($con);
?>
