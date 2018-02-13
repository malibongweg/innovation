<?php
/*require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");
    $dbo =& JFactory::getDBO();
    $user = & JFactory::getUser();
    $dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='its'");
    $row = $dbo->loadObject();
    $oci_host = $row->host;
    $oci_connect_string = $row->connect_string;
    $oci_user = $row->user_name;
    $oci_pass = $row->password;*/

require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");
require(dirname(__FILE__)."/class.phpmailer.php");
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='its'");
$row = $dbo->loadObject();
$oci_host = $row->host;
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;

if (isset($_GET)) {
    
   if ($_GET['action'] == 'insert_request') {
    
    
                $sql = sprintf("insert into vm_db.vmrequestlog (staffno, username, staffname, faculty, extention, operator, motivation, backuprequirement, retentionperiod, numcopies, requestdate, cpu, ram, harddisk, vnic, os, location, prodtest, status)"
                        . "values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s','%s', '%s', '%s', '%s', '%s', '%s', '%s','%s')",
                        $_POST['p_num'],$_POST['u_name'], $_POST['p_name'], $_POST['f_name'], $_POST['exten'], $_POST['admin'],$_POST['motivation'],$_POST['back_req'], $_POST['ret_period'],$_POST['num_copies'],date("Y/m/d"),$_POST['vm_cpu'],$_POST['ram'],
                        //$_POST['p_num'],$_POST['u_name'], $_POST['p_name'], $_POST['f_name'], $_POST['exten'], $_POST['admin'],$_POST['motivation'],$_POST['back_req'], $_POST['ret_period'],$_POST['num_copies'],date("Y/m/d") .' '. date("h:i:sa"),$_POST['vm_cpu'],$_POST['ram'],
                        $_POST['hard_disk'], $_POST['vm_nic'], $_POST['op_system'], $_POST['location'], $_POST['prod_test'], 'Submitted');
    //               echo $sql;
                $dbo->setQuery($sql);
                $result = $dbo->query();
                //echo $sql;
                if($result)
                {
                    $sql = sprintf("select seqno, staffno, username from vm_db.vmrequestlog where staffno='%s' order by seqno desc limit 1", $_POST['p_num']);
                 
                    $dbo->setQuery($sql);
                    $row = $dbo->loadObject();
        
                    //echo $row->seqno;       

                    $bodytext = 'Hi guys,<br/><br/> A request has been made for VM by: ' .$row->username . ' with ID= ' . $row->seqno  . '.<br/><br/> Please login to OPA and search for ID to see and respond to request.<br/><br/>From,<br/>OPA';
                    $email = new PHPMailer();
                    $email->isHTML(); 
                    $email->From      = 'opa@cput.ac.za';
                    $email->FromName  = 'Online Personal Access';
                    $email->Subject   = 'VM Change Request Application';
                    $email->Body      = $bodytext;
                    $email->AddAddress('GQALANGILEL@cput.ac.za');
                    
                    
                    $email->Send();
                    
                    /*$sql = sprintf("select login from portal.cput_users_cellular where userid=%d", $_POST['p_num']);                 
                    $dbo->setQuery($sql);
                    $row = $dbo->loadObject();
                    $email = $row->login . '@cput.ac.za';
                    
                    $bodytext = 'Hi guys,<br/><br/> A request has been made for VM by: ' .$row->username . ' with ID= ' . $row->seqno  . '.<br/><br/> Please login to OPA and search for ID to see and respond to request.<br/><br/>From,<br/>OPA';
                    $email = new PHPMailer();
                    $email->isHTML(); 
                    $email->From      = 'opa@cput.ac.za';
                    $email->FromName  = 'Online Personal Access';
                    $email->Subject   = 'VM Change Request Application';
                    $email->Body      = $bodytext;
                    $email->AddAddress('dywibibam@cput.ac.za');
                    
                    $email->Send();*/
                    
                   /* $recipients = array(
                    'dywibibam@cput.ac.za' => 'Person One',
                    'SebengaM@cput.ac.za' => 'Person Two',
                    
                    );
                    
                    foreach($recipients as $email => $name)
                    {
                        $mail->AddCC($email, $name);
                    }*/

                    

                    echo '1';
                }
                else{
                    echo '0';
                }
       

   }
   
   else if ($_GET['action'] == 'send_reccomendation') {
       
           $sql = sprintf("update vm_db.vmrequestlog set status='%s', reccomendation='%s' where seqno = %d", 'Pending Approval', $_GET['msg'], $_GET['seqno']);
           //echo $sql;
           //exit();
		$dbo->setQuery($sql);
		$return = $dbo->query();
                
            if ($return){
                $bodytext = 'Hi Boss,<br/><br/> A request has been made for VM by: ' .$row->username . ' with ID= ' . $_GET['seqno']  . '.<br/><br/> Please login to OPA and search for ID to see my reccomendation.<br/><br/>From,<br/>OPA';
                    $email = new PHPMailer();
                    $email->isHTML(); 
                    $email->From      = 'opa@cput.ac.za';
                    $email->FromName  = 'Online Personal Access';
                    $email->Subject   = 'VM Change Request Application';
                    $email->Body      = $bodytext;
                    $email->AddAddress('dywibibam@cput.ac.za');                              
                    $email->Send();
                echo '1';
            } else {
                echo '0';
            }
   }
   
      else if ($_GET['action'] == 'send_approval') {
       
           $sql = sprintf("update vm_db.vmrequestlog set status='%s', approval_comment='%s' where seqno = %d", $_GET['approval_status'], $_GET['comment'], $_GET['seqno']);
           //echo $sql;
           //exit();
		$dbo->setQuery($sql);
		$return = $dbo->query();
                
            if ($return){
                $bodytext = 'Hi Guys,<br/><br/> A request for VM ID= ' . $_GET['seqno'] . ' has been ' . $_GET['approval_status'] . '.<br/><br/> Please action and inform the user of the status..<br/><br/>From,<br/>Boss';
                    $email = new PHPMailer();
                    $email->isHTML(); 
                    $email->From      = 'opa@cput.ac.za';
                    $email->FromName  = 'Online Personal Access';
                    $email->Subject   = 'VM Request Approval Status';
                    $email->Body      = $bodytext;
                    $email->AddAddress('GQALANGILEL@cput.ac.za');                              
                    $email->Send();
                    
                    //send email to aplicant
                    $sql = sprintf("select username from vm_db.vmrequestlog where seqno=%d", $_GET['seqno']);                 
                    $dbo->setQuery($sql);
                    $row = $dbo->loadObject();
                    $email = $row->username . '@cput.ac.za';
                    
                    $bodytext = 'Hi user,<br/><br/> The request for VM was' . $_GET['approval_status'] . '<br/><br/>Please contact CTS on 6167 for more information.<br/><br/>From,<br/>OPA';
                    $email = new PHPMailer();
                    $email->isHTML(); 
                    $email->From      = 'opa@cput.ac.za';
                    $email->FromName  = 'Online Personal Access';
                    $email->Subject   = 'VM Change Request Application';
                    $email->Body      = $bodytext;
                    $email->AddAddress($email);
                    
                    $email->Send();
                    
                echo '1';
            } else {
                echo '0';
            }
   }
    
     else if ($_GET['action'] == 'load_data') {
       $cs = $oci_connect_string;
        $uname = $oci_user; $pass = $oci_pass; 
        $con = oci_connect($uname,$pass,$cs);        
                        
        $sql = "BEGIN dirxml.adstaffinfo_inserts; END;";
        $result = oci_parse($con,$sql);
        oci_execute($result);
        
        if($result)
        {
            echo '1';
        }
        else{
            echo '-1';
        }
         
 }

        else if ($_GET['action'] == "delete_record") {		
                $cs = $oci_connect_string;
                $uname = $oci_user;
                $pass = $oci_pass;
                $con = oci_connect($uname,$pass,$cs);
                $data = array();
                
		$sql = sprintf("delete from dirxml.adstaffinfo where staffno='%s'",$_GET['staff_no2']);      
                
		$result = oci_parse($con,$sql);
                oci_execute($result);
                
                if (!$result) { echo "-1"; } else { echo "1"; }
                
                oci_close($con);               
            
            //echo $_GET['staff_no2'];
                
	}

    else if ($_GET['action'] == 'show_request') {
        
        
       /* echo '1';
        
           $sql = sprintf("select staffno from vm_db.vmrequestlog where seqno = %d", 15);
           
        $dbo->setQuery($sql);
        $row = $dbo->loadObject();
        
echo $row->seqno;        

exit();*/

        $sql = sprintf("select seqno, staffno, username, staffname, faculty, extention, operator, motivation, backuprequirement, retentionperiod, numcopies, requestdate, cpu, ram, harddisk, vnic, os, location, prodtest, status from vm_db.vmrequestlog where seqno=%d",$_GET['id']);

        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        $return = array();

//        echo $row->seqno.';'.$row->staffno.';'.$row->username.';'.$row->staffname.';'.$row->faculty.';'.$row->extention.';'.$row->operator.';'.$row->motivation.';'.$row->backuprequirement.';'.$row->retentionperiod.';'.$row->numcopies.';'.$row->requestdate.';'.$row->cpu.';'.$row->ram.';'.$row->harddisk.';'.$row->vnic.';'.$row->os.';'.$row->location.';'.$row->prodtest.';'.$row->status;
        
        foreach($result as $row){
            //$data[] = $row->requestdate.';'.$row->username .';'.$row->staffname .';'.$row->extention .';'.$row->operator . ';' . $row->faculty;
            $data[] = $row->seqno.';'.$row->staffno.';'.$row->username.';'.$row->staffname.';'.$row->faculty.';'.$row->extention.';'.$row->operator.';'.$row->motivation.';'.$row->backuprequirement.';'.$row->retentionperiod.';'.$row->numcopies.';'.$row->requestdate.';'.$row->cpu.';'.$row->ram.';'.$row->harddisk.';'.$row->vnic.';'.$row->os.';'.$row->location.';'.$row->prodtest.';'.$row->status;
        }
       // echo $data;
        //exit();
        echo json_encode($data);

    }

    else if ($_GET['action'] == 'list_requests') {
        
        
       /* echo '1';
        
           $sql = sprintf("select staffno from vm_db.vmrequestlog where seqno = %d", 15);
           
        $dbo->setQuery($sql);
        $row = $dbo->loadObject();
        
echo $row->seqno;        

exit();*/

        $sql = sprintf("select seqno, staffno, username, staffname, faculty, extention, operator, motivation, backuprequirement, retentionperiod, numcopies, requestdate, cpu, ram, harddisk, vnic, os, location, prodtest, status from vm_db.vmrequestlog");

        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        $return = array();

//        echo $row->seqno.';'.$row->staffno.';'.$row->username.';'.$row->staffname.';'.$row->faculty.';'.$row->extention.';'.$row->operator.';'.$row->motivation.';'.$row->backuprequirement.';'.$row->retentionperiod.';'.$row->numcopies.';'.$row->requestdate.';'.$row->cpu.';'.$row->ram.';'.$row->harddisk.';'.$row->vnic.';'.$row->os.';'.$row->location.';'.$row->prodtest.';'.$row->status;
        
        foreach($result as $row){
            //$data[] = $row->requestdate.';'.$row->username .';'.$row->staffname .';'.$row->extention .';'.$row->operator . ';' . $row->faculty;
            $data[] = $row->seqno.';'.$row->staffno.';'.$row->username.';'.$row->staffname.';'.$row->faculty.';'.$row->extention.';'.$row->operator.';'.$row->motivation.';'.$row->backuprequirement.';'.$row->retentionperiod.';'.$row->numcopies.';'.$row->requestdate.';'.$row->cpu.';'.$row->ram.';'.$row->harddisk.';'.$row->vnic.';'.$row->os.';'.$row->location.';'.$row->prodtest.';'.$row->status;
        }
       // echo $data;
        //exit();
        echo json_encode($data);

    }

    else if ($_GET['action'] == 'edit_request') {
        $sql = sprintf("select seqno, staffno, username, staffname, faculty, extention, operator, motivation, backuprequirement, retentionperiod, numcopies, requestdate, cpu, ram, harddisk, vnic, os, location, prodtest, status, reccomendation, approval_comment from vm_db.vmrequestlog where seqno=%d",$_GET['id']);
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        $return = array();

        foreach($result as $row){
            //$data[] = $row->requestdate.';'.$row->username .';'.$row->staffname .';'.$row->extention .';'.$row->operator . ';' . $row->faculty;
            echo $row->seqno.';'.$row->staffno.';'.$row->username.';'.$row->staffname.';'.$row->faculty.';'.$row->extention.';'.$row->operator.';'.$row->motivation.';'.$row->backuprequirement.';'.$row->retentionperiod.';'.$row->numcopies.';'.$row->requestdate.';'.$row->cpu.';'.$row->ram.';'.$row->harddisk.';'.$row->vnic.';'.$row->os.';'.$row->location.';'.$row->prodtest.';'.$row->status. ';' . $row->reccomendation . ';' . $row->approval_comment;
        }
        //echo json_encode($data);
    }

	else if ($_GET['action'] == 'list_users') {
		   $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);
	$data = array();
        $id = strtoupper($_GET['id']);
                                
		if ($_GET['scond'] == 'N') {
                    $sql = sprintf("select staffno,login_name,username,cardno2 from dirxml.adstaffinfo where staffno = '%s'", $id);
                    //echo $sql;
                   // exit();
                }
                elseif ($_GET['scond'] == 'S') {
                    $sql = sprintf("select staffno,login_name,username,cardno2 from dirxml.adstaffinfo where username = '%s'", $id);
                    //echo $sql;
                   // exit();
                }
                elseif ($_GET['scond'] == 'C') {
                    $sql = sprintf("select staffno,login_name,username,cardno2 from dirxml.adstaffinfo where cardno2 like '%s%%'", $id);
                    //echo $sql;
                   // exit();
                }
                
                $result = oci_parse($con,$sql);
        oci_execute($result);

        $i = 0;
        while ($row = oci_fetch_object($result)) {
            $data[] = $row->STAFFNO.';'. $row->LOGIN_NAME . ';' . $row->USERNAME . ';' . $row->CARDNO2;          
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
	$sql = sprintf("select staffno, title, login_name, username, email, aka, faculty, department, pcampus, pextention, cardno2 from dirxml.adstaffinfo where staffno = '%s'", ($_GET['id']));
                                
        $result = oci_parse($con,$sql);
        oci_execute($result);

       $row = oci_fetch_object($result);
       
       oci_close($con);
       
         echo $row->STAFFNO.';'. $row->TITLE . ';' . $row->LOGIN_NAME . ';' . $row->USERNAME . ';' . $row->EMAIL.';'. $row->AKA . ';' . $row->FACULTY . ';' . $row->DEPARTMENT.';'. $row->PCAMPUS . ';' . $row->PEXTENTION . ';' . $row->CARDNO2;          
	}
        else if ($_GET['action'] == "save_edit_staff") {		
                $cs = $oci_connect_string;
                $uname = $oci_user;
                $pass = $oci_pass;
                $con = oci_connect($uname,$pass,$cs);
                $data = array();
                
                $sql = sprintf("select count(*) as CNT from dirxml.ad_prox_allocation where proxcard='%s'",$_POST['cardno']);  
                $result = oci_parse($con,$sql);
                oci_execute($result);
                $row = oci_fetch_object($result);    
                $count = $row->CNT;
                
		 if($count == '0')
                {
                    $sql1 = sprintf("update dirxml.adstaffinfo set cardno2 = '%s' where staffno='%s'",$_POST['cardno'],$_POST['staff_no']);      
                    $result1 = oci_parse($con,$sql1);
                    oci_execute($result1);
                
                    echo '1';
                }
                else if($count == '1')
                {
                    echo '0';
                }
                
                oci_close($con);               
                
	}
        
         else if ($_GET['action'] == "update_ext") {		
                $cs = $oci_connect_string;
                $uname = $oci_user;
                $pass = $oci_pass;
                $con = oci_connect($uname,$pass,$cs);
                $data = array();
                
		$sql = sprintf("update dirxml.adstaffinfo set pextention = '%s' where staffno='%s'",$_GET['ext_no'],$_GET['staff_no']);                      
		$result = oci_parse($con,$sql);
                oci_execute($result);
                
                if (!$result) { echo "-1"; } else { echo "1"; }
                
                oci_close($con);               
                
	}
	}
exit();

?>
