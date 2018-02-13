<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
//$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/idphoto/admin/index.js');
$doc->addScript('scripts/jtable/jquery.min.js');
$doc->addScript('scripts/jtable/jquery-ui.js');
$doc->addScript('scripts/jtable/jquery.jtable.js');
$doc->addScript('scripts/jtable/jquery.validationEngine.js');
$doc->addScript('scripts/jtable/jquery.validationEngine-en.js');
$doc->addScript('scripts/json.js');
$doc->addStyleSheet('scripts/jtable/themes/metro/blue/jtable.css');
$doc->addStyleSheet('scripts/jtable/jquery-ui.css');
$doc->addStyleSheet('scripts/jtable/validationEngine.jquery.css');

?>
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="staff-no" value="" />
<input type="hidden" id="staff-dept" value="" />
<input type="hidden" id="login-name" value="<?php echo $user->username; ?>" />

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
       ID Card Temp Users <span id="hr-details"></span></h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
		 
	<div id="tableData" style="position: relative; width: auto; height: auto"></div>

	
	
</div></div></div></div>