<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/budgets/budgets.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/datepick/Locale.en-US.DatePicker.js");
$doc->addScript("scripts/datepick/Picker.js");
$doc->addScript("scripts/datepick/Picker.Attach.js");
$doc->addScript("scripts/datepick/Picker.Date.js");
$doc->addStyleSheet("scripts/datepick/datepicker.css");
$dbo->setQuery("select allow_budget,ifnull(budget_year,year(now())) as budget_year,budget_cutoff from cput_system_setup where system_name = 'budgets'");
$row = $dbo->loadObject();
$budget_year = $row->budget_year;
$budget_cutoff = $row->budget_cutoff;
?>
<input type="hidden" id="allow-budget" value="<?php echo $row->allow_budget; ?>" />
<input type="hidden" id="empno" value="" />
<input type="hidden" id="fac" value="" />
<a id="show-permissions" class="modalizer" href="#"></a>
<form id="user_id">
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="username" value="<?php echo $user->username; ?>" />
</form>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Budgets" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
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
       Budget Allocations</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
	

<div id="permissions" class="main-div" style="display: none">
	<div class="main-header"><strong><span id="del-heading">Cost Centre Allocation</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<input type="button" value="Grant Permission" class="button art-button" id="grant-permission" /><br />
<p>The budget allocation process for year <b><?php echo $budget_year; ?></b> is now open. Please grant yourself permission to your respective cost centres. Cut-off date for budget period is <?php echo "<b>".$budget_cutoff."</b>"; ?></p>
</div>

<!-----Budget Allocations-->
<div id="budget-allocations" class="main-div" >
	<div class="main-header"><strong><span id="del-heading">Budget Allocations</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<div id="budget-busy" style="display: none; position: absolute; top:	 10px; left: 10px; width: auto; height: auto; background-color: #bddeff; border: 2px solid #6eb7ff; padding: 2px">
	<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;<span id="msg">Loading...</span>	
	</div>
	<label class="input_label">Select Cost Code:</label>
		<select name="cc_name" id="cc-name" size="1" class="input_select" style="width: 50%">
		</select><br style="clear: both" />
		<p><b>Account Category</b></p>
	<div style="position: relative; width: auto; height: auto; border: 1px solid #b8b8b8; background-color: #e8e8e8; padding: 5px">
		<input type="radio" name="acc_name"  value="staff" />Staff Members&nbsp;&nbsp;
		<input type="radio" name="acc_name" value="expenses" checked />Expenses&nbsp;&nbsp;
		<input type="radio" name="acc_name" value="capital"/>Capital&nbsp;&nbsp;
		<input type="radio" name="acc_name" value="manpower"/>Man Power&nbsp;&nbsp;
		<input type="radio" name="acc_name" value="capital"/>Capital&nbsp;&nbsp;
		<input type="radio" name="acc_name" value="all"/>Everything
	</div>

</div>

</div></div></div></div>
