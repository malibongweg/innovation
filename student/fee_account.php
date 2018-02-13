<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/student/fee.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Fee Account')");
$dbo->query();
?>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Fee Account" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="lgname" value="<?php echo $user->username; ?>" />
<input type="hidden" id="cyr" value="<?php echo date('Y'); ?>" />
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
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Student Fee Account</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">


<div class="main-div" id="mn" >

	<div id="srch" style="position: relative; width: auto; height: auto; display: block; border: 2px solid #000000; margin: 5px; padding: 3px">
		<input type="text" class="numeric" size="9" id="stdno" maxlength="9" />&nbsp;
		<input type="button" class="button art-button" value="Display Student Account" id="display-account" />
	</div>

	<div id="student-details" style="position: relative; width: auto; height: auto; display: block">
		<div style="position: relative: width: auto; height: auto; font-size: 12px; font-weight: bold; text-align: center; background-color: #bababa; border: 2px solid #6c6c6c; margin: 5px 5px 0 5px; padding: 3px">
			Un-Official Fee Account Statement
		</div>
		<div style="position: relative: width: auto; height: auto; font-size: 10px; text-align: left; background-color: #d4d4d4; border-left: 2px solid #6c6c6c;border-right: 2px solid #6c6c6c;border-bottom: 2px solid #6c6c6c; margin: 0 5px 0 5px; padding: 3px">
			Statement Date: <?php echo date('Y-m-d'); ?>
			<div id="sid" style="float: right"><?php echo $user->username; ?></div>
		</div>

		<div style="position: relative: width: auto; height: auto; font-size: 10px; text-align: left; background-color: #e5e5e5; border-left: 2px solid #6c6c6c;border-right: 2px solid #6c6c6c;border-bottom: 2px solid #6c6c6c; margin: 0 5px 0 5px; padding: 3px">
			<table width="100%" border="0"  cellpadding="2" cellspacing="2">
				<tr>
					<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6">Date</td>
					<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; text-align: center">Doc#</td>
					<td width="40%" style="overflow: hidden; border-right: 1px solid #a6a6a6">Description</td>
					<td width="15%" style="overflow: hidden; text-align: center; border-right: 1px solid #a6a6a6">Debit</td>
					<td width="15%" style="overflow: hidden; text-align: center">Credit</td>
				</tr>
					<?php
						$sql = "SELECT fin_date, fin_fee, fin_type, fin_refno, fin_amount, fin_desc FROM student.finance a left join structure.transdef on (fin_code = transno) , structure.accdef WHERE stud_numb = ' AND fin_acctyp = accno AND ifnull(acc_fee, 'F') = 'D' ORDER BY fin_date,fin_refno";
					?>
				<!--tr>
					<td width="70%" colspan="3" style="background-color: #ffffff;border-right: 1px solid #a6a6a6">Balance brought foward</td>
					<td width="15%" style="background-color: #ffffff;border-right: 1px solid #a6a6a6">0.00</td>
					<td width="15%" style="background-color: #ffffff">&nbsp;</td>
				</tr-->
			</table>
					<div id="fee-ajax" style="position: relative; height: auto; width: auto; display: none">
						<img src="/images/kit-ajax.gif" width="16" height="11" border="0" style="vertical-align: middle">&nbsp;Loading data...Please wait.
					</div>

					<div id="fee-data" style="position: relative; height: auto; width: auto; display: block">
						
					</div>
		</div>

	</div>


</div>
</div></div></div></div>