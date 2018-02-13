<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/budgets/actuals.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Budget Summary)");
$dbo->query();
?>

<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<a href="#" id="lnk" class="modalizer"></a>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Budget Summary" />
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
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Budget Summary Report</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">


<div class="main-div" id="sum-heading" style="padding: 10px 10px 10px 16px; display: block">
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

				<div style="position: relative; width: auto; border: 2px solid #3C619C; background-color: #FFFFFF; padding: 3px; margin: 5px 0 5px 0; height: auto; display: block">

							<div style="float: left; width: auto; height: auto;  margin: 0 3px 0 3px; vertical-align: middle; border: 1px solid #d8d8d8; padding: 3px; background-color: #e8e8e8">
							<?php	
							$y = date('Y');
							echo "<select name='year' id='year-select' size='1' name='cyear' style='width: auto'>";
							for($i=$y-2;$i< ($y+1);$i++){
								if ($i == ($y)) {
									echo "<option value='".$i."' selected>".$i."</option>";
								} else {
									echo "<option value='".$i."'>".$i."</option>";
								}
							}
							echo "</select>&nbsp;<strong><i>Budget Year</i></strong>";
							?>
							</div>

							<div style="float: left; width: auto; height: auto; margin: 0 3px 0 3px; vertical-align: middle; border: 1px solid #d8d8d8; padding: 3px; background-color: #e8e8e8">
								<strong><i>Cost Code:</i></strong>&nbsp;<input type="text" size="5" class="input_select" id="cost-code" maxlength="5" />
							</div>

							<div style="float: left; width: auto; height: auto; margin: 0 3px 0 3px; vertical-align: middle; border: 1px solid #d8d8d8; padding: 3px; background-color: #e8e8e8">
								<strong><i>Summary:</i></strong>&nbsp;<input type="checkbox" checked id="summary" disabled>
							</div>
							<!--div style="float: left; width: auto; height: auto; margin: 0 3px 0 3px; vertical-align: middle; border: 1px solid #d8d8d8; padding: 3px; background-color: #e8e8e8">
								<strong><i>CAPEX Only:</i></strong>&nbsp;<input type="checkbox" id="capex">
							</div-->

							<div style="float: left; width: auto; height: auto; margin: 1px 3px 0 3px; vertical-align: middle">
								<input type="button" value="View Report" class="button -art-button" id="run-report" /> 
							</div>

							<div style="clear: both"></div>
				</div>

	<div id="ajax-budget" style="position: relative; width: auto; height: auto; display: none">
				<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
			</div>
	<div  id="actuals" style="position: relative; width: auto; height: auto; display: block">
	</div>
			

			<div  id="details" style="overflow: auto; position: relative; width: auto; height: auto; display: none">
			</div>


<p>To print the background colours on the report, click on the 'Print Report' button (on the next screen) and select the tab which enables you to select the 'Print Background Colors' option.
Detailed reports can take up to 1 minute to generate.
</div>

</div></div></div></div>