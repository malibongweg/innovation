<?php
/*define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();*/

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
    
    if ($_GET['action'] == 'list_service') {///List companies
        $company = "";
        $product = "";
        $data = array();
            $sql = sprintf("select jobno, servicedate, contactnumber, status, username, companyid, productid from ups_register.serviceSchedule");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
                foreach ($result as $row)
                {
                    $sql = sprintf("select companyname from ups_register.company where companyid = %d",$row->companyid);
                    $dbo->setQuery($sql);
                    $row2 = $dbo->loadObject();
                    $company = $row2->companyname;
                    
                    $sql = sprintf("select unittype from ups_register.product where productid = %d",$row->productid);
                    $dbo->setQuery($sql);
                    $row3 = $dbo->loadObject();
                    $product = $row3->unittype;
                    
                    $data[] = $row->jobno . ';' . $row->servicedate . ';' . $row->contactnumber . ';' . $row->status . ';' . $row->username . ';' . $company . ';' . $product;
                }
		echo json_encode($data);               
	}
  else if ($_GET['action'] == 'add_service') {
            $sql = sprintf("insert into ups_register.serviceSchedule (servicedate, contactnumber, status, username, companyid, productid) values ('%s', '%s', '%s', '%s', %d, %d)",
            $_POST['service_date'],$_POST['contactno'],$_POST['status'],$_POST['servOperator'],$_POST['company'],$_POST['product']);            
            $dbo->setQuery($sql);
            $result = $dbo->query();	
            if (!$result){
                echo '-1';
            } else {
                echo '1';
            } 			                       
    }
      else if ($_GET['action'] == 'update_service') {
		$sql = sprintf("update ups_register.serviceSchedule set servicedate='%s', contactnumber='%s', status='%s', username='%s', companyid=%d, productid=%d where jobno = %d", 
                        $_GET['date'],$_GET['contactno'],$_GET['status'],$_GET['username'],$_GET['company'],$_GET['product'],$_GET['jid']);
		$dbo->setQuery($sql);
		$return = $dbo->query();
            if (!$return){
                echo '-1';
            } else {
                echo '1';
            } 			
	}
        else if ($_GET['action'] == "edit_service") {
            
        $data = array();
            $sql = sprintf("select jobno, servicedate, contactnumber, status, username, companyid, productid from ups_register.serviceSchedule where jobno=%d",$_GET['id']);
            //echo $sql;
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
                    echo $row->jobno . ';' . $row->servicedate . ';' . $row->contactnumber . ';' . $row->status . ';' . $row->username . ';' . $row->companyid . ';' . $row->productid . ';' . $product;
        }
        else if ($_GET['action'] == 'mailsUser') {
            
           $sDate = date("Y-m-d");  
        
        $data = array();  
        $sql3 = sprintf("select username, companyid, productid, servicedate from ups_register.serviceSchedule where DATE_SUB(servicedate,INTERVAL 14 DAY)='%s'", $sDate);
        $dbo->setQuery($sql3);
	$result = $dbo->loadObjectList();
        
        foreach ($result as $row)
        {
        $opEmail = $row->username.'@CPUT.AC.ZA';
       // echo $opEmail;
       // exit();
        if ($opEmail) {
        $sql = sprintf("select companyname from ups_register.company where companyid=%d", $row->companyid);
        $dbo->setQuery($sql);
	$row2 = $dbo->loadObject();
        
        $sql1 = sprintf("select unittype, model from ups_register.product where productid=%d", $row->productid);
        $dbo->setQuery($sql1);
	$row3 = $dbo->loadObject();
        
        $bodytext = 'This is an automatic generated message from the OPA system. Please do not reply to this message.<br /><br />This is to remind you of the service for UPS unit type: ' . $row3->unittype . ' model '. $row3->model. ' with company '. $row2->companyname.' due '. $row->servicedate;
        $email = new PHPMailer();
        //$email->IsSMTP();
        $email->isHTML(); 
        $email->From      = 'opa@cput.ac.za';
        $email->FromName  = 'Online Personal Access';
        $email->Subject   = 'UPS Service Reminder';
        $email->Body      = $bodytext;
        $email->AddAddress($row->username.'@CPUT.AC.ZA');
        //$email->AddAddress('reynoldsl@cput.ac.za');
        $email->Send();
        
//            $sendTo = array();
//            $sendTo[] = $opEmail;
//            $addresses = serialize($sendTo);
//            $details .= 'This is an automatic generated message from the OPA system. Please do not reply to this message.<br /><br />';
//            $details .= 'This is to remind you of the service for UPS unit type: ' . $row3->unittype . ' model '. $row3->model. ' with company'. $row2->companyname.' due '. $row->servicedate;            
//            //sendMail($addresses, 'UPS Service Reminder', $details);
//           echo $addresses,$details;
        } else {
            echo '0';
        }
    }
        }

}
exit();
?>