<?php
$tns = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.47.10.100)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi03) ))';
$con = oci_connect('dirxml','dirxml',$tns) or die ('ERR');

$stno = $_POST['student_no'];
$idno = $_POST['id_no'];
$fname = $_POST['f_name'];

$data = array();

//check if student exists
$sql1 = sprintf("select count(*) as cnt1, iadstno from stud.iadbio where iadstno = '%s' group by iadstno",$_POST['student_no']);
$result1 = oci_parse($con,$sql1);
oci_execute($result1);
$row1 = oci_fetch_object($result1);
$stno = $row->IADSTNO;

//check if ID/Passport is correct
$sql2 = sprintf("select count(*) as cnt2, iadstno, iadidno, iadpassport from stud.iadbio where iadstno = '%s' and (iadidno='%s' or iadpassport='%s') group by iadstno, iadidno, iadpassport",$_POST['student_no'], strtoupper($_POST['id_no']), strtoupper($_POST['id_no']));
$result2 = oci_parse($con,$sql2);
oci_execute($result2);
$row2 = oci_fetch_object($result2);
$idno2 = $row2->IADSTNO;
$pass_no = strtoupper($row2->IADPASSPORT);

//check if names are correct
$sql3 = sprintf("select count(*) as cnt3, iadstno, substr(iadnames,1, instr(t.iadnames||' ', ' ')-1)||' '||t.iadsurn as fullname from stud.iadbio t where iadstno = '%s' and substr(iadnames,1, instr(t.iadnames||' ', ' ')-1)||' '||t.iadsurn = '%s'
 group by iadstno,substr(iadnames,1, instr(t.iadnames||' ', ' ')-1)||' '||t.iadsurn",$_POST['student_no'], strtoupper($_POST['f_name']));
$result3 = oci_parse($con,$sql3);
oci_execute($result3);
$row3 = oci_fetch_object($result3);
$fname = strtoupper($row->FULLNAME);

//select  PIN where all conditions are true
$sql = sprintf("select count(*) as cnt7, t.iadstno, t.iadidno,t.iadpassport,
    substr(iadnames,1, instr(t.iadnames||' ', ' ')-1)||' '||t.iadsurn AS fullname, t.iadpasswd
     from stud.iadbio t where to_char(iaddate,'YYYY') + (to_char(sysdate,'YYYY') - to_char(iaddate,'YYYY')) = to_char(sysdate,'YYYY')
    and t.iadstno = '%s'
     and (t.iadidno = '%s' or t.iadpassport = '%s')
     and substr(iadnames,1, instr(t.iadnames||' ', ' ')-1)||' '||t.iadsurn = '%s'
     group by t.iadpasswd, t.iadstno,t.iadidno,t.iadpassport,substr(iadnames,1, instr(t.iadnames||' ', ' ')-1)||' '||t.iadsurn", $row1->IADSTNO, $row2->IADIDNO, strtoupper($row2->IADPASSPORT), strtoupper($row3->FULLNAME));

$result = oci_parse($con, $sql);
oci_execute($result);
$row = oci_fetch_object($result);

//echo $sql;
//check if PIN is correct
if($row->CNT7 == 1)
{
    $pin = $row->IADPASSWD;

    //if(strlen($row->IADPASSWD) == '')
   // {
        //call procedure generate pin for student
        //header('Location: http://www.google.com');
       // exit();
        //$pin = $row->IADPASSWD;
   // }
    //echo $pin;
}
else{
    $pin = '4';
    //echo '4';
}

//check  if student exists
if($row1->CNT1 != 1)
{
    $std = '0';
    //echo '0';
}
else{
    $std = '1';
    //echo '1';
}

//Check if correct ID/Passport for student
if($row2->CNT2  != 1)
{
    $id_pass = '0';
    //echo '0';
}
else{
    $id_pass = '1';
    //echo '1';
}

//Check if fullname is correct for student
if($row3->CNT3 != 1)
{
    $name = '0';
    //echo '0';
}
else{
    $name = '1';
    //echo '1';
}

$data[] = $std . ';' . $id_pass . ';' . $name . ';' . $pin;
echo json_encode($data);
//echo $std . ';' . $id_pass . ';' . $name . ';' . $pin;
//exit();
oci_close($con);

?>
