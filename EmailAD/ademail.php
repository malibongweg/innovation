<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
//$doc->addScript("scripts/security/itspin.js");
$doc->addCustomTag("<meta http-equiv='X-UA-Compatible' content='IE=8' />");
$doc->addScript("scripts/EmailAD/ademail.js");
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
                 ADS Password Request System</h3>
        </div>
        <div class="art-blockcontent">
            <div class="art-blockcontent-body">

                <div style="width: auto; height: auto; display: block; margin-bottom: 5px">
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
    </div>