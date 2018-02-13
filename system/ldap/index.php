<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/system/ldap/ldap.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
?>
<form id="user_id">
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
</form>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="LDAP Maintenance" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
</form>
<div class="header-bg">
		<div class="l-image"></div>
	<div class="r-image"></div>
<div class="header-text">
<a href="#" id="fav-def"><img src="images/default.png" width="20" height="20" title="Set as default." /></a>	
<a href="#" id="fav-app"><img src="images/fav.png" width="20" height="20" title="Add to favorite." /></a>
LDAP Account Maintenance</div>
</div>


<!--Navigation-->
<div id = "ldap-nav" style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; padding: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-top: 3px; display: block">
<fieldset style="border: 1px solid #C0C6BA;-moz-border-radius: 5px; border-radius: 5px; background-color: #E7E9E5">
	<legend style="margin-left: 5px;border: 1px solid #C0C6BA; background-color: #E7E9E5"><strong>Navigation</strong></legend>
<form name="nav_form" id="nav-form">
<table border="0" width="100%">
<tr>
<td width="100%" style="text-align: left">
<select name="nav" size="1" id="nav">
<option value="1" selected>LDAP Lookup</option>
<option value="2">Write to LDAP</option>
</select>
</td>
</tr>
</table>
</form>
</fieldset>

<!--LDAP Lookup-->
<div id = "ldap-lookup" style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; padding: 5px 3px 5px 10px; -moz-border-radius: 5px; border-radius: 5px; margin: 3px 3px; display: none">
CN = <input type="text" size="30" id="search" />&nbsp;<input type="button" value="Search" id="search-button" />
<div id="result1" style="width: 100%; height: auto; position: relative; display: none">
<select name="ldap-result1" size="1" style="width: 100%">
</select>
</div>
</div>

<!--LDAP Wite-->
<div id = "ldap-write" style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; padding: 5px 3px 5px 10px; -moz-border-radius: 5px; border-radius: 5px; margin: 3px 3px; display: none">
CN = <input type="text" size="30" id="search" />&nbsp;<input type="button" value="Search" id="search-button" />
<div id="result2" style="width: 100%; height: auto; position: relative; display: none">
<select name="ldap-result2" size="1" style="width: 100%">
</select>
</div>
</div>


</div>