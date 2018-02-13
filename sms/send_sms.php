<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addStyleSheet("scripts/formcheck/theme/classic/formcheck.css");
$doc->addScript("scripts/sms/send_sms.js");
$doc->addScript("scripts/formcheck/formcheck.js");
$doc->addScript("scripts/formcheck/lang/en.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");

$dbo->setQuery("call proc_pop_application('SMS Gateway')");
$dbo->query();

$dbo->setQuery("CALL proc_check_sms_address_book(".$user->id.")");
$dbo->query();

$dbo->setQuery("select sms_charge from #__sms_settings where id = 1");
$result = $dbo->loadRow();
$sys_charge = $result[0];
$dbo->setQuery("select current_balance,enabled from #__sms_accounts where username = '".$user->username."'");
$result = $dbo->loadRow();
$ubalance = $result[0];
$uenabled = $result[1];
?>
<form id="user_id">
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="username" value="<?php echo $user->username; ?>" />
<input type="hidden" id="sysstat" value="0" />
</form>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="SMS Gateway" />
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
      SMS Gateway - Current SMS Charge: R<?php echo number_format($sys_charge,2); ?> [Your current balance is R<span id="balance"><?php echo number_format($ubalance,2); ?></span>]</h3>
        </div>
            <div class="art-blockcontent">
            <div class="art-blockcontent-body">
<!--Error messages,status,account status etc...-->
<div id = "sms-status" style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; padding: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-top: 3px; display: none">
<span id="err-msg" style="color: red; font-weight: bold"></span>
</div>


<!--Navigation-->
<div class="main-div">
	<div class="main-header"><strong>Electronic Communications and Transactions(ECT) Act requires all SMS's to be logged.</strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<div style="position: relative; padding: 0 0 0 0">
<form name="nav_form" id="nav-form">
<table border="0" width="100%">
<tr>
<td width="100%" style="text-align: left">
<select name="nav" size="1" id="sms-nav" class="input_select" style="width: 200px">
<option value="1" selected>Send Single SMS</option>
<?php
if (isset($_FILES['cell_file']['name'])) 
	echo	 '<option value="2" selected>Send Bulk SMS</option>';
	else echo  '<option value="2">Send Bulk SMS</option>';
?>
<option value="3">Address Book</option>
<option value="4">Delivery Reports</option>
<option value="5">Standard Message Types</option>
</select>
</td>
</tr>
</table>
</form>
</div>
</div>





<!--SINGLE SMS -->
<div id="sms-single" class="main-div" style="padding: 10px 10px 0 16px">
	<div class="main-header"><strong>Send Single SMS</strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<form name="single_form" id="single-form" method="post">
<input type="hidden"name="single_user" value="<?php echo $user->username; ?>" />
<input type="hidden"name="action" value="send_single" />

<div id="sms-single" class="field_content" >
	<div class="field_label"><strong>Cellular#</strong>&nbsp;<img src="images/cell.png" width="8" height="13" border="0" alt="" style="vertical-align: middle" ></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<input type="text" name="single_number" id="single-number" size="10" maxlength="10" class="numeric" />
</div>

<div id="sms-single" class="field_content" >
	<div class="field_label"><strong>Address Book</strong>&nbsp;<img src="images/add-book.png" width="12" height="13" border="0" alt="" style="vertical-align: middle" ></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<select name="address_book" id="address-book" size="1" class="input_select" style="width: 200px">
<?php
	$dbo->setQuery("select id,book_name from #__sms_address_books where userid=".$user->id." order by id");
	$result = $dbo->loadObjectList();
	foreach ($result as $item) {
		echo '<option value="'.$item->id.'">'.$item->book_name.'</option>';
	}
?>
</select>
</div>

<div id="sms-single" class="field_content" >
	<div class="field_label"><strong>Addres Book Entries</strong>&nbsp;<img src="images/add-book.png" width="12" height="13" border="0" alt="" style="vertical-align: middle" ></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<select name="address_book_entries" id="address-book-entries" size="1" class="input_select" style="width: 200px">
