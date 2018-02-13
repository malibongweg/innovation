<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);

if (isset($_GET['action'])) {


	if ($_GET['action'] == 'show_log') {
		$data = array();
		$sql = "select id,date_format(trans_date,'%Y-%m-%d') as trans_date,date_format(processed_date,'%Y-%m-%d') as processed_date,account_no,rec_type,receipt_no,amount,old_value,new_value,retry_seq,status_flag
		from #__pcounter where year(trans_date) = ".$_GET['yr'];

		if (strlen($_GET['filter']) > 0) {
				if ($_GET['search_on'] == "filter_studentno") {
					$sql .= " and account_no = '".$_GET['filter']."' and month(trans_date) = ".$_GET['mth'];
				} else if ($_GET['search_on'] == "filter_receiptno") {
					$sql .= " and receipt_no = '".$_GET['filter']."'";
				}
			}
			else
		{
					if (intval($_GET['status']) == 2) {
						$sql .= " and account_no REGEXP '^[0-9]+$' = 0";
					}
					else if (intval($_GET['status']) != 3) {
						$sql .= " and status_flag = ".$_GET['status']." and month(trans_date) = ".$_GET['mth'];
					}// else {
					//	$sql .= " and status_flag = ".$_GET['status'];
					//}

					if ($_GET['he'] == "true") {
						$sql .= " and account_no REGEXP '^[0-9]+$' > 0 ";
					}
					if ($_GET['hbc'] == "true") {
						$sql .= " and rec_type not in (11111,11112) ";
					}
		}

			
		$sql .= " order by trans_date desc limit 1000";
		//echo $sql;
		$dbo->setQuery($sql);
		$r = $dbo->query();
			if (!$r) { echo $dbo->getErrorMsg(); exit(); }
		if ($dbo->getNumRows() ==  0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			switch(intval($row->rec_type)) {
				case 212: $rtype = "PRINTING"; break;
				case 272: $rtype = "MEALS BVL"; break;
				case 37: $rtype = "MEALS CT"; break;
				case 2222: $rtype = "PRN/BURSARY"; break;
				case 121: $rtype = "PRINTING"; break;
				case 224: $rtype = "COPY"; break;
				case 2192: $rtype = "COPY/BURSARY"; break;
				case 2191: $rtype = "COPY/BURSARY"; break;
				case 21: $rtype = "COPY"; break;
				case 33: $rtype = "PRINTING"; break;
				case 190: $rtype = "INTERNET"; break;
				case 30: $rtype = "INTERNET"; break;
				case 11111: $rtype = "COPY BCU"; break;
				case 11112: $rtype = "MEALS BCU"; break;
			}
			$acc = substr($row->account_no,0,9);
			$data[] = $row->id.";".$row->trans_date.";".$row->processed_date.";".$acc.";".$rtype.";".$row->receipt_no.";".$row->amount.";".$row->old_value.";".$row->new_value.";".$row->retry_seq.";".$row->status_flag;
		}
		//echo $sql; exit();
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'reset') {
		$dbo->setQuery("update #__pcounter set seq_reset = 1 where id=".$_GET['id']);
		$result = $dbo->query();
		if (!$result) {
			echo "-1";
		} else {
			echo "1";
		}
	}
	else if ($_GET['action'] == 'reset_acc') {
		$dbo->setQuery("update #__pcounter set seq_reset = 1,account_no='".$_GET['acc']."' where id=".$_GET['id']);
		$result = $dbo->query();
		if (!$result) {
			echo "-1";
		} else {
			echo "1";
		}
	}
	else if ($_GET['action'] == 'check_invalid') {
		$dbo->setQuery("select id,account_no from #__pcounter where id=".$_GET['id']);
		$row = $dbo->loadObject();
		echo $row->account_no;
	}
	else if ($_GET['action'] == 'modify_account') {
		$dbo->setQuery("update #__pcounter set account_no = '".$_GET['acc']."',seq_reset = 1 where id = ".$_GET['id']);
		$result = $dbo->query();
		if (!$result) {
			echo "-1";
		} else {
			echo "1";
		}
	}
	else if ($_GET['action'] == 'check_valid_id') {
			$sql = sprintf("select userid,login from cput_users_cellular where login = '%s'",$_GET['uid']);
			$dbo->setQuery($sql);
			$dbo->query();
			if ($dbo->getNumRows() == 0) { echo "0"; } 
			else { 
				$row = $dbo->loadObject();
				echo $row->userid;
			}
	}
	else if ($_GET['action'] == 'send_token') {
			$token =  mt_rand(100,999);
			$token .= mt_rand(100,999);
			$dbo->setQuery("insert into cput_tokens (token,userid) values ('".$token."',".$_GET['lg'].")");
			$dbo->query();
			$sql = "select cmd_location from cput_sms_settings limit 1";
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			$msg = "CPUT Access Token Request. Your access token number is ".$token.". Enter exactly as displayed.";
			exec($row->cmd_location." ".trim($_GET['cell'])." ".trim($msg));
	}
	else if ($_GET['action'] == 'send_password') {
			$sql = "select cmd_location from cput_sms_settings limit 1";
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			$msg = "CPUT Temporary password. Your password is ".$_GET['password']." Enter exactly as displayed and change as soon as possible. Password is valid for 48 hours.";
			exec($row->cmd_location." ".$_GET['cell']." ".$msg);
	}
	else if ($_GET['action'] == 'verify_credentials') {
		
			$cnt = preg_match('/^[0-9]+$/',$_GET['uid']);
			if ($cnt == 0) {
				$sql = sprintf("select * from staff.staff a where a.staff_idno = '%s' and a.staff_dob = '%s' and a.staff_magstrip = %d and a.staff_no = %d",// and date(a.staff_resign) > date(now())",
					$_GET['idno'],$_GET['dob'],$_GET['ms'],$_GET['lg']);
				$dbo->setQuery($sql);
				$dbo->query();
				if ($dbo->getNumRows() == 0){
					echo "-1"; exit(); 
				} else { echo "1"; exit(); }
		} else {
			$sql = sprintf("select * from student.personal_curr a where a.pers_idno='%s' and a.pers_dob='%s' and a.pers_magstrip=%d and a.stud_numb = %d",
				$_GET['idno'],$_GET['dob'],$_GET['ms'],$_GET['lg']);
			$dbo->setQuery($sql);
			$dbo->query();
			if ($dbo->getNumRows() == 0 ) { echo "-1"; exit(); } else { echo "1"; }
		}
	}
	else if ($_GET['action'] == 'verify_token') {
		$dbo->setQuery("select token from cput_tokens where token = '".$_GET['token']."' and userid = ".$_GET['lg']);
		$result = $dbo->query();
			if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
		$dbo->setQuery("delete from cput_tokens where token = '".$_GET['token']."'");
		$dbo->query();
		echo "1";
	}
	else if ($_GET['action'] == 'check_cell') {
		$dbo->setQuery("select cell from cput_users_cellular where userid = ".$_GET['lg']);
		$dbo->query();
			if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
		$row = $dbo->loadObject();
		echo $row->cell;
	}
	else if ($_GET['action'] == 'save_cell_profile') {
		$cnt = preg_match('/^[0-9]+$/',$_GET['uid']);
			if ($cnt == 0) {
				$sql = sprintf("call proc_update_cell(%d,'%s',1,'%s')",$_GET['lg'],$_GET['cell'],$_GET['uid']);
			} else {
				$sql = sprintf("call proc_update_cell(%d,'%s',2,'%s')",$_GET['lg'],$_GET['cell'],$_GET['uid']);
			}
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) { echo "-1"; exit(); }
		echo "1";
	}
	else if ($_GET['action'] == 'check_onceoff') {
		$dbo->setQuery("select userid from cput_token_onceoff where userid = ".$_GET['lg']);
		$dbo->query();
			if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
		echo "1";
	}
	else if ($_GET['action'] == 'change_password') {
		$ldap_config = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
		$fp = file($ldap_config);
		$ldap_server = trim($fp[4]);
		$luser = trim($fp[5]);
		$lpass = trim($fp[6]);
			$lcon = @ldap_connect($ldap_server);
			if (!$lcon) { echo "-9"; exit(); }
			$lbind = @ldap_bind($lcon);
			if (!$lbind) { echo "-1"; exit(); }

			$sr = @ldap_search($lcon,"o=cput","cn=".$_GET['lg']);
			if (!$sr) { echo "-2"; exit(); }

			$en=ldap_get_entries($lcon,$sr);
			if (!$en) { echo "-3"; exit(); }
			$dn = $en[0]['dn'];
			
			$root = "cn=".$luser.",o=cput";
			$rbind = @ldap_bind($lcon,$root,$lpass);
			if (!$rbind) { echo "-4"; exit(); }

			$pass =  substr(md5(rand(100,1000)),0,8);
			$entry = array();
			$entry['userPassword'] = $pass;
			$expdate = time() + 172800;
			$entry['passwordExpirationtime'] = date('YmdHis',$expdate).'Z';

				if (@ldap_modify($lcon,$dn,$entry)){
					ldap_close($lcon);
					echo $pass;
				} else {
					ldap_close($lcon);
					echo "-10";
				}
	}
	else if ($_GET['action'] == 'verify_staffno') {
		$sql = sprintf("select * from cput_users_cellular where login = '%s'",$_GET['lg']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { echo "0"; exit(); }
		echo "1";
	}
	else if ($_GET['action'] == 'getUniReceipt') {
		$sql = sprintf("select receipt_no from portal.cput_pcounter where id = %d",$_GET['rn']);
		$dbo->setQuery($sql);
		$dbo->query();
		$cnt =  $dbo->getNumRows();
		if (intval($cnt) == 0) {
			echo "-1";
		} else {
			$rn = $dbo->loadResult();
			echo $rn;
		}
	}
	else if ($_GET['action'] == 'getUniData') {
		$mscon = mssql_connect('uniflow','OPA_uniflowdb','CPUTopa2008');
		if ($mscon) {
			$sql = sprintf("select ACC,TansDate,ProDate,Amount from StudentAccounts.dbo.Account where Receipt = '%s' order by Sequence desc",$_GET['rn']);
			$result = mssql_query($sql);
			$cnt = mssql_num_rows($result);
			if (intval($cnt) == 0) {
				echo "-1"; exit;
			} else {
				$row = mssql_fetch_object($result);
				echo $row->ACC.";".$row->TansDate.";".$row->ProDate.";".$row->Amount;
				exit;
			}

		} else {
			echo "-1";
		}
	}

}
exit();
