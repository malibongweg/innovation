<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
?>

<div id = "bugtrack" style="position: relative; width: auto; min-height: 195px;  padding: 5px; margin-top: 3px; display: block; background-image: url(/images/404error.png); background-repeat: no-repeat">
<br /><br /><br />
<p>Have you found an error in one of the applications? Got a suggestion? Discovered any missing data?</p>
<p>Select your application and click on the animated radar icon in the header to report your comments!</p>
<p style="font-size: 9px; color:#ff0000">NB: All comments are recorded against your login name.</p>

</div>
