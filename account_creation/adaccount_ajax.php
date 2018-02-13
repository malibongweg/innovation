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

   if ($_GET['func'] == 'srch') {
    $cs = $oci_connect_string;
    $uname = $oci_user; $pass = $oci_pass; 
    $con = oci_connect($uname,$pass,$cs);
        $pnum = $_GET['uid1'];
        $sql = sprintf("select count(staffno) as cnt from dirxml.staffinfo where staffno = '%s'", $pnum);
        $result = oci_parse($con, $sql);
        oci_execute($result);
        $row = oci_fetch_object($result);
        $cnt = $row->CNT;

        //check if staff member is in the staffinfo view. if so read view and display staff data
        if (intval($cnt) >= 1) {
            $cs = $oci_connect_string;
            $uname = $oci_user; $pass = $oci_pass; 
            $con = oci_connect($uname,$pass,$cs);
            $data = array();
            $stno11 = $_GET['uid1'];
            $sql11 = sprintf("select staffno, login_name, username, email from dirxml.staffinfo where staffno = '%s'",$stno11);
            $result11 = oci_parse($con,$sql11);
            oci_execute($result11);
            $row11 = oci_fetch_object($result11);
            $data[] =  $row11->STAFFNO.";".$row11->LOGIN_NAME.";".$row11->USERNAME.";".$row11->EMAIL;
            oci_close($con);
            echo json_encode($data);
        }

        //if staff number is not in the view it means person is either an exclusion or needs to update hr data. cheack m08 to determine if person is exclusion or hr
        else if (intval($cnt) == 0) {
            $cs = $oci_connect_string;
            $uname = $oci_user; $pass = $oci_pass; 
            $con = oci_connect($uname,$pass,$cs);
            $data = array();
            $stno = $_GET['uid1'];
            $sql2 = sprintf("select count(personnel_number) as cnt from miss.m08v_curr_personnel_detail  where personnel_number = '%s'", $stno);
            $result2 = oci_parse($con, $sql2);
            oci_execute($result2);
            $row2 = oci_fetch_object($result2);
            $cnt2 = $row2->CNT;

            // if person is not in mo8. person must go to hr to update details
            if (intval($cnt2) == 0) {
                echo "0";
                exit();
            }

            //if person is in mo8. person is an exclusion
            if (intval($cnt2) >= 1) {
                $sql3 = sprintf("select COUNT(personnel_number) as cnt from miss.m08v_curr_personnel_detail x
               where x.appointment_names not like '%CLAIM ACAD 25%'
                and x.appointment_names not like '%CLAIM ACAD<24%'
                and x.appointment_names not like '%CLAIM STUD<24%'
                and x.appointment_names not like '%MODERATOR%'
                and x.appointment_names not like '%INVIG<24%'
                and x.appointment_names not like '%CLAIM ADMIN 25%' 
                and personnel_number = '%s'", $stno);
                $result3 = oci_parse($con, $sql3);
                oci_execute($result3);
                $row3 = oci_fetch_object($result3);
                $cnt3 = $row3->CNT;

                //if person is CLAIM ACAD 25 or CLAIM ACAD<24 or CLAIM STUD<24 or MODERATOR or INVIG or CLAIM ADMIN 25. Person is an exclusion
                if (intval($cnt3 == 0)) {
                    $sql5 = sprintf("select COUNT(staffno) as cnt from dirxml.staffxrules  where staffno = '%s'", $stno);
                    $result5 = oci_parse($con, $sql5);
                    oci_execute($result5);
                    $row5 = oci_fetch_object($result5);
                    $cnt5 = $row5->CNT;

                    if (intval($cnt5) == 0) {
                        echo '1';
                          exit();
                    }
                }
            }
        }
    }
    //get pin
 elseif ($_GET['func'] == 'srchEx') {
       $cs = $oci_connect_string;
        $uname = $oci_user; $pass = $oci_pass; 
        $con = oci_connect($uname,$pass,$cs);
        $data = array();
        $snum = $_GET['uid1'];
     
         $sql5 = sprintf("select COUNT(staffno) as cnt from dirxml.staffxrules  where staffno = '%s'", $snum);
                    $result5 = oci_parse($con, $sql5);
                    oci_execute($result5);
                    $row5 = oci_fetch_object($result5);
                    $cnt5 = $row5->CNT;
     if (intval($cnt5 ==0)){
       $sql6 = sprintf("insert into dirxml.staffxrules (staffno) values('%s')",$snum);
                        $result6 = oci_parse($con,$sql6);
                        oci_execute($result6);

                        
                        $sql7 = "BEGIN dirxml.adstaffinfo_inserts; dirxml.adstaffinfo_updates; commit; END;";
                        $result7 = oci_parse($con,$sql7);
                        oci_execute($result7);

                        
                        echo '1';
         
     }
 }
    elseif ($_GET['func'] == 'getpass') {
        $cs = $oci_connect_string;
        $uname = $oci_user; 
        $pass = $oci_pass; 
        $con = oci_connect($uname,$pass,$cs);
        $data = array();
        $snum = $_GET['stdno'];
        $reques = $_GET['req'];

        $sql3 = sprintf("select email from dirxml.staffinfo where staffno = %d", $reques);
        $result3 = oci_parse($con, $sql3);
        oci_execute($result3);
        $row3 = oci_fetch_object($result3);
        //echo $row3->EMAIL;
        if ($row3->EMAIL) {

            $sql2 = sprintf("select username,adpassword from dirxml.staffinfo where staffno = %d", $snum);
            $result2 = oci_parse($con, $sql2);
            oci_execute($result2);
            $row2 = oci_fetch_object($result2);
            //echo $row2->ADPASSWORD . ',' . $row2->USERNAME; here
            // oci_close($conn);
            // $data = array();
            // echo json_encode($data);
            // echo $passs;
            $sendTo = array();
            $sendTo[] = $row3->EMAIL;
            $addresses = serialize($sendTo);
            $details .= 'This is an automatic generated message from the OPA system. Please do not reply to this message as it is an unattended Mailbox.<br /><br />';
            $details .= 'Below please find your temporary ADS Password.<br /><br />';
            $details .= 'Please NOTE this Password is temporary and you will need to change it to your permanent personal password at the labs.<br /><br />';
            $details .= 'Keep you Password safe to prevent unauthorised access of your credentials.<br /><br />';
            $details .= '<br /><br />';
            $details .= 'Your ADS Username is: ' . $row2->USERNAME;
            $details .= '<br />'; 
            $details .= 'Your ADS temporal Password is: ' . $row2->ADPASSWORD;
            sendMail($addresses, 'ADS Password Request', $details);
            // echo json_encode($data);
            // echo $passs;
            echo $row2->ADPASSWORD . ',' . $row2->USERNAME;
        } else {
            echo '0';
        }
    }


    elseif ($_GET['func'] == 'getemail') {
        $cs = $oci_connect_string;
        $uname = $oci_user; $pass = $oci_pass; 
        $con = oci_connect($uname,$pass,$cs);

        $data = array();

        $reques = $_GET['reqs'];

        $sql3 = sprintf("select email from dirxml.adstaffinfo where staffno = %d", $reques);
        $result3 = oci_parse($con, $sql3);
        oci_execute($result3);
        $row3 = oci_fetch_object($result3);
        $email = $row3->EMAIL;

        if ($email) {
            echo $email;
        } else {
            echo '0';
        }

        oci_close($con);
        // $data = array();
        //echo json_encode($data);

    }

    //AD NOT STAFF INFO AJAX

    /*if($_GET['func'] = 'display_campus')
    {
        $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);
        
        $data = array();  
        
        $sql = sprintf("select * from dirxml.campus_v");                      
        $result = oci_parse($con,$sql);
        oci_execute($result);                
        
        $i = 0;
         while ($row = oci_fetch_object($result)) {
            $data[] = $row->PCAMPUSCODE.';'. $row->PCAMPUS;
            $i++;
        }
        
        oci_close($con);
        echo json_encode($data);                   
        exit();
    }*/
    
    elseif ($_GET['func'] == 'add_staff_member') {        
        $cs = $oci_connect_string;
        $uname = $oci_user; $pass = $oci_pass; 
        $con = oci_connect($uname,$pass,$cs);

        $login_name = $_POST['fname'] .' '. $_POST['surname'];
        
        //$email = $_POST['surname'].'@cput.ac.za';

        $camp = $_POST['camp'];
        //echo $camp;
        //exit();
        $sql3 = ("SELECT dirxml.SEQ_ADOTHERINFO.NEXTVAL as SEQ FROM dual");
        //echo $sql3;
        //exit();
        $result3 = oci_parse($con, $sql3);
        oci_execute($result3);
        $row3 = oci_fetch_object($result3);
        $staffno = $row3->SEQ;
       
        $campus_code = "";
            
        if($_POST['camp'] == 'Granger Bay Campus')
        {
            $campus_code = 'GB';
        }
        elseif($_POST['camp'] == 'Tygerberg Hospital')
        {
            $campus_code = 'TG';
        }
        
        elseif($_POST['camp'] == 'Athlone Campus')
        {
            $campus_code = 'AT';
        }         
        elseif($_POST['camp'] == 'Bellville Campus (Main)')
        {
            $campus_code = 'BV';
        }        
        elseif($_POST['camp'] == 'Wellington Campus')
        {
            $campus_code = 'WL';
        }        
          
        elseif($_POST['camp'] == 'Tygerberg:Dental & Radiography')
        {
            $campus_code = 'TG';
        }
        elseif($_POST['camp'] == 'Groote Schuur Hospital')
        {
            $campus_code = 'GT';
        }
        
        elseif($_POST['camp'] == 'Worcester Campus')
        {
            $campus_code = 'WO';
        }
        elseif($_POST['camp'] == 'Bellville Servicepoint Campus')
        {
            $campus_code = 'BL';
        }        
          
        elseif($_POST['camp'] == 'Cape Town Campus (Main)')
        {
            $campus_code = 'CT';
        }
        elseif($_POST['camp'] == 'Mowbray Campus')
        {
            $campus_code = 'MB';
        }
        
        elseif($_POST['camp'] == 'George Campus')
        {
            $campus_code = 'GE';
        }
          elseif($_POST['camp'] == 'Dental Services & Radiography')
        {
            $campus_code = 'TG';
        }
       
        $department = trim($_POST['department'], '"');

        $sql5 = sprintf("select COUNT(1) as cnt from dirxml.adnotstaffinfo where lower(surname) = '%s' and lower(firstname) = '%s'", strtolower($_POST['surname']), strtolower($_POST['fname']));
        $result5 = oci_parse($con, $sql5);
        oci_execute($result5);
        $row5 = oci_fetch_object($result5);
        $cnt5 = $row5->CNT;

        if(intval($cnt5) > 1)
        {
            echo '3';
            exit();
        }
        else {
            $sql = sprintf("insert into dirxml.adnotstaffinfo_bu (LOGIN_NAME,USERNAME,EMAIL,STAFFNO,TITLE,INITIALS,SURNAME,FIRSTNAME,AKA,FACULTY,DEPARTMENT,CELLNO,DESIGNATION,PCAMPUS,PBUILDING,PFLOOR_NUMBER,PROOM_NUMBER,PEXTENTION,UNIFLOW_COSTCENTER,STATUS,PCAMPUSCODE)
                   values ('%s','%s','%s',%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                'null', 'null', 'null', $staffno, $_POST['title'], $_POST['initials'], $_POST['surname'], $_POST['fname'],$_POST['aka'], $_POST['faculty'], $department, $_POST['cellno'], $_POST['designation'],
                $_POST['camp'], $_POST['building'], $_POST['floor_no'], $_POST['room_no'], $_POST['extention'], $_POST['costcenter'], 'null', $campus_code);
            $result = oci_parse($con, $sql);
            //echo $sql;
            //exit();
            oci_execute($result);

            $sql1 = "BEGIN dirxml.adnotstaffinfo_inserts; dirxml.adnotstaffinfo_updates; END;";
            $result1 = oci_parse($con, $sql1);
            oci_execute($result1);

            if ($result) {
                $sql6 = sprintf("select username from dirxml.adnotstaffinfo where STAFFNO = %d", $staffno);
                $result6 = oci_parse($con, $sql6);
                oci_execute($result6);
                $row6 = oci_fetch_object($result6);
                echo $row6->USERNAME .';'. $staffno;

            } else {
                echo '0';
            }
        }

    }
}

exit();
?>