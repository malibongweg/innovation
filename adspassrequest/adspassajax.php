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
            //oracle
            $cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
            $stno = $_GET['user'];
            $sql = sprintf("select iadstno, iadidno, iadsurn,ibcname, gamname, iadbarcode from stud.iadbio, stud.ibcsch, gen.GAMLNG where iadschoolcode=ibccode and gamcode = iadlang and iadstno like %d",$_GET['user']);
            //$sql = "select iadstno, iadidno, iadsurn,ibcname from stud.iadbio, stud.ibcsch where iadschoolcode=ibccode and iadstno like '$_GET['user']%'";
            $row = $dbo->loadObject();
            $data = array();
            $con = oci_connect($uname,$pass,$cs);
            $result = oci_parse($con,$sql);
            oci_execute($result);
            while ($row = oci_fetch_object($result)) {
               $data[] = $row->IADSTNO.';'.$row->IADSURN.';'.$row->IADIDNO.';'.$row->GAMNAME;
            }
            oci_close($con);
            echo json_encode($data);

            //mysql
            /*$sql = "select id,name,username,email from #__users where lower(username) like '".strtolower($_GET['user'])."%'";
            $dbo->setQuery($sql);
            $result = $dbo->loadObjectList();
            $data = array();
            foreach($result as $row){
                    $data[] = $row->id.";".$row->username.";".$row->name;
            }
            echo json_encode($data);*/
    }

    else if ($_GET['func'] == 'info') {
        $tns = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))';
        $conn = oci_connect('dirxml','dirxml',$tns) or die ('ERR');
    
        $sql2 = sprintf("select password from dirxml.adstudinfo where stdno=%d",$_GET['uid']);
        $result2 = oci_parse($conn,$sql2);
        oci_execute($result2);
        $row2 = oci_fetch_object($result2);
        $passs = $row2->PASSWORD;
            
        $cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 

        //$sql = sprintf("select iadstno, iadidno, iadnames, iadsurn,ibcname, gamname, iadbarcode from stud.iadbio, stud.ibcsch, gen.GAMLNG where iadschoolcode=ibccode and gamcode = iadlang and iadstno=%d", $_GET['uid']);
        $sql = sprintf("select iadstno, iadidno, iadpassport, iadnames, iadsurn, iadbarcode from stud.iadbio where iadstno=%d", $_GET['uid']);
        $data = array();
  
        $con = oci_connect($uname,$pass,$cs);
        
        $result = oci_parse($con,$sql);
        oci_execute($result);
        
                 $sql1 = sprintf("select distinct 
                        stud.get_address(iadstno, 'CE',1,'I','S') cell_telephone, stud.get_address(iadstno, 'CT',1,'I','S') cell_telephone2
                        from stud.iadbio, stud.iagenr
                        where iadstno = '%s'
                        and iadstno = iagstno
                        and iagenr.IAGCANCDATE is null
                        and iagprimary = 'Y'",$_GET['uid']);

        $result1 = oci_parse($con,$sql1);
        oci_execute($result1);
        $row1 = oci_fetch_object($result1);                
                
        $cellno = $row1->CELL_TELEPHONE;
        $cellno2 = $row1->CELL_TELEPHONE2;
        
        $sq = sprintf("select iadstno, ibcname from stud.iadbio, stud.ibcsch where iadschoolcode=ibccode and iadstno=%d", $_GET['uid']);
        $res = oci_parse($con,$sq);
        oci_execute($res);
        $ro = oci_fetch_object($res);  
        $school = $ro->IBCNAME;
       
        if($cellno !="" and $cellno2=="")
        {
            $cellno = $row1->CELL_TELEPHONE;
        }
        else if($cellno == "" and $cellno2 !="")
        {
            $cellno = $row1->CELL_TELEPHONE2;
        }
        else if($cellno !="" and $cellno2!="")
        {
            $cellno = $row1->CELL_TELEPHONE;
        }
        else
        {
            $cellno = $row1->CELL_TELEPHONE;
        }
        
        while ($row = oci_fetch_object($result)) {
         if($row->IADIDNO){
             $data = $row->IADSTNO.";".$row->IADNAMES.";".$row->IADSURN.";".$row->IADIDNO.";".$school.';'.$row->GAMNAME.';'.$row->IADBARCODE.";".$passs.';'.$cellno;
            }
            else{
                $data = $row->IADSTNO.";".$row->IADNAMES.";".$row->IADSURN.";".$row->IADPASSPORT.";".$school.';'.$row->GAMNAME.';'.$row->IADBARCODE.";".$passs.';'.$cellno;
            }
        }
               
        oci_close($con);
        echo json_encode($data);
    }


    //get pin

    if ($_GET['func'] == 'getpass') {
       /* $tns = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))';
        $con = oci_connect('stud','stud',$tns) or die ('ERR');

        $sql = sprintf("select iadpasswd pindet from iadbio where iadstno = '%s'",$_GET['stdno']);
        $result = oci_parse($con,$sql);
        oci_execute($result);

        $row = oci_fetch_object($result);
        $pin = $row->PINDET;

        $sql = "BEGIN eapinloggen('".$_GET['op']."',".$_GET['stdno']."); END;";
        $result = oci_parse($con,$sql);
        oci_execute($result);

        echo $pin;*/
        
        $tns = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))';
        $conn = oci_connect('dirxml','dirxml',$tns) or die ('ERR');
    
        $sql2 = sprintf("select password from dirxml.adstudinfo where stdno=%d",$_GET['stdno']);
        $result2 = oci_parse($conn,$sql2);
        oci_execute($result2);
        $row2 = oci_fetch_object($result2);
        $passs = $row2->PASSWORD;
        $cellno = $_GET['cell'];
        //echo $passs;
        
        $con11=mysql_connect("10.47.2.131","root","hp9000s");
        mysql_select_db("portal");
        // Check connection
        if (mysqli_connect_errno()) {
          echo "Failed to connect to database: " . mysqli_connect_error();
        }
        else{
        $result11 = mysql_query("select system_status from cput_sms_settings where id=1");
        $row11 = mysql_fetch_object($result11);    
        if ($row11->system_status == 0) { echo "0"; exit; }
        //$p = (string)$msg;
        //$message = 'your pin is '.$p;    
        //$sql12 = mysql_query("insert into cput_sms_log (username,to_cell,to_message,delivery_status,charge_amount) values('dywibibam','$cellno',CONCAT('Your Temporal ADS Password is ','$passs'),1,0.25)");       
        $sql12 = mysql_query("insert into cput_sms_log (username,to_cell,to_message,delivery_status,charge_amount) values('dywibibam','$cellno',CONCAT('Your temporary ADS Password is ','$passs'),1,0.25)");

        if (!$sql12) {
            echo "0";
            
        } 
        else {
            /*$sendTo = array();
            //$user_email = $_GET['stdno'].'@cput.ac.za';
            $user_email = 'dywibibam@cput.ac.za';
            $sendTo[] = $user_email;
            $addresses = serialize($sendTo);
            $details = 'Your password is ' . $passs;
            sendMail($addresses,'ADS Password Request',$details);*/
           /*$to = array();
            $to[] = 'dywibibam@cput.ac.za';
            $to = serialize($to);            
            $msg .= "Your ADS Password is: " . $passs;
            sendMail($to,'ADS Password Request',$msg);
            echo $passs;*/
            $sendTo = array();
            $sendTo[] = $_GET['stdno'] . '@mycput.ac.za';
            //$sendTo[] = '207128154@mycput.ac.za';
            $addresses = serialize($sendTo);
            $details .= 'Dear Student,<br /><br />';
            $details .= 'This is an automatic generated message from the OPA system. Please do not reply to this message as it is an unattended Mailbox.<br /><br />';
            $details .= 'Below please find your temporal ADS Password.<br /><br />';
            $details .= 'Please NOTE this Password is temporary and you will need to change it to your permanent personal password at the labs.<br /><br />';
            $details .= 'Keep your Password safe to prevent unauthorised access of your credentials.<br /><br />';            
            $details .= '<b>Your ADS temporary Password is:</b> '.$passs;  
            $details .= '<br/><br/>Regards,<br />CTS Department';         
            sendMail($addresses,'ADS Password Request',$details);
            echo $passs;
        }
        
        /*$sendTo = array();
        //$user_email = $_GET['stdno'].'@cput.ac.za';
        $user_email = 'dywibibam@cput.ac.za';
        $sendTo[] = $user_email;
        $addresses = serialize($sendTo);
        $details .= 'Your password is ' . $passs;
        sendMail($addresses,'ADS Password Request',$details);*/
        /*$to = "malibongwe@gmail.com";
        $subject = "My Password";
        $txt = "Your password is !";        
        mail($to,$subject,$txt);
        mail('malibongwe@gmail.com', 'My Subject', 'test');*/
       // echo $passs;
        }
        
    }
}
exit();
?>
