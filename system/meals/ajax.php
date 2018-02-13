<?php
$dbo = &JFactory::getDBO();
$dbo->setQuery("select connect_string,user_name,password,host from cput_system_setup where system_name='meals'");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
		$row = $dbo->loadObject();
		$con = ibase_connect($row->host.$row->connect_string,$row->user_name,$row->password);
		if (!$con) {
			echo "-9";
			exit();
		}
		
if (isset($_GET)) {

	if ($_GET['action'] == 'get_credentials') {
		$cnt = preg_match('/^[0-9]+$/',$_GET['id']);
		if ($cnt <= 0) { echo "0"; exit(); }
		$sql = sprintf("select count(*) as cnt,users.surname,users.firstnames from users where users.userno=%d group by users.surname,users.firstnames",$_GET['id']);
		$result = ibase_query($con,$sql);
			if (!$result) { echo "-8"; exit(); }
			$row = ibase_fetch_object($result);
				if (intval($row->CNT) == 0) { $res = "UNKOWN;0.00;0000000";  } 
				else {
							$res = $row->FIRSTNAMES." ".$row->SURNAME;
							$sql = sprintf("select balance from balances where userno=%d",$_GET['id']);
							$result = ibase_query($con,$sql);
							if (!$result) { $res .= ";Not Available."; }
							$row = ibase_fetch_object($result);
							//if ((!is_object($row)) || (is_null($row)) || (strlen($row) == 0)) 
							//	$bal = number_format(0,2);
							//else
							$bal = number_format($row->BALANCE,2);
							$res .= ";".$bal;

							$sql = sprintf("select cardno from cards where userno=%d",$_GET['id']);
							$result = ibase_query($con,$sql);
							if (!$result) { $res .= ";"."0000000";  }
							else {
								$row = ibase_fetch_object($result);
								$res .= ";".$row->CARDNO;
							}
				}
					echo $res;
	}
	
	else if ($_GET['action'] == 'display_data') {
		$data = array();
		$yr = Date('Y');
		$sql = sprintf("select transdate,transtime,amount,machine from ledger where extract(month from transdate) = %d and extract(year from transdate) = %d and userno = %d order by transdate,transtime desc",$_GET['mth'],$_GET['yr'],$_GET['id']);
		$result = ibase_query($con,$sql);
			if (!$result) { $data[] = "-8"; echo json_encode($data); exit(); }
		while ($row = ibase_fetch_object($result)) {
			$data[]  = $row->TRANSDATE.";".$row->TRANSTIME.";".$row->AMOUNT.";".$row->MACHINE;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'acc_status') {
			$sql = sprintf("select blocked from cards where userno=%d",$_GET['id']);
			$result = ibase_query($con,$sql);
			if (!$result) { echo "-1"; exit(); }
			$row = ibase_fetch_object($result);
			switch ($row->BLOCKED) {
				case 'Y': echo "1"; break;
				case 'N': echo "0"; break;
			}
	}
	else if ($_GET['action'] == 'cblock') {
			if ($_GET['action2'] == 'block_acc'){
				$sql = sprintf("update cards set blocked = 'Y' where userno=%d",$_GET['id']);
			} else {
				$sql = sprintf("update cards set blocked = 'N' where userno=%d",$_GET['id']);
			}
			$result = ibase_query($con,$sql);
			if (!$result) { echo "-1"; exit(); } else { echo "1"; }
	}
	else if ($_GET['action'] == 'check_barcode') {
		$dbo->setQuery("select connect_string,user_name,password from cput_system_setup where system_name = 'its'");
		$row = $dbo->loadObject();
		$cs = $row->connect_string;
		$uname = $row->user_name;
		$pass = $row->password;
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "-1";
			exit();
		}
		$sql = sprintf("select magstrip,campus from stud.idphoto_view where card_num = %d",$_GET['id']);
		$res = oci_parse($con,$sql);
		oci_execute($res);
		$row = oci_fetch_object($res);
			if (is_object($row)){
				echo $row->MAGSTRIP;
			} else {
				echo "-1"; 
			}
	}
	else if ($_GET['action'] == 'sync_barcode') {
			$sql = sprintf("update cards set cardno = %d where userno=%d",$_GET['magno'],$_GET['id']);
			$result = ibase_query($con,$sql);
			if (!$result) { echo "-1"; } else { echo "1"; }
	}
	else if ($_GET['action'] == 'check_journal') {
		$sql = sprintf("select coalesce(count(*),0) as cnt from iamlog where iamrefno = '%s'",$_GET['journal']);
		$result = ibase_query($con,$sql);
			if (!$result) { echo "-1"; exit(); }
			$row = ibase_fetch_object($result);
			echo $row->CNT;
	}
	else if ($_GET['action'] == 'its_journal') {
		$dbo->setQuery("select connect_string,user_name,password from cput_system_setup where system_name = 'its'");
		$row = $dbo->loadObject();
		$cs = $row->connect_string;
		$uname = $row->user_name;
		$pass = $row->password;
		$cono = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "-1";
			exit();
		}

		$sql = sprintf("select count(*) as CNT from stud.iamlog where iamrefno = '%s' and iamcode in (2185,2131)",$_GET['journal']);
		$res = oci_parse($cono,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		$row = oci_fetch_object($res);
		if (intval($row->CNT) == 0) { echo "0"; exit(); }

		$data = array();
		$sql = sprintf("select iamstno,to_char(iamdate,'mm/dd/yyyy') as iamdate,iamrefno,iamamt,iamuser,'DEBBEL' as debcrd from stud.iamlog where iamrefno = '%s' and iamcode in (2185,2131)",$_GET['journal']);
		$res = oci_parse($cono,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		while ($row = oci_fetch_object($res)){
			$data[] = $row->IAMSTNO.";".$row->IAMDATE.";".$row->IAMREFNO.";".number_format($row->IAMAMT,2,'.','').";".$row->IAMUSER.";".$row->DEBCRD;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'its_journal2') {
		$dbo->setQuery("select connect_string,user_name,password from cput_system_setup where system_name = 'its'");
		$row = $dbo->loadObject();
		$cs = $row->connect_string;
		$uname = $row->user_name;
		$pass = $row->password;
		$cono = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "-1";
			exit();
		}

		$sql = sprintf("select count(*) as CNT from stud.iamlog where iamrefno = '%s' and iamcode in (2185,2131)",$_GET['journal']);
		$res = oci_parse($cono,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		$row = oci_fetch_object($res);
		if (intval($row->CNT) == 0) { echo "0"; exit(); }

		$data = array();
		$sql = sprintf("select iamstno,to_char(iamdate,'mm/dd/yyyy') as iamdate,iamrefno,iamamt,iamuser,'DEBBEL' as debcrd,iamcode from stud.iamlog where iamrefno = '%s' and iamcode in (2185,2131)",$_GET['journal']);
		$res = oci_parse($cono,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		while ($row = oci_fetch_object($res)){
			$amt = floatval($row->IAMAMT);
			if (intval($row->IAMCODE) == 2131) {
				$amt = $amt * (-1);
			}
			$data[] = $row->IAMSTNO.";".$row->IAMDATE.";".$row->IAMREFNO.";".number_format($amt,2,'.','').";".$row->IAMUSER.";".$row->DEBCRD;
		}
		//$data[] = "-3;-3;-3;-3;-3;-3";
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'proc_journal') {
		if (intval($_GET['stno']) != -3){
			$data = $_GET['stno'];
			$sql = sprintf("select coalesce(balance,0) as balance from balances where userno=%d",$_GET['stno']);
			$result = ibase_query($con,$sql);
			if (!$result) { echo "-1"; exit(); }
			$row = ibase_fetch_object($result);
			$prev_bal = $row->BALANCE;

			/*debug*/
			echo "Previous balance: ".$prev_bal;
			$sql = sprintf("insert into iamlog (iamstno,iamdate,iamrefno,iamamt,iamuser,iamdebcrd) values (%d,CAST('%s' as DATE),'%s',%0.2f,'%s','%s')",
				$_GET['stno'],$_GET['pdate'],$_GET['ref'],$_GET['amt'],$_GET['usr'],$_GET['crd']);
			echo $sql;

			/******/
			
			$sql = sprintf("insert into iamlog (iamstno,iamdate,iamrefno,iamamt,iamuser,iamdebcrd) values (%d,CAST('%s' as DATE),'%s',%0.2f,'%s','%s')",
				$_GET['stno'],$_GET['pdate'],$_GET['ref'],$_GET['amt'],$_GET['usr'],$_GET['crd']);
			
				$result = ibase_query($con,$sql);
				if (!$result) { echo "-2";  exit(); }
			$sql = sprintf("select coalesce(balance,0) as balance from balances where userno=%d",$_GET['stno']);
			$result = ibase_query($con,$sql);
			if (!$result) { echo "-1"; exit(); }
			$rowx = ibase_fetch_object($result);
			$data = $data . ";" .number_format($_GET['amt'],2,'.','').";". $prev_bal.";" . number_format($rowx->BALANCE,2,'.','');
			echo $data;
		} else { echo "-3"; }
	}
}
exit();

?>
