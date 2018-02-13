<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/bugtrack/log.js");
$dbo->setQuery("call proc_pop_application('OPA Application Tracker')");
$dbo->query();
?>
 <style>
     html,body{margin:0;padding:0; height: 100%}
	 body{font: 11px verdana,sans-serif,arial; color: #000000; text-align:left; background: #ffffff url('/images/bigbug.png') no-repeat left top; }
	 td { border: 1px solid #b5b8ff; background-color: #ddddff } /* filter: alpha(opacity=90); -moz-opacity:0.9; opacity: 0.9;}*/
    </style>

<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<input type="hidden" id="appname" value="<?php echo $_GET['appname']; ?>" />

<table border="0" cellspacing="2" cellpadding="2" width="100%" ><!--style="filter: alpha(opacity=90); -moz-opacity:0.9; opacity: 0.9"-->
<tr>
	<td width="30%">App ID</td>
	<td width="70%" style="background-color: #ffffff"><input type="text" style="height: 20px; width: 98%" readonly maxlength="100" id="appname" value="<?php echo $_GET['appname']; ?>" /></td>
</tr>
<tr>
	<td width="30%">User ID</td>
	<td width="70%" style="background-color: #ffffff"><input type="text" style="height: 20px; width: 98%" readonly maxlength="100" id="username" value="<?php echo $user->username; ?>" /></td>
</tr>
<tr>
	<td width="30%">Email</td>
	<td width="70%" style="background-color: #ffffff"><input type="text" style="height: 20px; width: 98%" readonly maxlength="100" id="uemail" value="<?php echo $user->email; ?>" /></td>
</tr>

<tr>
	<td width="30%">Type</td>
	<td width="70%" style="background-color: #ffffff">
	<select name="bug_type" id="bug-type" size="1" style="width: 100%">
	<?php
		$dbo->setQuery("select id,`desc` from #__bug_track_types order by id");
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			echo "<option value='".$row->id."'>".$row->desc."</option>";
		}
	?>
	</select>
	</td>
</tr>

<tr>
	<td width="100%" colspan="2"><strong><i>Description</i></strong></td>
</tr>
<tr>
	<td width="100%" colspan="2"><textarea name="bug_desc" id="bug-desc" rows="5" style=" width: 98%"></textarea></td>
</tr>

<tr>
	<td width="100%" colspan="2"><input type="button" class="button art-button" value="Submit Request" id="submit-bug"/>&nbsp;
	<input type="button" class="button art-button" value="Cancel" id="cancel-track" /></td>
</tr>
</table>
<div id="ajax" style="position: relative; width: auto; height: auto; display: none">
<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">Saving and sending email....
</div>