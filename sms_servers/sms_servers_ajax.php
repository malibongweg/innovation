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
    if ($_GET['action'] == 'list_servers') {
        $data = array();
        $sql = sprintf("select ID, IPADDRESS, SERVERNAME, COSTCENTRE, STATUSYN from portal.cput_sms_servers");
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        $return = array();
        if(!$result){
            echo '-1';
        }
        else
        {
            foreach($result as $row){
                $data[] = $row->ID.';'.$row->IPADDRESS.';'.$row->SERVERNAME.';'.$row->COSTCENTRE.';'.$row->STATUSYN;
            }                   
        }
        
        echo json_encode($data);
    }
}

   if ($_GET['action'] == 'insert_server') {
        $sql = sprintf("insert into portal.cput_sms_servers (IPADDRESS, SERVERNAME, COSTCENTRE, STATUSYN) values ('%s', '%s', '%s', '%s')",
            $_POST['ip_address'],$_POST['server_name'], $_POST['cost_centre'], $_POST['status']);
        
        $dbo->setQuery($sql);
        $result = $dbo->query();
        
        if($result)
        {
            echo '1';
        }
        else{
            echo '0';
        }
   }
   
   else if ($_GET['action'] == 'edit_server') {
        $data = array();
        $sql = sprintf("select ID, IPADDRESS, SERVERNAME, COSTCENTRE, STATUSYN from portal.cput_sms_servers where ID = %d", $_GET['id']);
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        
        foreach($result as $row){
            echo $row->ID.';'.$row->IPADDRESS.';'.$row->SERVERNAME.';'.$row->COSTCENTRE.';'.$row->STATUSYN;
        }        
    }
    
    else if ($_GET['action'] == 'update_server') {
        //r&id='+id+'&ip='+ip+'&server_name='+server_name+'&cost_centre='+cost_centre+'&status='+status,
		$sql = sprintf("update portal.cput_sms_servers set IPADDRESS='%s', SERVERNAME='%s', COSTCENTRE='%s', STATUSYN='%s' where ID = %d", 
                        $_GET['ip'],$_GET['server_name'],$_GET['cost_centre'],$_GET['status'],$_GET['id']);
		$dbo->setQuery($sql);
		$return = $dbo->query();
            if (!$return){
                echo '0';
            } else {
                echo '1';
            } 			
	}
        
    else if ($_GET['action'] == 'delete_server') {
        //r&id='+id+'&ip='+ip+'&server_name='+server_name+'&cost_centre='+cost_centre+'&status='+status,
		$sql = sprintf("delete from portal.cput_sms_servers where ID = %d", $_GET['id']);
		$dbo->setQuery($sql);
		$return = $dbo->query();
            if (!$return){
                echo '0';
            } else {
                echo '1';
            } 			
	}
        
     else if ($_GET['action'] == 'show_server') {
        $data = array();
        $sql = sprintf("select ID, IPADDRESS, SERVERNAME, COSTCENTRE, STATUSYN from portal.cput_sms_servers where ID = %d", $_GET['id']);
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        $return = array();
        if(!$result){
            echo '-1';
        }
        else
        {
            foreach($result as $row){
                echo $row->ID.';'.$row->IPADDRESS.';'.$row->SERVERNAME.';'.$row->COSTCENTRE.';'.$row->STATUSYN;
            }                   
        }
        
        //echo json_encode($data);
    }
        
exit();
?>
