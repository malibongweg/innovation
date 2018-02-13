<?php
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
    
       if ($_GET['action'] == "display_changeData") {
            
        $data = array();
	$sql = sprintf("select logid,date, requester, operator, status, extention, vmname from vm_db.vmchangelog");  
        //echo $sql;
	$dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        if (!$result) {
            $data[] = "-1"; 
            echo json_encode($data); //exit();             
        }       
        foreach($result as $row) {
            $data[] = $row->logid.";".$row->date.";".$row->requester.";".$row->operator.";".$row->status.";".$row->extention.";".$row->vmname;
        }
        echo json_encode($data);
	} 
///////////////////////////////////////////////////////////////////////Display Search Results////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
        else if ($_GET['action'] == "display_changeSearch") {
            
        $data = array();
	$sql = sprintf("select logid, date, requester, operator, status, extention, vmname from vm_db.vmchangelog where logid = '%s'",$_GET['id']);  
        //echo $sql;
        //exit();
        $dbo->setQuery($sql);
        $result = $dbo->loadObject(); 
        if (!$result) {
            $data[] = "-1"; 
            echo json_encode($data); //exit();             
        }   
       // foreach($result as $row) {
            echo $result->logid.";".$result->date.";".$result->requester.";".$result->operator.";".$result->status.";".$result->extention.";".$result->vmname;
        //}
       // echo json_encode($data);
	} 
