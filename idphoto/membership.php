<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/idphoto/member.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
?>
<a href="#" id="mem-card-lnk" class="modalizer"></a>
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
      ID Cards - Membership</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">


<div class="main-div" id="md">
<div class="main-header"><span id="frame-title" style="font-weight: bold">Search student number...</span></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<div id="table-header">
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
		<input type="text" value="" id="srch-std" size="9" maxlength="9" />&nbsp;<input type="button" value="Search..." id="srch-button" />
</div></div>

	<form id="mem-form">
		<div style="with: 100%; padding: 5px">
				<label>Student#</label><br />
				<input type="text" value="" size="9" maxlength="9" disabled=true id="mem-stdno" name="mem_stdno" /><br /><br />
				<label>Name:</label><br />
				<input type="text" value="" size="50" id="mem-name" disabled=true name="mem_name" /><br /><br />
				<label>Branch:</label><br />
				<input type="text" value="" size="50" id="mem-branch" disabled=true name="mem_branch" onKeyUp="this.value=this.value.toUpperCase();" /><br /><br />
				<label>Organisation:</label><br />
				<select id="mem-org" name="mem_org" size="1" disabled=true/></select><br />		
		</div>
	</form>

		<div style="with: 100%; padding: 5px; margin-top: 10px">
			<div id="new-div1" style="display: none" /><input type="button" id="new-member" value="Save Record" /></div>
			<div id="new-div2" style="display: none" /><input type="button" id="del-member" value="Delete Record" /></div>
			<div id="new-div3" style="display: none" /><input type="button" id="prn-member" value="Print Card" /></div>
		</div>

</div></div></div></div>
