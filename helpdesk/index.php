<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/helpdesk/helpdesk.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('CTS Helpdesk')");
$dbo->query();
?>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="CTS Helpdesk" />
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
      CTS Helpdesk</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">


<div class="main-div" id="helpdesk" style="padding: 10px 10px 10px 16px; display: block">
	<!--div class="main-header"><strong>Please Note</strong></div-->
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

<div style="position: relative; border: 2px solid #f6d292; width: auto; padding: 10px; background-color: #fbeacc">
<p><img src="images/important.png" width="224" height="66" border="0" alt=""></p>
	<p>Do not log any Konica Minolta calls using this facility.
All <strong>KONICA MINOLTA</strong> calls must be logged telephonically by calling the CTS Service Desk @ ext 6407.</p>
<p>This will aid in the problem of lack of information in the details of the call.
Also note that CBA will not attend to calls if the required information is not entered.</p>
<p><strong>Regards</p>
<p>CTS Department.</strong></p>
</div>


<?php
	$ldap_config = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
	$fp = file($ldap_config);
	$ldap_server = $fp[4];
	echo "<input type='hidden' id='ldap' value='".$ldap_server."' />";
	
?>

<div class="main-div" id="helpdesk" style="display: block">
	<div class="main-header"><strong><span id="staff-no"><img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">Retrieving staff/student number...Please Wait.</span></strong></div>
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

	<div id="log" style="position: relative; width: auto; height: auto; overflow: hidden; display: none">
		<div id="redir" style="top: 0px; left: 0px; width: auto; height: auto; position: absolute;  background-color: #ffffff; padding: 2px; border: 2px solid #aeaeae; display: none">
		<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">
		Getting data...please wait...
		</div>
	<form name="hlp_data" id="hlp-data">
	<input type="hidden" id="user-number" name="user_number" value="" />
	<table border="0" width="100%">
	<tr>
		<td width="100%"><strong>Service Required<strong><br />
		<select name="service_name" id="service-name" size="1" class="input_select" style="width: 300px"><option value="-1">Waiting for data population.</option></select></td>
	</tr>
	<tr>
		<td width="100%" style="padding: 5px 0 0 0"><strong>Sub Service</strong><br />
		<select name="sub_service" id="sub-service" size="1" class="input_select" style="width: 300px"><option value="-1">Waiting for data population.</option></select></td>
	</tr>
	<tr>
		<td width="100%" style="padding: 5px 0 0 0"><strong>Campus</strong><br />
		<select name="campus" id="campus" size="1" class="input_select" style="width: 300px"><option value="-1">Waiting for data population.</option></select></td>
	</tr>
	<tr>
		<td width="100%" style="padding: 5px 0 0 0"><strong>Contact# (required)</strong><br />
		<input type="text" name="contact" id="contact" size="15" class="input_field" /></td>
	</tr>
	<tr>
		<td width="100%" valign="top" style="padding: 5px 0 0 0"><strong>Description<strong><br />
		<textarea name="details" id="details" style="width: 300px" rows="5" class="input_textarea"></textarea></td>
	</tr>
	<tr>
	<td width="100%" colspan="2">
	<input type="submit" value="Log Call" id="log-call" class="button art-button" style="display:none" />&nbsp;
	<input type="button" value="Cancel" class="button art-button" style="display:none" id="cancel-call" onclick="window.location.href='index.php'" />
	</tr>
	</table>
	</form>
		<div id="save-ajax" style="width: auto; height: auto; position: relative; display: none">
		<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">Saving request...
		</div>
	</div>
	
</div>
</div>

</div></div></div></div>