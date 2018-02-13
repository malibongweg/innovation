<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/system/pcron.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->query();
?>
<a href="#" id="reset-invalid" class="modalizer"></a>
<a href="#" class="modalizer" id="validate_student"></a>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Pcounter Logs" />
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
      PCounter Logs</h3>
        </div>
            <div class="art-blockcontent" style="background-color: #EEEEEE">
            <div class="art-blockcontent-body" style="background-color: #EEEEEE">

<div id="log-details" class="main-div">
	<div class="main-header"><strong><span id="del-heading">Select Log Month (Current Year Only)</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

	<div style="position: relative; width: auto; height: auto; padding: 3px; border: 1px solid #bfbcfe; vertical-align: middle; background-color: #d9ddff">
<strong>Account Filter [</strong>
<input type="radio" name="filter_type" id="filter-type" value="filter_studentno" checked>&nbsp;Student#&nbsp;
<input type="radio" name="filter_type" id="filter-type" value="filter_receiptno">&nbsp;Receipt#<strong>&nbsp;]</strong><br />
<input type="text" id="filter-id"  size="15" maxlength="15" class="input_field">&nbsp;
<input type="button" class="button art-button" id="srch-button" value="Search" />&nbsp;
<input type="button" class="button art-button" id="bc-sync" value="Synchronize Barcode"/>
</div>
<div style="position: relative; margin: 3px 0 3px 0; padding: 3px; background-color: #fd9999; border: 1px solid #f86e6e">
<strong>Last 1000 entries...use filter for more acurate search.</strong>
</div>

	<select name="mth" id="mth" size="1" style="width: 60px" class="input_select" >
	<?php
		$t = date('m');
		for ($i=1;$i<13;$i++) {
		if ($i == intval($t)) {
			echo "<option value=".$i." selected>".$i."</option>\n";
		} else {
			echo "<option value=".$i.">".$i."</option>\n";
		}
	}
	?>
	</select>&nbsp;<strong>Month</strong>&nbsp;&nbsp;&nbsp;
	&nbsp;
	<select name="log_year" id="log-year" size="1" style="width: 60px" class="input_select" >
	<?php
		$y = date('Y');
		for ($i=$y-1;$i<$y+1;$i++) {
		if ($i == intval($y)) {
			echo "<option value=".$i." selected>".$i."</option>\n";
		} else {
			echo "<option value=".$i.">".$i."</option>\n";
		}
	}
	?>
	</select>&nbsp;<strong>Year</strong>&nbsp;&nbsp;&nbsp;
	<select name="status_flag" id="status-flag" size="1" style="width: 120px" class="input_select" >
		<option value="0">Un-Processed</option>
		<option value="1">Processed</option>
		<option value="2">Invalid Entries</option>
		<option value="3">All</option>
	</select>&nbsp;<strong>Status Flag</strong>

	&nbsp;&nbsp;&nbsp;<input type="checkbox" name="hide_errors" id="hide-errors" checked />&nbsp;Hide Errors&nbsp;
	<input type="checkbox" name="hide_barcodes" id="hide-barcodes" checked />HBC

<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
<table style="width: 100%; position: relative; height: auto; border: 0px; table-layout: fixed">
<tr>
<td style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF">&nbsp;</td>
<td style="width: 4%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF"><strong>UF</strong></td>
<td style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; text-align: center"><strong>TRD</strong></td>
<td style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; text-align: center"><strong>PRD</strong></td>
<td style="width: 13%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF"><strong>ACC#</strong></td>
<td style="width: 13%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF"><strong>TYPE</strong></td>
<td style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF"><strong>RECEIPT</strong></td>
<td style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; text-align: right"><strong>AMT</strong></td>
<td style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; text-align: right"><strong>NV</strong></td>
<td style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; text-align: center"><strong>RC</strong></td>
</tr>
</table>
</div>


<div id="ajax-pcounter" style="width: auto; height: auto; position: relative; padding: 5px; display: none">
<img src="images/kit-ajax.gif" width="16" height="11" alt="" style="vertical-align: middle" />&nbsp;Loading Data...
</div>

<div id="display-log" style="width: auto; height: 500px; overflow: scroll; position: relative">

</div>
</div><br clear=both />

<div id="balances-div" style="display: none; position: relative; margin: 3px 0 0 0; padding: 3px; border: 1px solid #bfbcfe; vertical-align: middle; background-color: #d9ddff">
	<strong>Printing Balance: <span id="prn-bal" style="color: #336600"><img src="images/kit-ajax.gif" width="16" height="16" /></span>&nbsp;&nbsp;&nbsp;
	Copy Balance: <span id="cp-bal" style="color: #336600"><img src="images/kit-ajax.gif" width="16" height="16" /></span>&nbsp;&nbsp;&nbsp;
	Meals Balance: <span id="mls-bal" style="color: #336600"><img src="images/kit-ajax.gif" width="16" height="16" /></span></strong>
</div>

</div></div></div></div>