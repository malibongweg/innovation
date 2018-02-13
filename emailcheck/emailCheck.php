<?php
//header('Content-Type: text/plain; charset=ISO-8859-1');
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/emailcheck/emailCheck.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");

?>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Hotbox - Staff Details Check" />
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
      Hotbox - Staff Details Check</h3>
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
	<input type="radio" value="S" name="srch_cond" />Find by Surname&nbsp;&nbsp;
	<input type="submit" id="get-details" class="button art-button" value="Filter" />        
	</div>
	</form>  
        &nbsp;&nbsp; Show Active Staff Only: <input type="checkbox" id="myCheck">  
	<div style="clear: both"></div>
    
</div>
<!----------------------------------------------Query Data----------------------------------------------------------------------------------------------------------------->
<div class="main-div" id="md">
<div class="main-header"><span id="frame-title" style="font-weight: bold">Staff Query</span></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	
		<div id="header-details" style="position: relative; display: block">
				<div style="position: relative; width: auto; height: 500; margin: 0 16px 0 0">
						<table width="100%" border="0" style="table-layout: fixed">
						<tr>
							<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">STAFF#</th>
							<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">FULLNAME</th>
<!--							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">FIRSTNAME</th>-->
							<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">INITIALS</th>
                                                        <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">RESIGNATION</th>
						</tr>
						</table>
				</div>
							<div id="ajax-staff" style="position: relative; width: auto; height: 250px;  display: block; overflow: scroll">
								<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
							</div>

                      <div id="staff-query" style="position: relative; width: auto; height: 250px;  display: block; overflow: scroll"></div>
    	    </div>

</div>
<!--------------------------------------------Staff AD INFO----------------------------------------------------------------------------------------------------------------->
        <div class="main-div" id="staff-ad">
                    <div class="main-header"><strong>Staff AD Info</span></strong></div>
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	
		<div id="header-details" style="position: relative; display: block">
				<div style="position: relative; width: auto; height: 500; margin: 0 16px 0 0">
						<table width="100%" border="0" style="table-layout: fixed">
						<tr>
							<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">STAFF#</th>
							<th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">FULLNAME</th>
<!--							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">FIRSTNAME</th>-->
							<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">INITIALS</th>
                                                        <th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">TEL#</th>
                                                        <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">EMAIL</th>
                                                        <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
						</tr>
						</table>
				</div>
							<div id="ajax-adstaff" style="position: relative; width: auto; height: 250px;  display: block; overflow: scroll">
								<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
							</div>

                      <div id="staff-adinfo" style="position: relative; width: auto; height: 250px;  display: block; overflow: scroll"></div>
    	    </div>              
 </div>    
<!----------------------------------------------Staff Data Form----------------------------------------------------------------------------------------------------------------->
        <div class="main-div" id="staff-check">
                    <div class="main-header"><strong>Staff Details Check</span></strong></div>
                    <!--form-->
                    <div id="staffData" style="position: relative; width: auto; height: auto; display: block">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>    
                        <form id="staffForm">
                            <input type="hidden" id="staff-action" />
                             
                                <label class="input_label">Staff Number:</label>
                                    <input type="text" size="20" name="staff_no" id="staffNo" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Full Name:</label>
                                    <input type="text" size="35" name="f_name" id="fName" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Telephone#:</label>
                                    <input type="text" size="20" name="s_ext" id="sExt" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Email:</label>
                                    <input type="text" size="35" name="s_email" id="sEmail" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Department:</label>
                                    <input type="text" size="35" name="s_dept" id="sDept" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                    <label class="input_label">Cost Code:</label>
                                    <input type="text" size="10" name="s_code" id="sCode" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Campus:</label>
                                    <input type="text" size="20" name="s_campus" id="sCampus" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                       <br/><br/>
                                     <div align="center">
                                        <input type="button" value="Close" id="staff-cancel" class="button art-button"/>
                                     </div>    
                        </form>
                            <!--End form-->
                    </div>                                        
                </div>
</div> </div></div></div>
