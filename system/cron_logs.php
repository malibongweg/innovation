<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/system/cron.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->query();
?>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Utility Cron Logs" />
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
      Utility Cron Logs</h3>
        </div>
            <div class="art-blockcontent" style="background-color: #EEEEEE">
            <div class="art-blockcontent-body" style="background-color: #EEEEEE">

<div id="log-details" class="main-div">
	<div class="main-header"><strong><span id="del-heading">Select Log Month (Current Year Only)</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<select name="mth" id="mth" size="1" style="width: 60px" class="input_select" >
	<?php
		$t = date('m');
		for ($i=1;$i<13;$i++) {
		if ($i == intval($t)) {
			echo "<option value=".$i." selected>".$i."</option>\n";
		} else {
			echo "<option value=".$i.">".$i."</option>\n";
		}
	}
	?>
	</select>

<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
<table style="width: 100%; position: relative; height: auto; border: 0px">
<tr>
<td style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px"><strong>ENTRY DATE</strong></td>
<td style="width: 25%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px"><strong>SYSTEM</strong></td>
<td style="width: 45%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px"><strong>MESSAGE</strong></td>
<td style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px"><strong>OBJ ID</strong></td>
</tr>
</table>
</div>


<div id="ajax" style="width: auto; height: auto; position: relative; padding: 5px; display: none">
<img src="images/kit-ajax.gif" width="16" height="11" alt="" style="vertical-align: middle" />&nbsp;Busy...Please Wait.
</div>

<div id="display-log" style="width: auto; height: 250px; overflow: scroll; position: relative; padding: 5px">

</div></div><br clear=both />
<div style="position: relative; width: 165px; height: auto; padding: 3px; border: 1px solid #bfbcfe; vertical-align: middle; background-color: #d9ddff">
Object ID Filter:&nbsp;<input type="text" id="id-filter"  size="9" maxlength="9" class="input_field">
&nbsp;<input type="button" class="button art-button" id="srch-button" value="Search" /></span>
</div>

</div></div></div></div>