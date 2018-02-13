<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/ServiceSystem/service/service.js");
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
    <input type="hidden" id="app-name" value="Hotbox" />
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
            <h3 class="t">
                <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
                <a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
                <a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
                UPS Service Schedule
            </h3>
        </div>
        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                
              <!-- BEGIN LOG --> 
              <div class="main-div" id="md" style="display: block;">
                    <div class="main-header"><span id="frame-title" style="font-weight: bold">Service Schedule Log</span></div>                    
                    <div style="margin-left: 450px;">
                        <input type="button" value="Add Schedule Service" name="schedule" id="ups-schedule" class="button art-button"/><br/><br/>
<!--                       <input type="button" value="Send Swannie Email" name="emailUPS" id="emailUPS" class="button art-button"/>                    -->
                    </div><br/>
                    <div id="header-details">
                        <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
                            <table width="100%" border="0" style="table-layout: fixed">
                                <tr>                                        
                                        <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">Job#</th>
                                        <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">DATE</th>
                                        <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">TEL#</th>
                                        <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">COMPANY</th>
                                        <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">PRODUCT</th>                                        
                                        <th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">STATUS</th>                                        
                                        <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
                                </tr>
                            </table>
                        </div>
                        <div id="ajax-service" style="position: relative; width: auto; height: auto; display: none">
                            <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                        </div>
                        <div id="service-form" style="position: relative; width: auto; height: 500px;  display: block; overflow: scroll"></div>                          
                    </div>
                </div>
              <!-- END LOG --> 
              <!-- BEGIN FORM -->
              <div class="art-block-body" id="md1" style="display: none;">
                    <div class="art-blockcontent" >
                        <div class="art-blockcontent-body">
                            <div class="main-div">
                                <div id="main-header-title" class="main-header"><span id="frame-title" style="font-weight: bold">Schedule Service</span></div>
                                <br/>
                            <form id="schedule-form">
                                <input type="hidden" id="service-action" />
                    <input type="hidden" name="servOperator" id="servOperator" value="<?php echo $user->username; ?>" />
                                <label class="input_label">Service Date:</label>
                                <input type="text" size="15" name="service_date" id="service_date" readonly class="input_field" />&nbsp;<img class='date_toggler1' src="/images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt=""><br />
                                
                                <label class="input_label">Company:</label>
                                <select name="company" id="company" size="1" class="input_select" style="width: 200px;" />
                                    <?php
                                    $dbo->setQuery("select distinct companyid,companyname,contactnumber,email from ups_register.company order by companyname");
                                    $result = $dbo->loadObjectList();
                                    foreach($result as $row){
                                        echo "<option value='".$row->companyid."'>".$row->companyname."</option>";
                                    }
                                    ?>
                                </select><br/>
                                 <label class="input_label">Product:</label>
                                 <select name="product" id="product" size="1" class="input_select" style="width: 200px;" />
                                    <?php
                                    $dbo->setQuery("select distinct productid,unittype,model,serialno,address from ups_register.product order by unittype");
                                    $result = $dbo->loadObjectList();
                                    foreach($result as $row){
                                        echo "<option value='".$row->productid."'>".$row->unittype."</option>";
                                    }
                                    ?>
                                </select><br/>                                 
                                <label class="input_label">Contact#:</label>
                                <input type="text" name="contactno" size="15" id="contact-no"  class="input_field"/><br/>
                                 
                                 <label class="input_label">Status:</label>
                                 <select id="status" name="status" id="status" size="1" class="input_select" style="width: 200px;">
                                     <option value="Done">Done</option>
                                     <option value="Scheduled">Scheduled</option>
                                     <option value="Reschduled">Reschduled</option>
                                     <option value="Cancelled">Cancelled</option>                                    
                                 </select><br/>
                                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                                    
                                    <div style="margin-left: 200px;">
                                        <div id="schedule" style="display: inline;"><input type="submit" id="schedule-service" value="Schedule Service" class="button art-button"/></div>
                                        <div id="update" style="display: inline;"><input type="button" name="update_service" id="update-service" value="Update Service" class="button art-button"/></div>
                                        <div id="cancel" style="display: inline;"><input type="button" name="cancel_service" id="cancel-service" value="Cancel" class="button art-button"/></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
              <!-- END FORM --> 
            </div>
      </div>
    </div>
</div>