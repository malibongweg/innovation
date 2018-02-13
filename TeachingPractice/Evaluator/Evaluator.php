<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/TeachingPractice/jquery.js");
$doc->addScript("scripts/TeachingPractice/jquery-ui.js");
$doc->addStyleSheet("scripts/TeachingPractice/jquery-ui.css");
$doc->addScript("scripts/TeachingPractice/Evaluator/EvaluatorJs.js");

?>
<input type="hidden" id="cmp" value="<?php echo $cmp->campus; ?>" />
<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Fleet Management" />
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
       Evaluator1</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                
     <div class="main-div" id="mn" >
     <div class="main-header"><span id="frame-title" style="font-weight: bold">Add Evaluator</span></div>
     <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>    
                
                         
<!--form-->
<div id="claim-data" class="table-header" style="position: relative; width: auto; height: auto; ">
	<div id="frm-ajax" style="position: absolute; background-color: #dbdbdb; top: 5px; left: 0px; width: auto; height: auto; z-index: 1000; padding: 3px; border: 2px solid #cdcdcd; display: none">
		<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Populating form...please wait.
	</div>


	<label class="input_label">Name:</label>
        <input type="text" size="30" name="evaluator_addres" value="<?php echo $user->id; ?>" id="evaluator-addres" class="input_field" maxlength="30" onKeyUp="javascript: this.value = this.value.toUpperCase();" /><br />
	<label class="input_label">Surname:</label>
		<input type="text" size="30" name="evaluator_addres" id="evaluator-addres" class="input_field" maxlength="30" onKeyUp="javascript: this.value = this.value.toUpperCase();" /><br />
	<label class="input_label">Address 1:</label>
		<textarea style="width: 30%" name="bulk_message" id="bulk-message" rows="10" class="input_textarea"></textarea>
		<br />

	<label class="input_label">Address 2:</label>
		<textarea style="width: 30%" name="bulk_message" id="bulk-message" rows="10" class="input_textarea"></textarea><br/>
	<label class="input_label">Email Address:</label>
		<input type="text" size="30" name="evaluator_addres" id="evaluator-addres" class="input_field" maxlength="30" onKeyUp="javascript: this.value = this.value.toUpperCase();" /><br />
	
	<label class="input_label">Telephone Number:</label>
		<input type="text" name="evaluator_telephone" id="evaluator-telephone" class="input_field" size="4" maxlength="4" onKeyUp="this.value=this.value.toUpperCase();" />
		<span id="cname"></span>
		<br />

	<label class="input_label">Cell phone Number:</label>
       <input type="text" size="30" name="evaluator_addres" id="evaluator-addres" class="input_field" maxlength="30" onKeyUp="javascript: this.value = this.value.toUpperCase();" /><br />

	<label class="input_label">Category:</label>
        <select size="1" class="input_select" name="vehicle_reg" id="evaluator-addres" style="width: 30%">
        </select><br />
		
	<label class="input_label">Number of visits:</label>
		<input type="text" size="10" name="end_km" id="end-km" class="numeric" maxlength="10"  /><br />                
                <label class="input_label">&nbsp;</label><br />
                <input type="submit"  value="Add Evaluator" id="trip-save" class="button art-button"/>&nbsp;
		<input type="button" value="Cancel" id="trip-cancel" class="button art-button"/>	
</form>
</div>

</div>
</div></div></div></div>




