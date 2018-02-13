<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
//$user = & JFactory::getUser();
//$doc = & JFactory::getDocument();
//$doc->addScript('scripts/budgets/reports/index.js');
//$doc->addScript('scripts/json.js');

function sum_consol($cc_num, $old_cc, $sel_year){
	 $number=array();
	 $dbo =& JFactory::getDBO();
	 $sql = sprintf("select detail_cc from budgets.costcodes where consol_cc = '%s' order by 1 desc",$cc_num);
	 $dbo->setQuery($sql);
	 $dbo->query();
	 if ($dbo->getNumRows() > 0) {
	 	$result = $dbo->loadObjectList();
		foreach($result as $row){
			$numberT=sum_consol($row->detail_cc, $cc_num, $sel_year);
			  for ($j=0; $j<5;$j++){
			 	$number[$j]=$number[$j]+$numberT[$j];
			  }
		}

	 } else {

			//INCOME - 50033 is the Subsudy Income
			$SQL2 = "select sum(budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc between 50000 and 59999 and account_code != '50033' and account_code = fcdacc  and A.cost_code = '$cc_num'";
			$dbo->setQuery($SQL2);
			$res = $dbo->loadResult();
			$number[0] = $res;
			//EXPENSES

			$SQL2 = "select sum(budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc between 40000 and 49999 and account_code = fcdacc and  A.cost_code = '$cc_num'";
			$dbo->setQuery($SQL2);
			$res = $dbo->loadResult();
			$number[1] = $res;

		//CAPITAL
			$SQL2 = "select sum(budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc between 60330 and 86699 and account_code = fcdacc and display_rec = 1 and A.cost_code = '$cc_num'";
			$dbo->setQuery($SQL2);
			$res = $dbo->loadResult();
			$number[2] = $res;

		//MANPOWER
			$SQL2 = "select sum(budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc between 30000 and 39999 and account_code = fcdacc and display_rec = 1 and A.cost_code = '$cc_num'";
			$dbo->setQuery($SQL2);
			$res = $dbo->loadResult();
			$number[3] = $res;

		//SUBSIDY INCOME
			$SQL2 = "select sum(budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc=50033 and account_code = fcdacc and display_rec = 1 and A.cost_code = '$cc_num'";
			$dbo->setQuery($SQL2);
			$res = $dbo->loadResult();
			$number[4] = $res;
	}

			$bud_amnt = $number[0] + $number[4] - $number[1] - $number[2] - $number[3];

			$SQL = sprintf("insert into budgets.budget_consol (consol_cc, detail_cc, amount, for_year, income, expense, capital, manpower, fee_income) values ('%s','%s',%0.2f,%d,%0.2f,%0.2f,%0.2f,%0.2f,%0.2f)",$old_cc,$cc_num,$bud_amnt,
			$sel_year,$number[0],$number[1],$number[2],$number[3],$number[4]);
			//echo $SQL.'<br />';
			ob_flush();
			$dbo->setQuery($SQL);
			$dbo->query();

			return $number;
} //END Function sum*/


$sql = sprintf("select admin_email,cutoff,approve,allow_superusers,budget_cycle from budgets.budget_config where id = 1");
$dbo->setQuery($sql);
$row = $dbo->loadObject();
$sql = "delete from budgets.budget_consol where for_year = ".$row->budget_cycle;
$dbo->setQuery($sql);
$dbo->query();
$number = sum_consol('99CP', 0, $row->budget_cycle);
echo "Done";
exit();
?>