</select>
</div>

<div id="sms-single" class="field_content" >
	<div class="field_label"><strong><span id="single-chars-left">Message (160 characters max)</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<textarea style="width: 100%" name="single_message" id="single-message" rows="5" class="input_textarea" ></textarea><br />
	<input type="button" id="send-single" class="button art-button" value="&nbsp;&nbsp;&nbsp;Send SMS&nbsp;&nbsp;&nbsp;" />
</select>
</div>

<div id="show-sending" style="position: relative; width: auto; height: auto; display: none">
<img src="images/kit-ajax.gif" width="16" height="11" alt="" style="vertical-align" />Sending SMS...
</div>
</form>
</div>
<!--          END OF SINGLE SMS -->

<!--BULK SMS -->
<div id="sms-bulk" class="main-div" style="padding: 10px 10px 0 16px; display: none">
	<div class="main-header"><strong>Send Bulk SMS</strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<form name="bulk_form" id="bulk-form" method="post">
<input type="hidden"name="bulk_user" value="<?php echo $user->username; ?>" />
<input type="hidden"name="action" value="send_bulk" />
<table border="0" width="100%">
<tr>
<td width="50%" style="text-align: left">
<select name="address_book_bulk" id="address-book-bulk" size="1" class="input_select" style="width: 90%">
<option value="-1">Select Address Book</option>
<?php
	$dbo->setQuery("select id,book_name from #__sms_address_books where userid=".$user->id." order by id");
	$result = $dbo->loadObjectList();
	foreach ($result as $item) {
		echo '<option value="'.$item->id.'">'.$item->book_name.'</option>';
	}
?>
</select>
</td>
<td width="50%" style="text-align: left">
<select name="pick_message" id="pick-message" size="1" class="input_select" style="width: 90%">
<option value="-1">Defined Messages</option>
</select></td></tr>
<tr>
<td width="50%" style="text-align: left">
<textarea name="bulk_numbers" id="bulk-numbers" style="width: 90%" rows="10" readonly class="input_textarea"></textarea>
</td>
<td width="50%" style="text-align: left">
<textarea style="width: 90%" name="bulk_message" id="bulk-message" rows="10" class="input_textarea"></textarea>
</td></tr>
<tr><td width="50%" style="text-align: left">&nbsp;</td>
<td width="50%" style="text-align: left">
<span id="bulk-chars-left">Message (160 characters max)</span><span id="sms-busy" style="display: none"><img src="/images/kit-ajax.gif" width="24" height="16" alt="" style="vertical-align: middle" /> Sending SMS(s)</span><br />
</td></tr>
</table>
<div id="send-bulk-buttons" style="width: 100%; display: block">
<input type="button" class="button art-button" value="Choose File" id="loadBulkCell" onclick="loadCellFromFile();" />&nbsp;
<input type="button" class="button art-bttuon" value="Clear List" id="clearBulkCell" onclick="$('bulk-numbers').set('value','');" />&nbsp;
<input type="button" class="button art-button" value="Send Bulk SMS" id="send-bulk" />&nbsp;
</div>
</form>

<form name="upload_bulk_form" id="upload-bulk-form" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div id="loadBulkButton" style="width: 100%; display: none">
Choose file: <input type="file" name="cell_file" id="cell_file" />

<div id="frm1Submit" style="display: none; width: 100%"><input type="submit" value="Process File" id="submitFile"  /></div>
</div>
</form>

</div>
<?php
	if (isset($_FILES['cell_file'])) {
		$fc = '';
		echo '<script type="text/javascript">';
		echo "$('bulk-numbers').set('value','');";
		$fp = fopen($_FILES['cell_file']['tmp_name'],'r');
		echo "var fn = ''; ";
		while (!feof($fp)) {
			$fc = trim(fgets($fp));
			?>
			 fn = fn + '<?php echo trim($fc); ?>'+'\n';
		<?php
		}
		echo "$('bulk-numbers').set('value',fn);";
		echo '</script>';
}
?>
<!--          END OF BULK SMS -->


