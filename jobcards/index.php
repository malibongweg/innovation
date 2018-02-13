<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
///jTable includes/////
$doc->addScript('scripts/jobcards/jtable/js/jquery-1.10.2.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery-ui-1.10.4.js');
$doc->addScript('scripts/jobcards/jtable/jquery.jtable.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery.validationEngine.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery.validationEngine-en.js');

$doc->addStyleSheet('scripts/jobcards/jtable/css/custom-theme/jquery-ui-1.10.4.custom.css');
$doc->addStyleSheet('scripts/jobcards/jtable/themes/metro/blue/jtable.css');
$doc->addStyleSheet('scripts/jobcards/jtable/css/validationEngine.jquery.css');

$doc->addScript("scripts/jobcards/jc2.js");
$doc->addScript("scripts/json.js");
?>

<script type="text/javascript">
function refreshTable(){
	jt('#tableJobcards').jtable('reload');
}
</script>
<a href="#" class="modalizer" id="prn-link"></a>
<a href="#" class="modalizer" id="jobcard-details-link"></a>
<a href="#" class="modalizer" id="jobcard-artisans"></a>
<form id="user_id">
<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />
<input type="hidden" id="staffno" value="" />
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
</form>

<div id="new-job-ajax" style="top: 10px; left: 10px; z-order: 1000; border: 1px solid #c8c8c8; background-color: #d8d8d8;padding: 3px; display: none">
	<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Sending email...Please wait.
</div>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Jobcards</a></li>
		<li><a href="#tabs-2">Suppliers</a></li>
		<li><a href="#tabs-3">Reports</a></li>
		<li><div style="font-weight: normal; color: #000000; padding: 3px 0 0 10px">
		Campus <select id="job-campus" size="1" style="width: 70%; display: inline-block"></select>
		</div></li>
	</ul>

	<div id="tabs-1">

	<div style="float: left; border: 1px solid #c8c8c8; background-color: #d9d9d9; padding: 3px">
	<form>
		Jobcard Filter: <input type="text" id="job-filter" name="job_filter" size="4" />
		<input type="button" value="Go!" id="filter-button" class="button" />
	</form>
	</div>
	<div style="float: right; padding: 3px">
			<div style="float: right; display: inline-block; border: 1px solid #000000; background-color: #000000; width: 55px; height: 14px; padding: 3px; margin: 0 3px 0 3px; font-size: 8px; font-weight: bold; text-align: center; color: #ffffff" >CANCELLED</div>
			<div style="float: right; display: inline-block; border: 1px solid #000000; background-color: #ff8888; width: 55px; height: 14px; padding: 3px; margin: 0 3px 0 3px; font-size: 8px; font-weight: bold; text-align: center; color: #000000" >ESCALATED</div>
			<div style="float: right; display: inline-block; border: 1px solid #000000; background-color: #5983ff; width: 55px; height: 14px; padding: 3px; margin: 0 3px 0 3px; font-size: 8px; font-weight: bold; text-align: center; color: #ffffff" >DELAYED</div>
			<div style="float: right; display: inline-block; border: 1px solid #000000; background-color: #ffffff; width: 55px; height: 14px; padding: 3px; margin: 0 3px 0 3px; font-size: 8px; font-weight: bold; text-align: center" >COMPLETED</div>
			<div style="float: right; display: inline-block; border: 1px solid #000000; background-color: #ffff99; width: 50px; height: 14px; padding: 3px; margin: 0 3px 0 3px; font-size: 8px; font-weight: bold; text-align: center" >ASSIGNED</div>
			<div style="float: right; display: inline-block; border: 1px solid #000000; background-color: #66ff66; width: 50px; height: 14px; padding: 3px; margin: 0 3px 0 3px; font-size: 8px; font-weight: bold; text-align: center" >NEW</div>
		</div>

	<div id="tableJobcards" style="margin: 65px 0 0 0">
	</div>
	</div>

	<div id="tabs-2">
		<div id="tableSuppliers">
		</div>
	</div>

	<div id="tabs-3">
		<a class="modal" href="/scripts/jobcards/rep_jobstatus.php">Pie Chart - Job status</a><br />
		<a class="modal" href="/scripts/jobcards/rep_ratings.php">Pie Chart - Job ratings</a><hr />
		<a href="#" id="rep2" onclick="javascript: openJobcardsParams();">Jobcards report for selected Campus</a><br />
		<a href="#" id="rep1" onclick="javascript: openArtisanRepParams();">Artisans - Labour hours for selected Campus</a><br />
		<a href="#" id="rep3" onclick="javascript: openBuildingRepParams();;">Cost per building for selected Campus</a><br />
	</div>

</div>

<div id="dialog-form-completion" style="display: none">
	<form id="completion-report-params">
		 <fieldset style="text-align: left">
		 		<p style="font-size: 14px; font-weight: bold">Labour Costs</p>
		 		<div id="setArtisanList" style="font-size: 12px"></div>
		 		<div id="jobMaterialCosts" style="font-size: 12px">
		 		<table width="100%"><tr><td width="60%" style="font-size: 14px; font-weight: bold">Material Costs</td>
		 		<td width="25%"><input type="text" style="width: auto; text-align: right" value="0" id="material-costs-form" /></td>
		 		<td width="15%">&nbsp;</td>
		 		</table>

		 		</div>
		 		<div id="jobCosts" style="border-top: 1px solid #000000; font-size: 12px">
		 		<table width="100%"><tr><td width="60%" style="font-size: 14px; font-weight: bold">Total:</td>
		 		<td width="25%"><input type="text" id="costTotal" value="" /></td>
		 		<td width="15%"><input type="button" value="Calc" id="calc-Costs" class="button" onclick="javascript: calcCosts();" /></td>
		 		</table>
		 		</div>
		</fieldset>
	</form>
</div>

<div id="dialog-report-artisan" style="font-size: 12px; display: none">
	<form id="report-artisan-form" target="_blank" action="/scripts/jobcards/rep_artisans.php" method="post">
		<fieldset style="text-align: left">
			<p>Report Range</p>
			<p><input type="text" size="10" name="start_date" id="start-date" /> to <input type="text" size="10" name="end_date" id="end-date" /></p><br />
			<p>Artisans</p>
			<p><span id="select-artisan"></span></p>
		</fieldset>
	</form>
</div>

<div id="dialog-delay-assign" style="font-size: 12px; display: none; text-align: center">
<p>Do you want to add a delay entry or re-assign?</p>
</div>

<div id="dialog-cost-report" style="font-size: 12px; display: none; text-align: center">
<fieldset style="text-align: left">
			<p>Report Range</p>
			<p><input type="text" size="10" name="cost_start_date" id="cost-start-date" /> to <input type="text" size="10" name="cost_end_date" id="cost-end-date" /></p><br />
		</fieldset>
</div>

<div id="dialog-open-report" style="font-size: 12px; display: none; text-align: center">
<fieldset style="text-align: left">
			<p>Report Range</p>
			<p><input type="text" size="10" name="open_start_date" id="open-start-date" /> to <input type="text" size="10" name="open_end_date" id="open-end-date" /></p><br />
			<p>Select Jobcard Type</p>
			<p><input type="radio" name="jotype" value="0" checked />All Jobcards<br />
			<input type="radio" name="jotype" value="1" />Open Jobcards<br />
			<input type="radio" name="jotype" value="2" />Closed Jobcards<br /><br />
			<input type="checkbox" name="jo_urgent" id="jo-urgent" />Urgent?
		</fieldset>
</div>

