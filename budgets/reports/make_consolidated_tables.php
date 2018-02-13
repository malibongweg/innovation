<?php
error_reporting(E_ERROR & E_WARNING);
ini_set('display_errors', 1);

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/budgets/reports/index.js');
$doc->addScript('scripts/json.js');
//require_once($_SERVER['DOCUMENT_ROOT']."/scripts/budgets/reports/functions.php");
?>
<form id="user_id">
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<input type="hidden" id="user-id" value="<?php echo $user->id; ?>" />
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
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
       <span id="budget-title">Creating Consolidated Tables</span></h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

					<div id="budget-loader" style="display: block">
						<img src="images/kit-ajax.gif" width="16" height="11" border="0" style="vertical-align: middle"/>&nbsp;Busy...please wait.&nbsp;
						<span id="consol-progress"></span>
					</div>
					<div id="budget-fin" style="display: none">
					Consolodated tables generation completed...
					</div>

</div></div></div></div>

<script type="text/javascript">
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=142',
			method: 'get',
			noCache: true,
			onComplete: function(response){
			$('budget-loader').setStyle('display','none');
			$('budget-fin').setStyle('display','block');
		}
	}).send();
</script>
