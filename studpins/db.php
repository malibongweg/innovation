<?php
$tns = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))';
$con = oci_connect('dirxml','dirxml',$tns) or die ('ERR');

$sql = sprintf("select iadidno from stud.iadbio where iadstno = %d",$_POST['student_no']);

$result = oci_parse($con,$sql);
oci_execute($result);

$row = oci_fetch_object($result);
$idno = substr((string) $row->IADIDNO,-8);
$idsubmit = $_POST['pass_no'];
if (intval($idno) == intval($idsubmit)) {
echo 'OK';
} else {
echo 'ERR';
}
?>
