<?php
require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");
    $dbo =& JFactory::getDBO();
    $user = & JFactory::getUser();
    $dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='its'");
    $row = $dbo->loadObject();
    $oci_host = $row->host;
    $oci_connect_string = $row->connect_string;
    $oci_user = $row->user_name;
    $oci_pass = $row->password;

if (isset($_GET)) {

	if ($_GET['action'] == "display_staffData") {
               
        $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);

	$data = array();
	$sql = sprintf("select staffno,login_name,username,cardno2 from dirxml.adnotstaffinfo");
                                
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $i = 0;
        while ($row = oci_fetch_object($result)) {
            $data[] = $row->STAFFNO.';'. $row->LOGIN_NAME . ';' . $row->USERNAME. ';' . $row->CARDNO2;          
            ++$i;
        }
        oci_close($con);
        echo json_encode($data);
	} 
	else if ($_GET['action'] == 'list_users') {
		   $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);
	$data = array();
        $id = strtoupper($_GET['id']);
                                
		if ($_GET['scond'] == 'N') {
                    $sql = sprintf("select staffno,login_name,username,cardno2 from dirxml.adnotstaffinfo where staffno = '%s'", $id);
                    //echo $sql;
                   // exit();
                }
                elseif ($_GET['scond'] == 'S') {
                    $sql = sprintf("select staffno,login_name,username,cardno2 from dirxml.adnotstaffinfo where username = '%s'", $id);
                    //echo $sql;
                   // exit();
                }
                elseif ($_GET['scond'] == 'C') {
                    $sql = sprintf("select staffno,login_name,username,cardno2 from dirxml.adnotstaffinfo where cardno2 like '%s%%'", $id);
                    //echo $sql;
                   // exit();
                }
                
                $result = oci_parse($con,$sql);
        oci_execute($result);

        $i = 0;
        while ($row = oci_fetch_object($result)) {
            $data[] = $row->STAFFNO.';'. $row->LOGIN_NAME . ';' . $row->USERNAME. ';' . $row->CARDNO2;          
            ++$i;
        }
        oci_close($con);
        echo json_encode($data);
	}
        else if ($_GET['action'] == "edit_staff") {
            $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);

	$data = array();
	$sql = sprintf("select staffno, title, login_name, username, email, aka, faculty, department, pcampus, pextention,cardno2 from dirxml.adnotstaffinfo where staffno = '%s'", ($_GET['id']));
                                
        $result = oci_parse($con,$sql);
        oci_execute($result);

       $row = oci_fetch_object($result);
       
       oci_close($con);
       
         echo $row->STAFFNO.';'. $row->TITLE . ';' . $row->LOGIN_NAME . ';' . $row->USERNAME . ';' . $row->EMAIL.';'. $row->AKA . ';' . $row->FACULTY . ';' . $row->DEPARTMENT.';'. $row->PCAMPUS . ';' . $row->PEXTENTION. ';' . $row->CARDNO2;          
	}
        else if ($_GET['action'] == "save_edit_staff") {		
                $cs = $oci_connect_string;
                $uname = $oci_user;
                $pass = $oci_pass;
                $con = oci_connect($uname,$pass,$cs);
                $data = array();
                
		$sql = sprintf("update dirxml.adnotstaffinfo set cardno2 = '%s' where staffno='%s'",$_POST['cardno'],$_POST['staff_no']);                                
                
		$result = oci_parse($con,$sql);
                oci_execute($result);
                
                if (!$result) { echo "-1"; } else { echo "1"; }
                
                oci_close($con);               
                
	}
	}
exit();

?>
