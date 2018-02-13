<?php
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='mas_test'");
$row = $dbo->loadObject();
$oci_host = $row->host;
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;


if (isset($_GET['function'])) {
	
	if ($_GET['function'] == 'subjSearch') {

		
			$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
		
		
		$sql = sprintf("select ialsubj, ialdesc from stud.ialsub where ialcyr = %d and ialsubj like '%s' order by ialsubj",$_GET['cyr'],$_GET['srch']."%"); 
        
		//$sql = sprintf("select distinct substr(ialdesc,1,35) as ialdesc,iahsubj as ialsubj from stud.iahsub,stud.ialsub where iahcyr = %d and ialsubj = iahsubj and iahsubj like '%s' order by iahsubj",$_GET['cyr'],$_GET['srch']."%");
		//echo $sql;
		$data = array();
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			//$e = oci_error($con);
			//echo $e['message'];                                                                                    
			echo '99999';
			exit();
		}     
		$res = oci_parse($con,$sql);
		oci_execute($res);
			while ($row = oci_fetch_object($res)) { 
				//echo $row->IALSUBJ;
				$data[$row->IALSUBJ] = $row->IALDESC;
			}
		oci_close($con);
		echo json_encode($data);
	}
	else if($_GET['function'] == 'checkDBStatus') {
		$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='mas'");
		$row = $dbo->loadObject();
		$oci_host_dr = $row->disaster_host;
		$oci_system_mode = intval($row->system_mode);
		$oci_log = intval($row->log_only);
		echo $oci_system_mode.";".$oci_host_dr.";".$oci_log;
	}
	else if($_GET['function'] == 'qualification') {
		$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
		$sql = sprintf("select distinct ialsubj,iahqual from stud.ialsub, stud.iahsub where ialsubj = iahsubj and ialsubj = '%s' and ialcyr = %d",$_GET['subj'],$_GET['cyr']);
		$data = array();
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo '99999';
			exit();
		}
		$result = oci_parse($con,$sql);
		oci_execute($result);
			while ($row = oci_fetch_object($result)) {
				$data[] = $row->IAHQUAL;
			}
		oci_close($con);
		echo json_encode($data);
	}
	else if($_GET['function'] == 'offering') {
		$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
		$sql = sprintf("select distinct ialsubj, iahot from stud.iahsub, stud.ialsub where ialsubj = iahsubj and ialsubj = '%s' and ialcyr = %d",$_GET['subj'],$_GET['cyr']);
		//echo $sql;
		$data = array();
		$con = oci_connect($uname,$pass,$cs);
		$result = oci_parse($con,$sql);
		oci_execute($result);
			while ($row = oci_fetch_object($result)) {
				$data[] = $row->IAHOT;
			}
		oci_close($con);
		echo json_encode($data);
	}
        
        else if($_GET['function'] == 'table') {
            $cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
            $sql = sprintf("select distinct iadstno,iadsurn,iadinit,IAGQUAL,IAGOT,
                            stud.get_address(iadstno, 'CE',1,'I','S') cell_telephone
                            from stud.iadbio, stud.iagenr
                            where iadstno = iagstno
                            and iagcyr = 2014
                            and iagenr.IAGCANCDATE is null
                            and iagenr.IAGQUAL = 'PGCEFT'
                            and iagprimary = 'Y'");
            //echo $sql;
            $data = array();
            $con = oci_connect($uname,$pass,$cs);
            $result = oci_parse($con,$sql);
            oci_execute($result);
                    while ($row = oci_fetch_object($result)) {
                            $data[] = $row->IADSTNO.';'.$row->IADSURN.';'.$row->CELL_TELEPHONE;
                    }
                   // $data[] = '21201235'.';'.'Mali'.';'.'0834942638';
            oci_close($con);
            echo json_encode($data);
           
        }
               	
}
exit();
?>
