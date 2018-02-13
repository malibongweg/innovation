<?php
$con = mysql_connect("localhost","opa_cput","hp9000s") or die ("Error");
mysql_select_db("blackbox");
$unit = $_GET['unit'];
$code = $_GET['code'];
$sql = sprintf("insert into `reset` (device,rtype) values ('%s',%d)",$unit,$code);
$res = mysql_query($sql,$con);
mysql_close($con);
?>
