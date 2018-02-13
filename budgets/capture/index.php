<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
//$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/budgets/capture/index.js');
$doc->addScript('scripts/jtable/jquery.min.js');
$doc->addScript('scripts/jtable/jquery-ui.js');
$doc->addScript('scripts/jtable/jquery.jtable.js');
$doc->addScript('scripts/jtable/jquery.validationEngine.js');
$doc->addScript('scripts/jtable/jquery.validationEngine-en.js');
$doc->addScript('scripts/json.js');
$doc->addStyleSheet('scripts/jtable/themes/metro/blue/jtable.css');
$doc->addStyleSheet('scripts/jtable/jquery-ui.css');
//$doc->addStyleSheet('http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
$doc->addStyleSheet('scripts/jtable/validationEngine.jquery.css');
?>
<style type="text/css">
	.ui-dialog{font-size: 12px;}
</style>

<form id="user_id">
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<input type="hidden" id="user-id" value="<?php echo $user->id; ?>" />
<input type="hidden" id="budget-cycle" />
<input type="hidden" id="cutoff-date" />
<input type="hidden" id="super-users" />
<input type="hidden" id="staffno" />
<input type="hidden" id="admin-email" />
</form>

 <div class="art-block">
            <div class="art-block-tl"></div>

            <div class="art-block-tr"></div>
            <div class="art-block-bl"></div>
            <div class="art-block-br"></div>
            <div class="art-block-tc"></div>
            <div class="art-block-bc"></div>
            <div class="art-block-cl"></div>
            <div class="art-block-cr"></div>
            <div class="art-block-cc"></div>
            <div class="art-block-body">

        
            <div class="art-blockheader">
            <div class="l"></div>
            <div class="r"></div>
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
       <span id="budget-title"></span></h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

					<div id="budget-loader" style="display: none">
						<img src="images/kit-ajax.gif" width="16" height="11" border="0" />&nbsp;Busy...please wait.
					</div>
					
					<!--div style="padding: 5px; background-color: #ff4848; color: #ffffff; font-size: 14px">
						<p>Please note: Some Account/Cost code descriptions for 2016 has changed.</p>
					</div-->

					<div class="main-div" id="table-header1">
						<div class="main-header"><strong><span id="mtitle">Authorised Cost Codes for </span><span id="dept-name"></span></strong></div>
							<select name="cost_codes" id="cost-codes" size="1" style="width: 50%">
							</select>&nbsp;
							<input type="button" id="requestCostCode" class="button" value="Request Cost Code" />&nbsp;
							<input type="button" id="requestBudgetCostCode" class="button" value="Request Access" />
					</div>

					<div class="main-div" id="table-header2">
						<div class="main-header"><strong><span id="mtitle">Account Category</span></strong>
						</div><br />
							<select name="account_category" id="account-category" size="1" style="display: none; width: 50%">
								<option value="1" selected>Capital</option>
								<option value="2">Expenses</option>
								<option value="3">Income</option>
								<option value="4">Personnel Expenses [Read Only]</option>
								<option value="5">Personnel Expenses [New Staff]</option>
							</select>
					</div><br>

					<div id="filter-div" class="filtering" style="margin-bottom: 3px">
					<form>
						<span style="line-height: 24px">Account#: </span><input type="text" name="fcost" id="f-cost" size="5" />
						<button type="submit" id="LoadRecordsButton" class="button">Search Account Code...</button>&nbsp;
						<button type="button" id="requestAccountCode" class="button">Request Account Code...</button>
					</form>
					</div>

					<div id="tableData" style="position: relative; width: auto; height: auto"></div>

					<div id="dialog-form" title="Request Cost Code..." style="display: none">
						 <form>
							<fieldset>
							<label for="costcode_request" style="font-size: 12px; float: left">Cost Code</label><br />
							<span style="float: left"><input type="text" name="costcode_request" id="cost-code-request" onkeyup="this.value=this.value.toUpperCase()" style="font-size: 14px" size="4" maxlength="4" /></span>
							</fieldset>
						</form>
					</div>

					<div id="dialog-form-access" title="Request Access..." style="display: none">
						 <form>
							<fieldset>
							<label for="costcode_access_request" style="font-size: 12px; float: left">Cost Code</label><br />
							<span style="float: left"><input type="text" name="costcode_access_request" id="costcode-access-request" onkeyup="this.value=this.value.toUpperCase()" style="font-size: 14px" size="4" maxlength="4" /></span><br style="clear:both" />
							<label for="access_motiv" style="font-size: 12px; float: left">Motivation</label><br />
							<span style="float: left"><textarea name="access_motiv" id="access-motiv" rows="3" cols="30" style="font-size: 14px" /></textarea></span>
							</fieldset>
						</form>
					</div>
</div></div></div></div>

<div id="user-type" style="display: none">
	<p>Please select your appointment type...</p><br />
	<input type="radio" name="utype" id="u-type1" checked value="acd" />Academic&nbsp;&nbsp;
	<input type="radio" name="utype" id="u-type2" value="adm" />Administration
</div>


<div id="user-notice" style="display: none">
	<p style="font-size: 14px; font-weight: bold">IMPORTANT NOTICE</p>
	<p>The budget allocations will close on Thursday, 07 July 2016 at 16h30.</p>
	<p>Please make sure that you have reviewed your complete budget</p>
	<p>before this time. Should any information be missing, please re-capture.</p>
</div>


	
