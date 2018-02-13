<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/ict/change_control/chg.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/datepick/Locale.en-US.DatePicker.js");
$doc->addScript("scripts/datepick/Picker.js");
$doc->addScript("scripts/datepick/Picker.Attach.js");
$doc->addScript("scripts/datepick/Picker.Date.js");
$doc->addStyleSheet("scripts/datepick/datepicker.css");
$dbo->setQuery("call proc_pop_application('CTS Change Control')");
$dbo->query();
?>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="CTS Change Control" />
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
			<a id="my-requests" href="index.php/my-requests"><img src="images/myrequests.png" width="14" height="16" border="0" alt="" title="My requests" style="vertical-align: middle"></a>
			<a id="all-requests" href="index.php/all-requests"><img src="images/allrequests.png" width="16" height="14" border="0" alt="" title="Requests for your attention." style="vertical-align: middle"></a>
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      CTS Change Control</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body" >


<div style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; -moz-border-radius: 5px; border-radius: 5px; margin-top: 3px; display: block">
<form name="new_request" id="new-request">
<input type="hidden" name="uname" value="<?php echo $user->username; ?>">

<div style="position: relative; width: 99%; height: auto; overflow: hidden; margin: 5px 3px">
	<div id="lcol" style="float: left; width: 45%; height: 250px; border: 1px solid #C0C6BA; overflow: hidden">
	<table border="0" width="100%" cellspacing="3">
	<tr>
	<td width="100%" style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>SYSTEMS AFFECTED</b></td>
	</tr>
	<tr>
	<td width="100%" style="text-align: left">
	<select name="system_no" id="system-no" size="1" style="width: 100%">
	<?php
		$dbo->setQuery("select * from change_control.system_index order by index_no");
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			echo "<optgroup label='".$row->index_no."-".$row->index_descr."'>";
				$dbo->setQuery("select * from change_control.cts_systems where index_no = '".trim($row->index_no)."'");
				$res = $dbo->loadObjectList();
				foreach($res as $xrow) {
					echo "<option value='".$xrow->system_no."'>".$xrow->system_name."</option>";
				}
			echo "</optgroup>";
		}
	?>
	</select>
	</td>
	</tr>
	<tr>
	<td width="100%" style="text-align: left">
	<b>Description of Change...</b>
	</td>
	</tr>
	<tr>
	<td width="100%" style="text-align: left">
	<textarea name="request_desc" id="request-desc" style="width: 99%" rows="8" class="input_textarea"></textarea>
	</td>
	</tr>
	<tr>
	<td width="100%" style="text-align: left">
	<b>Project Leader:</b>
	</td>
	</tr>
	<tr>
	<td width="100%" style="text-align: left">
	<select name="leader" id="leader" size="1" style="width: 100%">
	<?php
	$dbo->setQuery("select * from change_control.cts_technicians order by sname");
	$result = $dbo->loadObjectlist();
	foreach($result as $row) {
	echo "<option value='".$row->empno."'>".$row->sname. " " . $row->fname."</option>";
	}
	?>
	</select>
	</td>
	</tr>
	</table>
	</div>
	<div id="rcol" style="float: right; width: 45%; height: 250px; overflow: hidden">
		<div style="position: relative; width: auto; height: auto;border: 1px solid #C0C6BA">
			<table border="0" width="100%" cellspacing="0">
			<tr>
			<td width="100%" style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>URGENCY</b></td>
			</tr>
			<tr>
			<td width="100%"><input type="radio" name="urgencyChoice" value="1" checked="checked">Emergency</td>
			</tr>
			<tr>
			<td width="100%"><input type="radio" name="urgencyChoice" value="2"> Scheduled</td>
			</tr>
			<tr>
			<td width="100%"><input type="radio" name="urgencyChoice" value="3">Un-Scheduled</td>
			</tr>
			</table>
		</div>
		<div style="position: relative; width: auto; height: auto;border: 1px solid #C0C6BA; margin-top: 3px">
			<table border="0" width="100%" cellspacing="0">
			<tr>
			<td width="100%" style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>CAMPUS</b></td>
			</tr>
			<tr>
			<td width="100%"><input type="checkbox" name="campus" value="1">Bellville</td>
			</tr>
			<tr>
			<td width="100%"><input type="checkbox" name="campus" value="2">Cape Town</td>
			</tr>
			<tr>
			<td width="100%"><input type="checkbox" name="campus" value="3">Granger-Bay</td>
			</tr>
			<tr>
			<td width="100%"><input type="checkbox" name="campus" value="4">Mowbray</td>
			</tr>
			<tr>
			<td width="100%"><input type="checkbox" name="campus" value="5">Tygerberg</td>
			</tr>
			<tr>
			<td width="100%"><input type="checkbox" name="campus" value="6"> Wellington</td>
			</tr>
			<tr>
			<td width="100%"><input type="checkbox" name="all" value="-1" onclick="selectAllCampus()">All</td>
			</tr>
			</table>
		</div>
	</div>
