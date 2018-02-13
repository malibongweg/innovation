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
?>
<style>
	body {
		font-size: 12px;
		font-family: Verdana, Arial
	}
</style>
<script type="text/javascript" src="/media/system/js/core.js"></script>
<script type="text/javascript" src="/media/system/js/mootools-core.js"></script>
<script type="text/javascript" src="/media/system/js/mootools-more.js"></script>

<?php
if (!isset($_POST['code_no'])){
?>
<div style="text-align: center; margin: 0 auto; font-size: 12px" id="rep-content">
	<p>Select cost centre and report year.</p>
	<form id="consol-params" action="/scripts/budgets/reports/consol_detailed.php" method="post">
	<input type="text" name="code_no" id="cost-code" size="4" maxlength="4" onKeyUp="javascript: this.value=this.value.toUpperCase();" /><br /><br />
	<select name="budget_year" id="budget-year" size="1">
		<?php
			$yr = intval(date('Y'));
			for ($i = ($yr+1); $i > ($yr-2); $i--){
				echo '<option value="'.$i.'">'.$i.'</option>';
			}
		?>
	</select><br /><br />
	<input type="submit" value="Run Report" />&nbsp;
	<input type="button" value="Close" onclick="javascript: window.parent.$j.colorbox.close();" />
	</form>
</div>
<script type="text/javascript">
	window.parent.$j.colorbox.resize({ 'width': '30%','height': '30%' });
	$('cost-code').focus();
</script>
<?php
 } else {
?>
<script type="text/javascript">
	window.parent.$j.colorbox.resize({ 'width': '60%','height': '70%' });
</script>
<?php
$code_no = $_POST['code_no'];
$yr = $_POST['budget_year'];

if (isset($_POST['exp_code'])){
	$exp_code = $_POST['exp_code'];
	if (intval($exp_code) > 0)	{

		echo '<form name="show_details" action="/scripts/budgets/reports/consol_detailed_rep.php" method="post">';
  		echo '<input type="hidden" name="code_no" value="'.$code_no.'">';
  		echo '<input type="hidden" name="sel_year" value="'.$yr.'">';
    	echo '<input type="hidden" name="exp_code" value="'.$exp_code.'">';
  		echo '<input type=submit value="Please wait...calculating">';
		echo '</form>';
		?>
		<script type="text/javascript">
		document.forms['show_details'].submit();
		</script>
		<?php
	exit();
	}

}
$sql = sprintf("select cc_name from budgets.costcodes where detail_cc = '%s'",$code_no);
$dbo->setQuery($sql);
$cc_name = $dbo->loadResult();
echo '<div style="border: 1px solid #c8c8c8; margin-bottom: 5px; padding: 5px; font-size: 12px; font-family: verdana,arial">';
echo 'Consolidated Report for '.$cc_name.' ['.$code_no.']</div>';
echo '<div style="text-align: left"><input type="button" value="Back" onclick="javascript: window.history.back();" /></div>';


$sql = sprintf("select distinct A.consol_cc, A.detail_cc, B.cc_name, A.amount, A.income, A.expense, A.capital, A.manpower, A.fee_income from budgets.budget_consol A,
budgets.costcodes B where A.consol_cc = '%s' and A.detail_cc = B.detail_cc
and A.consol_cc = B.consol_cc and A.for_year = %d and A.detail_cc not IN ('A999', 'E999')",$code_no,$yr);
$dbo->setQuery($sql);


echo  "<table width='100%' cellpadding=2 cellspacing=5 style='font-size: 12px; font-family: verdana,arial; border: 1px solid #c8c8c8'>";
echo "<tr><td><b>Cost Code</b></td><td><b>Name</b></td><td><b>Balance</b></td><td><b>Less Capital</b></td><td><b>Income</b></td>";
echo "<td><b>Subsidy</b></td><td><b>Expense</b></td><td><b>Manpower</b></td><td><b>Capital</b></td></tr>";
$dbo->query();
$result = $dbo->loadObjectList();
    echo '<form name="consol" action="/scripts/budgets/reports/consol_detailed.php" method="post">';
    echo '<input type="hidden" name="code_no" value="'.$row->detail_cc.'">';
    echo '<input type="hidden" name="budget_year" value="'.$yr.'">';
    echo '<input type="hidden" name="exp_code">';

foreach($result as $row){
	$bal2 = $row->income + $row->fee_income - $row->expense - $row->manpower;
	echo "<tr><td style='border: 1px solid #c8c8c8'>";
	echo '<input type=submit value="'.$row->detail_cc.'" onclick="javascript:document.forms[\'consol\'].code_no.value=\''.$row->detail_cc.'\'" /></td>';
    echo '<td style="border: 1px solid #c8c8c8" nowrap>'.$row->cc_name.'</td><td style="border: 1px solid #c8c8c8" align=right nowrap>'.Number_format($row->amount,2).'</td><td style="border: 1px solid #c8c8c8" align=right nowrap>'.Number_format($bal2,2).'</td>';
    echo '<td style="border: 1px solid #c8c8c8" align=right nowrap><input type="submit" value="'.number_format($row->income,2).'" onclick="javascript:document.forms[\'consol\'].code_no.value=\''.$row->detail_cc.'\'; document.forms[\'consol\'].exp_code.value=1;"></td>';
    echo '<td style="border: 1px solid #c8c8c8" align=right nowrap><input type="submit" value="'.number_format($row->fee_income,2).'" onclick="javascript:document.forms[\'consol\'].code_no.value=\''.$row->detail_cc.'\'; document.forms[\'consol\'].exp_code.value=2;"></td>';
    echo '<td style="border: 1px solid #c8c8c8" align=right nowrap><input type="submit" value="'.number_format($row->expense,2).'" onclick="javascript:document.forms[\'consol\'].code_no.value=\''.$row->detail_cc.'\'; document.forms[\'consol\'].exp_code.value=3;"></td>';
    echo '<td style="border: 1px solid #c8c8c8" align=right nowrap><input type="submit" value="'.number_format($row->manpower,2).'" onclick="javascript:document.forms[\'consol\'].code_no.value=\''.$row->detail_cc.'\'; document.forms[\'consol\'].exp_code.value=4;"></td>';
    echo '<td style="border: 1px solid #c8c8c8" align=right nowrap><input type="submit" value="'.number_format($row->capital,2).'" onclick="javascript:document.forms[\'consol\'].code_no.value=\''.$row->detail_cc.'\'; document.forms[\'consol\'].exp_code.value=5;"></td>';
    echo '</tr>';
	}
echo '</form></table>';

}
?>

