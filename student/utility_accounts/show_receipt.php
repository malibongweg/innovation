<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/student/utility_accounts/receipt.js");
?>
 <style>
     html,body{margin:0;padding:0; height: 100%}
	 body{font: 11px verdana,sans-serif,arial; color: #000000; text-align:center; background: #ffffff url('/images/receipt.png') repeat-x left top; }
    </style>

<input type="hidden" id="receipt" value="<?php echo $_GET['receipt']; ?>"/>
<div id="ajax" style="position: absolute; top: 10px; left: 10px; height: auto; width: auto; display: none">
<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Retrieving information...
</div>

<div style="position: relative; width: auto; height: auto">

<div id="receipt-no" style="margin: 1px; border: 1px solid black; position: relative; float: right; padding: 3px; color: #ffffff; background-color: black; width: 60%; text-align: right"></div>
<div style="clear: both"></div>
<div id="receipt-date" style="margin: 1px;border: 1px solid black;position: relative; float: right; padding: 3px; color: #ffffff; background-color: black; width: 60%; text-align: right"></div>
<div style="clear: both"></div>
<div id="receipt-proc" style="margin: 1px;border: 1px solid black;position: relative; float: right; padding: 3px; color: #ffffff; background-color: black; width: 60%; text-align: right"></div>
<div style="clear: both"></div>
<div id="receipt-acc" style="margin: 1px;border: 1px solid black;position: relative; float: right; padding: 3px; color: #ffffff; background-color: black; width: 60%; text-align: right"></div>
<div style="clear: both"></div><br /><br />
<p style="text-align: left">Transaction Details</p>
<div id="receipt-type" style="margin: 1px;border: 1px solid black;position: relative; padding: 3px; color: #000000; background-color: #ffffff; width: auto; text-align: left">
</div>
<div style="clear: both"></div>
<div id="receipt-msg" style="margin: 1px; position: relative; padding: 3px; color: #ffffff; background-color: #ffffff; width: auto; text-align: left"></div>

</div>