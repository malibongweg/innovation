<?php

$tns = "(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521))
(CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))";

$con = oci_connect("miss","miss",$tns) or die ("Error connecting to Oracle.");

$db = mysql_connect("10.47.2.131","opa_cput","hp9000s") or die ("Error connecting to Mysql.");
mysql_select_db("2013_budget");


$sql = "select upper(costcentre) as costcentre,account,budget from capex_combined";
$res = mysql_query($sql,$db);
while ($row = mysql_fetch_object($res)){
	
	$sql = sprintf("insert into maebud (maeglcc,maeglacc,maednamt,maeapamt,maegldcc,maegldacc,maeprmset) values ('%s','%s',%0.2f,%0.2f,8008,83800,'2013')",$row->costcentre,$row->account,$row->budget,$row->budget);
	$result = oci_parse($con,$sql);
	oci_execute($result);
	echo $sql."\n";

}
?>
