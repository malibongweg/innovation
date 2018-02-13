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
        if ($_GET['action'] == "save_product") {		
                                
               $sql = sprintf("insert into ups_register.product (unittype, model, serialno, address) values ('%s','%s', '%s', '%s')",
		$_POST['unitType'],$_POST['model'],$_POST['serialNo'],$_POST['address']);            
		$dbo->setQuery($sql);
                  $result = $dbo->query();		
		$return = array();
                if (!$result){
			$return = array();
			$return['Result'] = 'ERROR';
			$return['Message'] = 'An error occured.';
		} else {
			$sql = sprintf("select unittype, model, serialno, address from ups_register.product");
			$dbo->setQuery($sql);
			$row = $dbo->loadAssoc();
			$return = array();
			$return['Result'] = 'OK';
			$return['Record'] = $row;
                        $return['Message'] = 'Record successfully saved.';
		} 	
		
                	
		echo json_encode($return);
	}
        else if ($_GET['action'] == "display_productData") {
        $data = array();
	$sql = sprintf("select productid, unittype, model, serialno, address from ups_register.product");  
        
	$dbo->setQuery($sql);
       $result = $dbo->loadObjectList();
       
        if (!$result) {
            $data[] = "-1"; echo json_encode($data); //exit();             
        }
        
        foreach($result as $row) {
            $data[] = $row->productid.";".$row->unittype.";".$row->model.";".$row->serialno.";".$row->address;
        }
        echo json_encode($data);
	}
 //////////////////////////////////////////////////////////edit product form////////////////////////////////////////////////////////////////////////////////////////       
        else if ($_GET['action'] == "edit_product") {
        $data = array();
	$sql = sprintf("select productid, unittype, model, serialno, address from ups_register.product where productid = %d", ($_GET['id']));  
        
	$dbo->setQuery($sql);
       $result = $dbo->loadObjectList();
       //echo $result;
       
        if (!$result) {
            $data[] = "-1"; 
            echo json_encode($data); //exit();             
        }
        
        foreach($result as $row) {
            echo $row->productid.";".$row->unittype.";".$row->model.";".$row->serialno.";".$row->address;
        }
       // echo json_encode($data);
	}
//////////////////////////////////////////////////////////delete product data////////////////////////////////////////////////////////////////////////////////////////       
        else if ($_GET['action'] == "delete_product") {
        $sql = sprintf("delete from ups_register.product where productid = '%s'", $_GET['prod_id']);
        echo $sql;
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	} 
 //////////////////////////////////////////////////////////delete product data////////////////////////////////////////////////////////////////////////////////////////       
        else if ($_GET['action'] == "product_edit") {
         $sql = sprintf("update ups_register.product set unittype = Upper('%s'), model = Upper('%s'), serialno = Upper('%s'), address = Upper('%s') where productid = '%s'",
                   $_GET['uType'],$_GET['mod'],$_GET['serNo'],$_GET['addr'],$_GET['prod_id']);
        echo $sql;
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
