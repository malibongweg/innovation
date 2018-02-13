<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/hotbox/student/student.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
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
                Hotbox - Student Data
            </h3>
        </div>
        
        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                
                <div class="main-div" id="student-pop" style="display: none">
                    <div class="main-header"><strong>Update Student Data</span></strong></div>
                    <!--form-->
                    <div id="std-data" style="position: relative; width: auto; height: auto; display: block">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>    
                        <form id="student-data">
                            <input type="hidden" id="student-action" />
                                                            
                                <label class="input_label">Student Number</label>
                                    <input type="text" size="20" name="std_no" id="std-no" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                                                        
                                <label class="input_label">Full Name</label>
                                    <input type="text" size="35" name="f_name" id="f-name" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                
                                    <label class="input_label">Email</label>
                                    <input type="text" size="35" name="email" id="email-id" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                    
                                    
                                    <label class="input_label">Qualification</label>
                                    <input type="text" size="35" name="qualification" id="qualification-id" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                    
                                                                      
                                    <label class="input_label">Faculty</label>
                                    <input type="text" size="35" name="faculty" id="faculty-id" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                    
                                    
                                    <label class="input_label">Department</label>
                                    <input type="text" size="35" name="department" id="department-id" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                    
                                    <label class="input_label">Magstrip</label>
                                    <input type="text" size="35" name="magstrip" id="magstrip-id" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                    
                                    <label class="input_label">Barcode</label>
                                    <input type="text" size="35" name="barcode" id="barcode-id" readonly class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                    
                                <label class="input_label">Card Number</label>
                                        <input type="text" size="10" name="cardno" id="card-no" class="numeric"/><br />
                                        
                                <br/>
                                <label class="input_label">&nbsp;</label>                                
                                <input type="submit" value="Update Card#" id="student-save" class="button art-button"/>&nbsp;
                                <input type="button" value="Cancel" id="student-cancel-update" class="button art-button"/>
                        </form>
                            <!--End form-->
                    </div>                                        
                </div>
                
                <div class="main-div" id="filter-block">
                         <div class="main-header"><strong>Filter Selection</span></strong></div>
                        
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                          <!--<form id="srch-details-form">
                        <div style="width: 560px; height: auto; border: 1px solid black; padding: 10px; background: plum;">-->
                        <form id="srch-details-form">
                            <div style="float: left; width: auto; height: auto">
                                <input type="text" size="30" id="srch"  name="srch"/>&nbsp;
                                <input type="radio" value="std_no" name="filter" checked />Student#&nbsp;&nbsp;
                                <input type="radio" value="s_name" name="filter" />Surname&nbsp;&nbsp;
                                <input type="radio" value="card_no" name="filter" />Card#&nbsp;&nbsp;
                                <input type="submit" id="get-details" class="button art-button" value="Filter" />
                            </div>
                        </form>    
                          <div style="position: relative; clear: both; margin: 20px 0 0 0"></div>
                    </div> 
                
                
                <div class="main-div" id="md">
                    <div class="main-header"><span id="frame-title" style="font-weight: bold">Student Data</span></div>
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                    
                           
                   
                    
                    <div id="header-details">
                        <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
                            <table width="100%" border="0" style="table-layout: fixed">
                                <tr>                                        
                                        <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">STD#</th>
                                        <th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">FULLNAME</th>
                                        <th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">QUALIFICATION</th>
                                        <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">CARD#</th>                                        
                                        <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
                                </tr>
                            </table>
                        </div>

                        <div id="ajax-students" style="position: relative; width: auto; height: auto; display: none">
                            <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                        </div>

                        <div id="student-form" style="position: relative; width: auto; height: 500px;  display: block; overflow: scroll"></div>
                                                    
                    </div>
		
                </div>
            </div>
      </div>
    </div>
</div>

