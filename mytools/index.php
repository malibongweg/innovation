<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/mytools/tools.js");
?>
<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />
<div id="ajax" style="position: relative; display: none"><img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Getting status...
</div>


<div id="ccard" style="position: relative; display: block; text-align: center; margin: 3px 0 3px 0">
</div>

<div id="mcard" style="position: relative; display: block; text-align: center; margin: 3px 0 3px 0">
</div>
