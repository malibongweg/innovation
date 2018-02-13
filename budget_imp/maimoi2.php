<?php

$tns = "(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521))
(CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))";

$con = oci_connect("miss","miss",$tns) or die ("Error connecting to Oracle.");

$db = mysql_connect("10.47.2.131","opa_cput","hp9000s") or die ("Error connecting to Mysql.");
mysql_select_db("2013_budget");


$sql = "select upper(costcentre) as costcentre,account,budget from capex_combined";
$res = mysql_query($sql,$db);
while ($row = mysql_fetch_object($res)){
	
	$sql = sprintf("insert into maimoi (maiglcc,maiglacc,maiitemno,maidnamt,maiapamt,maifmc,maiprmset) values ('%s','%s',1,%0.2f,%0.2f,1,'2013')",$row->costcentre,$row->account,$row->budget,$row->budget);
	$result = oci_parse($con,$sql);
	oci_execute($result);
	echo $sql."\n";

}
?>
