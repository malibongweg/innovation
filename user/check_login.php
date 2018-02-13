<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/user/login.js");
?>

<div style="position: relative; width: auto; height: auto; border: 1px solid #b3b3b3; background-color: #dbdbdb; padding: 3px; margin-bottom: 3px">
	<p>This facility tests if your user account and password is valid. If the test is unsuccessful, you will have to reset your password at the CTS helpdesk or online password reset facility.</p>
</div>

<label style="float: left; width: 100px">Username:</label>
<input type="text" size="30" name="uname" id="user-name" />
<br style="clear: both" />

<label style="float: left; width: 100px">Password:</label>
<input type="password" size="30" name="pword" id="user-password" />
<br style="clear: both" />

<label style="float: left; width: 100px">&nbsp;</label>
<input type="button" value="Test Credentials" id="test-cred" />&nbsp;
<input type="button" value="Close Window" onclick="window.parent.$j.colorbox.close();" />
<br style="clear: both" />

<div id="check-busy" style="position: relative; width: auto;height: auto; display: none" >
<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Checking...Please wait.
</div>




















