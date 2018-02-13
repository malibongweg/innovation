<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/system/password.js");
$doc->addScript("scripts/datepick/Locale.en-US.DatePicker.js");
$doc->addScript("scripts/datepick/Picker.js");
$doc->addScript("scripts/datepick/Picker.Attach.js");
$doc->addScript("scripts/datepick/Picker.Date.js");
$doc->addStyleSheet("scripts/datepick/datepicker.css");
?>
 <style>
     html,body{margin:0;padding:0; height: 100%}
	 body{font: 11px verdana,sans-serif,arial; color: #000000; text-align:left }

	 .input_label {
	font-weight: bold;
	color: #000000;
	width: 100px;
	float: left;
	vertical-align: middle;
	padding: 6px 0 0 0;
	font-family: Verdana, Arial;
}
.input_field {
	background-color: #eef1fe;
	border: 1px solid #cad4fa;
	line-height: 14px;
	font-size: 11px;
	height: 14px;
	padding: 3px;
	margin: 1px 0 1px 0;
	color: #000000;
	font-family: Verdana, Arial;
}
.numeric {
	border: 1px solid #cad4fa;
	height: 14px;
	line-height: 14px;
	font-size: 11px;
	padding: 3px;
	background-color: #eef1fe;
	color: #000000;
	font-family: Verdana, Arial;
	margin: 1px 0 1px 0;
}

    </style>
<input type="hidden" id="lname" />
<input type="hidden" id="uid" value="<?php echo $user->username; ?>"/>
<div id="ajax-password" style="position: absolute; top: 5px; left: 5px; height: auto; width: auto; display: none; background-color: #c0c0c0; border: 2px solid #9e9e9e; padding: 2px">
<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Processing...Please wait.
</div>


<div style="position: relative; display: block; background-color: #5B8EC5; border: 2px solid #39699e; margin: 0 0 5px 0">
	<img src="/images/small_lock.png" width="20" height="29" border="0" alt="" style="vertical-align: middle">&nbsp;
	<span style="font-size: 14px; font-weight: bold">PASSWORD RESET REQUEST</span>
</div>


<div id="details" style="position: relative; display: block">
<label class="input_label">Login Name:</label>
	<input type="text" name="login_name" id="login-name" size="15" maxlength="30" onFocus="resizeOrig();" class="input_field" onKeyUp="this.value=this.value.toLowerCase()" /><br />

<label class="input_label">Barcode#:</label>
	<input type="text" name="barcode" id="barcode-number" size="7" maxlength="7" class="numeric" onFocus="resizeOrig();" onKeyUp="this.value=this.value.toLowerCase()" />
	(Only the last seven digits on student/staff card)<br />

<label class="input_label">ID#:</label>
	<input type="text" name="id_number" id="id-number" size="13" maxlength="13" class="input_field" onFocus="resizeOrig();" /><b>&nbsp;Foreign users use DOB (YYYY-MM-DD)</b><br />

<label class="input_label">DOB:</label>
	<input type="text" name="sm0" id="sm0" size="10" class="input_field" />&nbsp;<b>Format (YYYY-MM-DD)</b><br />
	<!--img class='date_toggler1' src="/images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="" onClick="resizeCal();" ><br /-->


<label class="input_label">Cellular#:</label>
	<input type="text" name="cell" id="cellular" size="13" maxlength="13" class="numeric" onFocus="resizeOrig();"/>
	(Required for access token)<br />

<div id="token-div" style="position: relative; width: auto; height: auto; display: none">
<label class="input_label">Token:</label>
	<input type="text" name="tk" id="token" size="6" maxlength="6" class="input_field" onFocus="resizeOrig();" />&nbsp;(Sent via SMS)
</div>

<label class="input_label">&nbsp;</label>
	<input type="button" value="Submit" id="request" />&nbsp;
	<input type="button" value="Close" id="close-wnd" />

</div>

</div>