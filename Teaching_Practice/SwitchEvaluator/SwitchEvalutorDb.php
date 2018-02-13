<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/TeachingPractice/jquery.js");
$doc->addScript("scripts/TeachingPractice/jquery-ui.js");
$doc->addScript("scripts/TeachingPractice/SchoolMaintanance/school.js");
$doc->addStyleSheet("scripts/TeachingPractice/jquery-ui.css");

?>