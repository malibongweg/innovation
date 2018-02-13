<?php

$tns = "(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521))
(CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))";

$con = oci_connect("miss","miss",$tns) or die ("Error connecting to Oracle.");

$db = mysql_connect("10.47.2.131","opa_cput","hp9000s") or die ("Error connecting to Mysql.");
mysql_select_db("budget2014_import");

$fp = fopen('/var/www/html/scripts/budget_imp/details.txt','w') or die ('Error');

$sql = "select distinct MAEGLCC from maebud where MAEPRMSET='2014' and to_number(MAEGLACC) between 30000 and 39999";
$res = oci_parse($con,$sql);
oci_execute($res);
while ($row = oci_fetch_object($res)){
	fwrite($fp,'COST CENTRE: '.$row->MAEGLCC."\n");

		$sql = sprintf("select MAEGLACC,MAEDNAMT from maebud where MAEPRMSET='2014' and to_number(MAEGLACC) between 30000 and 39999
		and MAEGLCC = '%s' order by MAEGLACC",$row->MAEGLCC);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$ctot = 0;
			while ($r = oci_fetch_object($result)) {
				fwrite($fp,"\t".$r->MAEGLACC."\t".number_format($r->MAEDNAMT,2,'.','')."\n");
				$ctot = $ctot + $r->MAEDNAMT;
				echo $r->MAEGLACC."      ".$r->MAEDNAMT."\n";
			}
		fwrite($fp,"COST CENTRE TOTALS: ".$ctot);
		fwrite($fp,"\n\n\n");
}


fclose($fp);
?>
