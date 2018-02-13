<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
$dbo->setQuery("select connect_string,user_name,password,host from cput_system_setup where system_name='meals'");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
		$row = $dbo->loadObject();
		$con = ibase_connect($row->host.$row->connect_string,$row->user_name,$row->password);
		if (!$con) {
			echo "-2";
			exit();
		}

if (isset($_GET)) {

	if ($_GET['action'] == 'get_credentials') {
		$sql = sprintf("select users.surname,users.firstnames from users where users.userno=%d",$_GET['id']);
		$result = ibase_query($con,$sql);
			if (!$result) { echo "-8"; exit(); }
		$row = ibase_fetch_object($result);
			if (!is_object($row)) { $res = "UNKOWN;0.00"; echo $res; exit(); } 
				$res = $row->FIRSTNAMES." ".$row->SURNAME;
			$sql = sprintf("select balance from balances where userno=%d",$_GET['id']);
				$result = ibase_query($con,$sql);
				if (!$result) { $res .= ";Not Available."; echo $res; exit(); }
				$row = ibase_fetch_object($result);
				if ((!is_object($row)) || (is_null($row)) || (strlen($row) == 0)) 
					$bal = number_format(0,2);
				else
					$bal = number_format($row->BALANCE,2);
				$res .= ";".$bal;
				echo $res;
	}
	
	 else if ($_GET['action'] == 'display_data') {
		$data = array();
		$yr = Date('Y');
		$sql = sprintf("select transdate,transtime,amount,machine from ledger where extract(month from transdate) = %d and extract(year from transdate) = %d and userno = %d order by transdate,transtime desc",$_GET['mth'],$yr,$_GET['id']);
		$result = ibase_query($con,$sql);
			if (!$result) { $data[] = "-8"; echo json_encode($data); exit(); }
		while ($row = ibase_fetch_object($result)) {
			$data[]  = $row->TRANSDATE.";".$row->TRANSTIME.";".$row->AMOUNT.";".$row->MACHINE;
		}
		echo json_encode($data);
	}
		
}
exit();

?>