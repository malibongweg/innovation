<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/student/utility_accounts/utility.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Payment Credits')");
$dbo->query();
?>
<a href="#" class="modalizer" id="receiptWND"></a>
<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Payment Credits" />
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
            <div class="art-block-body">

        
                <div class="art-blockheader">
            <div class="l"></div>
            <div class="r"></div>
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Printing & Photocopy Credit History</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div" id="table-header">
	<div class="main-header"><strong>Enter Receipt#</strong></div>
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

	<input type="text" id="receipt-no" name="receipt_no" size="15" maxlength="25" class="numeric" />&nbsp;
	<input type="button" class="button" id="get-receipt" value="Display Receipt Details" /><br />
	<select id="display-month" size="1" class="input_select" style="width: 50px">
	<?php
		$m= date('m');
		for ($i = 1; $i<13; ++$i) {
			if ($i == $m) {
				echo "<option value='".$i."' selected>".$i."</option>";
			} else {
				echo "<option value='".$i."'>".$i."</option>";
			}
		}
	?>
	</select>
	<label style="font-weight: bold" for="display-month">Display Month (Current Year Only)</label>

		<div id="ajax1" style="width: auto; height: auto; position: relative; display: none">
			<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Busy...
		</div>


		<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
				<table width="100%" border="0" style="table-layout: fixed">
				<tr>
					<th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
					<th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">DATE</th>
					<th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">TYPE</th>
					<th style="width: 25%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">AMOUNT</th>
					<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">STATUS</th>
				</tr>
				</table>
		</div>

<div id="receipt-display" style="position: relative; width: auto; height: auto;  display: none; overflow: scroll">
</div>

</div>
</div></div></div></div>
