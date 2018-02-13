<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
//$doc->addScript("scripts/security/itspin.js");
$doc->addCustomTag("<meta http-equiv='X-UA-Compatible' content='IE=8' />");
$doc->addScript("scripts/account_creation/adaccount.js");
$doc->addScript("scripts/json.js");
$doc->addScript('scripts/fav_apps.js');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$dbo->setQuery("call proc_pop_application('User Maintenance')");
$dbo->query();
?>

<input type="hidden" id="uname" name="user_name" value="<?php echo $user->username; ?>" />

<!--Define app name here-->
<form id="app-details">
    <input type="hidden" id="app-name" value="User Management" />
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
                 AD Account Creation</h3>
        </div>
        <div class="art-blockcontent">
            <div class="art-blockcontent-body">

                <div id="staff-tabs" style="position: relative; width: auto; height: auto;  display: block; overflow: none; margin: 1px 0 0 0">
                    <input type="button" value="Staff Data" name='staff_data' id="staff-data" class="button art-button"/>
                    <input type="button" value="Non Staff Data" name='none_staff_data' id="none-staff-data" class="button art-button"/>
                    <input type="button" value="Capture Staff Exclusions" name='staff_exclusions' id="staff-exclusions" class="button art-button"/>
                </div>

                <div style="width: auto; height: auto; display: none; margin-bottom: 5px" id="staff-tab">
                    <!--div style="position: relative; padding: 3px 0 0 5px; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; -moz-border-radius: 5px; border-radius: 5px"-->
                    <fieldset class="input_fieldset">
                            Staff Number<br />
                            <input type="text" size="20" name="srch" id="srch" maxlength="9"  class="numeric" />
                            <input type="button" id="getUser" class="button art-button" value="Get User Details">
                            <div id="list-users" style="width: 100px; height: auto; display: none">
                                    <select name="userList" id="userList" size="10" class="input_select" style="width: 300px">
                                    </select>
                            </div>
                    </fieldset>
                </div>

                <div style="width: auto; height: auto; display: none; margin-bottom: 5px" id="exclusion-tab">
                    <fieldset class="input_fieldset">
                        Staff Number<br />
                        <input type="text" size="20" name="srch-ex" id="srch-ex" maxlength="9"  class="numeric" />
                        <input type="button" id="capture-ex" class="button art-button" value="Add User Details">
                    </fieldset>
                </div>

                <div id="ajax-loader" style="width: auto; height: auto; display: none">
                    <img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Searching...
                </div>

                <div id="user-details" class="main-div" style="display: none">
                        <div class="main-header"><strong><span id="del-heading">User Details</span></strong></div>
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                <table border="0" width="100%">
                <tr>
                <td width="100%"><strong>Staff Number</strong><br /><input type="text" name="uid" id="uid" size="30" readonly class="input_field" /></td>
                </tr><tr>
                <td width="100%"><strong>Display Name</strong><br /><input type="text" name="name" id="name" size="50" readonly class="input_field"/></td>
                </tr><tr>
                <td width="100%"><strong>Username</strong><br /><input type="text" name="username" id="username" size="50" readonly class="input_field"/></td>
                </tr><tr>
                <td width="100%"><strong>Email</strong><br /><input type="text" name="email" id="email" size="50" readonly class="input_field"/></td>
                </tr>
                <tr>
                    <td width="100%"><strong>Enter Requestor Staff# Here...</strong><br /><input type="text" name="reqstaff" id="req-staff" size="30" class="numeric"/>
                    <input type="button" id="show-email" class="button" value="Show Requestor Email" /></td>
                      
                </tr>
                <tr>
                <td width="100%"><strong>Requestor Email</strong><br /><input type="text" name="reqemail" id="req-email" size="50" readonly class="input_field"/></td>
                </tr>
                </table>

                <span style="margin-top: 3px">&nbsp;</span>
                
                <div style="align: middle">
                    <input type="button" id="getpin" class="button" value="Sent ADS Password" />
                     <input type="button" name="finish" class="button" id="finish-but" value="Reset"/>
                     </div>
            </div>
        </div>
    </div>
