<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/sms/sms_setup.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
?>
<form id="user_id">
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="username" value="<?php echo $user->username; ?>" />
</form>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="SMS Setup" />
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
       General Application Setup</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
	

<div id="user-details" class="main-div">
	<div class="main-header"><strong><span id="del-heading">Configuration</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<table border="0" width="100%">
<tr>
<td width="100%"><strong>System Enabled?</strong><br />
<input type="checkbox" checked id="sms-stat"></td>
</tr>
<tr>
<td width="100%"><strong>SMS Charge Rate</strong><br />
<input type="text" id="sms-charge" size="4" maxlength="4" value="" class="input_field" /></td>
</tr>
<tr>
<td width="100%"><strong>SMS Notification Email</strong><br />
<input type="text" id="sms-notify" size="50" onkeyup="this.value=this.value.toLowerCase();" class="input_field" /></td>
</tr>
<tr>
<td width="100%"><strong>SMS Command Location</strong><br />
<input type="text" id="sms-command" size="50" onkeyup="this.value=this.value.toLowerCase();" class="input_field" /></td>
</tr>
<tr>
<td width="100%"><strong>HR Contact Email</strong><br />
<input type="text" id="hr-email" size="50" onkeyup="this.value=this.value.toLowerCase();" class="input_field" /></td>
</tr>
<tr>
<td width="100%"><strong>System Mode</strong><br />
<input type="radio" id = "s-mode-1" name="smode" value="0" />Normal<input type="radio" id="s-mode-2" name="smode" value="1" />Disaster
</tr>
<tr>
<td width="100%"><input type="button" class="button" value="Update Configuration" onclick="setStatus();" /></td>
</tr>
</table>
</div>

</div></div></div></div>