/////////////////////////////////////////////////////////////////////////////////////////////////edit change form////////////////////////////////////////////////////////////////////////////////////////       
        else if ($_GET['action'] == "edit_change") {
        $data = array();
        
	$sql = sprintf("select logid, staffno, staffname, faculty, extention, vmname, motivation, vmcpu, vmram, vmharddisk, vnic, vmlocation, producttest, backuprequirement, comment1, comment2 from vm_db.vmchangelog where logid = %d", ($_GET['id']));
	//echo $sql;
        $dbo->setQuery($sql);
       $result = $dbo->loadObject();
       
        if (!$result) {
            $data[] = "-1"; 
            echo json_encode($data); //exit();             
        }
           echo $result->logid.";".$result->staffno.";".$result->staffname.";".$result->faculty.";".$result->extention.";".$result->vmname.";".$result->motivation.";".$result->vmcpu.";".$result->vmram
                   .";".$result->vmharddisk.";".$result->vnic.";".$result->vmlocation.";".$result->producttest.";".$result->backuprequirement.";".$result->comment1.";".$result->comment2;
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
        else if ($_GET['action'] == "save_change") {		
                  $sDate = date("Y-m-d");              
               $sql = sprintf("insert into vm_db.vmchangelog (staffno, requester, staffname, faculty, extention, operator, motivation, status, vmname, vmcpu, vmram, "
                       . "vmharddisk,vnic, vmlocation, backuprequirement, producttest, date)"
                       . " values ('%s','%s', '%s','%s', '%s','%s', '%s','%s', '%s','%s', '%s','%s', '%s','%s', '%s','%s', '%s')",
		$_POST['personnelNo'],$_POST['changeRequester'],$_POST['pName'],$_POST['fac_name'],$_POST['extention'],'',$_POST['reason'],'SUBMITTED'
                ,$_POST['vmName'],$_POST['vcpu'],$_POST['ram'],$_POST['hardDisk'],$_POST['vnic'],$_POST['location'],$_POST['backup'],$_POST['prod'],$sDate);
               //echo $sql;             
		$dbo->setQuery($sql);
                  $result = $dbo->query();		
		$return = array();
                if ($result){
                    $sql = sprintf("select logid,date, requester, operator, status, extention, vmname from vm_db.vmchangelog ORDER BY logid DESC LIMIT 1");
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			//$return = array();
			
                    $bodytext = 'Hi guys,<br/><br/> A request has been made for VM by: ' .$row->requester . ' with ID= ' . $row->logid  . '.<br/><br/> Please login to OPA and search for ID to see and respond to request.<br/><br/>From,<br/>OPA';
                    $email = new PHPMailer();
                    $email->isHTML(); 
                    $email->From      = 'opa@cput.ac.za';
                    $email->FromName  = 'Online Personal Access';
                    $email->Subject   = 'VM Request Application';
                    $email->Body      = $bodytext;
                    $email->AddAddress('dywibibam@cput.ac.za');
                    //$email->AddAddress('palesa.sello17@gmail.com');
			
                     $email->Send();
                     echo '1';
		} else {
			//$return = array();
			echo '-1';
		} 	
		//echo json_encode($return);
	}
//////////////////////////////////////////////////////////delete product data////////////////////////////////////////////////////////////////////////////////////////       
        else if ($_GET['action'] == "approve_change") {
            
           if ($_GET['approval'] == "Approved"){
            $sql = sprintf("update vm_db.vmchangelog set comment2 = Upper('%s'), status = Upper('%s') where logid = '%s'",
                   $_GET['comment'],'APPROVED',$_GET['log_id']);
        //echo $sql;
        //exit();
		$dbo->setQuery($sql);
		$result1 = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		
                if ($result1){
                    $sql = sprintf("select logid,date, requester, operator, status, extention, vmname from vm_db.vmchangelog where logid = '%s'",$_GET['log_id']);
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			//$return = array();
			
                    $bodytext = 'Hi '.$row->operator.',<br/><br/> A request for VM ID= ' . $row->logid. ' has been ' . $row->status. '.<br/><br/> Please follow up and inform the user of the status..<br/><br/>From,<br/>Boss';
                    $email = new PHPMailer();
                    $email->isHTML(); 
                    $email->From      = 'opa@cput.ac.za';
                    $email->FromName  = 'Online Personal Access';
                    $email->Subject   = 'VM Request Application';
                    $email->Body      = $bodytext;
                    $email->AddAddress($row->operator.'@cput.ac.za');
                    $email->AddAddress($row->requester.'@cput.ac.za');
			
                     $email->Send();
                     echo '1';
		} else {
			//$return = array();
			echo '-1';
		}
        }
        else if ($_GET['approval'] == "Rejected"){
        $sql = sprintf("update vm_db.vmchangelog set comment2 = Upper('%s'), status = Upper('%s') where logid = '%s'",
                   $_GET['comment'],'DECLINED',$_GET['log_id']);
        //echo $sql;
        //exit();
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
	if ($result1){
                    $sql = sprintf("select logid,date, requester, operator, status, extention, vmname from vm_db.vmchangelog where logid = '%s'",$_GET['log_id']);
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			//$return = array();
			
                    $bodytext = 'Hi '.$row->operator.',<br/><br/> A request for VM ID= ' . $_GET['logid'] . ' has been ' . $row->status. '.<br/><br/> Please follow up and inform the user of the status..<br/><br/>From,<br/>Boss';
                    $email = new PHPMailer();
                    $email->isHTML(); 
                    $email->From      = 'opa@cput.ac.za';
                    $email->FromName  = 'Online Personal Access';
                    $email->Subject   = 'VM Request Application';
                    $email->Body      = $bodytext;
                    $email->AddAddress($row->operator.'@cput.ac.za');
                    //$email->AddAddress($row->requester.'@cput.ac.za');
			
                     $email->Send();
                     echo '1';
		} else {
			//$return = array();
			echo '-1';
		}      
        }
            }
//////////////////////////////////////////////////////////edit product data//////////logid,persNo,name,fac,ext,vmName,why,cpu,ram,disk,vnic,loc,product,back//////////////////////////////////////////////////////////////////////////////       
        else if ($_GET['action'] == "change_edit") {    
     
            $sql = sprintf("update vm_db.vmchangelog set comment1 = Upper('%s'), status = Upper('%s'), operator = Upper('%s') where logid = '%s'",
                   $_GET['comm1'],'PENDIND APPROVAL',$_GET['op'],$_GET['log_id']);
        //echo $sql;
        //exit();
		$dbo->setQuery($sql);
		$result1 = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		//echo json_encode($return);
                
                if ($result1){
                    $sql = sprintf("select logid,date, requester, operator, status, extention, vmname from vm_db.vmchangelog where logid = '%s'",$_GET['log_id']);
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			//$return = array();
			
                    $bodytext = 'Hi Boss,<br/><br/> A request has been made for VM by: ' .$row->requester . ' with ID= ' . $row->logid  . '.<br/><br/> Please login to OPA and search for ID to see and respond to request.<br/><br/>From,<br/>OPA';
                    $email = new PHPMailer();
                    $email->isHTML(); 
                    $email->From      = 'opa@cput.ac.za';
                    $email->FromName  = 'Online Personal Access';
                    $email->Subject   = 'VM Request Application';
                    $email->Body      = $bodytext;
                    $email->AddAddress('dywbibam@cput.ac.za');
                    //$email->AddAddress('palesa.sello17@gmail.com');
			
                     $email->Send();
                     echo '1';
		} else {
			//$return = array();
			echo '-1';
		} 
                
           $sql1 = sprintf("select status from vm_db.vmchangelog where logid = %d", ($_GET['id']));
	//echo $sql;
        $dbo->setQuery($sql1);
       $result = $dbo->loadObject();
       
        if (!$result) {
            $data[] = "-1"; 
            echo json_encode($data); //exit();             
        }
           echo $result->status;       
	}
	}
exit();

?>
