<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/json.js');

$code_no = $_POST['cost_code'];
$yr = $_POST['budget_year'];

$sql = sprintf("select cc_name from budgets.costcodes where detail_cc = '%s'",$code_no);
$dbo->setQuery($sql);
$cc_name = $dbo->loadResult();

$sql = sprintf("select distinct A.consol_cc, A.detail_cc, B.cc_name, A.amount, A.income, A.expense, A.capital, A.manpower, A.fee_income from budgets.budget_consol A,
budgets.costcodes B where A.consol_cc = '%s' and A.detail_cc = B.detail_cc
and A.consol_cc = B.consol_cc and A.for_year = %d and A.detail_cc not IN ('A999', 'E999')",$code_no,$yr);
$dbo->setQuery($sql);

$html .= '<div style="border: 1px solid #c8c8c8; margin-bottom: 5px; padding: 5px; font-size: 12px; font-family: verdana,arial">';
$html .= 'Consolidated Report for '.$cc_name.' ['.$code_no.']</div>';
$html .= '<div style="text-align: left"><input type="button" value="Back" onclick="javascript: window.history.back();" /></div>';

$html .=  "<table width='100%' cellpadding=2 cellspacing=5 style='font-size: 12px; font-family: verdana,arial; border: 1px solid #c8c8c8'>";
$html .= "<tr><td><b>Cost Code</b></td><td><b>Name</b></td><td><b>Balance</b></td><td><b>Less Capital</b></td><td><b>Income</b></td>";
$html .= "<td><b>Subsidy</b></td><td><b>Expense</b></td><td><b>Manpower</b></td><td><b>Capital</b></td></tr>";
$dbo->query();
$result = $dbo->loadObjectList();
foreach($result as $row){

	$bal2 = $row->income + $row->fee_income - $row->expense - $row->manpower;
    $html .= "<tr><td style='border: 1px solid #c8c8c8'>";
    $html .= '<form name="back_frm" action="/scripts/budgets/reports/consolidated2.php" method="post">';
    $html .= '<input type="hidden" name="cost_code" value="'.$row->detail_cc.'">';
    $html .= '<input type="hidden" name="budget_year" value="'.$yr.'">';
    $html .= '<input type=submit value="'.$row->detail_cc.'">';
    $html .= "</form>";
    $html .= "</td><td style='border: 1px solid #c8c8c8' nowrap>$row->cc_name</td><td style='border: 1px solid #c8c8c8' align=right nowrap>".Number_format($row->amount,2)."</td><td style='border: 1px solid #c8c8c8' align=right nowrap>".Number_format($bal2,2)."</td><td style='border: 1px solid #c8c8c8' align=right nowrap>".Number_format($row->income,2)."</td>";
    $html .= "<td style='border: 1px solid #c8c8c8' align=right nowrap>".Number_format($row->fee_income,2)."</td><td style='border: 1px solid #c8c8c8' align=right nowrap>".Number_format($row->expense,2)."</td><td style='border: 1px solid #c8c8c8' align=right nowrap>".Number_format($row->manpower,2)."</td><td style='border: 1px solid #c8c8c8' align=right nowrap>".Number_format($row->capital,2)."</td></tr>";
	}
$html .= '</table>';
echo $html;
?>
