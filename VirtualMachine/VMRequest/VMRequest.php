<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/VirtualMachine/request_application/request_app.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/datepick/Locale.en-US.DatePicker.js");
$doc->addScript("scripts/datepick/Picker.js");
$doc->addScript("scripts/datepick/Picker.Attach.js");
$doc->addScript("scripts/datepick/Picker.Date.js");
$doc->addStyleSheet("scripts/datepick/datepicker.css");
?>

<!--Define app name here-->
<form id="app-details">
    <input type="hidden" id="app-name" value="Virtual Machine Request Form" />
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
                Virtual Machine Request Form
            </h3>
        </div>

        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                <div class="main-div" id="md" style="display: none;">
                    <div class="main-header"><span id="frame-title" style="font-weight: bold">Staff Data</span></div>
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                    <div style="margin-left: 420px; display: block;"><input type="button" value="Request Virtual Machine" id="request-vm" class="button art-button"/></div>
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                    <div id="header-details" style="position: relative; display: block">
                        <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
                            <table width="100%" border="0" style="table-layout: fixed">
                                <tr>
                                    <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">DATE</th>
                                    <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">USERNAME</th>
                                    <th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">STAFFNAME</th>
                                    <th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">EXT</th>
                                    <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">OPERATOR</th>
                                    <th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">STATUS</th>
                                    <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"></th>
                                </tr>
                            </table>
                        </div>

                        <div id="ajax-requests" style="position: relative; width: auto; height: auto; display: none">
                            <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                        </div>

                        <div id="VM-form" style="position: relative; width: auto; height: 500px;  display: block; overflow: scroll"></div>
                    </div>

                </div>

                <div class="main-div" id="vm-request-div" style="display:block;">
                    <div class="main-header"><strong>VM Request Form</strong></div>
                    <div id="vm-data" style="position: relative; width: auto; height: auto; display: block;">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                        <form id="vm-request-form">
                            <input type="hidden" id="request-action" />
                            <br/>
                            <div class="main-div" id="vm-request-div">
                                <div class="main-header"><strong>Personal Data</strong></div>
                                <input type="hidden" id="admin" name="admin" value="<?php echo $user->username; ?>" />
                                <label class="input_label">Service Date:</label>
                                <input type="text" size="15" name="service_date" id="service_date" readonly class="input_field" />&nbsp;<img class='date_toggler1' src="/images/calendar.gif" width="16" height="16" border="0" style="vertical-align: middle" alt=""><br />

                                <label class="input_label">Personnel Number:</label>
                                <input type="text" size="15" name="p_num" id="p_num" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Username:</label>
                                <input type="text" size="15" name="u_name" id="u_name" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Name of Personnel:</label>
                                <input type="text" size="35" name="p_name" id="p_name" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Faculty/Dept:</label>
                                <input type="text" size="35" name="f_name" id="f_name" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Extention:</label>
                                <input type="text" size="15" name="exten" id="exten" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Motivate why is change required:</label><br />
                                <textarea cols="33" rows="5" name="motivation" id="motivation"> </textarea><br /><br />
                                <label class="input_label">Backup Req:</label>
                                <input type="text" size="35" name="back_req" id="back_req" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Retention Period:</label>
                                <input type="text" size="20" name="ret_period" id="ret_period" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Number of Copies:</label>
                                <input type="text" size="10" name="num_copies" id="num_copies" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                            </div>
                            <div class="main-div" id="vm-request-div">
                                <div class="main-header"><strong>VM Data</strong></div>
                                <label class="input_label">VM CPU:</label>
                                <input type="text" size="35" name="vm_cpu" id="vm_cpu" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">RAM:</label>
                                <input type="text" size="35" name="ram" id="ram" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Hard Disk:</label>
                                <input type="text" size="35" name="hard_disk" id="hard_disk" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">VM NIC:</label>
                                <input type="text" size="35" name="vm_nic" id="vm_nic" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Operating System:</label>
                                <input type="text" size="35" name="op_system" id="op_system" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Location:</label>
                                <input type="text" size="35" name="location" id="location" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Production/Test:</label>
                                <input type="text" size="35" name="prod_test" id="prod_test" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br /><br />
                            </div>
                            <div align="center"><br/>
                                <input type="submit" id="submit-vm" class="button art-button" value="Submit" />
                                <input type="button" value="cancel" id="cancel" class="button art-button"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

