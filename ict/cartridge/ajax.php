<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);

if (isset($_GET['action'])) {

	if ($_GET['action'] == 'display_data') {
		$data = array();
		//$data[] = "-1"; echo json_encode($data); exit();
		$sql = sprintf("select captured,transno,caseid,staffno,costcentre,status from #__cartridge_issue where year(captured) = %d and month(captured) = %d order by transno desc",$_GET['yr'],$_GET['mth']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->captured.";".$row->transno.";".$row->caseid.";".$row->staffno.";".$row->costcentre.";".$row->status;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'list_staff') {
		$data = array();
		$dbo->setQuery("select a.staff_no,concat(a.staff_sname,' ',a.staff_fname) as fullname from staff.staff a where cast(a.staff_no as char) like '".$_GET['staffno']."%' order by a.staff_sname,a.staff_fname limit 300");
		$result = $dbo->loadObjectList();
		if (!$result) { $data[] = "-1"; echo json_encode($data); exit(); }
		foreach($result as $row) {
			$data[$row->staff_no] = "[".$row->staff_no."]  ".$row->fullname;
		}
		echo json_encode($data);
		
	}

	else if ($_GET['action'] == 'list_centres') {
		$data = array();
		$dbo->setQuery("select a.detail_cc,a.cc_name from budgets.costcodes a where detail_cc like '".$_GET['id']."%' order by a.detail_cc");
		$result = $dbo->loadObjectList();
		if (!$result) { $data[] = "-1"; echo json_encode($data); exit(); }
		foreach($result as $row) {
			$data[$row->detail_cc] = "[".$row->detail_cc."]  ".$row->cc_name;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'get_transno') {
		$dbo->setQuery("insert into #__cartridge_issue (captured) value (now())");
		$dbo->query();
		$trans_no = $dbo->insertid();
		echo $trans_no.";".date('Y-m-d');
	}

	else if ($_GET['action'] == 'validate_case') {
		$data = array();
		$dbo->setQuery("select connect_string,user_name,password from #__system_setup where system_name = 'helpdesk'");
		$row = $dbo->loadObject();
		$cs = $row->connect_string;
		$uname = $row->user_name;
		$pass = $row->password;
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			$data[] = "-1";
			echo json_encode($data);
			exit();
		}
		$sql = sprintf("select caseid,details,callerid from requests where caseid = %d",$_GET['id']);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$row = oci_fetch_object($result);
			if (strlen($row->CASEID) > 0) {
				$data[] = $row->CASEID.";".$row->DETAILS.";".$row->CALLERID;
			} else {
				$data[] = "-2";
			}
		echo json_encode($data);

	}

	else if ($_GET['action'] == 'list_stock') {
		$data = array();
		$dbo->setQuery("select a.id,a.stockid,a.onhand,a.price,b.cartname from #__cartridge_stock a left outer join  #__cartridges b on (a.cartridge = b.code)
			where stockid like '".$_GET['id']."%' order by a.stockid");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-2"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		if (!$result) { $data[] = "-1"; echo json_encode($data); exit(); }
		foreach($result as $row) {
			$data[$row->stockid] = "[".$row->stockid."]  ".$row->cartname;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'get_stock_details') {
		$data = array();
		$dbo->setQuery("select a.id,a.stockid,a.onhand,a.price,b.cartname from #__cartridge_stock a left outer join  #__cartridges b on (a.cartridge = b.code)
			where stockid = '".$_GET['id']."'");
		$row = $dbo->loadObject();
		$data[] = $row->cartname.";".$row->price;
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'update_price') {
		$dbo->setQuery("update #__cartridge_stock set price = ".$_GET['price']." where stockid = '".$_GET['id']."'");
		$dbo->query();
		echo "1";
	}

	else if ($_GET['action'] == 'delete_illegals') {
		$dbo->setQuery("call proc_delete_illegal_cartissue()");
		$dbo->query();
	}

	else if ($_GET['action'] == 'save_record') {
		$sql = sprintf("update #__cartridge_issue set caseid=%d,case_details='%s',doneby='%s',staffno=%d,captured='%s',costcentre='%s',status='%s'
			where transno=%d",$_POST['caseid'],$_POST['casedetails'],$_POST['doneby'],$_POST['staffno'],$_POST['captured'],$_POST['costcentre'],
				$_POST['status'],$_POST['transno']);
			//echo $sql;exit();
				$dbo->setQuery($sql);
				$result = $dbo->query();
				if (!$result) { echo "-1"; exit(); }

		//if ($_GET['proc'] == "new") {
				$sql = sprintf("delete from #__cartridge_issue_items where transno=%d",$_POST['transno']);
				$dbo->setQuery($sql);
				$dbo->query();
				$items = $_POST['booked_items'];
				$items = substr($items,0,-1);
				//echo $items."<br />";
				$rec = explode(";",$items);
				foreach($rec as $key1=>$value1) {
					list($cde,$qty) = explode(":",$value1);
						
						$dbo->setQuery("select a.id,a.stockid,a.onhand,a.price from #__cartridge_stock a where a.stockid = '".$cde."'");
						$sql  = "select a.id,a.stockid,a.onhand,a.price from #__cartridge_stock a where a.stockid = '".$cde."'";
						echo $sql;
						$row = $dbo->loadObject();
						if (!$row) { echo $dbo->getErrorMsg(); exit(); }
						$total = ($row->price * $qty);
						
						$sql = sprintf("insert into #__cartridge_issue_items (transno,stockcode,qty,price,totalprice)
						values (%d,'%s',%d,%0.2f,%0.2f)",$_POST['transno'],$cde,$qty,$row->price,$total);
						
							$dbo->setQuery($sql);
							$result = $dbo->query();
							if (!$result) {
								$dbo->setQuery("delete from #__cartridge_issue where transno=".$_POST['transno']);
								$dbo->query();
								echo "-3"; } else { echo "1"; }
				}
			//}
	}

	else if ($_GET['action'] == 'cancel_record') {
		$dbo->setQuery("delete from #__cartridge_issue where transno=".$_GET['id']);
		$dbo->query();
	}

	else if ($_GET['action'] == 'edit_record') {
		$sql = sprintf("select transno,caseid,case_details,staffno,captured,status,costcentre from #__cartridge_issue where transno=%d",$_GET['id']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		if (!$row) { $data = "-1"; echo json_encode($data); exit(); }
		$data = $row->transno.";".$row->caseid.";".$row->case_details.";".$row->staffno.";".$row->captured.";".$row->costcentre.";".$row->status;
		echo $data;		
	}

	else if ($_GET['action'] == 'pop_stock') {
		$data = array();
		$sql = sprintf("select distinct a.stockcode,a.qty,a.totalprice,c.cartname from #__cartridge_issue_items a left outer join #__cartridge_stock b on (a.stockcode = b.stockid)
		left outer join #__cartridges c on (b.cartridge = c.code) where a.transno=%d",$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->stockcode.";".$row->qty.";".$row->totalprice.";".$row->cartname;
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == 'validate_centre') {
		$sql = sprintf("select a.staff_costcode from staff.staff a where a.staff_no=%d",$_GET['sn']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0 ) { echo "-2"; exit(); }
		$row = $dbo->loadObject();
		echo $row->staff_costcode;
	}
		
}
exit();

?>