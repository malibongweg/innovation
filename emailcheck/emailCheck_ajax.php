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
    
	if ($_GET['action'] == "display_staffQuery") {
               
        $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);

	$data = array();
	$sql = sprintf("select StaffNo, Surname, FirstName, initials, ResignDate from dirxml.staffqry WHERE ROWNUM < 500");
        //$sql = sprintf("select staffno,login_name,username,cardno2 from dirxml.adstaffinfo where staffno ='%s'",'30080976');
                                
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $i = 0;
        while ($row = oci_fetch_object($result)) {
            $data[] = $row->STAFFNO.';'. $row->FIRSTNAME . ';' . $row->SURNAME . ';' . $row->INITIALS . ';' . $row->RESIGNDATE;          
            ++$i;
        }
        oci_close($con);
        echo json_encode($data);
	} 
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////       
	else if ($_GET['action'] == "display_staffAdInfo") {
               
        $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);

	$data = array();
	$sql = sprintf("select StaffNo, FirstName, Surname, initials, pextention, username from dirxml.adstaffinfo WHERE ROWNUM < 500");
        //$sql = sprintf("select staffno,login_name,username,cardno2 from dirxml.adstaffinfo where staffno ='%s'",'30080976');
                                
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $i = 0;
        while ($row = oci_fetch_object($result)) {
            $data[] = $row->STAFFNO.';'. $row->FIRSTNAME . ';' . $row->SURNAME . ';' . $row->INITIALS . ';' . $row->PEXTENTION . ';' . $row->USERNAME;           
            ++$i;
        }
        oci_close($con);
        echo json_encode($data);
	} 
///////////////////////////////////////////Filter Staff Quety////////////////////////////////////////////////////////////////////////////////////////
else if ($_GET['action'] == 'list_staffQuery') {
		   $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);
	$data = array();
        $id = strtoupper($_GET['id']);
                                
		if ($_GET['scond'] == 'N') {
                    $sql = sprintf("select StaffNo, FirstName, Surname, initials, ResignDate from dirxml.staffqry where staffno = '%s'", $id);
                    //echo $sql;
                   // exit();
                }
                elseif ($_GET['scond'] == 'S') {
                    $sql = sprintf("select StaffNo, FirstName, Surname, initials, ResignDate from staffqry where upper(Surname) like '%s%%'", $id);
                    //echo $sql;
                   // exit();
                }
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $i = 0;
        while ($row = oci_fetch_object($result)) {
            $data[] = $row->STAFFNO.';'. $row->FIRSTNAME . ';' . $row->SURNAME . ';' . $row->INITIALS . ';' . $row->RESIGNDATE; ;          
            ++$i;
        }
        oci_close($con);
        echo json_encode($data);
	} 
//////////////////////////////////////////////////////////////////Filter Staff AD/////////////////////////////////////////////////////////////////////////////////        
        else if ($_GET['action'] == 'list_staffAD') {
		   $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);
	$data = array();
        $id = strtoupper($_GET['id']);
                                
		if ($_GET['scond'] == 'N') {
                    $sql = sprintf("select StaffNo, FirstName, Surname, initials, pextention, username from dirxml.adstaffinfo where staffno = '%s'", $id);
                    //echo $sql;
                   // exit();
                }
                elseif ($_GET['scond'] == 'S') {
                    $sql = sprintf("select StaffNo, FirstName, Surname, initials, pextention, username from dirxml.adstaffinfo where upper(Surname) like '%s%%'", $id);
                    //echo $sql;
                   // exit();
                }
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $i = 0;
        while ($row = oci_fetch_object($result)) {
            $data[] = $row->STAFFNO.';'. $row->FIRSTNAME . ';' . $row->SURNAME . ';' . $row->INITIALS . ';' . $row->PEXTENTION . ';' . $row->USERNAME;         
            ++$i;
        }
        oci_close($con);
        echo json_encode($data);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_GET['action'] == "displayRec") {
            $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);

	$data = array();
	$sql = sprintf("select staffno, firstname, surname, pextention, username, department, uniflow_costcenter, pcampus from dirxml.adstaffinfo where staffno = '%s'", ($_GET['id']));
                                
        $result = oci_parse($con,$sql);
        oci_execute($result);

       $row = oci_fetch_object($result);
       
       oci_close($con);
       
         echo $row->STAFFNO.';'. $row->FIRSTNAME . ';' . $row->SURNAME . ';' . $row->PEXTENTION . ';' . $row->USERNAME . ';' . $row->DEPARTMENT . ';' . $row->UNIFLOW_COSTCENTER . ';' . $row->PCAMPUS;            
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else if ($_GET['action'] == 'display_activeStaff') {
        $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);
	$data = array();
        $id = strtoupper($_GET['id']);
                                
		if ($_GET['he'] == "true") 
                    {
                        $sql = sprintf("select t.StaffNo, t.FirstName, t.Surname, t.initials, t.ResignDate from dirxml.staffqry t, dirxml.adstaffinfo y where t.StaffNo = y.staffno and ROWNUM < 500");
		    }
                else {
                    $sql = sprintf("select StaffNo, Surname, FirstName, initials, ResignDate from dirxml.staffqry WHERE ROWNUM < 500");
                }
                                      
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $i = 0;
        while ($row = oci_fetch_object($result)) {
            $data[] = $row->STAFFNO.';'. $row->FIRSTNAME . ';' . $row->SURNAME . ';' . $row->INITIALS . ';' . $row->RESIGNDATE; ;          
            ++$i;
        }
        oci_close($con);
        echo json_encode($data);
	}         
}
exit();
?>

