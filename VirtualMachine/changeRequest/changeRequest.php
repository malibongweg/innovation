<?php
//header('Content-Type: text/plain; charset=ISO-8859-1');
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/VirtualMachine/changeRequest/changeRequest.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
?>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="VirtualMachine - Change Request Data" />
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
      Virtual Machine - Change Request Data</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
<!----------------------------------------------New product----------------------------------------------------------------------------------------------------------------->                
        <div id="head" style="display: block" >
             <div align="right"> <br />
                <input type="button" id="add-new" class="button art-button" value="Add New Change Request"  />
             </div> <br />
            <div align="left"> 
                <label><b>ID:</b></label>&nbsp;&nbsp;
                <input type="text" size="15" name="srch" id="srch" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/>&nbsp;&nbsp;
                <input type="button" id="get-details" class="button art-button" value="View Change Request" /> 
             </div>         
             </div><br />
<!----------------------------------------------Change Request Data----------------------------------------------------------------------------------------------------------------->
<div class="main-div" id="md">
<div class="main-header"><span id="frame-title" style="font-weight: bold">Change Data</span></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
		<div id="header-details" style="position: relative; display: block">
				<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
						<table width="100%" border="0" style="table-layout: fixed">
						<tr>
                                                        <th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">LOGID</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">DATE</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">REQUESTOR</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">OPERATOR</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">STATUS</th>
                                                        <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">EXTENSION</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">VM NAME</th>
                                                        <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
						</tr>
						</table>
				</div>
							<div id="ajax-change" style="position: relative; width: auto; height: auto; display: none">
								<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
							</div>

                      <div id="change-form" style="position: relative; width: auto; height: 500px;  display: block; overflow: scroll"></div>
	    </div>
</div>
<!----------------------------------------------Change Request Form----------------------------------------------------------------------------------------------------------------->
        <div class="main-div" id="change-forms">
                    <div class="main-header"><strong>Change Request Form</span></strong></div>
                    <!--form-->
                    <div id="changeData" style="position: relative; width: auto; height: auto; display: block">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>    
                        <form id="changeForm">
                            <input type="hidden" id="change-action" />   
                                <input type="hidden" size="35" name="logID" id="logID" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <input type="hidden" name="changeRequester" id="changeRequester" value="<?php echo $user->username; ?>" />
                                <input type="hidden" name="changeOperator" id="changeOperator" value="<?php echo $user->username; ?>" />
                                <label class="input_label">Personnel Number:</label>
                                    <input type="text" size="35" name="personnelNo" id="personnelNo" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Name of Personnel:</label>
                                    <input type="text" size="35" name="pName" id="pName" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Faculty/Dept:</label>
                                    <input type="text" size="35" name="fac_name" id="fac_name" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Extention:</label>
                                    <input type="text" size="35" name="extention" id="extention" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />    
                                <label class="input_label">VM Name:</label>
                                    <input type="text" size="35" name="vmName" id="vmName" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Motivate why is change required:</label><br />
                                    <textarea cols="33" rows="5" name="reason" id="reason" onKeyUp="javascript: this.value=this.value.toUpperCase();"> </textarea><br /><br />  
                              <div class="main-div" id="vmrequirements">
                                <div class="main-header"><span id="frame-title" style="font-weight: bold">VM Requirements</span></div>
                                    <label class="input_label">VCPU:</label>
                                    <input type="text" size="35" name="vcpu" id="vcpu" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Ram:</label>
                                    <input type="text" size="35" name="ram" id="ram" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Hard Disk:</label>
                                    <input type="text" size="35" name="hardDisk" id="hardDisk" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">VNIC:</label>
                                    <input type="text" size="35" name="vnic" id="vnic" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Location:</label>
                                   <input type="text" size="35" name="location" id="location" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Production/Test:</label>
                                    <input type="text" size="35" name="prod" id="prod" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Backup Requirement:</label><br />
                                    <input type="text" size="35" name="backup" id="backup" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/>
                                        <div style="clear: both"></div>
                                </div><br />
                        <div class="main-div" id="cards">
                        <div class="main-header"><strong> Recommendation Section </span></strong></div>
                        <div style="float: left; width: auto; height: auto"></div>
                                <label class="input_label">Comment:</label><br />
                                <textarea cols="33" rows="5" name="comment1" id="comment1"onKeyUp="javascript: this.value=this.value.toUpperCase();"> </textarea><br /><br /> 
                                <div class="main-div" id="approvals">
                        <div class="main-header"><span id="frame-title" style="font-weight: bold">Approvals:</span></div><br />
                                <label class="input_label">Approval:</label>
                                 <select id="approval" name="approval" id="approval" size="1" class="input_select" style="width: 200px;">
                                     <!--<option value="Select Option">Select Option</option>-->
                                     <option value="Approved">Approved</option>
                                     <option value="Rejected">Rejected</option>                                                                
                                 </select><br/>
                                <label class="input_label">Comment:</label><br />
                                <textarea cols="33" rows="5" name="comment" id="comment"onKeyUp="javascript: this.value=this.value.toUpperCase();"> </textarea><br /><br />
                                
                                
                                
                        <div style="clear: both"></div>   
                        </div> 
                        </div><br />
                                    <div id="boss_rec" align="center">
                                        <input type="button" id="aprove-record" class="button art-button" value="Submit Record" />
                                     </div>
                                    <div id="add_rec" style="display: none"align="center"><input type="submit" id="submit-record" class="button art-button" value="Submit" /></div>&nbsp;
                                         <div id="edit_rec" style="display: none" align="center"><input type="button" value="Update Change" id="change-edit" class="button art-button"/>
                                        
                                        </div><br />
                                     <div align="center">
                                        <input type="button" value="Close" id="cancel" class="button art-button"/>
                                     </div>    
                        </form>
                            <!--End form-->
                    </div>                                        
                </div>          
</div> </div></div></div>
