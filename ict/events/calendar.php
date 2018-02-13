<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/ict/events/cron.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/datepick/Locale.en-US.DatePicker.js");
$doc->addScript("scripts/datepick/Picker.js");
$doc->addScript("scripts/datepick/Picker.Attach.js");
$doc->addScript("scripts/datepick/Picker.Date.js");
$doc->addStyleSheet("scripts/datepick/datepicker.css");
$dbo->setQuery("call proc_pop_application('Event Calendar')");
$dbo->query();
?>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Event Calendar" />
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
      CTS Event Calendar</h3>
        </div>
            <div class="art-blockcontent" style="background-color: #EEEEEE">
            <div class="art-blockcontent-body" style="background-color: #EEEEEE">

<div id="log-details" class="main-div">
	<div class="main-header"><strong><span id="del-heading">Select Calendar Month (Current Year Only)</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<select name="mth" id="mth" size="1" style="width: 60px" class="input_select" >
	<?php
		$t = date('m');
		for ($i=1;$i<13;$i++) {
		if ($i == intval($t)) {
			echo "<option value=".$i." selected>".$i."</option>\n";
		} else {
			echo "<option value=".$i.">".$i."</option>\n";
		}
	}
	?>
	</select>

<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
		<table width="100%" style="table-layout: fixed">
		<tr>
		<td style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</td>
		<td style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>EVENT DATE</strong></td>
		<td style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>PUBLISH DATE</strong></td>
		<td style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>REMOVE DATE</strong></td>
		<td style="width: 35%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>MESSAGE</strong></td>
		</tr>
		</table>
		<div id="display-log" style="width: auto; height: auto; overflow: auto; position: relative; padding: 5px; margin: 0 0 5px 0">
</div>


		<div id="ajax-ev" style="width: auto; height: auto; position: relative; padding: 5px; display: none">
		<img src="images/kit-ajax.gif" width="16" height="11" alt="" style="vertical-align: middle" />&nbsp;Loading...
		</div>



</div>
<input type="button" class="button art-button" value="New Event" id="new-event" />&nbsp;
<input type="button" value="Edit Event Information" id="edit-event" class="button art-button" />&nbsp;
<input type="button" value="Delete Event Information" id="delete-event" class="button art-button" />&nbsp;
<input type="button" class="button art-button" value="Expire Event" id="expire-event" />
</div>

<!--Form-->
<div id="event-form" class="main-div" style="display: none">
<input type="hidden" name="form_action" id="form-action" />
<input type="hidden" name="id" id="form-id" />
	<div class="main-header"><strong><span id="del-heading">Event Details</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

<form name="event_form" id="event-form">
	
<div  class="field_content" >
	<div class="field_label"><strong>Event Date</strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<input type="text" name="sdate" id="start-date" size="10" maxlength="10"  readonly class="input_field" />&nbsp;<img class='date_toggler1' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="">
</div>


<div class="field_content" >
	<div class="field_label"><strong>Publish Date</strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<input type="text" name="pdate" id="publish-date" size="10" maxlength="10"  readonly class="input_field" />&nbsp;<img class='date_toggler3' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="">
</div>

<div class="field_content" >
	<div class="field_label"><strong>Remove Date</strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<input type="text" name="edate" id="end-date" size="10" maxlength="10"  readonly class="input_field" />&nbsp;<img class='date_toggler2' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="">
</div>

<div class="field_content" >
	<div class="field_label"><strong>Event Description</strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<textarea name="desc" id="event-description" style="width: 100%" rows="3" class="input_textarea"></textarea>
</div>
<div class="field_content" >
	<div class="field_label"><strong>Event Details</strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<textarea name="details" id="event-details" style="width: 100%" rows="3" class="input_textarea"></textarea>
</div>
<input type="submit" value="Save Event Information" id="save-event" class="button art-button" />&nbsp;
<input type="button" value="Cancel" id="cancel-button" class="button art-button" />

</form>

</div>

</div></div></div></div>