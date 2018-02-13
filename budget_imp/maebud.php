<?php

$tns = "(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.47.10.100)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi03) ))";
//$tns = "(description =  (address = (protocol = tcp)(host = 10.18.4.72)(port = 1521)) (connect_data =  (server = dedicated)   (sid = testi01) ))";

$con = oci_connect("dirxml","dirxml",$tns) or die ("Error connecting to Oracle.");

$db = mysql_connect("localhost","opa_cput","hp9000s") or die ("Error connecting to Mysql.");
mysql_select_db("budget2016");



$sql = "select upper(cc) as cc,acc,amt as amt from sal";
$res = mysql_query($sql,$db);
while ($row = mysql_fetch_object($res)){
	
	$sql = sprintf("insert into miss.maebud (maeglcc,maeglacc,maednamt,maeapamt,maegldcc,maegldacc,maeprmset) values ('%s','%s',%0.0f,%0.0f,8008,83800,'CC16')",$row->cc,$row->acc,$row->amt,$row->amt);
	$result = oci_parse($con,$sql);
	oci_execute($result);
	echo $sql."\n";

}



$sql = "select upper(cc) as cc,acc,amt as amt from cap";
$res = mysql_query($sql,$db);
while ($row = mysql_fetch_object($res)){
	
	$sql = sprintf("insert into miss.maebud (maeglcc,maeglacc,maednamt,maeapamt,maegldcc,maegldacc,maeprmset) values ('%s','%s',%0.0f,%0.0f,8008,83800,'CC16')",$row->cc,$row->acc,$row->amt,$row->amt);
	$result = oci_parse($con,$sql);
	oci_execute($result);
	echo $sql."\n";

}


$sql = "select upper(cc) as cc,acc,amt as amt from inc";
$res = mysql_query($sql,$db);
while ($row = mysql_fetch_object($res)){
	
	$sql = sprintf("insert into miss.maebud (maeglcc,maeglacc,maednamt,maeapamt,maegldcc,maegldacc,maeprmset) values ('%s','%s',%0.0f,%0.0f,8008,83800,'CC16')",$row->cc,$row->acc,$row->amt,$row->amt);
	$result = oci_parse($con,$sql);
	oci_execute($result);
	echo $sql."\n";

}

$sql = "select upper(cc) as cc,acc,amt as amt from exp";
$res = mysql_query($sql,$db);
while ($row = mysql_fetch_object($res)){
	
	$sql = sprintf("insert into miss.maebud (maeglcc,maeglacc,maednamt,maeapamt,maegldcc,maegldacc,maeprmset) values ('%s','%s',%0.0f,%0.0f,8008,83800,'CC16')",$row->cc,$row->acc,$row->amt,$row->amt);
	$result = oci_parse($con,$sql);
	oci_execute($result);
	echo $sql."\n";

}
?>
