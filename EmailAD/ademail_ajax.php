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
$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass;

$con = oci_connect($uname,$pass,$cs);



if (isset($_GET)) {

    if ($_GET['func'] == 'srch') {
            $data = array();
            $stno = $_GET['uid1'];
            //echo $stno;
            $sql = sprintf("select staffno, login_name, username, email from dirxml.adstaffinfo where staffno = '%s'",$stno);         
            $result = oci_parse($con,$sql);
            oci_execute($result);
            $row = oci_fetch_object($result);
            $data[] =  $row->STAFFNO.";".$row->LOGIN_NAME.";".$row->USERNAME.";".$row->EMAIL;
            
            oci_close($con);
           // $data = array();
            echo json_encode($data);
           
           
    }

    

    //get pin

    if ($_GET['func'] == 'getpass') {
        $data = array();
          $snum = $_GET['stdno'];
          $reques = $_GET['req'];
          
        $sql3 = sprintf("select email from dirxml.adstaffinfo where staffno = %d",$reques );
        $result3 = oci_parse($con,$sql3);
        oci_execute($result3);
        $row3 = oci_fetch_object($result3);
        //echo $row3->EMAIL;
        if($row3->EMAIL){   
        
        $sql2 = sprintf("select username,adpassword from dirxml.adstaffinfo where staffno = %d",$snum );
        $result2 = oci_parse($con,$sql2);
        oci_execute($result2);
        $row2 = oci_fetch_object($result2);
         echo $row2->ADPASSWORD.','.$row2->USERNAME;
           // oci_close($conn);
           // $data = array();
           // echo json_encode($data);
       // echo $passs;
            $sendTo = array();
            $sendTo[] = $row3->EMAIL;
            $addresses = serialize($sendTo);
            $details .= 'This is an automatic generated message from the OPA system. Please do not reply to this message as it is an unattended Mailbox.<br /><br />';
            $details .= 'Below please find your temporal ADS Password.<br /><br />';
            $details .= 'Please NOTE this Password is temporal and you will need to change it to your permanent personal password.<br /><br />';
            $details .= 'Keep you Password safe to prevent unauthorised access of your credentials.<br /><br />';
            $details .= '<br /><br />';
            $details .= 'Your ADS Username is: '.$row2->USERNAME;
            $details .= '<br />'; 
            $details .= 'Your ADS temporal Password is: '.$row2->ADPASSWORD; 
            sendMail($addresses,'ADS Password Request',$details);
             // echo json_encode($data);
           // echo $passs;
        }else {
            echo '0';}
        }
        
   
   if ($_GET['func'] == 'getemail') {    
        $data = array();
        
          $reques = $_GET['reqs'];
          
        $sql3 = sprintf("select email from dirxml.adstaffinfo where staffno = %d",$reques );
        $result3 = oci_parse($con,$sql3);
        oci_execute($result3);
        $row3 = oci_fetch_object($result3);
        $email = $row3->EMAIL;
        
        if($email){
            echo $email;
        } else{echo '0';}
         
            oci_close($con);
           // $data = array();
            //echo json_encode($data);
}
}
exit();
?>