<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/TeachingPractice/EvaluatorAllocation/evaluator.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Profile Update')");
$dbo->query();
?>

<input type="hidden" id="uname" name="user_name" value="<?php echo $user->username; ?>" />

<!--Define app name here-->
<form id="app-details">
    <input type="hidden" id="app-name" value="Evaluator Allocation" />
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
                Evaluator Details</h3>
        </div>

        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

                <div style="display: none">
                    <input type="button" name="add_evl" id="add-sevl" value="Add Evaluator" class="button art-button" >
                    <input type="button" name="edit_evl" id="edit-evl" value="Edit Evaluator" class="button art-button" >
                    <input type="button" name="remove_evl" id="remove-evl" value="Remove Evaluator" class="button art-button" >
                </div>

                <div id="test" style="width: auto; height: auto; display: none; margin-bottom: 5px">
                    <fieldset class="input_fieldset">
                        Search<br />
                        <input type="text" size="30" name="srch" id="srch" maxlength="30" class="input_field" />
                        <input type="button" id="getEvaluator" class="button art-button" value="Get Evaluator Details">
                        <div id="list-evaluators" style="width: 100px; height: auto; display: none">
                            <select name="evaluatorList" id="evaluator-List" size="10" class="input_select" style="width: 300px">
                            </select>
                        </div>
                    </fieldset>
                </div>

                <div id="user-details" class="main-div" style="display: none">
                    <div class="main-header"><strong><span id="del-heading">User Details</span></strong></div>


                    <label class="input_label">Name:</label>&nbsp;
                    <input type="text" name='fname' id="f-name" size="40" class="input_field"/><br/>

                    <label class="input_label">Surname:</label>&nbsp;
                    <input type="text" name='sname' id="s-name" size="40" class="input_field"/><br/>

                    <label class="input_label">Email Address:</label>&nbsp;
                    <input type="text" name='email' id="email" size="40" class="input_field"/><br/>

                    <label class="input_label">Location:</label>&nbsp;
                    <input type="text" name='location' id="location" size="40" class="input_field"/><br/>

                    <label class="input_label">Category:</label>
                    <select name="category1" id="category1" size="1" style="width: 200px;margin-left: 8px;" class="input_select">
                        <?php
                        $sql = sprintf("select * from teaching_practice.languages");
                        $dbo->setQuery($sql);
                        $result = $dbo->loadObjectList();


                        foreach($result as $row){
                            echo  "<option value='" . $row->lang_desc . "'>" . $row->lang_desc . "</option>";
                        }
                        ?>
                    </select><br/>

                    <label class="input_label">No of visits:</label>&nbsp;
                    <input type="text" name='visits' id="visits" size="5" class="input_field"/><br/><br/>

                    <span style="margin-top: 3px">&nbsp;</span>

                </div>

                <div id="allocation-details" class="main-div" style="display: none">
                    <div class="main-header"><strong><span id="allocate-heading">Allocation Details</span></strong></div>

                    <label class="input_label">School Language:</label>
                    <select name="category2" id="category2" size="1" style="width: 200px;margin-left: 8px;" class="input_select">
                        <option value="Choose language" selected="selected">Choose language</option>
                        <?php
                        $sql = sprintf("select * from teaching_practice.languages");
                        $dbo->setQuery($sql);
                        $result = $dbo->loadObjectList();


                        foreach($result as $row){
                            echo  "<option value='" . $row->lang_desc . "'>" . $row->lang_desc . "</option>";
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
                            echo  "<option value='" . $row->SubCode . "'>" . $row->SubName . "</option>";
                        }
                        ?>
                    </select>

                    <span style="margin-top: 3px">&nbsp;</span>

                    <div id="header-details2" style="position: relative; display: block">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                        <label class="input_label">Select School:</label>
                        <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
                            <table width="100%" border="0" style="table-layout: fixed">
                                <tr>
                                    <th style="width: 50%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">NAME</th>
                                    <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">TYPE</th>
                                    <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">LANG</th>
                                    <th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">SELECT</th>
                                </tr>
                            </table>
                        </div>

                        <div id="ajax-schools" style="position: relative; width: auto; height: auto; display: none">
                            <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                        </div>

                        <div id="school-form" style="position: relative; width: auto; height: auto;  display: block; overflow: scroll">
                        </div>
                    </div>

                    <div id="header-details3" style="position: relative; display: block">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                        <label class="input_label">Select Students:</label>
                        <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
                            <table width="100%" border="0" style="table-layout: fixed">
                                <tr>
                                    <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">STUDENT NO</th>
                                    <th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">NAME</th>
                                    <th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">SCHOOL</th>
                                    <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">GRADE</th>
                                    <th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">SELECT</th>
                                </tr>
                            </table>
                        </div>

                        <div id="ajax-students" style="position: relative; width: auto; height: auto; display: none">
                            <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                        </div>

                        <div id="students-form" style="position: relative; width: auto; height: auto;  display: block; overflow: scroll">
                        </div>

                        <input type="button" value="Save Allocation" name='save_allocation' id="save-allocation-but" class="button art-button"/>
                        <input type="button" value="Delete Student" name='delete_student' id="delete-student" class="button art-button"/>

                        <br/>
                    </div>

                    <!--List of students selected!-->

                    <div id="selected_students_list_header" style="position: relative; display: none">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                        <label class="input_label">Selected Students:</label>
                        <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
                            <table width="100%" border="0" style="table-layout: fixed">
                                <tr>
                                    <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">STUDENT NO</th>
                                    <th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">NAME</th>
                                    <th style="width: 35%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">SCHOOL</th>
                                    <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">DELETE</th>
                                </tr>
                            </table>
                        </div>

                        <div id="ajax-selected_students" style="position: relative; width: auto; height: auto; display: none">
                            <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                        </div>

                        <div id="selected-students-form4" style="position: relative; width: auto; height: auto;  display: block; overflow: scroll">
                        </div>
                    </div>


                </div>


                <div id="ajax-loader" style="width: auto; height: auto; display: none">
                    <img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Searching...
                </div>

                <div id="evaluators-buttons" style="position: relative; width: auto; height: auto;  display: block; overflow:none; margin: 1px 0 0 0">
                    <input type="button" value="New Evaluator" name='new_evaluator' id="new-evaluator" class="button art-button"/>
                    <input type="button" value="Search Evaluator" name='search_evaluator' id="search-evaluator" class="button art-button"/>
                </div>

                <div id="header-details" style="position: relative; display: block">
                    <div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
                        <table width="100%" border="0" style="table-layout: fixed">
                            <tr>
                                <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
                                <th style="width: 40%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">NAME</th>
                                <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">SURNAME</th>
                                <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">ID NUMBER</th>
                                <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">CELLPHONE</th>
                            </tr>
                        </table>
                    </div>

                    <div id="ajax-evaluators" style="position: relative; width: auto; height: auto; display: none">
                        <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                    </div>

                    <div id="evaluator-form" style="position: relative; width: auto; height: auto;  display: block; overflow: scroll">
                    </div>
                </div>

                <div class="main-div" style="display: none" id="main-dv">
                    <div id="main-header-title" class="main-header"><span id="frame-title" style="font-weight: bold">Evaluator Information</span></div>
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

                    <form id="evaluator-info">
                        <div id="evaluator-data" style="display: block">

                            <label class="input_label">Name:</label>&nbsp;
                            <input type="text" name='evaluator_name' id="evaluator-name" size="40" class="input_field"/><br/>

                            <label class="input_label">Surname:</label>&nbsp;
                            <input type="text" name='evaluator_surname' id="evaluator-surname" size="40" class="input_field"/><br/>

                            <label class="input_label">ID Number:</label>&nbsp;
                            <input type="text" name='id_number' id="id-number" size="40" class="input_field"/><br/>

                            <label class="input_label">Address 1:</label>&nbsp;
                            <input type="text" name='address_1' id="address-1" size="40" class="input_field"/><br/>

                            <label class="input_label">Address 2:</label>&nbsp;
                            <input type="text" name='address_2' id="address-2" size="40" class="input_field"/><br/>

                            <label class="input_label">Address 3:</label>&nbsp;
                            <input type="text" name='address_3' id="address-3" size="40" class="input_field"/><br/>

                            <label class="input_label">Address 4:</label>&nbsp;
                            <input type="text" name='address_4' id="address-4" size="40" class="input_field"/><br/>

                            <label class="input_label">Postal Code:</label>&nbsp;
                            <input type="text" name='postal_code' id="postal-code" size="10" class="input_field"/><br/>

                            <label class="input_label">Telephone Number:</label>&nbsp;
                            <input type="text" name='telephone_number' id="telephone-number" size="20" class="input_field"/><br/>

                            <label class="input_label">Cellphone Number:</label>&nbsp;
                            <input type="text" name='cellphone_number' id="cellphone-number" size="20" class="input_field"/><br/>

                            <label class="input_label">Fax Number:</label>&nbsp;
                            <input type="text" name='fax_number' id="fax-number" size="20" class="input_field"/><br/>

                            <label class="input_label">Email Address:</label>&nbsp;
                            <input type="text" name='email_address' id="email-address" size="40" class="input_field"/><br/>


                            <label class="input_label">Region/Location:</label>&nbsp;
                            <input type="text" name='region' id="region" size="40" class="input_field"/><br/>

                            <label class="input_label">Category:</label>
                            <select name="category" id="category" size="1" style="width: 200px;margin-left: 8px;" class="input_select">
                                <?php
                                $sql = sprintf("select * from teaching_practice.languages");
                                $dbo->setQuery($sql);
                                $result = $dbo->loadObjectList();


                                foreach($result as $row){
                                    echo  "<option value='" . $row->lang_desc . "'>" . $row->lang_desc . "</option>";
                                }
                                ?>
                            </select><br/>

                            <label class="input_label">No of visits:</label>&nbsp;
                            <input type="text" name='number_of_visits' id="number-of-visits" size="5" class="input_field"/><br/><br/>

                        </div>

                        <input type="submit" name="add_evaluator" id="add-evaluator" class="button art-button" value="Add Evaluator"/>

                        <div id="buttons" style="display:none">
                            <p style="text-align: center;">
                                <input type="button" name="update" id="update-but" class="button art-button" value="Update Evaluator" style="display:block;"/>
                                <input type="button" name="delete" id="delete-but" class="button art-button" value="Delete Evaluator" style="display:block;"/>
                                <input type="button" name="go_back" id="go-back" class="button art-button" value="Back to Homepage" style="display:block;"/>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
