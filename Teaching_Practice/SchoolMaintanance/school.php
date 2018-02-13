<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/TeachingPractice/SchoolMaintanance/school.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Profile Update')");
$dbo->query();
?>

<input type="hidden" id="uname" name="user_name" value="<?php echo $user->username; ?>" />

<!--Define app name here-->
<form id="app-details">
    <input type="hidden" id="app-name" value="School Maintanance" />
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
               School Maintanance</h3>
        </div>

        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                
                <div style="display: none">
                    <input type="button" name="add_sch" id="add-sch" value="Add New School" class="button art-button" >
                    <input type="button" name="edit_sch" id="edit-sch" value="Edit School" class="button art-button" >
                    <input type="button" name="remove_sch" id="remove-sch" value="Remove School" class="button art-button" >
                </div>
                
                <div id="test" style="width: auto; height: auto; display: none; margin-bottom: 5px">
                    <fieldset class="input_fieldset">
                            Search<br />
                            <input type="text" size="30" name="srch" id="srch" maxlength="30" class="input_field" />
                            <input type="button" id="getSchool" class="button art-button" value="Get School Details">
                            <div id="list-schools" style="width: 100px; height: auto; display: none">
                                    <select name="schoolList" id="school-List" size="10" class="input_select" style="width: 300px">
                                    </select>
                            </div>
                    </fieldset>
                </div>
                
                <div id="ajax-loader" style="width: auto; height: auto; display: none">
                    <img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Searching...
                </div>
                
                <!--Grid Start-->
                
                <div id="school-buttons" style="position: relative; width: auto; height: auto;  display: block; overflow: none; margin: 1px 0 0 0">
                    <input type="button" value="New School" name='new_school' id="new-school" class="button art-button"/>
                </div>
                
                <div id="header-details" style="position: relative; display: block">
                    <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
                        <table width="100%" border="0" style="table-layout: fixed">
                            <tr>
                                    <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
                                    <th style="width: 40%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">NAME</th>
                                    <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">TYPE</th>
                                    <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">TEL</th>														
                                    <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">LANG</th>
                            </tr>
                        </table>
                    </div>

                    <div id="ajax-schools" style="position: relative; width: auto; height: auto; display: none">
                        <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                    </div>

                    <div id="school-form" style="position: relative; width: auto; height: auto;  display: block; overflow: scroll">
                    </div>
	            </div>

                <!--Grid End-->
                
                <div class="main-div" style="display: none" id="main-dv">
                    <div id="main-header-title" class="main-header"><span id="frame-title" style="font-weight: bold">School Information</span></div>
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

                         <form id="school-info">
                        <div id="school-data" style="display: block"> 
                           
                            <label class="input_label">School Name:</label>&nbsp;
                            <input type="text" name='school_name' id="school-name" size="40" class="input_field" required style="text-transform: uppercase"/><br/>
                            
                            <label class="input_label">School Type:</label>
                            <select name="school_type" id="school-type" size="1" style="width: 200px;margin-left: 8px;" class="input_select">
                             <?php
                                $sql = sprintf("select * from teaching_practice.PlaceTypes");
                                $dbo->setQuery($sql);
                                $result = $dbo->loadObjectList();

                                
                                foreach($result as $row){
                                  echo  "<option value='" . $row->TypeDesc . "'>" . $row->TypeDesc . "</option>";                                
                                }                                
                            ?>  
                                </select><br/>
                            
                            <label class="input_label">Address 1:</label>&nbsp;
                            <input type="text" name='address_1' id="address-1" size="40" class="input_field" style="text-transform: uppercase"/><br/>
                            
                            <label class="input_label">Address 2:</label>&nbsp;
                            <input type="text" name='address_2' id="address-2" size="40" class="input_field" style="text-transform: uppercase"/><br/>
                            
                            <label class="input_label">Address 3:</label>&nbsp;
                            <input type="text" name='address_3' id="address-3" size="40" class="input_field" style="text-transform: uppercase"/><br/>    
                            
                            <label class="input_label">Address 4:</label>&nbsp;
                            <input type="text" name='address_4' id="address-4" size="40" class="input_field" style="text-transform: uppercase"/><br/> 
                            
                            <label class="input_label">Postal Code:</label>&nbsp;
                            <input type="text" name='postal_code' id="postal-code" size="10" class="input_field" style="text-transform: uppercase"/><br/>
                                                        
                            <label class="input_label">Telephone Number:</label>&nbsp;
                            <input type="text" name='telephone_number' id="telephone-number" size="20" class="input_field" style="text-transform: uppercase"/><br/>
                            
                             <label class="input_label">Cellphone Number:</label>&nbsp;
                            <input type="text" name='cellphone_number' id="cellphone-number" size="20" class="input_field" style="text-transform: uppercase"/><br/>
                            
                            <label class="input_label">Fax Number:</label>&nbsp;
                            <input type="text" name='fax_number' id="fax-number" size="20" class="input_field" style="text-transform: uppercase"/><br/>
                            
                            <label class="input_label">Email Address:</label>&nbsp;
                            <input type="text" name='email_address' id="email-address" size="40" class="input_field" style="text-transform: uppercase"/><br/>
                            
                            <label class="input_label">Principal Name:</label>&nbsp;
                            <input type="text" name='principal_name' id="principal-name" size="40" class="input_field" style="text-transform: uppercase"/><br/>

                            <label class="input_label">Liaison:</label>&nbsp;
                            <input type="text" name='liaison_person' id="liaison-person" size="40" class="input_field" style="text-transform: uppercase"/><br/>
                            
                            <label class="input_label">Language:</label>
                            <select name="language_used" id="language-used" size="1" style="width: 200px;margin-left: 8px;" class="input_select">
                             <?php
                                $sql = sprintf("select * from teaching_practice.languages");
                                $dbo->setQuery($sql);
                                $result = $dbo->loadObjectList();

                                
                                foreach($result as $row){
                                  echo  "<option value='" . $row->lang_desc . "'>" . $row->lang_desc . "</option>";                                
                                }                                
                            ?>  
                                </select><br/>

                            <!--<label class="input_label">Category:</label>
                            <select name="category" id="category" size="1" style="width: 200px;margin-left: 8px;" class="input_select">-->
                                <?php
                               /* $sql = sprintf("select * from teaching_practice.Categories");
                                $dbo->setQuery($sql);
                                $result = $dbo->loadObjectList();


                                foreach($result as $row){
                                    echo  "<option value='" . $row->CategoryDesc . "'>" . $row->CategoryDesc . "</option>";
                                }*/
                                ?>
                            <!--</select><br/>-->
                                
                            <label class="input_label">No of Students:</label>&nbsp;
                            <input type="text" name='number_of_students' id="number-of-students" size="5" class="input_field"/><br/><br/>
                            
                         </div>
                        
                        <div class="main-div" style="display: block">
                            <div id="main-header-title" class="main-header"><span id="frame-title" style="font-weight: bold">Grades & Subjects</span></div>
                            <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>       
                            <form name="subjects" id="subjects-criteria">
                            <label class="input_label">Grades Offered:</label>
                            <select name="grade" id="grade-id" size="1" style="width: 200px;margin-left: 8px;" class="input_select">
                                 <?php
                                    $sql = sprintf("select * from teaching_practice.Grades");
                                    $dbo->setQuery($sql);
                                    $result = $dbo->loadObjectList();


                                    foreach($result as $row){
                                      echo  "<option value='" . $row->GradeDesc . "'>" . $row->GradeDesc . "</option>";                                
                                    }                                
                                ?>  
                             </select><br/><br/>
                                                                                 
                            <label class="input_label">Subjects Offered:</label>
                             <select name="subject" id="subject-id" size="1" style="width: 200px;margin-left: 8px;" class="input_select">
                                 <?php
                                    $sql = sprintf("select * from teaching_practice.Subjects");
                                    $dbo->setQuery($sql);
                                    $result = $dbo->loadObjectList();


                                    foreach($result as $row){
                                      echo  "<option value='" . $row->SubName . "'>" . $row->SubName . "</option>";                                
                                    }                                
                                ?>  
                             </select>

                            <input type="submit" name="add_sub" id="add-sub" align="right" value="Add" class="button art-button" style="display:block;"/>
                                <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                          <!--</form>--> 
                            <div id="sub-list-table" style="display:block">
                                 <table width="100%" border="0" style="table-layout: fixed">
                                        <tr>
                                            <th style="width: 29%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px;">Grade</th>
                                            <th style="width: 58%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px;">Subject</th>
                                            <th style="width: 13%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">Delete</th>
                                        </tr>
                                    </table>
                            </div>
                            <div id="sub-list-data" style="position: relative; width: auto; height: auto;  display: block; overflow: scroll">
                            </div>
                            
                        </div><br/>

                        <div id="buttons2" style="display:none">
                        <p style="text-align: center;">
                            <input type="reset" name="finish" id="finish-but" class="button art-button" value="Finish" style="display:block;"/>
                            <input type="button" name="back_home" id="back-home" class="button art-button" value="Back to Homepage" style="display:block;"/>
                        </p>
                        </div>
                        <div id="buttons" style="display:none">
                        <p style="text-align: center;">                        
                            <input type="button" name="update" id="update-but" class="button art-button" value="Update School" style="display:block;"/>
                            <input type="button" name="delete" id="delete-but" class="button art-button" value="Delete School" style="display:block;"/>
                            <input type="button" name="go_back" id="go-back" class="button art-button" value="Back to Homepage" style="display:block;"/>
                        </p>
                        </div>
                        </form>
                </div>
            </div>
        </div>
     </div>
</div>
