<html>
<?php
error_reporting(0);
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();
$user = & JFactory::getUser();
echo '<input type="hidden" id="hash" value="'.$_GET['hash'].'" />';
?>
<script type="text/javascript" src="/scripts/mobile_confirm/mootools.min.js"></script>
<script type="text/javascript" src="/scripts/mobile_confirm/mobile.js"></script>
<script type="text/javascript" src="/scripts/json.js"></script>
<style>
	p {
		font-family: Verdana, Arial;
		font-size: 12px;
		font-weight: bold;
	}
	input:focus {
		border: 1px solid navy;
		outline: 1px solid navy;
	}
	.frame {
		font-family: Verdana, Arial;
		font-size: 12px;
		font-weight: bold;
		padding: 5px;
		margin: 0 auto;
		width: 900px;
		height: 768px;
		border: 1px solid #c8c8c8;
	}
	label {
		color: #696969;
	}
	.general {
		font-family: Verdana,Arial;
		font-size: 12px;
		color: #696969;
	}
</style>
<body>

<div class="frame">
	<div id="img-logo" style="background-color: #053A63">
	<img src="/scripts/mobile_confirm/cput.png" alt="CPUT" width="350" /></div>

	<div style="float: left; width: 55%; height: 100%">
		<img alt="" src="mobile.png">
	</div>

	<div style="float: right: width: 40%; height: auto; text-align: left">
		<div style="margin: 5px; border: 0px solid #c8c8c8; padding 5px">
			<img alt="" src="security.gif">
		</div>
		<p>Confirm you mobile number now and take advantage of our new utilities we have installed for you.</p>
	</div>

	<form id="mobile-data">
		<input type="hidden" name="stud" id="stud-no" value="" />
		<label for="student-no">Student#</label><br />
		<input type="text" name="student_no" id="student-no" size="10" disabled="disabled" style="margin-bottom: 5px" /><br />
		<label for="current-cell">Current Mobile#</label><br />
		<input type="text" size="10" id="current-cell" name="current_cell" disabled="disabled" style="margin-bottom: 5px" /><br />
		<label for="current-barcode">Enter Barcode</label><span style="color: #ff0000; font-weight: bold">*</span><br />
		<input type="text" size="11" maxlength="11" id="current-barcode" name="current_barcode" value="X667" onKeyUp="this.value=this.value.toUpperCase();" style="margin-bottom: 5px" /><br />
		<label for="confirm-cell">Confirm Mobile#</label><span style="color: #ff0000; font-weight: bold">*</span><br />
		<input type="text" name="confirm_cell" maxlength="10" id="confirm-cell" size="10" style="margin-bottom: 5px" /><br />
		<input type="submit" value="Submit" />&nbsp;<input type="button" value="Cancel" onclick="window.localtion.url='http://www.cput.ac.za'" />
	</form>
</div>
</body>
</html>