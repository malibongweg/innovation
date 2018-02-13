<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/temp_cards/cards.js");
$dbo->setQuery("call proc_pop_application('Tem ID Cards')");
$dbo->query();
?>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Temp ID Cards" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
</form>
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
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Temp ID Cards</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">


<div id = "cards" style="position: relative; width: auto; height: auto; border: 1px solid #b9c0fe; background-color: #EEEEEE; padding: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-top: 3px; display: block">

<fieldset class="input_fieldset">
	<legend id="ctext" class="input_legend"><strong>Enter student identification number</strong></legend>
	<div style="float: left; width: auto; height: auto">
	<input type="text" class="input_field" size="25" maxlength="9" id="srch"  />
	</div>

	<div id="busy" style="float: left; width: auto; height: auto; display: none">
	<img src="images/kit-ajax.gif" width="16" height="16" border="0" alt="" style="vertical-align: middle" >Getting image...please wait.
	</div>
	<div style="clear: both"></div>

		<div id="ajax" style="position: relative; width: auto; height: auto; display: none">
			<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">Searching....
		</div>
	<div id="display-users" style="position: relative; width: auto; height: auto; display: none; margin: 3px 0 0 0">
		<select id="sel-user" name="sel_user" size="10" style="width: 250px" >
		</select>
	</div>
	
</fieldset>

</div>

</div></div></div></div>