<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/its/pinrequest/pin.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
?>

<input type="hidden" id="uname" name="user_name" value="<?php echo $user->username; ?>" />

<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="ITS Pin Request" />
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
      ITS Pin Request</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div">
<div id="main-header-title" class="main-header"><span id="frame-title" style="font-weight: bold">Pin Request</span></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	
	<div id="search-pin">
		<img src="/images/kit-ajax.gif" width="16" height="11" border="0" style="vertical-align: middle">&nbsp;Checking Pin Repository....Please wait..</img>
	</div>

	<div id="pin-data" style="display: block">
		<label>Student#</label>&nbsp;
		<input type="text" id="its-lookup" size="9" maxlength="9" />&nbsp;
		<input type="button" id="get-pin" class="button" value="Request Pin" />
	</div>

</div>
</div></div></div></div>
