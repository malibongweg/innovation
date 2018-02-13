<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/ict/cartridge/cart.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/datepick/Locale.en-US.DatePicker.js");
$doc->addScript("scripts/datepick/Picker.js");
$doc->addScript("scripts/datepick/Picker.Attach.js");
$doc->addScript("scripts/datepick/Picker.Date.js");
$doc->addStyleSheet("scripts/datepick/datepicker.css");
$dbo->setQuery("call proc_pop_application('Cartridge Distribution')");
$dbo->query();
?>
<a href="#" class="modalizer" id="monthly-link"></a>
<a href="#" class="modalizer" id="slip-link"></a>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="CTS Cartridge Distribution" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="form-action" value="" />
<input type="hidden" id="record-id" value="0" />
<input type="hidden" id="x-cord" />
<input type="hidden" id="y-cord" />
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
      CTS Cartridge Distribution</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div" id="table-header">
	<div class="main-header"><strong>Cartridge Distributions</strong></div>
	
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

				<div style="width: auto; border: 2px solid #3C619C; background-color: #FFFFFF; padding: 3px; margin: 5px 0 5px 0">
					<?php
					$dbo->setQuery("select distinct year(captured) as yr from #__cartridge_issue order by yr desc limit 3");
					$result = $dbo->loadObjectList();
					echo "<select name='yr' id='yr-select' size='1' style='width: auto'>";
					foreach($result as $row){
						if (intval($row->yr) == intval(date('Y')))
							{ echo "<option value='".$row->yr."' selected>".$row->yr."</option>"; }
						else
							{ echo "<option value='".$row->yr."'>".$row->yr."</option>";}
					}
					echo "</select>";
					
					echo "<select name='mth' id='month-select' size='1' style='width: auto'>";
					for($i=1;$i<13;++$i){
						if (intval($i) == intval(date('m'))) {
							echo "<option value='".$i."' selected>".$i."</option>";
						} else {
							echo "<option value='".$i."'>".$i."</option>";
						}
					}
					echo "</select>";
					?>
					&nbsp;
					<input type="button" value="New" class="button art-button" id="new-record" onclick="newRecord()" />&nbsp;
					<input type="button" value="Edit" class="button art-button" id="edit-record" onclick="editRecordPre();" />&nbsp;
					<input type="button" value="Print Slip" class="button art-button" id="print-slip" />&nbsp;
					<input type="button" class="button art-button" value="Monthly Report" id="prn-monthly" /><br />
					<input type="radio" name="rtype" value="admin" checked/>Admin Report&nbsp;<input type="radio" name="rtype" value="cart"/>Cartrdige Report&nbsp;
					<input type="text" readonly name="sname" id="start-date" size="10" />&nbsp;<img class='date_toggler1' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="">&nbsp;
					<input type="text" readonly name="ename" id="end-date" size="10" />&nbsp;<img class='date_toggler2' src="images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt="">
					<div id="ajax-cart" style="position: relative; width: auto; height: auto; margin: 0 0 0 3px; display: none">
						<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">Busy...please wait.
					</div>
				</div>
		
				<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
				<table style="width: 100%; height: auto; border: 0px">
				<tr>
					<th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px">&nbsp;</th>
					<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">DATE</th>
					<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">CASE#</th>
					<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">STAFF#</th>
					<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">COSTCENTRE</th>
					<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">STATUS</th>
				</tr>
				</table>
				</div>

						<div id="data-div" style="position: relative; width: auto; height: 300px; overflow: scroll">
						</div>
	</div>
	
	
	<div class="main-div" id="form-data" style="display: none">
	<div class="main-header"><strong><span id="info">&nbsp;</span></strong></div>
	<form name="issue_form" id="cart-issue-form">
		
			<input type="hidden" name="booked_items" id="booked-items" value="" />
			<input type="hidden" id="doneby" name="doneby" value="<?php echo $user->username; ?>" />
			<input type="hidden" name="captured" id="captured" size="10" />
			<input type="hidden" name="transno" id="transno" size="10" />
			<br />


			<label for="caseid" class="input_label">Case#</label>
			<div id = "ajax3" style="position: absolute; display: none; z-index: 20"><img src="images/kit-ajax.gif" width="16"		height="16" border="0" alt="">
				</div>
			<input type="text" class="input_field" name="caseid" value="Case ID " id="caseid" size="10" autocomplete="off" />	
			<br />
				

			<label for="casedetails" class="input_label" style="vertical-align: top">Case Details</label>
			<textarea name="casedetails" id="casedetails" rows="3" class="input_textarea" style="width: 213px"></textarea><br />
					
			<label for="staffno" class="input_label">Staff#</label>
			<div id = "ajax1" style="z-index: 10; display: none; width: auto; z-index: 20"><img			src="images/kit-ajax.gif" width="16" height="16" border="0" alt="">
				</div>
			<input type="text" class="input_field" name="staffno" id="staffno" size="10" autocomplete="off"/><br />
					<div id="staff-select" style="display: none; position: absolute; top: 138px; left: 116px; z-index: 500">
						<select class="input_select_big" id="staff-select-drop" size="10" style="width: auto">
						</select>
					</div>

			<label for="costcentre" class="input_label">Cost Centre</label>
			<div id = "ajax2" style="display: none; z-index: 20"><img src="images/kit-ajax.gif" width="16"		height="16" border="0" alt="">
				</div>
			<input type="text" class="input_field" name="costcentre" id="costcentre" size="10" maxlength="4" autocomplete="off"/><br />
					<div id="centre-select" style="display: none; position: absolute; top: 165px; left: 116px; z-index: 500">
						<select class="input_select_big"  id="centre-select-drop" size="10" style="width: auto" >
						</select>
					</div>
			
			<label for="status" class="input_label">Status</label>
			<select class="input_select" name="status" id="status" size="1" class="input_select" style="width: 214px">
							<option value="AUTH REQ" selected>AUTH REQ</option>
							<option value="DELIVERED">DELIVERED</option>
							<option value="CANCELLED">CANCELLED</option>
							</select><br />

			<label class="input_label">&nbsp;</label>
			<input type="submit" class="button art-button" value="Save Record" id="save-record" />&nbsp;
			<input type="button" class="button art-button" value="Cancel" id="cancel-record" />
			

				
				<!--Items-------------------------------------------->
				<div style="position: relative; width: 400px; height: auto; padding: 5px; border: 2px solid #8d9dfb; margin: 10px 0 0 0">
					<div class="main-header"><strong>Cartridge Items</strong></div><br />
					<select class="input_select_big" name="items" id="items" size="5" style="width: 100%; font-size: 12px; margin: 0 0 3px 0">
					<select><br />
							<div id="stockbuttons">
								<input type="button" class="button art-button" value="New Item" id="new-item" />&nbsp;
								<input type="button" class="button art-button" value="Delete Item" id="del-item" />
							</div>
				
									<div id="items-form" style="position: relative; display: none; margin: 5px 0 0 0">
										<table style="width: auto; position: relative; height: auto; border: 0px">
										<tr>
										<td><strong>Stock Code</strong><br />
										<input type="text" class="input_field" size="10" id="stockcode" autocomplete="off"/>
											<div id="lookup-stock" style="display: none; position: absolute; top: 53px; left: 5px; z-index: 30">
												<select name="stockitems" class="input_select_big" id="stockitems" size="10" style="width: auto">
												</select>
											</div>
										<td>
										<td><strong>Stock Description</strong><br />
										<input type="text" id="description" readonly class="input_field" style="width: 183px" /><br />
										</td>
										</tr>
									</table>

									<table style="width: auto; position: relative; height: auto; border: 0px">
										<tr>
												<td><strong>Price</strong><br />
												<input type="text" id="price" size="10" class="input_field"/>&nbsp;<input type="button" class="button art-button" id="update-price" value="Chg" />
												</td>
								
												<td><strong>Qty</strong><br /><input type="text" id="qty" size="2" class="input_field" autocomplete="off"/>
												</td>
												<td><strong>Total Price</strong><br /><input type="text" id="total" class="input_field" style="text-align: right; width: 95px" readonly />
												<input type="button" class="button art-button" value="Add" id="add-item" />
										</td></tr>
										</table>
									</div>
									</form>		
					</div>
	</div>

</div></div></div></div>