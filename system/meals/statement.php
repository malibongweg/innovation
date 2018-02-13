<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/system/meals/meals.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Meals Account')");
$dbo->query();
?>
<a href="#" class="modalizer" id="monthly-link"></a>
<a href="#" class="modalizer" id="journal-link"></a>
<a href="#" class="modalizer" id="validate_student"></a>
<a href="#" class="modalizer" id="lnk-print"></a>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<input type="hidden" id="cardmag"/>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Meals Account" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="form-action" value="" />
<input type="hidden" id="record-id" value="0" />
<input type="hidden" id="x-cord" />
<input type="hidden" id="y-cord" />
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
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Meals Admin</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div" id="table-header">
	<!--div class="main-header"><strong><span id="mtitle">Current Transactions</span></strong></div-->
	
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

		<div id="user-balance" style="position: relative; display: none">
			<strong><span id="show-balance"></span></strong>
		</div>
				<div style="width: auto; border: 2px solid #3C619C; background-color: #FFFFFF; padding: 3px; margin: 5px 0 5px 0">
					<input type="text" name="stdno" id="student-no" size="12" class="input_text" maxlength="12" onKeyUp="javascript: this.value=this.value.toUpperCase();" />&nbsp;<b>Student/Journal#</b>&nbsp;&nbsp;
					<?php				
					echo "<span id='params' style='display: none'/><select name='mth' id='month-select' size='1' style='width: auto'>";
					for($i=1;$i<13;++$i){
						if (intval($i) == intval(date('m'))) {
							echo "<option value='".$i."' selected>".$i."</option>";
						} else {
							echo "<option value='".$i."'>".$i."</option>";
						}
					}
					echo "</select>&nbsp;";
					echo "<b>Month</b>&nbsp;";
					$cyear = Date("Y");
					echo "<select name='cyear' id='year-select' size='1' style='width: auto'>";
					for($i=2010;$i<$cyear+1;++$i){
						if (intval($i) == intval($cyear)) {
							echo "<option value='".$i."' selected>".$i."</option>";
						} else {
							echo "<option value='".$i."'>".$i."</option>";
						}
					}
					echo "</select>&nbsp;<b>Year</b></span>";
					?>
					
				</div>

				<div id="barcheck" style="position: absolute; top: 20px; left: 20px; border: 2px solid #003162; z-index: 1000; padding: 5px; background-color: #0055aa; color: #ffffff; display: none"><img src="/images/kit-ajax.gif" width="16" height="11" border="0" style="vertical-align: middle">&nbsp;<span id="barmag">Checking Status/Barcode...</span></div>

				<div style="width: auto; background-color: #FFFFFF; padding: 0px; margin: 3px 0 5px 0">
					<input type="button"  value="Get Account Details" id="button-details" />&nbsp;
					<input type="button"  value="Print Transactions" id="button-print" />&nbsp;
					<input type="button" value="Account Status" id="button-status" />&nbsp;
					<input type="button"  value="Sync Barcode" id="button-barcode" />&nbsp;
					<br />
					<input type="button" value="Import Journal" id="button-journal" />
				</div>

				<div id="student-details" style="margin: 0 3px 3px 3px; position: relative; padding: 5px; background-color: #3C619C; color: #ffffff; display: none">
				  <span id="mtitle" style="font-weight: bold"></span>
				</div>
		
				<div id="table-header2" style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
				<table style="width: 100%; height: auto; border: 0px; table-layout: fixed">
				<tr>
					<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">DATE</th>
					<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">TIME</th>
					<th style="width: 40%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">PURCHASE POINT</th>
					<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: right">AMOUNT</th>
				</tr>
				</table>
				</div>
					<div id="ajax-meals" style="display: none; position: relative">
						<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading Data...
					</div>
						<div id="data-div" style="position: relative; width: auto; height: auto; overflow: scroll">
						</div>
</div>

</div></div></div></div>