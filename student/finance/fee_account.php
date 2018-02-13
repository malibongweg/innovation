<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/student/finance/fee.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
?>
<form id="user_id">
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
</form>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Fee Account" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
</form>
<div class="header-bg">
		<div class="l-image"></div>
	<div class="r-image"></div>
<div class="header-text">
<a href="#" id="fav-def"><img src="images/default.png" width="20" height="20" title="Set as default." /></a>	
<a href="#" id="fav-app"><img src="images/fav.png" width="20" height="20" title="Add to favorite." /></a>
Student Fee Account</div>
</div>


<div id = "fee-account" style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; padding: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-top: 3px; display: block">
<fieldset style="border: 1px solid #C0C6BA;-moz-border-radius: 5px; border-radius: 5px; background-color: #E7E9E5">
	<legend id="ctext" style="margin-left: 5px;border: 1px solid #C0C6BA; background-color: #E7E9E5">Intermin Fee Account</legend>

<div id="cajax" style="float: left; width: auto; line-height: 31px; display: block"><img src="images/kit-ajax.gif" width="16" height="11" alt="" style="vertical-align: middle" />
Retrieving account details...please wait.
</div>
</fieldset>

<fieldset id="showDiv" style="border: 1px solid #C0C6BA;-moz-border-radius: 5px; border-radius: 5px; background-color: #E7E9E5; display: none">
	<legend id="ctext" style="margin-left: 5px;border: 1px solid #C0C6BA; background-color: #E7E9E5">Transactions</legend>
<div id="trans" style="float: left; width: 90%; line-height: 31px; display: none">
<table border="0" width="90%">
<tr>
<td width="15%" align="left"><b>DATE</b></td>
<td width="15%" align="left"><b>REF#</b></td>
<td width="45%" align="left"><b>DESCRIPTION</b></td>
<td width="15%" align="right"><b>AMOUNT</b></td>
</tr>
</table>
<div id="transx" style="float: left; width: 90%; line-height: 31px; display: none; width: 90%"></div>
</div>
</fieldset>

</div>

