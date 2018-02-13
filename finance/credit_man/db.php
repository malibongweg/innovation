<?php
$dbo = &JFactory::getDBO();
$dbo->setQuery("select connect_string,user_name,password from cput_system_setup where system_name='its'");
$row = $dbo->loadObject();
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;
$con = oci_connect($oci_user,$oci_pass,$oci_connect_string);

if (isset($_GET)) {

	if ($_GET['action'] == 'getDetails') {
		$sql = sprintf("select count(distinct cmustno) as cnt from stud.eacmutab where cmustno=%d",$_GET['stno']);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$row = oci_fetch_object($result);
		$cnt = $row->CNT;
			if (intval($cnt) == 0) {
				echo "-1";
			} else if (intval($cnt) > 1) {
				echo "-2";
			} else {
				$sql = sprintf("select distinct cmuname,cmuinit,cmuidno,cmuqual,cmuyear,cmusubjreg,cmusubjpassed,cmupercpass,cmubal,cmubaldue,cmuperc_used,cmupayments from stud.eacmutab where cmustno=%d",$_GET['stno']);
				$result = oci_parse($con,$sql);
				oci_execute($result);
				$row = oci_fetch_object($result);
				echo $row->CMUNAME.";".$row->CMUINIT.";".$row->CMUIDNO.";".$row->CMUQUAL.";".$row->CMUYEAR.";".$row->CMUSUBJREG
					.";".$row->CMUSUBJPASSED.";".$row->CMUPERCPASS.";".$row->CMUBAL.";".$row->CMUBALDUE.";".$row->CMUPERC_USED.";".$row->CMUPAYMENTS;
			}
		//$return = array();
		//$return['Result'] = 'OK';
		//$return['Records'] = $result;
		//echo json_encode($return);		
	}
	else if ($_GET['action'] == 'getSeq') {
		$sql = sprintf("select stud.cmu_seq.nextval as seq from dual");
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$row = oci_fetch_object($result);
		echo $row->SEQ;
	}
	else if ($_GET['action'] == 'browseComments') {
		$sql = sprintf("select cmcdate as listdate,cmcstno,cmcdate,cmccomnt,cmcseq,sysuser as dispuser,sysuser from stud.eacmucom where cmcstno=%d order by cmcseq desc",$_GET['stno']);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$return = array();
		$return['Result'] = 'OK';
		oci_fetch_all($result,$return['Records'],0,-1,OCI_FETCHSTATEMENT_BY_ROW);
		//$return['Records'] = oci_fetch_all($result,$return['Records']);
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createComments') {
		$comment = preg_replace("/\r\n|\r|\n/",'<br />',$_POST['CMCCOMNT']);
		$sql = sprintf("insert into stud.eacmucom (cmcstno,cmcdate,cmccomnt,cmcseq,sysuser) values (%d,to_date(sysdate,'DD-MM-YYYY'),'%s',stud.cmu_seq.nextval,'%s')",
			$_POST['CMCSTNO'],$comment,$_POST['SYSUSER']);
		$result = oci_parse($con,$sql);
		oci_execute($result);

		$sql = "select cmu_seq.currval as seq from dual";
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$row = oci_fetch_object($result);
		
		$sql = sprintf("select cmcdate as listdate,cmcstno,cmcdate,cmccomnt,cmcseq,sysuser as dispuser,sysuser from stud.eacmucom where cmcseq=%d",$row->SEQ);
		$result = oci_parse($con,$sql);
		oci_execute($result);

		$row = oci_fetch_array($result);
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteComments') {
		$sql = sprintf("delete from stud.eacmucom where cmcseq = %d",$_POST['CMCSEQ']);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}

}
exit();

?>

