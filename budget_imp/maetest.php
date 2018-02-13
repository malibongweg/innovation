<?php

$tns = "(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521))
(CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))";

$con = oci_connect("miss","miss",$tns) or die ("Error connecting to Oracle.");

$db = mysql_connect("10.47.2.131","opa_cput","hp9000s") or die ("Error connecting to Mysql.");
mysql_select_db("budget2014_import");


$sql = "select upper(cc) as cc,acc,amt from capex";
$res = mysql_query($sql,$db);
while ($row = mysql_fetch_object($res)){
	
	$sql = sprintf("insert into maebud (maeglcc,maeglacc,maednamt,maeapamt,maegldcc,maegldacc,maeprmset) values ('%s','%s',%0.0f,%0.0f,8008,83800,'2014')",$row->cc,$row->acc,$row->amt,$row->amt);
	echo $sql."\n";
	$result = oci_parse($con,$sql);
	oci_execute($result);

}
?>