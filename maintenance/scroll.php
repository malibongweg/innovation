<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
$dbo =& JFactory::getDBO();
?>

<div class="items-leading">
<div class="leading-0">
<h2>System Messages</h2>
</div></div>

<div id="ajax-loader" style="width: auto; height: auto; display: none">
<img src="scripts/ajax-loader.gif" width="160" height="24" />
</div>

<div id = "system-messages" style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; padding: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-top: 3px; display: none">
<fieldset style="border: 1px solid #C0C6BA;-moz-border-radius: 5px; border-radius: 5px; background-color: #E7E9E5">
	<legend style="margin-left: 5px;border: 1px solid #C0C6BA; background-color: #E7E9E5"><strong>System Messages</strong></legend>
<table border="0" width="100%">
<tr>
<td width="20%">User Id:</td><td width="80%"><input type="text" name="uid" id="uid" size="5" readonly style="background-color: #E7E9E5; border: 0px; font-weight: bold" /></td>
</tr><tr>
<td width="20%">Login Name:</td><td width="80%"><input type="text" name="lname" id="lname" size="50" readonly style="background-color: #E7E9E5; border: 0px; font-weight: bold"/></td>
</tr><tr>
<td width="20%">User Name:</td><td width="80%"><input type="text" name="uname" id="uname" size="50" readonly style="background-color: #E7E9E5; border: 0px; font-weight: bold"/></td>
</tr><tr>
<td width="20%">Email:</td><td width="80%"><input type="text" name="email" id="email" size="50" readonly style="background-color: #E7E9E5; border: 0px; font-weight: bold"/></td>
</tr>
</table>
</fieldset>
</div>