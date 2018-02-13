<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/TeachingPractice/jquery.js");
$doc->addScript("scripts/TeachingPractice/jquery-ui.js");
$doc->addScript("scripts/TeachingPractice/SchoolMaintanance/school.js");
$doc->addStyleSheet("scripts/TeachingPractice/jquery-ui.css");
echo "hi reuben";
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
       Switch Evaluator</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                
     <div class="main-div" id="mn" >
     <div class="main-header"><span id="frame-title" style="font-weight: bold">Switch Evaluator</span></div>
     <div style="position: relative; clear: both; margin: 10px 0 0 0"></div> 


<div id="claim-data" class="table-header" style="position: relative; width: auto; height: auto; ">
	<div id="frm-ajax" style="position: absolute; background-color: #dbdbdb; top: 5px; left: 0px; width: auto; height: auto; z-index: 1000; padding: 3px; border: 2px solid #cdcdcd; display: none">
		<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Populating form...please wait.
	</div>
    


	<label class="input_label">Evaluator Name:</label>
		<input type="text" size="15" name="trip_date" id="Evaluator-name" readonly class="input_field" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="submit" value="Search" id="trip-save" class="button art-button"/>&nbsp;<br /><br /><br />
	<label class="input_label">Student Details:</label><br />
         <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
				<table width="100%" border="0" style="table-layout: fixed">
				<tr>
					<th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px">&nbsp;</th>
					<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px">Student No</th>
					<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">Grade</th>
					<th style="width: 25%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">Mentor</th>
					<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: center">Evaluator</th>
					<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: center">Evaluator 2</th>
					<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: center">TP Dates</th>
			
				</tr>
				</table>
	 </div>
                <label class="input_label">&nbsp;</label><br />
                <input type="submit" value="Switch Evaluator" id="trip-save" class="button art-button"/>&nbsp;
		<input type="button" value="Cancel" id="trip-cancel" class="button art-button"/>
	
</form>
</div>





</div>
</div></div></div></div>



