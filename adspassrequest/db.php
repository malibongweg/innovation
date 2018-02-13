<?php
$tns = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))';
$con = oci_connect('stud','stud',$tns) or die ('ERR');

$sql = sprintf("select iadpasswd pindet from iadbio where iadstno = '%s'",$_GET['stdno']);
$result = oci_parse($con,$sql);
oci_execute($result);

$row = oci_fetch_object($result);
$pin = $row->PINDET;

$sql = "BEGIN eapinloggen('".$_GET['op']."',".$_GET['stdno']."); END;";
$result = oci_parse($con,$sql);
oci_execute($result);

echo $pin;
exit();
?>
