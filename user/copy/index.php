<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/user/copy/copy.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Copy Account')");
$dbo->query();
?>
<a href="#" class="modalizer" id="monthly-link"></a>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Copy Account" />
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
      Photo Copy Transactions</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div" id="table-header">
	<div class="main-header"><strong><span id="mtitle">Current Transactions</span></strong></div>
	
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

				<div style="width: auto; border: 2px solid #3C619C; background-color: #FFFFFF; padding: 3px; margin: 5px 0 5px 0">
					<?php				
					echo "<select name='mth' id='month-select' size='1' style='width: auto'>";
					for($i=1;$i<13;++$i){
						if (intval($i) == intval(date('m'))) {
							echo "<option value='".$i."' selected>".$i."</option>";
						} else {
							echo "<option value='".$i."'>".$i."</option>";
						}
					}
					echo "</select>&nbsp;<strong><i>Transaction Month (Current Year Only)</i></strong>";
					?>
					
				</div>
		
				<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
				<table style="width: 100%; height: auto; border: 0px; table-layout: fixed">
				<tr>
					<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">DATE</th>
					<th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">POINTID</th>
					<th style="width: 40%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">DETAILS</th>
					<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: right">AMOUNT</th>
				</tr>
				</table>
				</div>
					<div id="ajax-copy" style="display: none; position: relative">
						<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading Data...
					</div>
						<div id="data-div" style="position: relative; width: auto; height: auto; overflow: scroll">
						</div>
</div>

</div></div></div></div>