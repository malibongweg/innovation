<?php

$tns = "(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521))
(CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))";

$con = oci_connect("finc","finc",$tns) or die ("Error connecting to Oracle.");


$fp = file("/var/www/html/scripts/budget_imp/codes.csv");

foreach ($fp as $line){

	list($cc,$acc) = explode(",",$line);
	$sql = sprintf("insert into fgagla (fgaglcc,fgaglacc,fgafiny,fgaactv) values ('%s','%s',2013,'A')",trim($cc),trim($acc));
	//$sql = sprintf("update fgagla set fgaactv = 'A' where fgaglcc = '%s' and fgaglacc = '%s' and fgafiny = 2013",trim($cc),trim($acc));
	$res = oci_parse($con,$sql);
	oci_execute($res);
	echo $sql."\n";

}

?>
