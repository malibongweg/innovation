<?php
//header('Content-Type: text/plain; charset=ISO-8859-1');
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/hotbox/staff/staff.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");

?>
<!--Define app name here-->
<!--What does Wandisa mean in Chinese-->
<form id="app-details">
<input type="hidden" id="app-name" value="Hotbox - Staff Data" />
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
            <div class="art-block-body" id="bh">

            <div class="art-blockheader">
            <div class="l"></div>
            <div class="r"></div>
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Hotbox - Staff Data</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                
<!----------------------------------------------Search Staff Data----------------------------------------------------------------------------------------------------------->                
                
<div class="main-div" id="cards" style="display: block">
	<div class="main-header"><strong> Filter Selection </span></strong></div>
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

	<form id="srch-details-form">
	<div style="float: left; width: auto; height: auto">
	<input type="text" size="9" maxlength="20" id="srch"  name="srch" />&nbsp;
	<input type="radio" value="N" name="srch_cond" checked />Find by Staff#&nbsp;&nbsp;
	<input type="radio" value="S" name="srch_cond" />Find by Username&nbsp;&nbsp;
        <input type="radio" value="C" name="srch_cond" />Find by Card#&nbsp;&nbsp;
	<input type="submit" id="get-details" class="button art-button" value="Filter" />        
	</div>
	</form> 
        
        <input type="button" id="load-data" class="button art-button" value="Load ITS Data" />
         
	<div style="clear: both"></div>
    
</div>
<!----------------------------------------------Staff Data----------------------------------------------------------------------------------------------------------------->
<div class="main-div" id="md">
<div class="main-header"><span id="frame-title" style="font-weight: bold">Staff Data</span></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	
		<div id="header-details" style="position: relative; display: block">
				<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
						<table width="100%" border="0" style="table-layout: fixed">
						<tr>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">STAFF NUMBER</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">FULLNAME</th>
							<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">USERNAME</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">CARD NUMBER</th>
                                                        <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
						</tr>
						</table>
				</div>
							<div id="ajax-staff" style="position: relative; width: auto; height: auto; display: none">
								<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
							</div>

                      <div id="staff-form" style="position: relative; width: auto; height: 500px;  display: block; overflow: scroll"></div>
	    </div>

</div>
<!----------------------------------------------Staff Data Form----------------------------------------------------------------------------------------------------------------->
        <div class="main-div" id="staff-forms">
                    <div class="main-header"><strong>Update Staff Data</span></strong></div>
                    <!--form-->
                    <div id="staffData" style="position: relative; width: auto; height: auto; display: block">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>    
                        <form id="staffForm">
                            <input type="hidden" id="staff-action" />
                             
                                <label class="input_label">Staff Number:</label>
                                    <input type="text" size="20" name="staff_no" id="staffNo" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Title:</label>
                                    <input type="text" size="10" name="s_title" id="sTitle" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Full Name:</label>
                                    <input type="text" size="35" name="f_name" id="fName" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">UserName:</label>
                                    <input type="text" size="35" name="u_name" id="uName" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Email:</label>
                                    <input type="text" size="35" name="s_email" id="sEmail" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">AKA:</label>
                                    <input type="text" size="20" name="s_aka" id="sAKA" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Faculty:</label>
                                    <input type="text" size="35" name="s_fac" id="sFac" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Department:</label>
                                    <input type="text" size="35" name="s_dept" id="sDept" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Campus:</label>
                                    <input type="text" size="20" name="s_campus" id="sCampus" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Extension:</label>
                                    <input type="text" size="20" name="s_ext" id="sExt" onKeyUp="javascript: this.value=this.value.toUpperCase();"/>
                                    <input type="button" value="Update Ext#" id="upt-ext" class="button art-button"/>
                                    <br />
                                <label class="input_label">Card Number:</label>
                                    <input type="text" size="10" name="cardno" id="cardNo" /><!--class="input_field" <!--maxlength="4"--> 
                                
                                    <input type="submit" value="Update Card#" id="staff-save" class="button art-button"/>&nbsp;
                                     <br/><br/>
                                     <div align="center">
                                        <input type="button" value="Cancel" id="staff-cancel-update" class="button art-button"/>
                                        <input type="button" id="delete-record" class="button art-button" value="Delete Record" />
                                     </div>    
                        </form>
                            <!--End form-->
                    </div>                                        
                </div>
<!----------------------------------------------End of Staff Data Form---------------------------------------------------------------------------------------------------------->
                
</div> </div></div></div>
