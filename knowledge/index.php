<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/knowledge/knowledge_add.js");
?>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<input type="hidden" id="qid" />

<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Knowledge Base" />
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
      CTS Knowledge Base</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

<!--Search Header-->
<div class="main-div" id="page-header">
<div class="main-header"><strong>Enter Search Keywords</strong></div>
<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

	<input type="radio" value="0" name="stype" checked />Contains any word&nbsp;&nbsp;&nbsp;<input type="radio" value="1" name="stype" />Contains all words
	<br />
	<form>
		<input type="text" size="26" name="srch" id="srch" maxlength="50" class="input_field"/>
		<input type="button" class="button art-button" id="display-entries" value="Display Entries">
		<div id="list-users" style="width: 100px; height: auto; display: none">
			<select name="userList" id="userList" size="10" style="width: 330px">
			</select>
		</div>
	</form>

		<!-----div for question list-->
		<div id="q-list" style="width: auto; height: auto; position: relative; margin: 0 16px 0 0">
		<table width="100%" border="0" style="table-layout: fixed">
				<tr>
					<th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
					<th style="width: 60%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">QUESTION</th>
					<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">ANSWERS</th>
					<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">KEYWORKDS</th>
				</tr>
				</table>
		</div>

		<div id="ajax-x" style="position: relative; width: auto; height: auto; display: none">
			<img src="images/kit-ajax.gif" width="16" height="16" border="0" alt="" style="vertical-align: middle">Searching...Please wait
		</div>

	<div id="list-records" style="width: auto; height: auto; position: relative; overflow: scroll; margin: 0 0 3px 0">
	</div>
	<div>
		<input type="button" class="button" value="New Question" id="new-button" />
		</div>
</div>


<!----------------------------->
<!----------Form--------------->
<div class="main-div" id="entry-form" style="display: none">
<div class="main-header"><strong>Knowledge Base Maintenance</strong></div>
<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<input type="hidden" name="record_id" id="record-id" />


	<div style="position: relative; width: auto; height: auto; clear: both">
		<b>QUESTION:</b>&nbsp;<span id="question"></span>
	</div><br />

	<div style="float: left; width: 45%; height: auto;">
		<span id="ans-state"><b>EDIT ANSWER</b></span>&nbsp;<span style="color: #0066ff">(Double click-New entry)</span><br />
		<textarea name="answer" id="answer" style="width: 100%" rows="10" class="input_textarea"></textarea>
	</div>

	<div style="float: right; width: 45%; height: auto;">
		<b>ANSWERS</b></span>&nbsp;<span style="color: #0066ff">(Double click-Delete entry)</span><br />
		<select name="possible_answer" id="possible-answer" style="width: 100%" size="11" class="input_select_big">
		</select>
	</div>

	<div style="position: relative;  width: auto; height: auto; clear: both">
		<b>KEYWORDS:</b>&nbsp;<input type="text" name="db_keywords" id="db-keywords" size="30" maxlength="100" readonly class="input_field" />
	</div>
	<div style="position: relative;  width: auto; height: auto">
			<input type="button" class="button" value="Save Record" id="save-button" />&nbsp;
			<input type="button" class="button" value="Extract Keywords" id="keywords-button" />&nbsp;
			<input type="button" class="button" value="Back" id="back-button" />
	</div>
</div>
<!----------------------------->

</div></div></div></div>