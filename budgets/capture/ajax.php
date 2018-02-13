<?php
$dbo =& JFactory::getDBO();
require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");

if (isset($_GET)) {

	if ($_GET['action'] == 'displayBudget') {
		$dbo->setQuery("select distinct count(detail_cc) as cnt from budgets.current_cost_codes");
		$nr = $dbo->loadResult();
		$sql = sprintf("select distinct detail_cc,cc_name,dept_name from budgets.current_cost_codes order by '%s' limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		$dbo->setQuery($sql);
		$dbo->query();
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $nr;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displayCostCodes') {
		$return = array();
		$dbo->setQuery('select detail_cc,cc_name from budgets.current_cost_codes');
		$result = $dbo->loadObjectList();
		$return['Result'] = 'OK';
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->cc_name;
			$return['Options'][$i]['Value'] = $row->detail_cc;
			++$i;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'getConfig') {
		$sql = sprintf("select admin_email,cutoff,approve,allow_superusers,budget_cycle from budgets.budget_config where id = 1");
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		echo $row->admin_email.';'.$row->cutoff.';'.$row->approve.';'.$row->allow_superusers.';'.$row->budget_cycle;
	}
	else if ($_GET['action'] == 'saveConfig') {
		if (isset($_POST['superusers'])) {
			$super = 1;
		} else {
			$super = 0;
		}
		$sql = sprintf("update budgets.budget_config set admin_email = '%s',cutoff='%s',approve='%s',allow_superusers=%d,budget_cycle=%d where id=1",
			$_POST['admin_email'],$_POST['cutoff'],$_POST['approval'],$super,$_POST['budget_cycle']);
		$dbo->setQuery($sql);
		$dbo->query();
		echo '1';
	}
	else if ($_GET['action'] == 'displaySuperUsers') {
		$dbo->setQuery("select count(userid) from budgets.budget_super_users");
		$cnt = $dbo->loadResult();
		$sql = sprintf("select id,userid from budgets.budget_super_users order by '%s' limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displayRequestCostCodes') {
		$dbo->setQuery("select count(*) as cnt from budgets.costcode_exception a left outer join staff.staff b on (a.uid=b.staff_no)");
		$cnt = $dbo->loadResult();

		$sql = sprintf("select distinct a.id,a.cost_code,c.cc_name,a.uid,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as uname, a.cost_code as ccc from budgets.costcode_exception a
			left outer join staff.staff b on (a.uid=b.staff_no) left outer join budgets.current_cost_codes c on (a.cost_code = c.detail_cc) order by a.%s limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		//$fp = fopen('c:\temp\sql.txt','w');fwrite($fp,$sql);fclose($fp);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displayRequestCostCodesRemove') {
		$dbo->setQuery("select count(*) as cnt from budgets.costcode_removals a left outer join staff.staff b on (a.uid=b.staff_no)");
		$cnt = $dbo->loadResult();

		$sql = sprintf("select distinct a.id,a.cost_code,c.cc_name,a.uid,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as uname, a.cost_code as ccc from budgets.costcode_removals a
			left outer join staff.staff b on (a.uid=b.staff_no) left outer join budgets.current_cost_codes c on (a.cost_code = c.detail_cc) order by a.%s limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		//$fp = fopen('c:\temp\sql.txt','w');fwrite($fp,$sql);fclose($fp);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displayAccessCostCodes') {
		$dbo->setQuery("select count(*) as cnt from budgets.budget_users");
		$cnt = $dbo->loadResult();

		$sql = sprintf("select a.id,a.staffno,a.staffno as stfno,concat(b.staff_sname,' ',b.staff_fname,' ',b.staff_title) as budget_user,b.staff_sname from budgets.budget_users a
			left outer join staff.staff b on (a.staffno=b.staff_no) order by b.%s limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);

		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listCostCodeAccess') {
		$sql = sprintf("select distinct a.idref,a.id,a.cost_code,a.cost_code as cc,b.cc_name from budgets.budget_users_costcodes a left outer join budgets.current_cost_codes b on (a.cost_code=b.detail_cc) where a.id = %d order by b.cc_name",$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createAccessCostCodes') {
		$sql = sprintf("insert into budgets.budget_users (staffno) values (%d)",$_POST['staffno']);
		$dbo->setQuery($sql);
		$res = $dbo->query();

		$sql = sprintf("select a.id,a.staffno,a.staffno as stfno,concat(b.staff_sname,' ',b.staff_fname,' ',b.staff_title) as budget_user,b.staff_sname from budgets.budget_users a left outer join staff.staff b on (a.staffno=b.staff_no) where a.id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'assignCostCodes') {
		$sql = sprintf("insert into budgets.budget_users_costcodes (id,cost_code) values (%d,'%s')",$_POST['id'],$_POST['cost_code']);
		$dbo->setQuery($sql);
		$dbo->query();
		$sql = sprintf("select distinct a.id,a.cost_code,a.cost_code as cc,b.cc_name from budgets.budget_users_costcodes a left outer join budgets.current_cost_codes b on (a.cost_code=b.detail_cc) where a.id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteAccessCostCodes') {
		$sql = sprintf("delete from budgets.budget_users where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteAssignCostCodes') {
		$sql = sprintf("delete from budgets.budget_users_costcodes where idref='%s'",$_POST['idref']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createRequestsCostCodes') {
		$sql = sprintf("insert into budgets.costcode_exception (cost_code,uid) values ('%s',%d)",$_POST['cost_code'],$_POST['uid']);
		$dbo->setQuery($sql);
		$dbo->query();

		$id = $dbo->insertid();
		$sql = sprintf("select a.id,a.cost_code,a.uid,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as uname from budgets.costcode_exception a
			left outer join staff.staff b on (a.uid=b.staff_no) where a.id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createRequestsCostCodesRemove') {
		$sql = sprintf("insert into budgets.costcode_removals (cost_code,uid) values ('%s',%d)",$_POST['cost_code'],$_POST['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
		$id = $dbo->insertid();
		$sql = sprintf("select a.id,a.cost_code,a.uid,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as uname from budgets.costcode_removals a
			left outer join staff.staff b on (a.uid=b.staff_no) where a.id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createRequestsAccountCodes') {
		$sql = sprintf("insert into budgets.account_exception (account_code,cost_code) values ('%s','%s')",$_POST['account_code'],$_POST['cost_code']);
		$dbo->setQuery($sql);
		$dbo->query();
		$id = $dbo->insertid();
		$sql = sprintf("select distinct a.id,a.account_code,b.fcdname1,a.cost_code,c.cc_name,a.cost_code as ccc,a.account_code as aaa from budgets.account_exception a
			left outer join budgets.account_codes b on (a.account_code = b.fcdacc)
			left outer join budgets.current_cost_codes c on (a.cost_code = c.detail_cc) where a.id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createRequestsCostCodes') {
		$sql = sprintf("insert into budgets.costcode_exception (cost_code,uid) values ('%s',%d)",$_POST['cost_code'],$_POST['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
		$id = $dbo->insertid();
		$sql = sprintf("select a.id,a.cost_code,a.uid,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as uname from budgets.costcode_exception a
			left outer join staff.staff b on (a.uid=b.staff_no) where a.id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteRequestsCostCodes') {
		$sql = sprintf("delete from budgets.costcode_exception where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteRequestsCostCodesRemove') {
		$sql = sprintf("delete from budgets.costcode_removals where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteRequestsAccountCodes') {
		$sql = sprintf("delete from budgets.account_exception where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listUsernames') {
		$return = array();
		$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname,' [',a.staff_no,']') as uname from staff.staff a  where datediff(current_date,a.staff_resign) <= 0 order by a.staff_sname,a.staff_fname");
		$result = $dbo->loadObjectList();
		$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->uname;
				$return['Options'][$i]['Value'] = $row->staff_no;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listCostCodes') {
		$return = array();
		$dbo->setQuery("select distinct detail_cc,concat('[',detail_cc,'] ',cc_name) as cc_name from budgets.current_cost_codes order by detail_cc");
		$result = $dbo->loadObjectList();
		$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->cc_name;
				$return['Options'][$i]['Value'] = $row->detail_cc;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listAccountCodes') {
		$return = array();
		$dbo->setQuery("select distinct fcdacc,concat('[',fcdacc,'] ',fcdname1) as fcdname1 from budgets.account_codes where for_year=".date('Y')." order by fcdacc");
		$result = $dbo->loadObjectList();
		$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->fcdname1;
				$return['Options'][$i]['Value'] = $row->fcdacc;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displayRequestAccountCodes') {
		$dbo->setQuery("select count(*) from budgets.account_exception");
		$cnt = $dbo->loadResult();

		$sql = sprintf("select distinct a.id,a.account_code,b.fcdname1,a.cost_code,c.cc_name,a.cost_code as ccc,a.account_code as aaa from budgets.account_exception a
			left outer join budgets.account_codes b on (a.account_code = b.fcdacc)
			left outer join budgets.current_cost_codes c on (a.cost_code = c.detail_cc) order by a.%s limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displayStaff') {
		$sql = sprintf("select a.staff_no,concat(a.staff_sname,' ',a.staff_init,' ',a.staff_title) as username,b.dept_desc
				from staff.staff a left outer join structure.department b on (a.dept_code = b.dept_code) order by a.staff_sname,a.staff_init");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return['Result'] = 'OK';
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->username.' ['.$row->dept_desc.']';
			$return['Options'][$i]['Value'] = $row->staff_no;
			++$i;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteSuperUsers') {
		$sql = sprintf("delete from budgets.budget_super_users where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createSuperUsers') {
		$sql = sprintf("insert into budgets.budget_super_users (userid) values (%d)",$_POST['userid']);
		$dbo->setQuery($sql);
		$dbo->query();
		$lstid = $dbo->insertid();
		$sql = sprintf("select id,userid from budgets.budget_super_users where id = %d",$lstid);
		//$fp = fopen('c:\temp\sql.txt','w');fwrite($fp,"Hello...\n");fwrite($fp,$sql);fclose($fp);
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'getStaffNo') {
		$sql = sprintf("select a.userid from portal.cput_users_cellular a where lower(a.login) = '%s'",$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
			if ($dbo->getNumRows() == 0) {
				echo "-1";
			} else {
				$row = $dbo->loadResult();
				echo $row;
			}
	}
	else if ($_GET['action'] == 'checkSuperUser') {
		$sql = sprintf("select userid from budgets.budget_super_users where userid=%d",$_GET['staffno']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) {
			echo "0";
		} else {
			echo "1";
		}
	}
	else if ($_GET['action'] == 'displayDept') {
		$sql = sprintf("select a.dept_desc from structure.department a left join staff.staff b on (a.dept_code = b.dept_code) where b.staff_no=%d",$_GET['id']);
		$dbo->setQuery($sql);
		$dbo->query();
			if ($dbo->getNumRows() == 0) {
				echo "-1";
			} else {
				$row = $dbo->loadResult();
				echo $row;
			}
	}
	else if ($_GET['action'] == 'getCostCodes') {
		$data = array();
		$dbo->setQuery("select email from portal.cput_users a where a.id = ".$_GET['uid']);
		$adminEmail = strtolower($dbo->loadResult());
		$sysEmail = strtolower(urldecode($_GET['admin']));

		if ($sysEmail == $adminEmail) {
			$sql = sprintf("select distinct a.detail_cc,a.cc_name from budgets.current_cost_codes a");
		} else {
			$sql = sprintf("select distinct a.detail_cc,a.cc_name from budgets.current_cost_codes a left join staff.staff b on (a.fac_code = b.fac_code) where b.staff_no = %d and not exists (select c.cost_code,c.uid
			from budgets.costcode_removals c where c.uid = b.staff_no and c.cost_code = a.detail_cc) union select distinct a.cost_code,b.cc_name from budgets.costcode_exception a left outer join budgets.current_cost_codes b
			on a.cost_code=b.detail_cc where a.uid = %d order by detail_cc",$_GET['staffno'],$_GET['staffno']);
		}
		//echo $sql;
		$cnt = 0;
		$dbo->setQuery($sql);
		//echo $sql;
		$dbo->query();
		//echo $dbo->getErrorMsg();
		if ($dbo->getNumRows() == 0) {
			$data[$cnt] = -1;
		} else {
			$result = $dbo->loadObjectList();
			foreach ($result as $row) {
				$data[$cnt]['costcode'] =$row->detail_cc;
				$data[$cnt]['desc'] = $row->cc_name;
				$data[$cnt]['codetype'] = 'n';
				$cnt++;
			}

					//Get read/write costcodes
					$sql = sprintf("select id from budgets.budget_users where staffno=%d",$_GET['staffno']);
					$dbo->setQuery($sql);
					$dbo->query();
					if ($dbo->getNumRows() > 0) {
						$id = $dbo->loadResult();
						$sql = sprintf("select cost_code from budgets.budget_users_costcodes where id = %d",$id);
						$dbo->setQuery($sql);
						$dbo->query();
							if ($dbo->getNumRows() > 0) {
								$result = $dbo->loadObjectList();
								$cnt = count($data);
								foreach($result as $row){
									for($i=0;$i<$cnt;$i++){
										if ($data[$i]['costcode'] == $row->cost_code){
											$data[$i]['codetype'] = 'e';
										}
									}
								}
							}
					}
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'displayAccountCodes') {
		$admin = false;
		$utype = $_GET['usertype'];

		$dbo->setQuery("select email from portal.cput_users a where a.id = ".$_GET['uid']);
		$adminEmail = strtolower($dbo->loadResult());
		
		$dbo->setQuery("select admin_email from budget_config where id=1");
		$sysEmail = $dbo->loadResult();
		//$sysEmail = strtolower(urldecode($_GET['admin']));

		if ($sysEmail == $adminEmail) {
			$admin = true;
		}

		switch ($_POST['acc']) {
			case 1:  $codes = "'61513','62906','62016','63506','62017'"; break;//$st = '60330'; $ed = '86699'; break;
			case 2:  $st = '40000'; $ed = '49999'; break;
			case 3:  $st = '50000'; $ed = '59999'; break;
			case 4:  $st = '30020'; $ed = '39999'; break;
			case 5:  $st = 'S30020'; $ed = 'S39999'; break;
			default: break;
		}
		

					if ($admin == true){
						$sql = sprintf("SELECT DISTINCT a.fcdacc,a.fcdname1, abs(IFNULL(b.actuals1,0)) AS actuals1, abs(IFNULL(b.actuals2,0)) AS actuals2, abs(IFNULL(b.budget,0)) AS budget,
						ifnull((SELECT SUM(c.budget_amount)
						FROM budgets.budget_accounts c
						WHERE c.cost_code='%s' AND c.account_code = a.fcdacc AND c.for_year=%d
						GROUP BY c.cost_code,c.account_code),0) AS current_budget,1 as current_account
						FROM budgets.account_codes a
						JOIN budgets.m16_budget_history b ON (a.fcdacc=b.acc_code AND b.cc_code='%s')
						WHERE a.for_year=%d",$_POST['cc'],$_POST['cycle_now'],$_POST['cc'],$_POST['cycle_now']);

					} else {
						if ($utype == "acd"){
								$sql = sprintf("SELECT DISTINCT a.fcdacc,a.fcdname1, abs(IFNULL(b.actuals1,0)) AS actuals1, abs(IFNULL(b.actuals2,0)) AS actuals2, abs(IFNULL(b.budget,0)) AS budget,
								ifnull((SELECT SUM(c.budget_amount)
								FROM budgets.budget_accounts c
								WHERE c.cost_code='%s' AND c.account_code = a.fcdacc AND c.for_year=%d
								GROUP BY c.cost_code,c.account_code),0) AS current_budget,
								if (a.fcdacc not in (select n.account_code from budgets.accounts_academic n),0,1) as edit_account
								FROM budgets.account_codes a
								JOIN budgets.m16_budget_history b ON (a.fcdacc=b.acc_code AND b.cc_code='%s' and (b.actuals1 <> 0 or b.actuals2 <> 0 or b.budget <> 0))
								WHERE a.for_year=%d",$_POST['cc'],$_POST['cycle_now'],$_POST['cc'],$_POST['cycle_now']);
								$sql .= " and (if (a.fcdacc not in (select n.account_code from budgets.accounts_academic n),0,1)) > 0 ";
						} else {
								$sql = sprintf("SELECT DISTINCT a.fcdacc,a.fcdname1, abs(IFNULL(b.actuals1,0)) AS actuals1, abs(IFNULL(b.actuals2,0)) AS actuals2, abs(IFNULL(b.budget,0)) AS budget,
								ifnull((SELECT SUM(c.budget_amount)
								FROM budgets.budget_accounts c
								WHERE c.cost_code='%s' AND c.account_code = a.fcdacc AND c.for_year=%d
								GROUP BY c.cost_code,c.account_code),0) AS current_budget,
								if (a.fcdacc not in (select n.account_code from budgets.accounts_admin n),0,1) as edit_account
								FROM budgets.account_codes a
								JOIN budgets.m16_budget_history b ON (a.fcdacc=b.acc_code AND b.cc_code='%s' and (b.actuals1 <> 0 or b.actuals2 <> 0 or b.budget <> 0))
								WHERE a.for_year=%d",$_POST['cc'],$_POST['cycle_now'],$_POST['cc'],$_POST['cycle_now']);
								$sql .= " and (if (a.fcdacc not in (select n.account_code from budgets.accounts_admin n),0,1)) > 0 ";
						}
					}

						if (intval($_POST['acc']) == 1) {
							$sql .= sprintf(" AND a.fcdacc in (%s)",$codes);
						} else {
							$sql .= sprintf(" AND a.fcdacc BETWEEN '%s' AND '%s'",$st,$ed);
						}

						$sql .= " AND a.fcdfunct='D'";
//echo $sql;
						//Check filtering///
						if (isset($_POST['f_cost'])) {
							if (strlen($_POST['f_cost']) == 5) {
							$sql .= " and a.fcdacc='".$_POST['f_cost']."' ";
							}
						}

						if (intval($_POST['acc']) == 5){
							if ($utype == "acd"){
									$sql .= sprintf(" UNION SELECT DISTINCT a.fcdacc,a.fcdname1, abs(IFNULL(b.actuals1,0)) AS actuals1, abs(IFNULL(b.actuals2,0)) AS actuals2, abs(IFNULL(b.budget,0)) AS budget,
									ifnull((SELECT SUM(c.budget_amount)
									FROM budgets.budget_accounts c
									WHERE c.cost_code='%s' AND c.account_code = a.fcdacc AND c.for_year=%d
									GROUP BY c.cost_code,c.account_code),0) AS current_budget,
									if (a.fcdacc not in (select n.account_code from budgets.accounts_academic n),0,1) as edit_account
									FROM budgets.account_codes a
									LEFT OUTER JOIN budgets.m16_budget_history b ON (a.fcdacc=b.acc_code AND b.cc_code='%s' and (b.actuals1 <> 0 or b.actuals2 <> 0 or b.budget <> 0))
									WHERE a.for_year=%d AND a.fcdacc BETWEEN '%s' AND '%s' AND a.fcdfunct='D'",
									$_POST['cc'],$_POST['cycle_now'],$_POST['cc'],$_POST['cycle_now'],$st,$ed);
									if (isset($_POST['f_cost'])) {
										if (strlen($_POST['f_cost']) == 5) {
										$sql .= " and a.fcdacc='".$_POST['f_cost']."' ";
										}
									}
									$sql .= "and (if (a.fcdacc not in (select n.account_code from budgets.accounts_academic n),0,1)) > 0 ";
							} else {
									$sql .= sprintf(" UNION SELECT DISTINCT a.fcdacc,a.fcdname1, abs(IFNULL(b.actuals1,0)) AS actuals1, abs(IFNULL(b.actuals2,0)) AS actuals2, abs(IFNULL(b.budget,0)) AS budget,
									ifnull((SELECT SUM(c.budget_amount)
									FROM budgets.budget_accounts c
									WHERE c.cost_code='%s' AND c.account_code = a.fcdacc AND c.for_year=%d
									GROUP BY c.cost_code,c.account_code),0) AS current_budget,
									if (a.fcdacc not in (select n.account_code from budgets.accounts_admin n),0,1) as edit_account
									FROM budgets.account_codes a
									LEFT OUTER JOIN budgets.m16_budget_history b ON (a.fcdacc=b.acc_code AND b.cc_code='%s' and (b.actuals1 <> 0 or b.actuals2 <> 0 or b.budget <> 0))
									WHERE a.for_year=%d AND a.fcdacc BETWEEN '%s' AND '%s' AND a.fcdfunct='D'",
									$_POST['cc'],$_POST['cycle_now'],$_POST['cc'],$_POST['cycle_now'],$st,$ed);
									if (isset($_POST['f_cost'])) {
										if (strlen($_POST['f_cost']) == 5) {
										$sql .= " and a.fcdacc='".$_POST['f_cost']."' ";
										}
									}
									$sql .= " and (if (a.fcdacc not in (select n.account_code from budgets.accounts_admin n),0,1)) > 0 ";
							}
						}



							
							///get accounts not included in table///////////////////////
							if ($utype == "acd"){
								$sql .= sprintf(" UNION SELECT DISTINCT a.fcdacc,a.fcdname1, abs(IFNULL(b.actuals1,0)) AS actuals1, abs(IFNULL(b.actuals2,0)) AS actuals2, abs(IFNULL(b.budget,0)) AS budget,
								ifnull((SELECT SUM(c.budget_amount)
								FROM budgets.budget_accounts c
								WHERE c.cost_code='%s' AND c.account_code = a.fcdacc AND c.for_year=%d
								GROUP BY c.cost_code,c.account_code),0) AS current_budget,
								if (a.fcdacc not in (select n.account_code from budgets.accounts_academic n),0,1) as edit_account
								FROM budgets.account_codes a
								LEFT OUTER JOIN budgets.m16_budget_history b ON (a.fcdacc=b.acc_code AND b.cc_code='%s' and (b.actuals1 <> 0 or b.actuals2 <> 0 or b.budget <> 0))
								WHERE a.for_year=%d AND a.fcdacc in (select n.account_code from budgets.accounts_academic n)",
								$_POST['cc'],$_POST['cycle_now'],$_POST['cc'],$_POST['cycle_now']);
								if (intval($_POST['acc']) == 1) {
									$sql .= sprintf(" AND a.fcdacc in (%s)",$codes);
								} else {
									$sql .= sprintf(" AND a.fcdacc BETWEEN '%s' AND '%s' ",$st,$ed);
								}
								$sql .= sprintf(" AND a.fcdacc not in (
								SELECT DISTINCT x.fcdacc FROM budgets.account_codes x JOIN budgets.m16_budget_history b 
								ON (x.fcdacc=b.acc_code AND b.cc_code='%s' and (b.actuals1 <> 0 or b.actuals2 <> 0 or b.budget <> 0))
								WHERE x.for_year=%d ",$_POST['cc'],$_POST['cycle_now']);
								if (intval($_POST['acc']) == 1) {
									$sql .= sprintf(" AND x.fcdacc in (%s))",$codes);
								} else {
									$sql .= sprintf(" AND x.fcdacc BETWEEN '%s' AND '%s' )",$st,$ed);
								}
								//$sql .= " and (if (a.fcdacc not in (select n.account_code from budgets.accounts_academic n),0,1)) > 0 ";
						} else {
								$sql .= sprintf(" UNION SELECT DISTINCT a.fcdacc,a.fcdname1, abs(IFNULL(b.actuals1,0)) AS actuals1, abs(IFNULL(b.actuals2,0)) AS actuals2, abs(IFNULL(b.budget,0)) AS budget,
								ifnull((SELECT SUM(c.budget_amount)
								FROM budgets.budget_accounts c
								WHERE c.cost_code='%s' AND c.account_code = a.fcdacc AND c.for_year=%d
								GROUP BY c.cost_code,c.account_code),0) AS current_budget,
								if (a.fcdacc not in (select n.account_code from budgets.accounts_admin n),0,1) as edit_account
								FROM budgets.account_codes a
								LEFT OUTER JOIN budgets.m16_budget_history b ON (a.fcdacc=b.acc_code AND b.cc_code='%s' and (b.actuals1 <> 0 or b.actuals2 <> 0 or b.budget <> 0))
								WHERE a.for_year=%d AND a.fcdacc in (select n.account_code from budgets.accounts_admin n)",
								$_POST['cc'],$_POST['cycle_now'],$_POST['cc'],$_POST['cycle_now']);
								if (intval($_POST['acc']) == 1) {
									$sql .= sprintf(" AND a.fcdacc in (%s)",$codes);
								} else {
									$sql .= sprintf(" AND a.fcdacc BETWEEN '%s' AND '%s' ",$st,$ed);
								}
								$sql .= sprintf(" AND a.fcdacc not in (
								SELECT DISTINCT x.fcdacc FROM budgets.account_codes x JOIN budgets.m16_budget_history b 
								ON (x.fcdacc=b.acc_code AND b.cc_code='%s' and (b.actuals1 <> 0 or b.actuals2 <> 0 or b.budget <> 0))
								WHERE x.for_year=%d ",$_POST['cc'],$_POST['cycle_now']);
								if (intval($_POST['acc']) == 1) {
									$sql .= sprintf(" AND x.fcdacc in (%s))",$codes);
								} else {
									$sql .= sprintf(" AND x.fcdacc BETWEEN '%s' AND '%s' )",$st,$ed);
								}
								//$sql .= " and (if (a.fcdacc not in (select n.account_code from budgets.accounts_admin n),0,1)) > 0 "
						}

							////////////////////////////////////////

							/*if ($utype == "acd"){
								$sql .= sprintf(" union
								SELECT DISTINCT a.account_code,z.fcdname1, 0 AS actuals1, 0 AS actuals2, 0 AS budget,
								ifnull((SELECT SUM(c.budget_amount)
								FROM budgets.budget_accounts c
								WHERE c.cost_code='%s' AND c.account_code = a.account_code AND c.for_year=%d
								GROUP BY c.cost_code,c.account_code),0) AS current_budget,
								(if (a.account_code not in (select n.account_code from budgets.accounts_academic n),0,1)) as edit_account
								FROM budgets.account_exception a
								LEFT OUTER JOIN budgets.account_codes z on (a.account_code = z.fcdacc)
								WHERE a.cost_code = '%s'",$_POST['cc'],$_POST['cycle_now'],$_POST['cc']);
								$sql .= " and (if (z.fcdacc not in (select n.account_code from budgets.accounts_academic n),0,1)) > 0 ";
							} else {
								$sql .= sprintf(" union
								SELECT DISTINCT a.account_code,z.fcdname1, 0 AS actuals1, 0 AS actuals2, 0 AS budget,
								ifnull((SELECT SUM(c.budget_amount)
								FROM budgets.budget_accounts c
								WHERE c.cost_code='%s' AND c.account_code = a.account_code AND c.for_year=%d
								GROUP BY c.cost_code,c.account_code),0) AS current_budget,
								(if (a.account_code not in (select n.account_code from budgets.accounts_admin n),0,1)) as edit_account
								FROM budgets.account_exception a
								LEFT OUTER JOIN budgets.account_codes z on (a.account_code = z.fcdacc)
								WHERE a.cost_code = '%s'",$_POST['cc'],$_POST['cycle_now'],$_POST['cc']);
								$sql .= " and (if (z.fcdacc not in (select n.account_code from budgets.accounts_academic n),0,1)) > 0 ";
							}

						if (intval($_POST['acc']) == 1) {
							$sql .= sprintf(" AND a.account_code in (%s)",$codes);
						} else {
							$sql .= sprintf(" AND a.account_code BETWEEN '%s' AND '%s'",$st,$ed);
						}

						if (isset($_POST['f_cost'])) {
							if (strlen($_POST['f_cost']) == 5) {
							$sql .= " and a.account_code='".$_POST['f_cost']."' ";
							}
						}*/

			$sql2 = $sql;
			$sql .= " ORDER BY ".$_GET['jtSorting']." limit ".$_GET['jtStartIndex'].",".$_GET['jtPageSize'];
			//echo $sql;
		
		$dbo->setQuery($sql2);
		$dbo->query();
		$cnt = $dbo->getNumRows();

		$dbo->setQuery($sql);
			
		$result = $dbo->loadObjectList();
		
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displayBudgetAlloc') {
		$sql = sprintf("select a.bud_acc_num,a.cost_code,a.account_code,a.for_year,a.budget_desc,abs(a.budget_amount) as budget_amount,
		(select abs(b.budget_amount) from budgets.budget_accounts b where b.cost_code = a.cost_code
		and b.account_code = a.account_code and b.for_year = %d and b.lnk = a.lnk) as next_budget,
		(select abs(b.budget_amount) from budgets.budget_accounts b where b.cost_code = a.cost_code
		and b.account_code = a.account_code and b.for_year = %d and b.lnk = a.lnk) as next_next_budget
		from budgets.budget_accounts a where a.cost_code='%s' and a.account_code='%s' and a.for_year = %d",intval($_GET['yr']+1),intval($_GET['yr']+2),$_GET['cc'],$_GET['acc'],$_GET['yr']);
		//$fp = fopen('c:\temp\sql.txt','w');fwrite($fp,$sql);fclose($fp);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		//$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteBudgetAlloc') {
		$sql = sprintf("select lnk from budgets.budget_accounts where bud_acc_num = %d",$_POST['bud_acc_num']);
		$dbo->setQuery($sql);
		$lnk = $dbo->loadResult();

		$sql = sprintf("update budgets.delete_audit set uid='%s' where id=1",$_GET['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->query();

		$sql = sprintf("delete from budgets.budget_accounts where bud_acc_num=%d",$_POST['bud_acc_num']);
		$dbo->setQuery($sql);
		$result = $dbo->query();

		$sql = sprintf("delete from budgets.budget_accounts where lnk='%s'",$lnk);
		$dbo->setQuery($sql);
		$result = $dbo->query();

		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateBudgetAlloc') {
		$sql = sprintf("select lnk,for_year,cost_code,account_code from budgets.budget_accounts where bud_acc_num = %d",$_POST['bud_acc_num']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$lnk = $row->lnk;
		$for_year=$row->for_year;
		$cc = $row->cost_code;
		$acc = $row->account_code;

		$sql = sprintf("update budgets.budget_accounts set budget_desc='%s',budget_amount=%0.2f,entree='%s',enter_time=now() where bud_acc_num=%d",$_POST['budget_desc'],$_POST['budget_amount'],$_GET['uid'],$_POST['bud_acc_num']);
		$dbo->setQuery($sql);
		$result = $dbo->query();


		$sql = sprintf("CALL budgets.proc_check_future_budgets('%s','%s',%d,'%s','%s',%0.2f,'%s')",$cc,$acc,intval($for_year+1),$lnk,$_POST['budget_desc'],$_POST['next_budget'],$_GET['uid']);
		//$fp = fopen('c:\temp\sql.txt','w');fwrite($fp,$sql);fclose($fp);
		$dbo->setQuery($sql);
		$result = $dbo->query();

		$sql = sprintf("CALL budgets.proc_check_future_budgets('%s','%s',%d,'%s','%s',%0.2f,'%s')",$cc,$acc,intval($for_year+2),$lnk,$_POST['budget_desc'],$_POST['next_next_budget'],$_GET['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->query();

		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createBudgetAlloc') {
		$lnk = md5(microtime());
			$next_budget = floatval($_POST['next_budget']);
			$next_next_budget = floatval($_POST['next_next_budget']);

			$sql = sprintf("insert into budgets.budget_accounts (cost_code,account_code,budget_desc,budget_amount,for_year,lnk,entree)
			values ('%s','%s','%s',%0.2f,%d,'%s','%s')",$_POST['cost_code'],$_POST['account_code'],$_POST['budget_desc'],$next_budget,(intval($_POST['for_year'])+1),$lnk,$_GET['uid']);
			//$fp = fopen('c:\temp\sql.txt','w');fwrite($fp,$sql);fclose($fp);
			$dbo->setQuery($sql);
			$result = $dbo->query();
			//if (!$result) { echo $dbo->getErrorMsg(); }


			$sql = sprintf("insert into budgets.budget_accounts (cost_code,account_code,budget_desc,budget_amount,for_year,lnk,entree)
			values ('%s','%s','%s',%0.2f,%d,'%s','%s')",$_POST['cost_code'],$_POST['account_code'],$_POST['budget_desc'],$next_next_budget,(intval($_POST['for_year'])+2),$lnk,$_GET['uid']);
			$dbo->setQuery($sql);
			$dbo->query();
			//if (!$result) { echo $dbo->getErrorMsg(); }

		$sql = sprintf("insert into budgets.budget_accounts (cost_code,account_code,budget_desc,budget_amount,for_year,lnk,entree)
		values ('%s','%s','%s',%0.2f,%d,'%s','%s')",$_POST['cost_code'],$_POST['account_code'],$_POST['budget_desc'],$_POST['budget_amount'],intval($_POST['for_year']),$lnk,$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();

		$lstid = $dbo->insertid();
		$sql = sprintf("select a.bud_acc_num,a.cost_code,a.account_code,a.for_year,a.budget_desc,a.budget_amount
			from budgets.budget_accounts a where a.bud_acc_num=%d",$lstid);
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displaySalaryBudgetAlloc') {
		$sql = sprintf("select a.cost_code,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as budget_staff,a.post
						from budgets.budget_salary_persons a
						left outer join staff.staff b on (a.emp_no = b.staff_no) where a.cost_code = '%s'",$_GET['cc']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		//$return['TotalRecordCount'] = $cnt;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'getAdminEmail') {
		$dbo->setQuery("select admin_email from budgets.budget_config limit 1");
		$row = $dbo->loadResult();
		if (strlen($row) < 5) { $row = 'admin@cput.ac.za'; }
		echo $row;
	}
	else if ($_GET['action'] == 'verifyAccountCode') {
		$sql = sprintf("select count(*) from budgets.account_codes a where a.fcdacc = '%s' and a.for_year=%d",$_GET['acc'],$_GET['yr']);
		$dbo->setQuery($sql);
		$rec = $dbo->loadResult();
		echo $rec;
	}
	else if ($_GET['action'] == 'requestAccountEmail') {
		$sql = sprintf("select a.userid from portal.cput_users_cellular a where lower(a.login) = '%s'",$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
			if ($dbo->getNumRows() == 0) {
				echo "-1";
			} else {
				$uid = $dbo->loadResult();
				$sql = sprintf("select email from portal.cput_users where id = %d",$_GET['id']);
				$dbo->setQuery($sql);
				$email = $dbo->loadResult();

				$sql = sprintf("select concat(a.staff_title,' ',a.staff_init,' ',a.staff_sname) as username from staff.staff a where a.staff_no=%d",$uid);
				$dbo->setQuery($sql);
				$uname = $dbo->loadResult();

				$dbo->setQuery("select admin_email from budgets.budget_config limit 1");
				$admin_email = $dbo->loadResult();

				$sql = sprintf("select a.dept_desc from structure.department a left join staff.staff b on (a.dept_code = b.dept_code) where b.staff_no=%d",$uid);
				$dbo->setQuery($sql);
				$dept = $dbo->loadResult();

				$sendTo = array();
				$sendTo[] = $admin_email;
				$addresses = serialize($sendTo);
				$details = $uname .' ('.$email.') has requested the following account code to be linked to the following cost centre.<br /><br />';
				$details = $details . 'Cost Centre: '.$_GET['cc'].'<br />';
				$details = $details . 'Account Code: '.$_GET['acc'].'<br /><br />';
				sendMail($addresses,'Budget Account Code Request.',$details);
			}
	}
	else if ($_GET['action'] == 'costCodeRequest') {
		$sql = sprintf("select a.userid from portal.cput_users_cellular a where lower(a.login) = '%s'",$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
			if ($dbo->getNumRows() == 0) {
				echo "-1";
			} else {
				$uid = $dbo->loadResult();
				$sql = sprintf("select email from portal.cput_users where id = %d",$_GET['id']);
				$dbo->setQuery($sql);
				$email = $dbo->loadResult();

				$sql = sprintf("select concat(a.staff_title,' ',a.staff_init,' ',a.staff_sname) as username from staff.staff a where a.staff_no=%d",$uid);
				$dbo->setQuery($sql);
				$uname = $dbo->loadResult();

				$dbo->setQuery("select admin_email from budgets.budget_config limit 1");
				$admin_email = $dbo->loadResult();

				$sql = sprintf("select a.dept_desc from structure.department a left join staff.staff b on (a.dept_code = b.dept_code) where b.staff_no=%d",$uid);
				$dbo->setQuery($sql);
				$dept = $dbo->loadResult();
				$sendTo = array();
				$sendTo[] = $admin_email;
				$addresses = serialize($sendTo);
				$details = $uname .' ('.$email.') has requested the following cost code to be added to his/her profile.<br /><br />';
				$details = $details . 'Cost Centre: '.$_GET['cc'].'<br />';
				sendMail($addresses,'Budget Cost Code Request.',$details);
			}
	}
	else if ($_GET['action'] == 'costCodeRequestAccess') {
		$sql = sprintf("select a.userid from portal.cput_users_cellular a where lower(a.login) = '%s'",$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
			if ($dbo->getNumRows() == 0) {
				echo "-1";
			} else {
				$uid = $dbo->loadResult();
				$sql = sprintf("select email from portal.cput_users where id = %d",$_GET['id']);
				$dbo->setQuery($sql);
				$email = $dbo->loadResult();

				$sql = sprintf("select count(*) from budgets.budget_users where staffno=%d",$uid);
				$dbo->setQuery($sql);
				$cnt = $dbo->loadResult();
				if ($cnt == 0) {
					$sql = sprintf("insert into budgets.budget_users (staffno) values (%d)",$uid);
					$dbo->setQuery($sql);
					$dbo->query();
				}
				$sql = sprintf("select id from budgets.budget_users where staffno=%d",$uid);
				$dbo->setQuery($sql);
				$id = $dbo->loadResult();

				$sql = sprintf("select count(*) from budgets.budget_users_costcodes where id=%d and cost_code='%s'",$uid,$_GET['cc']);
				$dbo->setQuery($sql);
				$cnt = $dbo->loadResult();
				if ($cnt == 0) {
					$sql = sprintf("insert into budgets.budget_users_costcodes (id,cost_code,idref) values (%d,'%s','%s')",$id,$_GET['cc'], md5(microtime()));
					$dbo->setQuery($sql);
					$dbo->query();
				}

				$sql = sprintf("select concat(a.staff_title,' ',a.staff_init,' ',a.staff_sname) as username from staff.staff a where a.staff_no=%d",$uid);
				$dbo->setQuery($sql);
				$uname = $dbo->loadResult();

				$dbo->setQuery("select admin_email from budgets.budget_config limit 1");
				$admin_email = $dbo->loadResult();

				$sql = sprintf("select a.dept_desc from structure.department a left join staff.staff b on (a.dept_code = b.dept_code) where b.staff_no=%d",$uid);
				$dbo->setQuery($sql);
				$dept = $dbo->loadResult();
				$sendTo = array();
				$sendTo[] = $admin_email;
				$addresses = serialize($sendTo);
				$details = $uname .' ('.$email.') has requested access to the following cost code.<br /><br />';
				$details = $details . 'Cost Centre: '.$_GET['cc'].'<br />';
				$details = $details . 'Motivation: '.$_GET['motive'].'<br />';
				//sendMail($addresses,'Budget Cost Code Access Request.',$details);
			}
	}
	else if ($_GET['action'] == 'displayStaffBudget') {
			$sql = sprintf("select count(*) from budgets.budget_staff a
						left outer join budgets.budget_accounts b on (a.cost_code=b.cost_code and a.account_code = b.account_code and a.for_year = b.for_year)
						where a.for_year=%d and a.cost_code='%s' and a.account_code='%s'",$_GET['yr'],$_GET['cc'],$_GET['acc']);
			$dbo->setQuery($sql);
			$cnt = $dbo->loadResult();

			$sql = sprintf("select distinct a.id,a.cost_code,a.account_code,a.staff_name,a.job_title,a.grade,a.reason,a.budget_alloc,b.bud_acc_num,b.budget_desc,b.budget_amount,a.for_year,a.staff_type from budgets.budget_staff a
						left outer join budgets.budget_accounts b on (a.budget_alloc=b.bud_acc_num)
						where a.for_year=%d and a.cost_code='%s' and a.account_code='%s'",$_GET['yr'],$_GET['cc'],$_GET['acc']);
			$dbo->setQuery($sql);
			$result = $dbo->loadObjectList();
			$return = array();
			$return['Result'] = 'OK';
			$return['Records'] = $result;
			$return['TotalRecordCount'] = $cnt;
			echo json_encode($return);
	}
	else if ($_GET['action'] == 'createStaffBudget') {
		$lnk = md5(microtime());
		$sql = sprintf("insert into budgets.budget_accounts(cost_code,account_code,budget_amount,for_year,budget_desc,lnk,entree) values ('%s','%s',%0.2f,%d,'%s','%s','%s')",
			$_POST['cost_code'],$_POST['account_code'],$_POST['budget_amount'],$_POST['for_year'],'New Staff - '.$_POST['staff_name'],$lnk,$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
		$dbo->setQuery("select LAST_INSERT_ID() from budgets.budget_accounts");
		$id = $dbo->loadResult();
		$sql = sprintf("insert into budgets.budget_staff(cost_code,account_code,staff_name,job_title,grade,reason,staff_type,for_year,budget_alloc,entree,budget_amount) values ('%s','%s','%s','%s','%s','%s','%s',%d,%d,'%s',%0.2f)",
			$_POST['cost_code'],$_POST['account_code'],$_POST['staff_name'],$_POST['job_title'],$_POST['grade'],$_POST['reason'],$_POST['staff_type'],$_POST['for_year'],$id,$_GET['uid'],$_POST['budget_amount']);
		$dbo->setQuery($sql);
		$dbo->query();
		$dbo->setQuery("select LAST_INSERT_ID() from budgets.budget_staff");
		$sid = $dbo->loadResult();
		$sql = sprintf("select distinct a.id,a.cost_code,a.account_code,a.staff_name,a.job_title,a.grade,a.reason,a.budget_alloc,b.bud_acc_num,b.budget_desc,b.budget_amount,a.for_year from budgets.budget_staff a
						left outer join budgets.budget_accounts b on (a.budget_alloc=b.bud_acc_num)
						where a.id = %d",$sid);
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteStaffBudget') {
		$sql = sprintf("select budget_alloc from budgets.budget_staff where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$alloc = $dbo->loadResult();
		$sql = sprintf("delete from budgets.budget_staff where id=%d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$sql = sprintf("delete from budgets.budget_accounts where bud_acc_num = %d",$alloc);
		$dbo->setQuery($sql);
		$dbo->query();

		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'validateAccount') {
		$utype = $_GET['usertype'];
		$acc = $_GET['acc'];
		if ($utype == 'adm'){
			$sql = sprintf("select count(*) as cnt from budgets.accounts_admin where account_code = %d",$acc);
		} else {
			$sql = sprintf("select count(*) as cnt from budgets.accounts_academic where account_code = %d",$acc);
		}
			$dbo->setQuery($sql);
			$cnt = $dbo->loadResult();
			echo $cnt;
		}
	
}

exit();



?>
