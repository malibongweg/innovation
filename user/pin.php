<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/user/pin.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
?>
<input type="hidden" id="system-mode" />
<input type="hidden" id="system-log" />

<form id="user-details">
<input type="hidden" id="ufname" name="user_fname" value="<?php echo $user->name; ?>" />
<input type="hidden" id="uname" name="user_name" value="<?php echo $user->username; ?>" />
<input type="hidden" id="user-email" name="user_email" value="<?php echo $user->email; ?>" />
<input type="hidden" id="staff-no" name="staff_no" value="" />
<input type="hidden" id="staff-pin" name="staff_pin" value="" />
</form>

<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Canon Uniflow" />
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
            <div class="art-block-body" id="bh">

        
                <div class="art-blockheader">
            <div class="l"></div>
            <div class="r"></div>
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Canon Uniflow</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div">
<div id="main-header-title" class="main-header"><span id="frame-title" style="font-weight: bold">Pin Request</span></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	
	<div id="search-uniflow">
		<img src="/images/kit-ajax.gif" width="16" height="11" border="0" style="vertical-align: middle">&nbsp;Checking Pin Repository....Please wait..</img>
	</div>

	<div id="uniflow-data" style="display: none">
		<input type="button" id="email-pin" class="button" value="Email Pin To admin@cput.ac.za" />&nbsp;
		<input type="button" id="new-pin" class="button" value="Request New Pin"  />
	</div>

	<div id="sending-email" style="display: none">
		<img src="/images/kit-ajax.gif" width="16" height="11" border="0" style="vertical-align: middle">&nbsp;Sending Pin...Please Wait...</img>
	</div>

</div>
</div></div></div></div>
