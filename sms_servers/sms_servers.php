<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/sms_servers/sms_servers.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/datepick/Locale.en-US.DatePicker.js");
$doc->addScript("scripts/datepick/Picker.js");
$doc->addScript("scripts/datepick/Picker.Attach.js");
$doc->addScript("scripts/datepick/Picker.Date.js");
$doc->addStyleSheet("scripts/datepick/datepicker.css");
?>

<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />

<!--Define app name here-->
<form id="app-details">
    <input type="hidden" id="app-name" value="SMS Servers" />
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
                SMS Servers
            </h3>
        </div>
              
        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">                
                <div class="main-div" id="md" style="display: block;">
                    <div class="main-header"><span id="frame-title" style="font-weight: bold">SMS Servers</span></div>
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>                    
                    <div style="margin-left: 420px; display: block;"><input type="button" value="Add SMS Server" id="add-server" class="button art-button"/></div>
                    <label><b>ID:</b></label>
                    <input type="text" size="15" name="server_id" id="server-id" class="input_field"/> <input type="button" id="get-id" class="button art-button" value="View SMS Server" />
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                    <div id="header-details" style="position: relative; display: block">                        
                        <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
                            <table width="100%" border="0" style="table-layout: fixed">
                                <tr>
                                    <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">ID</th>
                                    <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">IP ADDRESS</th>
                                    <th style="width: 40%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">SERVER NAME</th>                                    
                                    <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">COST CENTRE</th>
                                    <th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">STATUS</th>
                                    <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"></th>
                                </tr>
                            </table>
                        </div>
                        
                        <div id="ajax-requests" style="position: relative; width: auto; height: auto; display: none">
                            <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                        </div>

                          <div id="sms-info" style="position: relative; width: auto; height: 500px;  display: block; overflow: scroll"></div>
                    </div>

                </div>

                <div class="main-div" id="add-server-div" style="display:none;">                      
                    <div class="main-header"><strong>Add SMS Server</strong></div>                    
                    <div id="sms-data" style="position: relative; width: auto; height: auto; display: block;">
                        
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>    
                        <form id="add-server-form">
                            <input type="hidden" id="server-action" />  
                            <br/>                                                                                            
                                <div id="serverid" style="display: none;">
                                    <label class="input_label">ID:</label>                                    
                                    <input type="text" size="7" name="server_id" id="server_id" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();" style="readonly:readonly; background-color: #ff6666;"/>
                                </div>
                                <label class="input_label">IP Address:</label>
                                <input type="text" size="20" name="ip_address" id="ip_address" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Server Name:</label>
                                <input type="text" size="50" name="server_name" id="server_name" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Cost Centre:</label>
                                    <input type="text" size="7" name="cost_centre" id="cost_centre" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Status:</label>
                                 <select id="status" name="status" id="status" size="1" class="input_select" style="width: 50px;">
                                     <option value="Y">Y</option>                                                                        
                                     <option value="N">N</option>                                                                        
                                 </select><br/>
                                 <div align="center" id="buts" style="display: none;">
                                <input type="submit" id="submit-server" class="button art-button" value="Submit" />
                                <input type="button" value="Cancel" id="cancel" class="button art-button"/><br/>                                
                             </div>
                             
                             <div align="center" id="update_buts" style="display: none;">
                                <input type="button" id="update-server" class="button art-button" value="Update Server" />                               
                                <input type="button" value="Cancel" id="update-cancel" class="button art-button"/>
                                <input type="button" id="delete-server" class="button art-button" value="Delete Server" /><br/>                                   
                             </div>
                        </form>                                                                              
                    </div>                                                            
                </div>                                        
            </div>
        </div>
    </div>
</div>

