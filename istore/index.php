<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
//$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/istore/index.js');
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
<style type="text/css">
	.label-left {
		text-align: left;
		font-size: 12px;
	}
</style>
<a id="lnk-report" href="#" class="modalizer" /></a>
<a id="sum-report" href="#" class="modalizer" /></a>
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="storeid" value="" />
<input type="hidden" id="storename" value="" />

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
			<span id="store-name"></span>
       </h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

	<div style="margin-bottom: 5px; padding: 3px; border: 2px solid #8099ff; background-color: #c4d1ff">
		<input type="button" value="Orders" class="button" id="orders-button" />&nbsp;
		<input type="button" value="Store Items" class="button" id="items-button" />&nbsp;
		<input type="button" value="Claim Report (Summary)" class="button" id="summary-button" />
			<div id="audit-div" style="float: right;  text-align: right">

			</div>
	</div>

	<div style="display: none; padding: 3px" id="estore-busy">
		<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="ajax" style="vertical-align: middle">&nbsp;Busy...Please wait.
	</div>

	<div id="store-orders" style="display: block">
		<div id="tableOrders" style="position: relative; width: auto; height: auto"></div>
	</div>

	<div id="store-items" style="display: none">
		<div id="tableItems" style="position: relative; width: auto; height: auto"></div>
	</div>

<div id="dialog-form-eStore">
	<form id="eStore-report-params">
		 <fieldset style="text-align: left">
				<label class="label-left" for="start_date">Start Date</label><br />
				<input type="text" name="start_date" id="start-date" size="10" class="text ui-widget-content ui-corner-all" style="font-size: 12px !important"><br /><br />
				<label class="label-left" for="end_date">End Date</label><br />
				<input type="text" name="end_date" id="end-date" value="" size="10" class="text ui-widget-content ui-corner-all" style="font-size: 12px !important"><br /><br />
				<label class="label-left" for="cost_centre-rep">Cost Centre</label><br />
				<select name="cost_centre" id="cost-centre-rep" size="1"  style="font-size: 12px !important" />
					<option value="0000">ALL</option>
				</select>
				<input type="hidden" value="1" id="rep-type-select" />
		</fieldset>
	</form>
</div>

</div></div></div></div>