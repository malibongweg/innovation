<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
//$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/budgets/admin/index.js');
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
       Budget Administration Module</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
		<form id="conf-form">
			<label style="float: left; width: 120px">Admin Email</label><br>
			<input type="text" id="admin-email" name="admin_email" size="50" onkeyup="this.value=this.value.toLowerCase();"><br><br>

			<label style="float: left; width: 120px">Budget Cycle</label><br>
			<select id="budget-cycle" name="budget_cycle" size="1" style="width: 70px" />
			</select><br><br>

			<label style="float: left; width: 120px">Cutoff Date</label><br>
			<?php
				echo JHTML::calendar('','cutoff','cutoff-date','%Y-%m-%d');
			?><br><br>

			<label style="float: left; width: 120px">Approval Date</label><br>
			<?php
				echo JHTML::calendar('','approval','approval-date','%Y-%m-%d');
			?><br><br>

			<label style="float: left; width: 120px">Allow Super-Users</label><br>
			<input type="checkbox" name="superusers" id="super-users" />

			<label style="float: left; width: 120px"></label><br>
			<input type="submit" value="Update Config" class="button" />
		</form>
		<br>
 
	<div id="tableData" style="position: relative; width: auto; height: auto"></div>

	
	
</div></div></div></div>