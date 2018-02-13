<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();

///jTable includes/////
$doc->addScript('scripts/jobcards/jtable/js/jquery-1.10.2.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery-ui-1.10.4.js');
$doc->addScript('scripts/jobcards/jtable/jquery.jtable.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery.validationEngine.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery.validationEngine-en.js');

$doc->addStyleSheet('scripts/jobcards/jtable/css/custom-theme/jquery-ui-1.10.4.custom.css');
$doc->addStyleSheet('scripts/jobcards/jtable/themes/metro/blue/jtable.css');
$doc->addStyleSheet('scripts/jobcards/jtable/css/validationEngine.jquery.css');

$doc->addScript("scripts/jobcards/jc.js");
$doc->addScript("scripts/json.js");
//////////////////////

$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
?>
<form id="user_id">
<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
</form>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Campus Managers</a></li>
		<li><a href="#tabs-2">Campus Foremans</a></li>
		<li><a href="#tabs-3">Artisan Trades</a></li>
		<li><a href="#tabs-4">Artisans</a></li>
		<li><a href="#tabs-5">General</a></li>
	</ul>
	<div id="tabs-1">
	<div id="tableManagers" style="margin: 20px 0 0 0">
		</div>
	</div>

	<div id="tabs-2">
		<div id="tableForeman" style="margin: 20px 0 0 0">
		</div>
	</div>
	<div id="tabs-3">
		<div id="tableTrades" style="margin: 20px 0 0 0">
		</div>
	</div>

	<div id="tabs-4">
		<div id="tableArtisans" style="margin: 20px 0 0 0">
		</div>
	</div>

	<div id="tabs-5">
		<div id="div-escalation" style="margin: 5px 0 5px 0">
		<label for="esc-hours">Escalation Hours: </label>
		<input type="text" id="esc-hours" name="esc_hours" value="" size="2"><br />
		<label for="esc-email">Escalation Email 1: </label>
		<input type="text" id="esc-email" name="esc_email" value="" size="50"><br />
		<label for="esc-email2">Escalation Email 2: </label>
		<input type="text" id="esc-email2" name="esc_email2" value="" size="50"><br />
		<label for="esc-send">Enable email function? </label>
		<input type="checkbox" id="esc-send" name="esc_send" value="1" /><br />
		<input type="button" id="save-esc" value="Update" class="button" />
		</div>
	</div>
</div>
