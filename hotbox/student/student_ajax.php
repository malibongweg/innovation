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
    if ($_GET['action'] == "display_students") {
        
        $cs = $oci_connect_string;
        $uname = $oci_user;
        $pass = $oci_pass;
        $con = oci_connect($uname,$pass,$cs);
        $data = array();
        //$sql = "select stdno, fullname, description, cardno2 from dirxml.adstudinfo where stdno in ('212010573','214317447')";
        //$sql = "select stdno, fullname, description, cardno2 from dirxml.adstudinfo";
        //$sql = "select stdno, fullname, description, cardno2 from dirxml.adstudinfo";
        $sql = "select stdno, fullname, description, cardno2 from dirxml.adstudinfo WHERE ROWNUM < 3000";
        $result = oci_parse($con,$sql);
        oci_execute($result);
        $i = 0;
        
        while ($row = oci_fetch_object($result)) {
            $data[] = $row->STDNO.';'. $row->FULLNAME . ';' . $row->DESCRIPTION . ';' . $row->CARDNO2;// . ';' . substr($row->CARDNO2, -1);          
            ++$i;
        }        
        oci_close($con);
        echo json_encode($data);        
    }
    
    if ($_GET['action'] == 'list_filtered_users') {		
                $cs = $oci_connect_string;
                $uname = $oci_user;
                $pass = $oci_pass;
                $con = oci_connect($uname,$pass,$cs);
                $data = array();
                
                $id = strtoupper($_GET['id']);
                
		if ($_GET['filter'] == 'std_no') {
                    //$sql = sprintf("select stdno, fullname, description, cardno2 from dirxml.adstudinfo where stdno='%s'",$id);                   
                    $sql = sprintf("select stdno, fullname, description, cardno2 from dirxml.adstudinfo where stdno='%s' and stdno is not null",$id);                   
                }
                elseif ($_GET['filter'] == 's_name') {
                    $sql = sprintf("select stdno, fullname, description, cardno2 from dirxml.adstudinfo where upper(lastname) like '%s%%' and stdno is not null",$id);                     
                }
                elseif ($_GET['filter'] == 'card_no') {
                    $sql = sprintf("select stdno, fullname, description, cardno2 from dirxml.adstudinfo where cardno2 like '%s%%' and stdno is not null",$id);                 
                    
                }
         
		$result = oci_parse($con,$sql);
                oci_execute($result);
                $i = 0;
                while ($row = oci_fetch_object($result)) {
                    $data[] = $row->STDNO.';'. $row->FULLNAME . ';' . $row->DESCRIPTION . ';' . $row->CARDNO2;          
                    ++$i;
                }
                
                if (!$data) {
                    echo '-1';
                    exit();
                }
                oci_close($con);
                echo json_encode($data);  
	}
        
        else if ($_GET['action'] == "edit_student") {
                $cs = $oci_connect_string;
                $uname = $oci_user;
                $pass = $oci_pass;
                $con = oci_connect($uname,$pass,$cs);
                $data = array();
                
		$sql = sprintf("select stdno, fullname, description, facultyname, deptname,magstripe,barcode,cardno2 from dirxml.adstudinfo where stdno='%s'",$_GET['id']);                                
                $result = oci_parse($con,$sql);
                oci_execute($result);
                //echo $sql;
                //exit();
		$result1 = oci_parse($con,$sql);
                oci_execute($result1);
                $row = oci_fetch_object($result1);
                oci_close($con);
                echo $row->STDNO.';'. $row->FULLNAME . ';' . $row->DESCRIPTION . ';' . $row->FACULTYNAME . ';' . $row->DEPTNAME . ';' . $row->MAGSTRIPE. ';' . $row->BARCODE. ';' . $row->CARDNO2;          
                                
	}
        
        else if ($_GET['action'] == "save_edit_student") {		
                $cs = $oci_connect_string;
                $uname = $oci_user;
                $pass = $oci_pass;
                $con = oci_connect($uname,$pass,$cs);
                $data = array();
                               
		
                
                /*$sql = sprintf("select count(*) as CNT from dirxml.adstudinfo where cardno2='%s'",$_POST['cardno']);  
                $result = oci_parse($con,$sql);
                oci_execute($result);
                $row = oci_fetch_object($result);    
                $count = $row->CNT;*/
                //exit();
                //echo $sql1 . 'and' . $sql;
                
                
                $sql = sprintf("select count(*) as CNT from dirxml.ad_prox_allocation where proxcard='%s'",$_POST['cardno']);  
                $result = oci_parse($con,$sql);
                oci_execute($result);
                $row = oci_fetch_object($result);    
                $count = $row->CNT;
                
                if($count == '0')
                {
                    $sql1 = sprintf("update dirxml.adstudinfo set cardno2 = '%s' where stdno='%s'",$_POST['cardno'],$_POST['std_no']);                  
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

}
exit();

?>
