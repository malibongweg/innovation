<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addScript("scripts/auction/java.js");
$doc->addScript("scripts/json.js");
$user = & JFactory::getUser();
?>
<style>
.round-borders {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	/*background-image: url(/scripts/auction/images/hammer.png);*/
}

.bid-header {
	opacity: 0.7;
    filter: alpha(opacity=70);
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=70)";
}

.bid-header2 {
	opacity: 0.8;
    filter: alpha(opacity=80);
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)";
}


.myButton {
	background-color:#51a4db;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	border:1px solid #6781e0;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Verdana;
	font-size:12px;
	padding:5px;
	text-decoration:none;
}
.myButton:hover {
	background-color:#5cbf2a;
}
.myButton:active {
	position:relative;
	top:1px;
}


</style>
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="username" value="<?php echo $user->username." [".$user->name."]"; ?>" />
<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />

<!-- Navigation -->
	<div style="padding: 5px; background-color: #c8c8c8; text-align: left">
		<input type="button" id="back-button" class="button" value="<<PREVIOUS"  width="32" height="32" />&nbsp;&nbsp;
		<input id="foward-button" type="button"  width="32" height="32" value="NEXT>>" class="button" style="vertical-align: middle" />&nbsp;&nbsp;
		<span id="bidding-timer" style="font-size: 14px; font-weight: bold"></span>
			<div id="auction-spinner" style="float: right; font-size: 14px; display: none">
				<img alt="" src="/scripts/auction/images/security.gif" style="vertical-align: middle">&nbsp;Loading page...Please wait.
			</div>
	</div>
<div style="margin: 5px 0 0 0">
<p>AUCTION RULES:</p>
<p>A user may only bid on <b>one</b> item displayed on the auction pages. If you are currently the highest bidder on an item, you bid amount will be displayed with a green background.
At this point you will not be allowed to bid on any other items.
 Should another user beat your bid price, you will be allowed to bid on the same item or another item.</p>
</div>

<div style="text-align: center">
	<img src="/scripts/auction/imac.jpg" width="250" height="175" border="0" alt="">
</div>

<div id="auction-container" style="padding: 5px; text-align: center">

</div>
<div style="padding: 5px; background-color: #c8c8c8; text-align: left">
		<input type="button" id="back-button2" class="button" value="<<PREVIOUS"  width="32" height="32" />&nbsp;&nbsp;
		<input id="foward-button2" type="button"  width="32" height="32" value="NEXT>>" class="button" style="vertical-align: middle" />&nbsp;&nbsp;
	</div>