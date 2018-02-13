<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/auction/firesale/java.js');
$doc->addScript('scripts/json.js');
?>

<style type="text/css">
	ul.products li {
    width: 200px;
    display: inline-block;
	background-image: url() !important;
}

ul.products h5 {
	margin: 5px;
}

</style>
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="uid-loginname" value="<?php echo $user->username; ?>" />

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
       Assets Sale <span id="hr-details"></span></h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
	
	<div id="data-div" style="padding: 3px; margin: 10px">
			<p><b><u>AUCTION RULES</u></b></p>		
			<ul>
				<li>Only one item per user allowed.</li>
				<li>You will be held liable for payment on any one item you bid on.</li>
				<li>Your bid on an item cannot be reversed.</li>
			</ul>
    </div>

	<div id="div-products" style="text-align: center; padding: 3px; background-color: #c9c9c9; border: 1px solid #9d9d9d">
		
	</div>
	
</div></div></div></div>