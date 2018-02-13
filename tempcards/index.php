<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/tempcards/cards.js");
$dbo->setQuery("call proc_pop_application('Tem ID Cards')");
$dbo->query();
?>
<a href="#" class="modalizer" id="prn-click"></a>
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


<div class="main-div" id="cards" style="display: block">
	<div class="main-header"><strong>Enter student identification number.</span></strong></div>
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

	<form id="srch-details-form">
	<div style="float: left; width: auto; height: auto">
	<input type="text" size="9" maxlength="20" id="srch"  name="srch" />&nbsp;
	<input type="radio" value="N" name="srch_cond" checked />Student#&nbsp;&nbsp;
	<input type="radio" value="S" name="srch_cond" />Surname&nbsp;&nbsp;
	<input type="submit" id="get-details" class="button art-button" value="Retrieve Info" />
	</div>
	</form>

	<div id="busy" style="position: relative; width: auto; height: auto; z-index: 500; display: none">
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
</div>

<!--DIV Display Image -->
<div class="main-div" id="display-details" style="display: block">
	<div class="main-header"><strong>Identity Information</span></strong></div>
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

<div style="float: left; width: auto; height: auto">
<img id="photo" src="images/blank.png" width="125" height="135" style="border: 3px solid #000000">
</div>
<div id="busy-ajax-temp" style="position: relative; width: auto; height: auto; z-index: 500; display: none">
		<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">Getting additional data...Please wait.
</div>

	<div style="float: left; width: auto; height: auto; margin: 0 0 0 3px">
	<table border="0" style="width: 100%">
	<tr>
		<td width="100%"><strong>Student#</strong><br />
		<input type="text" readonly class="input_field" id="sno" style="width: 250px">
	</td>
	</tr>
	<tr>
		<td width="100%"><strong>Name</strong><br />
		<input type="text" readonly class="input_field" id="sname" style="width: 250px">
	</td>
	</tr>
	<tr>
	<td width="100%"><strong>Qualification</strong><br />
		<input type="text" readonly class="input_field" id="qual" style="width: 250px">
	</td>
	</tr>
	</table>
	</div>

		<div style="clear: both"></div>
		<div style="position: relative; widht: auto; height: auto; margin: 3px 0 0 0">
			<input type="button" value="Print" id="prn" class="button art-button" />
		</div>



<!------------------------------->

</div>

</div></div></div></div>
