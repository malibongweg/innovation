<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/system/service.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/datepick/Locale.en-US.DatePicker.js");
$doc->addScript("scripts/datepick/Picker.js");
$doc->addScript("scripts/datepick/Picker.Attach.js");
$doc->addScript("scripts/datepick/Picker.Date.js");
$doc->addStyleSheet("scripts/datepick/datepicker.css");
$dbo->query();
?>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Service Failure Log" />
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
      Service Failure Log</h3>
        </div>
            <div class="art-blockcontent" style="background-color: #EEEEEE">
            <div class="art-blockcontent-body" style="background-color: #EEEEEE">

<div id="log-details" class="main-div">
	<div class="main-header"><strong><span id="del-heading">Log Entries</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<select name="month" id="mth" size="1" style="width: 60px" class="input_select" />
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
	</select>&nbsp;<span style="font-weight: bold">Month (Current Year Only)</span>

<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
<table style="width: 100%; position: relative; height: auto; border: 0px">
<tr>
<td style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px">&nbsp;</td>
<td style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px"><strong>DATE</strong></td>
<td style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px"><strong>SYSTEM</strong></td>
<td style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px"><strong>FAILURE</strong></td>
<td style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px"><strong>SEVERITY</strong></td>
</tr>
</table>
</div>


<div id="ajax-el" style="width: auto; height: auto; position: relative; padding: 5px; display: none">
	<img src="images/kit-ajax.gif" width="16" height="11" alt="" style="vertical-align: middle" />&nbsp;Busy...
</div>

<div id="display-log" style="width: auto; height: auto; overflow: scroll; position: relative; padding: 5px; margin: 0 0 3px 0">

</div>
<input type="button" id="new-record" class="button art-button" value="New Entry" />

</div>

<!-----------------Form------------------------->
<div id="log-form" class="main-div" style="display: none">
<div class="main-header"><strong><span id="del-heading">Log Entries</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<form id="failure-form" name="failure_form">
	<label for="sdate" class="input_label">Failure Date:</label>
	<input type="text" size="10" readonly name="sdate" id="start-date" class="input_field" />
	<img class='date_toggler1' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt=""><br />

	<label for="sname" class="input_label">System ID:</label>
	<select name="sname" id="system-name" size="1" class="input_select" style="width: 280px" />
	</select>
	<div id="ajax1" style="margin: 0 0 0 105px; display: none"><img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle" >&nbsp;Populating...</div>
	<br />

	<label for="fcode" class="input_label">Failure Reason:</label>
	<select name="fcode" id="failure-code" size="1" class="input_select" style="width: 280px"/>
	</select>
	<div id="ajax2" style="margin: 0 0 0 105px; display: none"><img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle" >&nbsp;Populating...</div>
	<br />

	<label for="scode" class="input_label">Severity:</label>
	<select name="scode" id="severity-code" size="1" class="input_select" style="width: 280px"/>
	</select>
	<div id="ajax3" style="margin: 0 0 0 105px; display: none"><img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle" >&nbsp;Populating...</div>
	<br />

	<label for="fmsg" class="input_label" style="vertical-align: top">Website Message:</label>
	<textarea name="fmsg" class="input_textarea" style="width: 280px" rows="5" id="failure-message"></textarea><br />

	<div>
	<label class="input_label">&nbsp;</label>
	<input type="button" value="Save" class="button art-button" id="save-record"  />&nbsp;
	<input type="button" value="Cancel" class="button art-button" id="cancel-record"  />
	</div>
</form>
</div>

<!-------------update log form---------------->
<div id="update-form" class="main-div" style="display: none">
<div class="main-header"><strong><span id="del-heading">Resolve Actions</span></strong></div>
<form name="action_form" id="action-form">
<input type="hidden" name="act_id" id="act-id" />
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<label class="input_label">Action Date:</label>
		<input type="text" id="action-date" name="action_date" size="10" readonly class="input_field" />
		<img class='date_toggler2' src="/images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt=""><br />

	<label class="input_label">Action Taken:</label>
		<textarea id="action-taken" name="action_taken" class="input_textarea" style="width: 280px" rows="5"></textarea><br />

	<label class="input_label">Current Status:</label>
		<select name="current_status" id="current-status" size="1" style="width: 280px">
			<?php
				$dbo->setQuery("select id,action,resolved_state from cput_service_failure_actions order by action");
				$result = $dbo->loadObjectList();
				foreach($result as $row){
					echo "<option value='".$row->id.";".$row->resolved_state."'>".$row->action."</option>\n";
				}
			?>
		</select><br /><br />

		<label class="input_label">&nbsp;</label>
			<input type="submit" id="save-action" value="Save Action" class="button art-button" /><br /><br />

</form>

		<label class="input_label">Action History:</label>
		<textarea id="action-history" name="action_history" class="input_textarea" style="width: 300px" rows="10" readonly ></textarea><br />

</div>

</div></div></div></div>