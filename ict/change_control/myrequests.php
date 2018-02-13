<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('CTS Change Control')");
$dbo->query();
?>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="CTS Change Control" />
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
			<a href="index.php/change-control"><img src="images/chg_home.png" width="16" height="16" border="0" alt="" title="Submit new request" style="vertical-align: middle"></a>
			<a id="my-requests" href="index.php/my-requests"><img src="images/myrequests.png" width="14" height="16" border="0" alt="" title="My requests" style="vertical-align: middle"></a>
			<a id="all-requests" href="index.php/all-requests"><img src="images/allrequests.png" width="16" height="14" border="0" alt="" title="Requests for your attention." style="vertical-align: middle"></a>
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      CTS Change Control - My Requests</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">


<div style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; -moz-border-radius: 5px; border-radius: 5px; margin-top: 3px; display: block">
<!-----------------Main DIV-------------------------------->

<table cellpadding='1' cellspacing='4' border='0' width='80%'>
				<tr>
			<td style='background-color: #B88A00'>&nbsp;</td>
			<td>
				<font style='color: #B88A00; font-size:9px;'>&nbsp;&nbsp;<b>INCLOMPLETE</b></td><td>&nbsp;&nbsp;Form is not fully completed, and therefore cannot be processed.</font>
			</td>
		</tr>
		<tr>
			<td style='background-color: #666666'>&nbsp;</td>
			<td>
				<font style='color: #666666; font-size:9px;'>&nbsp;&nbsp;<b>PENDING</b></td><td>&nbsp;&nbsp;Awaiting approval from Change Control Committee</font>
			</td>
		</tr>
		<tr>
			<td style='background-color: #009933'>&nbsp;</td>
			<td>
				<font style='color: #009933; font-size:9px;'>&nbsp;&nbsp;<b>ACCEPTED</b></td><td>&nbsp;&nbsp;Roll-out of request can commence</font>
			</td>
		</tr>
		<tr>
			<td style='background-color: #CC0033'>&nbsp;</td>
			<td>
				<font style='color: #CC0033; font-size:9px;'>&nbsp;&nbsp;<b>REJECTED</b></td><td>&nbsp;&nbsp;Change Control Committee has rejected the request</font>
			</td>
		</tr>
		<tr>
				<tr>
			<td style='background-color: #FF6633'>&nbsp;</td>
			<td>
				<font style='color: #FF6633; font-size:9px;'>&nbsp;&nbsp;<b>ONGOING</b></td><td>&nbsp;&nbsp;Technician is in the process of completing request</font>
			</td>
		</tr>
		<tr>
			<td style='background-color: #CC66FF'>&nbsp;</td>
			<td>
				<font style='color: #CC66FF; font-size:9px;'>&nbsp;&nbsp;<b>RESOLVED</b></td><td>&nbsp;&nbsp;Problem has been resolved</font>
			</td>
		</tr>
		<tr>
			<td style='background-color: #0066CC'>&nbsp;</td>
			<td>
				<font style='color: #0066CC; font-size:9px;'>&nbsp;&nbsp;<b>COMPLETED</b></td><td>&nbsp;&nbsp;Change has been fully implemented and but relevant documents is outstanding.</font>
			</td>
		</tr>
		<tr>
			<td style='background-color: #000000'>&nbsp;</td>
			<td>
				<font style='color: #000000; font-size:9px;'>&nbsp;&nbsp;<b>CLOSED</b></td><td>&nbsp;&nbsp;Relevant documents have been received and document officer has closed the request.</font>
			</td>
		</tr>
	</table>

<div style="position: relative; with: auto; height: auto">
<table width='100%' cellspacing='1' cellpadding="2" border='0'>
	<tr bgcolor='#1E4F62'>
	<td width = '10%' class="table_header"><b>REF #</b></td>
	<td width = '20%' class="table_header"><b>SENT DATE</b></td>
	<td width = '40%' class="table_header"><b>DESCRIPTION</b></td>
	<td width = '20%' class="table_header"><b>STATUS</b></td>
	<td width = '10%' class="table_header">&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
</div>

<!-----------------Main DIV-------------------------------->
</div>
</div></div></div></div>