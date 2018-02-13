<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/sms/sms.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
?>
<form id="user_id">
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="username" value="<?php echo $user->username; ?>" />
</form>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="SMS User Credit" />
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
      SMS User Credit</h3>
        </div>
            <div class="art-blockcontent" style="background-color: #EEEEEE">
            <div class="art-blockcontent-body" style="background-color: #EEEEEE">


<div id="div-standard" class="main-div">
	<div class="main-header"><strong><span id="del-heading">Enter User Name</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

<div style="position: relative; padding: 3px 0 0 3px; width: auto; height: auto; border: 0px solid #C0C6BA; background-color: #EEEEEE; -moz-border-radius: 5px; border-radius: 5px">
<form>
	<input type="text" size="25" name="srch" id="srch" maxlength="50" class="input_field" />
	<input type="button" id="getUser" value="Get User Details" class="button art-button">
	<div id="list-users" style="width: 100px; height: auto; display: none">
		<select name="userList" id="userList" size="10" class="input_select_big" style="width: 330px">
		</select>
	</div>
</form>
		<div id="ajax-loader" style="width: auto; height: auto; display: none">
		<img src="images/kit-ajax.gif" width="16" height="11" alt="" style="vertical-slign: middle" />&nbsp;Getting user details...Please wait.
		</div>


<!----credit div -->
<div id="credit-div" style="display: none">
<form name="sms_credit_form" id="sms-credit-form" action="index.php?option=com_jumi&fileid=20&func=smsCredit&cuser=<?php echo $user->username; ?>" >
<input type="hidden" name="luid" id="luid" value="">
<input type="hidden" name="xusername" id="xusername" value="">
<table border="0" width="100%">
<tr>
<td width="100%"><strong>User Name<strong><br /><input type="text" name="uname" id="uname" size="50" readonly class="input_field" /></td>
</tr><tr>
<td width="100%"><strong>Email</strong><br /><input type="text" name="email" id="email" size="50" readonly class="input_field" /></td>
</tr>
<tr>
<td width="100%"><strong>Reference#</strong><br /><input type="text" name="ref" id="ref" size="50" onkeyup="this.value=this.value.toUpperCase();" class="input_field" /></td>
</tr>
<tr>
<td width="100%"><strong>Amount</strong><br />
<select name="credit_amount" id="credit-amount" size="1" class="input_select" style="width: 100px">
<option value="10">R10.00</option>
<option value="20">R20.00</option>
<option value="50">R50.00</option>
<option value="100">R100.00</option>
<option value="200">R200.00</option>
</select>
</td>
</tr>
<tr>
<td width="100%"><strong>Cost Code:</strong><br />
<select name="costcode" id="cost-code" size="1" class="input_select" style="width: 418px">
<?php
$dbo->setQuery("select distinct budgets.costcodes.detail_cc, budgets.costcodes.cc_name from budgets.costcodes where budgets.costcodes.active = 'Y' order by budgets.costcodes.detail_cc");
$result = $dbo->loadObjectList();
foreach($result as $row){
	echo "<option value='".$row->detail_cc."'>".$row->detail_cc." [".$row->cc_name."]</option>\n";
}
?>
</select>
</td>
</tr>
<tr>
<td width="100%"><strong>Account Code</strong><br />
<select name="accountcode" id="account-code" size="1" class="input_select" style="width: 418px">
<?php
$dbo->setQuery("select distinct budgets.account_codes.fcdacc, budgets.account_codes.fcdname1 from budgets.account_codes where budgets.account_codes.for_year = ".date('Y')." and cast(budgets.account_codes.fcdacc as unsigned) between 40000 and 49999 order by budgets.account_codes.fcdacc");
$result = $dbo->loadObjectList();
foreach($result as $row){
	echo "<option value='".$row->fcdacc."'>".$row->fcdacc." [".$row->fcdname1."]</option>\n";
}
?>
</select>
</td>
</tr>
<tr>
<td width="100%"><strong>Credit Type</strong><br />
<select name="credit_type" id="credit-type" size="1" class="input_select" style="width: 418px">
<?php
	$dbo->setQuery("select id,description from #__sms_credit_type order by id");
	$result = $dbo->loadObjectList();
	foreach($result as $item) {
	echo '<option value="'.$item->id.'">'.$item->description.'</option>';
	} 
?>
</select>
</td>
</tr>
<tr>
<td width="100%"><input type="submit" class="button art-button" value="Credit User">
<input type="button" class="button art-button" value="Cancel" id="credit-cancel" />
</td>
</tr>
</table>
</form>
</div>

</div>
</div>


</div></div></div></div>