<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addScript('scripts/system/system.js');
$doc->addScript('scripts/json.js');
$doc->addScript('scripts/color-picker.js');
$doc->addScript('scripts/fav_apps.js');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
?>

<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="System Notification Messages" />
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
       System Scroll Messages</h3>
        </div>
            <div class="art-blockcontent">
            <div class="art-blockcontent-body">


<fieldset  id="browse-frame" class="input_fieldset">
	<legend class="input_legend"><strong>List of messages</strong></legend>
<div id="loader" style="width: auto; height: auto; display: none"></div>
<div id="msg-data" style="width: auto; height: auto">
<img src="scripts/ajax-loader.gif" width="160" height="24" alt="" />
</div>
<table border="0" width="100%">
<tr>
<td width="100%"><input type="button" value="New" id="new-rec" class="button art-button" />
<input type="button" value="Edit" id="edit-rec" class="button art-button" />
</td>
</tr>
</table>	
</fieldset>


<!--//////////Form////////////////////////-->
<fieldset id="form-frame" style="border: 1px solid #C0C6BA;-moz-border-radius: 5px; border-radius: 5px; background-color: #EEEEEE; display: none">
<form name="frm_msg" id="frm-msg">
<input type="hidden" name="rec_action" id="rec-action" />
<input type="hidden" name="rec_id" id="rec-id" />
Text message<br />
<input type="text" name="txt_msg" id="txt-msg" size="100" maxlength="100" class="input_field" /><br clear=both/>
Color<br />
<input type="text" size="10" name="txt_color" id="txt-color" onkeyup="this.value = '';" class="input_field" ><br clear=both />
Active
<input type="checkbox" name="txt_active" id="txt-active" /><br />
<input type="button" value="Save" id="save-button" class="button art-button" />&nbsp;
<input type="button" value="Cancel" id="cancel-button" class="button art-button" />
</form>
</fieldset>

</div></div></div></div>