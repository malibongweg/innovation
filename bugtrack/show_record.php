<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/bugtrack/view.js");
//$doc->addStyleSheet("templates/portal/css/template.css");
?>
 <style>
     html,body{margin:0;padding:0; height: 100%}
	 body{font: 11px verdana,sans-serif,arial; color: #000000; text-align:center; }
	 .input_label {
	background-color: #e1e7f4;
	border: 1px solid #bccbe5;
	border-right: 0px solid #ffffff;
	height: 18px;
	text-align: left;
	border-bottom: 0px solid;
	}
	.input_field {
	background-color: #ffffff;
	border: 1px solid #bccbe5;
	height: 18px;
	text-align: left;
	border-bottom: 0px solid;
	}
	.input_textarea {
	background-color: #ffffff;
	border: 1px solid #bccbe5;
	}
    </style>
<?php
	$sql = sprintf("select a.id, a.entry_date,a.app,b.desc,a.details,a.email from #__bug_track a left outer join #__bug_track_types b on (a.request_type = b.id) where a.id = %d",$_GET['id']);
	$dbo->setQuery($sql);
	$row = $dbo->loadObject();
?>
<div style="position: relative; border: 2px solid #bccbe5; width: auto; heigh: auto; background-color: #e1e7f4; overflow: hidden; margin-bottom: 3px">
	<div style="float: left; width: 30%; height: 18px; line-height: 18px; text-align: left; border-right: 2px solid #bccbe5;">ENTRY DATE</div>
	<div style="float: right; width: 69%; height: 18px; line-height: 18px; background-color: #ffffff; text-align: left"><?php echo $row->entry_date; ?></div>
</div>
<div style="position: relative; border: 2px solid #bccbe5; width: auto; heigh: auto; background-color: #e1e7f4; overflow: hidden; margin-bottom: 3px">
	<div style="float: left; width: 30%; height: 18px; line-height: 18px; text-align: left;border-right: 2px solid #bccbe5;">APP NAME</div>
	<div style="float: right; width: 69%; height: 18px; line-height: 18px; background-color: #ffffff; text-align: left"><?php echo $row->app; ?></div>
</div>
<div style="position: relative; border: 2px solid #bccbe5; width: auto; heigh: auto; background-color: #e1e7f4; overflow: hidden; margin-bottom: 3px">
	<div style="float: left; width: 30%; height: 18px; line-height: 18px; text-align: left;border-right: 2px solid #bccbe5;">REQUEST</div>
	<div style="float: right; width: 69%; height: 18px; line-height: 18px; background-color: #ffffff; text-align: left"><?php echo $row->desc; ?></div>
</div>
<div style="position: relative; border: 2px solid #bccbe5; width: auto; heigh: auto; background-color: #e1e7f4; overflow: hidden; margin-bottom: 3px">
	<div style="float: left; width: 30%; height: auto; text-align: left">DETAILS</div>
	<div style="float: right; width: 70%; height: auto; background-color: #ffffff; text-align: left"><textarea  readonly style="width:99%" class="input_textarea" rows="5"><?php echo $row->details; ?></textarea></div>
</div>
<div style="position: relative; border: 2px solid #bccbe5; width: auto; heigh: auto; background-color: #e1e7f4; overflow: hidden; margin-bottom: 3px">
	<div style="float: left; width: 30%; height: 18px; line-height: 18px; text-align: left;border-right: 2px solid #bccbe5;">REQUESTER</div>
	<div style="float: right; width: 69%; height: 18px; line-height: 18px; background-color: #ffffff; text-align: left"><?php echo $row->email; ?></div>
</div>

<form name="save_bug" id="save-bug">
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="email" value="<?php echo $row->email; ?>" />
<input type="hidden" name="details" value="<?php echo $row->details; ?>" />
<div style="position: relative; border: 2px solid #bccbe5; width: auto; heigh: auto; background-color: #e1e7f4; overflow: hidden; margin-bottom: 3px">
	<div style="float: left; width: 30%; height: 18px; line-height: 18px; text-align: left">FEEDBACK</div>
	<div style="float: right; width: 70%; height: auto; background-color: #ffffff; text-align: left"><textarea  style="width:99%" name="feedback" id="feedback-area" class="input_textarea" rows="5"></textarea></div>
</div>
<div style="position: relative; border: 2px solid #bccbe5; width: auto; heigh: auto; background-color: #e1e7f4; overflow: hidden; margin-bottom: 3px">
	<div style="float: left; width: 30%; height: 18px; line-height: 18px; text-align: left;border-right: 2px solid #bccbe5;">SEND EMAIL</div>
	<div style="float: right; width: 69%; height: 18px; line-height: 18px; background-color: #ffffff; text-align: left"><input type="checkbox" name="send_email" id="send-email" value="1" checked /></div>
</div>
<div style="position: relative; border: 2px solid #bccbe5; width: auto; heigh: auto; background-color: #e1e7f4; overflow: hidden; margin-bottom: 3px">
	<div style="float: left; width: 30%; height: 25px; line-height: 25px; text-align: left;border-right: 2px solid #bccbe5;">&nbsp;</div>
	<div style="float: right; width: 69%; height: 25px; line-height: 25px; background-color: #ffffff; text-align: left">
	<input type="submit" value="Resolved" class="button art-button" id="resolved-button" />
	<input type="button" value="Close" id="close-button" class="button art-button" />
	</div>
</div>
</form>
<div id="show-email" style="position: relative; width: auto; heigh: auto; display: none">
<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">Sending notification email.
</div>
