<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/user/internet_logs.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Internet Usage')");
$dbo->query();
?>

<a href="#" class="modalizer" id="credit-lnk" /></a>
<form id="app-details">
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="login-name" value="<?php echo $user->username; ?>" />
<input type="hidden" id="app-name" value="Internet Usage" />
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
      Internet Usage Logs</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

<div style="position: relative; margin-bottom: 3px; width: auto; height: auto">
	<input type="button" class="button" value="View Credit History" id="credit-history" />
</div>

<div id="date-select" style="position: relative; width: auto; height: auto; background-color: #ffffff; border: 1px solid #ddddff">
    <select class="input_select" size="1" id="dy" style="width: 40px"/></select>
	<select class="input_select" size="1" id="mth" style="width: 40px"/></select>
	&nbsp;View day/month
</div>
<div id="tbl-header" style="position: relative; width: auto;height: auto">
	<table border="0" style="table-layout: fixed; width: 98%">
		<tr>
			<td style="width: 15%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>DATE</strong></td>
			<td style="width: 55%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>DOMAIN</strong></td>
			<td style="width: 15%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>BYTES</strong></td>
			<td style="width: 15%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>COST</strong></td>
		</tr>
	</table>
</div>

<div id="squid-ajax" style="position: relative; width: auto; height: auto; display: none">
<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="">&nbsp;Loading....please wait.
</div>

<div id="squid-data" style="position:realtive; width: auto;height: 300px; overflow: auto">
</div>
	
</div></div></div></div>
