<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
jimport('joomla.environment.browser');
$dbo = &JFactory::getDBO();
$user = &JFactory::getUser();
$doc = &JFactory::getDocument();
$doc -> addCustomTag("<meta http-equiv='X-UA-Compatible' content='IE=8' />");
$doc -> addScript("scripts/fav_apps.js");
$doc -> addScript("scripts/json.js");
$doc -> addScript("scripts/idphoto/reset_print.js");
?>

<input type="hidden" id="uid" value="<?php echo $user -> username; ?>" />
<input type="hidden" id="sysid" value="<?php echo $user -> id; ?>" />

<div style="position: absolute; top: 5px; left: 5px; width: 100%">
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
				<h3 class="t" ><div style="float: right; width: auto; height: auto; text-align: right" id="status"></div><a href="#" id="fav-def">
				    <img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
				    <a href="#" id="fav-app">
				    <img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
				    <a href="#" class="modalizer" id="bug-app">
				        <img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
				         <span id="photo-title">Reset ID Card Print</span></h3>
			</div>

			<div class="art-blockcontent">
		
				<div id="show-busy" style="position: absolute; top: 30px; left: 70%; height: 50px; width: 100px; border: 2px solid #d4d4d4; background-color: #ffffff; text-align: center; padding: 5px; display: none">
					<img src="/scripts/idphoto/img/ajax.gif" width="32" height="32" border="0" alt="">
					<br style="clear: both" />
					<span style="font-weight: bold">Busy...</span>
				</div>

				<div id="srch-user-div" style="display: block; width: 99%; height: auto; background-color: #c0c0c0; border: 1px solid #808080; margin-top: 5px; padding: 3px">
					<b>Enter staff/student# </b>
					<input type="text" size="9" maxlength="9" id="srch-card" style="background-color: #c0c0c0" />&nbsp;&nbsp;
					<input type="button" value="Search" id="srch-button" />
				</div>

				

			</div>
		</div>
	</div>
</div>

