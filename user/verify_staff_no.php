<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/user/verify_staff.js");
?>
 <style>
     html,body{margin:0;padding:0; height: 100%}
	 body{font: 11px verdana,sans-serif,arial; color: #000000; text-align: left }
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
	/*background-image: url(/images/field_bg.png);
	background-repeat: repeat-x;*/
	background-color: #eef1fe;
	border: 1px solid #cad4fa;
	line-height: 14px;
	font-size: 11px;
	/*font-weight: bold;*/
	height: 14px;
	padding: 3px;
	margin: 1px 0 1px 0;
	color: #000000;
	font-family: Verdana, Arial;
}
    </style>
<div id="ajax" style="position: absolute; top: 10px; left: 10px; height: auto; width: auto; display: none; z-index: 1000; background-color: #ffa6a6; padding: 3px; border: 1px solid #ff8e8e">
<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Retrieving information...
</div>

<div style="width: auto; height: auto; position: relative; border: 2px solid #aaaaaa; background-color: #e3e3e3; padding: 3px; margin: 5px; overflow: hidden">
<div style="float: left; width: 10%; heigth: auto">
<img src="/images/j_login_lock.png" width="52" height="37" border="0" alt="" style="verical-align: middle">
</div>
<div style="float: right; width: 85%; height: auto">
As a security measurement, the CTS department will verify each staff member's personnel number against their login name. This information will be used by the new OPA system in various applications.
</div>
</div>

<div id="stf-details" style="position: relative; width: auto; height: auto; margin: 5px">

<label class="input_label">Login Name:</label>
<input type="text" size="20" name="lgname" id="login-name" class="input_field" value="<?php echo $user->username; ?>" readonly /><br />

<label class="input_label">Staff#</label>
<input type="text" class="numeric" name="staffno" id="staff-no" size="9" maxlength="9" />
<br />

<label class="input_label">ID#</label>
<input type="text" name="idno" id="id-no" size="13" maxlength="13" />[Foreign Staff Members enters DOB  YYYY-MM-DD]<br />
</div>

<div id="stf-btn" style="position: relative; width: auto; height: auto; margin: 5px">
<label class="input_label">&nbsp;</label>
<input type="button" value="Verify" id="verify-button" />&nbsp;
<input type="button" value="Close" id="cls-button" />
</div>

<div id="stf-btn2" style="position: relative; width: auto; height: auto; margin: 5px; display: none; z-index: 2000">
<input type="button" value="Close" id="cls-button2" />
</div>

</div>