</div>
<div style="clear: both"></div>

<div style="position: relative; width: auto; height: auto; margin-top: 3px; overflow: hidden; padding: 3px">
<div style="float: left; width: 45%; height: 160px; border: 1px solid #C0C6BA; overflow: hidden">
<table border="0" width="100%" cellspacing="0">
			<tr>
			<td width="100%" colspan="2"  style="background-color: #3B5E97; text-align: left; color: #ffffff"><span id="tsched" style="font-weight: bold">TIME SCHEDULE</span></td>
			</tr>
			<tr>
			<td width="100%">Proposed Date of Execution:</td>
			</tr>
			<tr>
			<td width="10%">
			<input type="text" readonly name="pdate" id="pdate" size="18" class="input_field" />
			<img class='date_toggler1' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="">
			</td>
			</tr>
			<tr>
			<td width="100%">
			Start Date:
			</td>
			</tr>
			<tr>
			<td width="100%">
			<input type="text" readonly name="sdate" id="sdate" size="18" class="input_field" />
			<img class='date_toggler2' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="">
			</td>
			</tr>
			<tr>
			<td width="100%">
			End Date:
			</td>
			</tr>
			<tr>
			<td width="100%">
			<input type="text" readonly name="edate" id="edate" size="18" class="input_field" />
			<img class='date_toggler3' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="">
			</td>
			</tr>
			<tr>
			<td width="100%">
			Office Hours? <input type="radio" name="officeHours" value="Yes" checked>Yes&nbsp;<input type="radio" name="officeHours" value="No">No
			</td>
			</tr>
</table>
</div>
<div style="float: right; width: 45%; height: 160px; border: 1px solid #C0C6BA; overflow: hidden">
<table border="0" width="100%" cellspacing="0">
			<tr>
			<td width="100%" colspan="2"  style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>SERVICES AFFECTED</b></td>
			</tr>
			<tr>
			<td width="100%">
			<select name="service_afftected" id="service-affected" size="9" style="width: 100%" multiple>
			<?php
				$dbo->setQuery("select * from change_control.services order by service_code");
				$result = $dbo->loadObjectList();
				foreach($result as $row) {
					echo "<option value='".$row->service_code."'>".$row->service_code."-".$row->service_name."</option>";
				}
			?>
			</select>
			</td>
			</tr>
			<tr>
			<td width="100%"><b>Hold Ctrl key for multiple selection</b></td>
			</tr>
</table>
</div>