</div>



    <!--AD NOT STAFF INFO SECTION-->

    <div class="art-block-body" id="bh" style="display: none">
        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                <div class="main-div">
                    <div id="main-header-title" class="main-header"><span id="frame-title" style="font-weight: bold">Staff Information</span></div>
                    <br/>

                    
                    
                    
                        
                    <form id="staff-info">
                     <label class="input_label">Campus:</label>
                   <select id="selected-campus" name="camp" size="1" class="input_select" style="width: 200px;">
                            <option>Granger Bay Campus</option>
                            <option>Tygerberg Hospital</option>
                            <option>Athlone Campus</option>
                            <option>Wellington Campus</option>
                            <option>Tygerberg:Dental & Radiography</option>
                            <option>Groote Schuur Hospital</option>
                            <option>Bellville Campus (Main)</option>
                            <option>Worcester Campus</option>
                            <option>Bellville Servicepoint Campus</option>
                            <option>Cape Town Campus (Main)</option>
                            <option>Mowbray Campus</option>
                            <option>George Campus</option>
                            <option>Dental Services & Radiography</option>                            
                        </select><br/>
                       <!-- <label class="input_label">Campus:</label>
                        <select name="camp" id="staff-campus" size="1" class="input_select" style="width: 200px;" />                       
                            
                        </select><br/>-->
                       
                       
                         
                        
                        <label class="input_label">Surname:</label>
                        <input type="text" name="surname" size="30" id="staff-surname"  class="input_field" required/><br/>

                        <label class="input_label">First Name:</label>
                        <input type="text" name="fname" size="30" id="staff-fname"  class="input_field" required/><br/>

                        <label class="input_label">Initials:</label>
                        <input type="text" name="initials" size="5" id="staff-initials"  class="input_field" required/><br/>

                        <label class="input_label">Title:</label>
                        <select name="title" id="staff-title" size="1" class="input_select" style="width: 70px;">
                         <option value="MR">MR</option>
                        <option value="MISS">MISS</option>                        
                        <option value="MS">MS</option>
                        <option value="MRS">MRS</option>
                        <option value="DR.">DR.</option>
                        <option value="PROF.">PROF.</option>
                        </select><br/>

                        <label class="input_label">Designation:</label>
                        <input type="text" name="designation" size="30" id="desig-nation"  class="input_field" required/><br/>

                        <label class="input_label">AKA:</label>
                        <input type="text" name="aka" size="30" id="staff-aka"  class="input_field"/><br/>


                        <label class="input_label">Faculty:</label>
                        <select name="faculty" id="staff-faculty" size="1" class="input_select" style="width: 200px;" />
                        <?php
                        $dbo->setQuery("select distinct fac_code,fac_name from budgets.costcodes where active = 'Y' order by fac_name");
                        $result = $dbo->loadObjectList();
                        foreach($result as $row){
                            echo "<option value='".$row->fac_name."'>".$row->fac_name."</option>";
                        }
                        ?>
                        </select><br/>

                        <label class="input_label">Department:</label>
                        <select name="department" id="staff-department" size="1" class="input_select" style="width: 200px;" />
                        <?php
                        $dbo->setQuery("select distinct dept_code,dept_name from budgets.costcodes where active = 'Y' order by dept_name");
                        $result = $dbo->loadObjectList();
                        foreach($result as $row){
                            echo "<option value='".$row->dept_name."'>".$row->dept_name."</option>";
                        }
                        ?>
                        </select><br/>

                        <label class="input_label">Cellular#:</label>
                        <input type="text" name="cellno" size="15" id="staff-cellno"  class="input_field"/><br/>

                        <label class="input_label">Building:</label>
                        <input type="text" name="building" size="15" id="buil-ding"  class="input_field" required/><br/>

                        <label class="input_label">Floor#:</label>
                        <input type="text" name="floor_no" size="6" id="staff-floor"  class="input_field"/><br/>

                        <label class="input_label">Room#:</label>
                        <input type="text" name="room_no" size="6" id="staff-room"  class="input_field"/><br/>

                        <!--<label class="input_label" required>Status:</label>
                        <input type="text" name="status" size="6" id="staff-status"  class="input_field"/><br/>-->

                        <label class="input_label">Extention:</label>
                        <input type="text" name="extention" size="6" id="exten-tion"  class="input_field"/><br/>

                        <label class="input_label" required>Cost Center:</label>
                        <input type="text" name="costcenter" size="6" id="cost-center"  class="input_field"/><br/>

                        <p style="text-align: center;">
                            <input type="submit" name="addstaff" id="add-staff" value="Add Staff Member" class="button art-button" style="display:block;"/>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>