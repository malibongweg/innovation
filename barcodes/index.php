<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/barcodes/index.js");
$doc->addScript("scripts/json.js");
?>
<a href="#" class="modalizer" id="monthly-link"></a>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<input type="hidden" id="cardno" value="" />
<!--Define app name here-->

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
      Barcodes Verification</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div" id="barcodes-header">
	<div id="ajax-barcode" style="margin: 3px; padding: 3px; display: none">
		<img src="/images/kit-ajax.gif" width="16" height="16" border="0" alt="">
	</div>
	<div style="margin: 5px; padding: 5px; border: 1px solid #c0c0c0">
		<span>Student#</span>
		<input type="text" name="std_no" id="std-no" size="9" value="" />&nbsp;
		<input type="button" id="std-search" class="button" value="Search" />
	</div>

</div>

<div class="barcode-div" id="barcodes-details" style="display: none; padding: 10px; position: relative; overflow: hidden">
	<img src="/scripts/barcodes/cputcard.png" width="390" height="250" border="0" alt="">

	<div id="crd-name" style="position: absolute; top: 110px; left: 15px; height: 70px; width: 220px; font-size:14px; font-weight: bold">
	</div>

	<div id="crd-barcode" style="position: absolute; top: 225px; left: 15px; height: 25px; width: 220px; font-size:16px; font-weight: bold; color: #006600">
	</div>

	<div id="crd-img" style="position: absolute; top: 100px; left: 275px; height: 115px; width: 110px; border: 1px solid #000000">
		<img id="student-pic" src="" style="width: 100%; height: 100%" border="0" alt="">
	</div>

	<div id="other-details" style="display: block;  position: relative; margin-top: 10px">
		<div style="float: left; width: 30%; padding: 5px">
			<span id="copy-barcode" style="font-size: 16px; font-weight: bold; color: #000000; margin-right: 10px"></span>
			<input type="button" class="button" id="copy-button-fix" value="Fix" style="display: none" />
		</div>
	
		<div style="float: right; width: 50%; padding: 5px">
			<span id="meals-barcode" style="font-size: 16px; font-weight: bold; color: #000000;  margin-right: 10px"></span>
			<input type="button" class="button" id="meals-button-fix" value="Fix" style="display: none" />&nbsp;
			<input type="button" class="button" id="meals-mail-button" value="Mail Pin" style="display: block" />
		</div>
	</div>
</div>




</div></div></div></div>