</div>
<!------------------------------------------------------------->
<div style="position: relative; width: auto; height: auto; margin-top: 3px; overflow: hidden; padding: 3px">
<table border="0" width="100%" cellpadding="2" cellspacing="1">
			<tr>
			<td width="100%" colspan="3"  style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>CHANGE CHECKLIST</b></td>
			</tr>
			<tr>
			<td width="50%">
			<b>Description of resources</b>
			</td>
			<td width="15%">
			<b>H/S Ready</b>
			</td>
			<td width="35%">
			<b>Date</b>
			</td>
			</tr>
			<tr>
			<td width="50%">
			<input type="text" style="width: 100%" id="resource" class="input_field">
			</td>
			<td width="15%">
			<select id="hsr" size="1" style="width: 100%">
			<option value="yes" selected>Yes</option>
			<option value="no">No</option>
			</select>
			</td>
			<td width="35%">
			<input type="text"  id="rdate" size="10" class="input_field" />
			<img class='date_togglerr' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="">
			<input type="button" id="add-resource" value="Add" />
			</td>
			</tr>
			<tr>
			<td width="100%" colspan="3">
			<select name="resources_used" id="resources-used" size="3" style="width: 100%">
			</select>
			</td>
			</tr>
			<tr>
			<td width="100%" colspan="3">
				<p><i><strong>Double click to delete entry</strong></i></p>
			</td>
			</tr>
</table>
</div>

<div style="position: relative; width: auto; height: auto; margin-top: 3px; overflow: hidden; padding: 3px">
<table border="0" width="100%" cellpadding="2">
			<tr>
			<td width="100%" style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>DETAILS OF CHANGE</b></td>
			</tr>
			<tr>
			<td width="100%">
			<textarea name="details_change" id="details-change" style="width: 100%" rows="2" class="input_textarea"></textarea>
			</td>
			</tr>
</table>
</div>

<div style="position: relative; width: auto; height: auto; margin-top: 3px; overflow: hidden; padding: 3px">
<table border="0" width="100%" cellpadding="2">
			<tr>
			<td width="100%" style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>REASONS FOR CHANGE</b></td>
			</tr>
			<tr>
			<td width="100%">
			<textarea name="reasons_change" id="reasons-change" style="width: 100%" rows="2" class="input_textarea"></textarea>
			</td>
			</tr>
</table>
</div>

<div style="position: relative; width: auto; height: auto; margin-top: 3px; overflow: hidden; padding: 3px">
<table border="0" width="100%" cellpadding="2">
			<tr>
			<td width="100%" style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>IMPACT OF CHANGE</b></td>
			</tr>
			<tr>
			<td width="100%">
			<textarea name="impact_change" style="width: 100%" rows="2" class="input_textarea"></textarea>
			</td>
			</tr>
</table>
</div>

<div style="position: relative; width: auto; height: auto; margin-top: 3px; overflow: hidden; padding: 3px">
<table border="0" width="100%" cellpadding="2">
			<tr>
			<td width="100%" style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>ROLLBACK PROCEDURE</b></td>
			</tr>
			<tr>
			<td width="100%">
			<textarea name="rollback_change" style="width: 100%" rows="2" class="input_textarea"></textarea>
			</td>
			</tr>
</table>
</div>

<div style="position: relative; width: auto; height: auto; margin-top: 3px; overflow: hidden; padding: 3px">
<table border="0" width="100%" cellpadding="2">
			<tr>
			<td width="100%" style="background-color: #3B5E97; text-align: left; color: #ffffff"><b>ADDITIONAL COMMENTS</b></td>
			</tr>
			<tr>
			<td width="100%">
			<textarea name="comments_change" style="width: 100%" rows="2" class="input_textarea"></textarea>
			</td>
			</tr>
</table>
</div>

<div style="position: relative; width: auto; height: auto; margin-top: 3px; overflow: hidden; padding: 3px">
<table border="0" width="100%" cellpadding="2">
			<tr>
			<td width="100%">
			<input type="button" value="Submit Request" id="submit-request" class="button art-button" />
			</td>
			</tr>
</table>
</div>
</form>
<div id="msg" style="position: relative; width: auto; height: auto; display: none">
<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle"><span id="msg-details">Saving Request...Please wait...</span>
</div>
<!-----------------Main DIV-------------------------------->
</div>
</div></div></div></div>