<!--ADDRESS BOOK -->

<div id="sms-address" class="main-div" style="padding: 10px 10px 0 16px; display: none">
	<div class="main-header"><strong>SMS Address Book</strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

<!--Display address book-->
<div id = "add1" style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; padding: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-top: 3px; display: block; overflow: hidden">

<div style="float: left; width: 200px; margin: 0 5px 0 0">
<select name="address_book_book" id="address-book-book" size="1" class="input_select" style="width: 100%">
<?php
	$dbo->setQuery("select id,book_name from #__sms_address_books where userid=".$user->id." order by id");
	$result = $dbo->loadObjectList();
	foreach ($result as $item) {
		echo '<option value="'.$item->id.'">'.$item->book_name.'</option>';
	}
?>
</select>
<br />
<input type="button" class="button art-button" value="New Address Book" id="add-new-group" style="width: 200px; text-align: center" /><br />
<input type="button" class="button art-button" value="Delete Selected Book" id="del-add-group" style="width: 200px; text-align: center" /><br />
<input type="button" class="button art-button" value="New Address Entry" id="new-entry" style="width: 200px; text-align: center" /><br />
<input type="button" class="button art-button" value="Delete Address Entry" id="delete-entry" style="width: 200px; text-align: center" /><br />
Please exit application to refresh address books after new entries.
</div>
<div style="float: left; width: 250px">
<select name="add_entries"  id="add-entries"  size="15" class="input_select_big" >
</select>
</div>
</div>
</div>
<!--end of add display-->

<!---Add new entry -->
<div id = "add-new-entry" style="position: relative; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; padding: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-top: 3px; display: none">

</div>
<!--end of new entry-->
<!--          END OF ADDRESS BOOK -->

<!--DELIVERY REPORTS -->
<div id="div-delivery" class="main-div" style="padding: 10px 10px 0 16px; display: none">
	<div class="main-header"><strong><span id="del-heading">SMS Delivery Reports (Current Year only.)</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<form name="delivery_form" id="delivery-form" method="post">
<input type="hidden" name="delivery_user" id="delivery-user" value="<?php echo $user->username; ?>" />
<table border="0" width="100%">
<tr>
<td width="20%" style="text-align: left" valign="top">
<select name="del_month" id="del-month" class="input_select" size="1" >
<option value="-1">Select Month</option>
<?php
	for ($i=1; $i<13; ++$i) {
		echo "<option value='".$i."'>".$i."</option>\n";
	}
?>
</select>
</td>
<td width="80%" valign="top">
<select name="delivery_entries" id="delivery-entries" size="15" class="input_select_big" style="width: 100%">
</select>
</td>
</tr>
</table>
</form>
</div>
<!--END OF DELIVERY REPORTS -->

<!--STANDARD MESSAGES -->
<div id="div-standard" class="main-div" style="padding: 10px 10px 0 16px; display: none">
	<div class="main-header"><strong><span id="del-heading">SMS standard message types.</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
			<div style="position: relative; overflow: hidden">
					<form name="messages_form" id="messages-form" method="post">
						<div style="float: left; width: 210px">
							<select name="msg_type" id="msg-type" size="1" class="input_select" style="width: 200px" >
							</select><br />
							<input type="button" class="button" value="New Type" style="width: 200px" id="new-msg-type" /><br />
							<input type="button" class="button" value="Delete Selected Type" style="width: 200px" id="del-msg-type" /><br />
						</div>
				
								<div style="float: left; width: 50%">
								<textarea name="std_message" id="std-message" style="width: 100%" rows="10" class="input_textarea"></textarea><br />
									<span id="std-chars-left"><strong>Message (160 characters max)</strong></span><br />
									<input type="button" class="button art-button" value="Save"  id="save-msg-type" style="display: none" />&nbsp;
									<input type="button" class="button art-button" value="Cancel"  id="cancel-msg-type" style="display:none" />
								</div>
					</form>
			</div>
</div>



</div></div></div></div>