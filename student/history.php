<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/student/history.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addStyleSheet("scripts/tables.css");
$dbo->setQuery("call proc_pop_application('Student History Report')");
$dbo->query();
?>
<a href="#" id="print-history" class="modalizer"></a>

<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<input type="hidden" id="sysid" value="<?php echo $user->id; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Student History Report" />
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
      Student History Report</h3>
        </div>
            <div class="art-blockcontent">
            <div class="art-blockcontent-body" >




<div id="search" class="main-div">
	<div class="main-header"><strong><span id="del-heading">Enter Student No</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<input type="text" id="stnumber" name="stno" size="9" maxlength="9" class="numeric" style="background-color: #ffffff; border: 1px solid #4670B4; height: 18px" />&nbsp;
	<input type="button" class="button" id="get-info" value="Display History" />&nbsp;
	Please note...verify this history report with your exams department.
	</div>

<div id="display-history" class="main-div" style="display: block">
<div class="main-header"><strong><span id="del-heading">Year</span></strong></div>
<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<select name="yr" id="yr" size="1" class="input_select" style="width: 200px">
	</select>&nbsp;<input type="button" value="Print" class="button" id="prn-history" />
	<div style="position: relative; clear: both; margin: 0 0 5px 0"></div>

<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0" id="header">
<table width="100%" border="0" style="table-layout: fixed">
<tr>
<td style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>YR/BC</strong></td>
<td style="width: 10%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>CRS CDE</strong></td>
<td style="width: 15%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>SAPSE</strong></td>
<td style="width: 7%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>OFF</strong></td>
<td style="width: 15%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>SUBJ</strong></td>
<td style="width: 5%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>YM</strong></td>
<td style="width: 7%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>EXAM</strong></td>
<td style="width: 10%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>FINAL</strong></td>
<td style="width: 11%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>RESULT</strong></td>
<td style="width: 7%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>CR</strong></td>
</tr>
</table>
</div>

<div id="display-details" style="width: auto; height: auto; position: relative; padding: 0; overflow: auto">
</div>

</div>

!--This module has been disabled during the marks assesment period. Thank you.--

</div></div></div></div>