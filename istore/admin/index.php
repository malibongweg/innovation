<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
//$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/istore/admin/index.js');
$doc->addScript('scripts/istore/jtable/js/jquery-1.10.2.js');
$doc->addScript('scripts/istore/jtable/js/jquery-ui-1.10.4.js');
$doc->addScript('scripts/istore/jtable/jquery.jtable.js');
$doc->addScript('scripts/istore/jtable/js/jquery.validationEngine.js');
$doc->addScript('scripts/istore/jtable/js/jquery.validationEngine-en.js');

$doc->addStyleSheet('scripts/istore/jtable/css/custom-theme/jquery-ui-1.10.4.custom.css');
$doc->addStyleSheet('scripts/istore/jtable/themes/metro/blue/jtable.css');
$doc->addStyleSheet('scripts/istore/jtable/css/validationEngine.jquery.css');

$doc->addScript('scripts/json.js');

?>
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
       eStore Administration Module</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

	<div id="tableData" style="position: relative; width: auto; height: auto"></div>



</div></div></div></div>