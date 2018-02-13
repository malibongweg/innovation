<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);


if (isset($_GET)) {
	if ($_GET['action'] == 'fee_account') {
		$dbo->setQuery("select connect_string,user_name,password from #__system_setup where system_name = 'its'");
		$row = $dbo->loadObjectList();
		$cs = $row[0]->connect_string;
		$uname = $row[0]->user_name;
		$pass = $row[0]->password;
		$data = array();
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			  echo "99999";
			exit();
		}
		$sql = "select sum(decode(iamtype,'D',iamamt,-iamamt)) as bfbal from stud.iamlog,finc.fujacd where fujacctyp = iamacctyp and iamstno = ".$_GET['acc']." and  nvl(fujfee,'F') = 'F' and nvl(iamshow,'Y') = 'Y' and iamdate <= to_date('01-01-".date('Y')."','dd-mm-yyyy')";
		$result = oci_parse($con,$sql);
		$x = oci_execute($result);
		//if (!$x) { $e = oci_error($x); echo $e['message']."Error1"; exit(); }
		$row = oci_fetch_object($result);
		if (!$row) {  echo "99999"; exit();	}
		echo number_format($row->BFBAL,2,'.','');
	}
	else if ($_GET['action'] == 'transactions') {
		$dbo->setQuery("select connect_string,user_name,password from #__system_setup where system_name = 'its'");
		$row = $dbo->loadObjectList();
		$cs = $row[0]->connect_string;
		$uname = $row[0]->user_name;
		$pass = $row[0]->password;
		$data = array();
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "99999";
			exit();
		}
		$sql = "select to_char(iamdate,'dd-mon-yyyy') as trandate,iamrefno,decode(iamtype,'D',iamamt,-iamamt) as amt, substr(nvl(iamnote,fclname),1,28) as trandesc,'F' as acctype from stud.iamlog,finc.fcltrn,finc.fujacd where iamstno = ".$_GET['acc']." and iamcode = fcltrn(+) and iamacctyp = fujacctyp and nvl(fujfee,'F') = 'F' and nvl(iamshow,'Y') = 'Y' and iamdate >= to_date('01-01-".date('Y')."','dd-mm-yyyy') order by iamdate";
		//echo $sql; exit();
		$result = oci_parse($con,$sql);
		$x = oci_execute($result);
		if (!$x) { $e = oci_error($result); echo $e['message']; exit(); }
		while ($row = oci_fetch_object($result)) {
			$data[] = $row->TRANDATE.";".$row->IAMREFNO.";".$row->TRANDESC.";".number_format($row->AMT,2,'.','');
		}
		echo json_encode($data);
	}

	else if ($_GET['action'] == "display_account") {
				$bf = false;
				$display_year = $_GET['yr']."-01-01";
				$data = array();
				$sql = "SELECT distinct fin_date, fin_fee, fin_type, fin_refno, fin_amount, fin_desc from student.finance a left join structure.transdef e on (fin_code = transno), structure.accdef WHERE stud_numb = ".$_GET['acc']." AND ifnull(trans_evnt,'x') not in ('26','36') AND (    (    ifnull(trans_evnt,'x') in ('TC','TD') AND exists (SELECT 1 FROM student.finance b, structure.accdef c, structure.transdef d WHERE b.stud_numb = a.stud_numb AND b.fin_acctyp = c.accno AND b.fin_refno = a.fin_refno AND b.fin_amount = a.fin_amount AND ifnull(c.acc_fee,'F') = 'D' AND d.transno = b.fin_code AND ifnull(d.trans_evnt,'x') in ('TC','TD'))) OR  (    ifnull(e.trans_evnt,'x') not in ('TC','TD'))) AND fin_acctyp = accno AND ifnull(acc_fee,'F') = 'F' UNION ALL SELECT distinct fin_date, fin_fee, fin_type, fin_refno, sum(fin_amount) as fin_amount, fin_desc FROM student.finance a left join structure.transdef on (fin_code = transno), structure.accdef WHERE stud_numb = ".$_GET['acc']." AND ifnull(trans_evnt,'x') in ('26','36') AND fin_acctyp = accno AND ifnull(acc_fee,'F') = 'F' GROUP BY fin_date, fin_fee, fin_type, fin_refno, fin_desc ORDER BY 1,4";
				//echo $sql;exit();
				$dbo->setQuery($sql);
				$result = $dbo->loadObjectList();
					if (!$result) { $data[] = "-1"; echo json_encode($data); exit(); }
				foreach($result as $row) {
						$credit = ""; $debit = "";
					if($row->fin_type == "C"){
						$credit = $row->fin_amount;
                        $totcred += $credit;
					} else if($row->fin_type == "D"){
						$debit = $row->fin_amount;
                        $totdeb += $debit;
					}
						if(strtotime($row->fin_date) >= strtotime($display_year)){
								if ($bf == false) {
									$DBal = abs( ($totcred - $credit) - ($totdeb -$debit));
								if( $totcred > $totdeb)
								  {
									  $data[0] = ";;;;;C;".number_format($DBal,2);
									  $bf = true;
								  }
								else
								  {
									  $data[0] = ";;;;;D;".number_format($DBal,2);
									  $bf = true;
								  }
								}
							  $data[] = $row->fin_date.";".$row->fin_refno.";".$row->fin_desc.";".$debit.";".$credit.";;";
						}
					
				}
				 $DBal = abs( $totcred - $totdeb);
				if( $totcred > $totdeb)
				{
					$data[9000] = ";;;;;C;".number_format($DBal,2);
				}
			  else
				{
				  	$data[9000] = ";;;;;D;".number_format($DBal,2);
				}
				echo json_encode($data);
	}
	
}
exit();

?>