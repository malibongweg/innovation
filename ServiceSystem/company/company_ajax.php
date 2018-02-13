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
     
  
        if ($_GET['action'] == "save_company") {		
                                
                $sql = sprintf("insert into ups_register.company (companyname,contactnumber,email) values ('%s','%s', '%s')",
		$_POST['companyName'],$_POST['contactNumber'],$_POST['email']);          
		$dbo->setQuery($sql);
                  $result = $dbo->query();		
		$return = array();
                echo $sql;
                exit();
                if (!$result){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'An error occured.';
		} else {
			$sql = sprintf("select companyname, contactnumber, email,  from ups_register.company");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
                        $return['Message'] = 'Record successfully saved.';
		} 	
		
                	
		echo json_encode($return);
	}
        else if ($_GET['action'] == "display_companyData") {
        $data = array();
	$sql = sprintf("select companyid, companyname, contactnumber, email from ups_register.company");  
        
	$dbo->setQuery($sql);
       $result = $dbo->loadObjectList();
       
        if (!$result) {
            $data[] = "-1"; echo json_encode($data); //exit();             
        }
        
        foreach($result as $row) {
            $data[] = $row->companyid.";".$row->companyname.";".$row->contactnumber.";".$row->email;
        }
        echo json_encode($data);
	} 
        
        //////////////////////////////////////////////////////////edit company form////////////////////////////////////////////////////////////////////////////////////////       
        else if ($_GET['action'] == "edit_company") {
        $data = array();
	$sql = sprintf("select companyid, companyname, contactnumber, email from ups_register.company where companyid = %d", ($_GET['id']));  
        
	$dbo->setQuery($sql);
       $result = $dbo->loadObjectList();
       
        if (!$result) {
            $data[] = "-1"; echo json_encode($data); //exit();             
        }
        
        foreach($result as $row) {
            echo $row->companyid.";".$row->companyname.";".$row->contactnumber.";".$row->email;
        }
       // echo json_encode($data);
	}
        //////////////////////////////////////////////////////////delete product data////////////////////////////////////////////////////////////////////////////////////////       
        else if ($_GET['action'] == "delete_company") {
        $sql = sprintf("delete from ups_register.company where companyid = '%s'", $_GET['comp_id']);
        //echo $sql;
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
        //////////////////////////////////////////////////////////delete product data////////////////////////////////////////////////////////////////////////////////////////       
        else if ($_GET['action'] == "company_edit") {
          //  echo '1';
         $sql = sprintf("update ups_register.company set companyname = Upper('%s'), contactnumber = Upper('%s'), email = Upper('%s') where companyid = '%s'",
                   $_GET['cname'],$_GET['cnum'],$_GET['eml'],$_GET['coid']);
         //url: 'index.php?option=com_jumi&fileid=246&action=company_edit&coid='+coid+'&cname='+cname+'&cnum='+cnum+'&eml='+eml,
        //echo $sql;
        //exit();
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
            
		//echo json_encode($return);
	}
   
	}
exit();

?>
