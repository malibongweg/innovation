<?php
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='photos'");
$row = $dbo->loadObject();
$oci_host = $row->host;
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;

if (isset($_GET)) {

    if ($_GET['func'] == 'srch') {
            $cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
            $stno = $_GET['user'];
            $sql = sprintf("select  distinct iadstno, iadidno,iadpassport, iadnames, iadsurn,ibcname, gamname, r.iaidesc, iadpostaddr2, iadaccountname
from stud.iadbio, stud.ibcsch, gen.GAMLNG,  stud.iaiqal r, stud.ieraad b
where iadschoolcode=ibccode
  and gamcode = iadlang
  and r.iaiqual = b.ierqual
  and b.ierchoice = 1
  and b.ierstno = iadstno
  and b.iercyr = 2015
  and iadstno like %d",$_GET['user']);
            $row = $dbo->loadObject();
            $data = array();
            $con = oci_connect($uname,$pass,$cs);
            $result = oci_parse($con,$sql);
            oci_execute($result);
            while ($row = oci_fetch_object($result)) {
               $data[] = $row->IADSTNO.';'.$row->IADSURN.';'.$row->IADIDNO.';'.$row->GAMNAME.';'.$row->iaidesc;
            }
            oci_close($con);
            echo json_encode($data);
    }

    /*else if ($_GET['func'] == 'info') {
        $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);
        $data = array();
        $sql = sprintf("select iadstno, iadidno, iadnames, iadsurn,ibcname, gamname from stud.iadbio, stud.ibcsch, gen.GAMLNG where iadschoolcode=ibccode and gamcode = iadlang and iadstno=%d", $_GET['uid']);
        $result = oci_parse($con,$sql);
        oci_execute($result);
        while ($row = oci_fetch_object($result)) {
             $data = $row->IADSTNO.";".$row->IADNAMES.";".$row->IADSURN.";".$row->IADIDNO.";".$row->IBCNAME.';'.$row->GAMNAME;
        }        
        oci_close($con);
        echo json_encode($data);
    }*/

    else if ($_GET['func'] == 'info') {
        $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);
        $data = array();
        /*$sql = sprintf("select  distinct iadstno, iadidno,iadpassport, iadnames, iadsurn,ibcname, gamname, r.iaidesc, iadpostaddr1,iadpostaddr2,iadpostaddr3, iadaccountname
from stud.iadbio, stud.ibcsch, gen.GAMLNG,  stud.iaiqal r, stud.ieraad b
where iadschoolcode=ibccode
  and gamcode = iadlang
  and r.iaiqual = b.ierqual
  and b.ierchoice = 1
  and b.ierstno = iadstno
  and b.iercyr = 2015
  and iadstno like %d", $_GET['uid']);*/
//and ((b.iercyr = to_char(sysdate, 'YYYY')) or (b.iercyr = to_char(sysdate, 'YYYY') + 1))
        $sql = sprintf("select  distinct iadstno, iadidno,iadpassport, iadnames, iadsurn,ibcname, gamname, r.iaidesc, iadpostaddr1,iadpostaddr2, iadpostaddr3, iadaccountname
from stud.iadbio
  FULL OUTER JOIN stud.ibcsch on iadschoolcode=ibccode
  FULL OUTER JOIN  gen.GAMLNG on gamcode = iadlang
  INNER JOIN stud.ieraad b on b.ierstno = iadstno
  INNER JOIN stud.iaiqal r on r.iaiqual = b.ierqual
  and b.ierchoice = 1
  and to_char(iaddate,'YYYY') + (to_char(sysdate,'YYYY') - to_char(iaddate,'YYYY')) = to_char(sysdate,'YYYY')
 and iadstno like %d", $_GET['uid']);

        $result = oci_parse($con,$sql);
        oci_execute($result);

        while ($row = oci_fetch_object($result)) {
            if((strlen($row->IADIDNO) <= 0)) // or ($row->IADIDNO = null) or ($row->IADIDNO = ""))
            {
                $idno = $row->IADIDNO . " " .'/'. " " . $row->IADPASSPORT;
            }
            else {
                $idno = $row->IADIDNO . " " .'/'. " " . $row->IADPASSPORT;
            }
            $data = $row->IADSTNO.";".$row->IADNAMES.";".$row->IADSURN.";".$idno.";".$row->IBCNAME.';'.$row->GAMNAME.';'.$row->IAIDESC.';'.$row->IADPOSTADDR1.';'.$row->IADPOSTADDR2.';'.$row->IADPOSTADDR3.';'.$row->IADACCOUNTNAME;
        }
        oci_close($con);
        echo json_encode($data);
    }

    //get pin

    if ($_GET['func'] == 'getpin') {
        $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);

        $sql = sprintf("select iadpasswd pindet from stud.iadbio where iadstno = '%s'",$_GET['stdno']);
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $row = oci_fetch_object($result);
        $pin = $row->PINDET;

        $sql = "BEGIN stud.eapinloggen('".$_GET['op']."',".$_GET['stdno']."); END;";
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $sql = sprintf("select iadpasswd pindet from stud.iadbio where iadstno = '%s'",$_GET['stdno']);
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $row = oci_fetch_object($result);
        $pin = $row->PINDET;

        echo $pin;
    }
}
exit();
?>
