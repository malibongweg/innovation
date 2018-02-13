<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/TeachingPractice/Reports/reports.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Profile Update')");
$dbo->query();
?>

<a href="#" class="modalizer" id="rep-discount"></a>
<a href="#" id="rep-discount2" target="_self"></a>

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
                Reports</h3>
        </div>

        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

                <div style="display: block">
                    <input type="button" name="school_report" id="school-report" value="Full Evaluated Students School Report" class="button art-button" >
                    <input type="button" name="evaluator_placement_report" id="evaluator-placement-report" value="Evaluator Placement Report" class="button art-button" >
                    <input type="button" name="student_placement_report" id="student-placement-report" value="Student Placement Report" class="button art-button" >
                    <input type="button" name="students_not_placed_report" id="students-not-placed-report" value="Students Not Placed Report" class="button art-button" >
                </div>

                <div id="search-school" style="width: auto; height: auto; display: none; margin-bottom: 5px">

                    <fieldset class="input_fieldset">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                        <label class="input_label">Choose Campus:</label>
                        <select name="campus" id="campus" size="1" style="width: 200px" class="input_select">
                            <option value="All" selected="selected">All</option>
                            <option value="Mowbray Campus">Mowbray Campus</option>
                            <option value="Wellington Campus">Wellington Campus</option>
                            <br/>

                        </select>
                        <input type="button" name="full_schools_report" id="full-schools-report" value="Run Full Schools Report" class="button art-button" >
                        <input type="button" name="correspondance" id="correspondance" value="Email Correspondance" class="button art-button" style="display: none"><br/><br/>
                        Search School<br />

                        <input type="text" size="30" name="srch_school" id="srch-school" maxlength="30" class="input_field" />
                        <input type="button" id="get-school" class="button art-button" value="Get School Details">
                        <div id="list-schools" style="width: 100px; height: auto; display: none">
                            <select name="school_list" id="school-list" size="10" class="input_select" style="width: 300px">
                            </select>
                        </div>
                    </fieldset>
                </div>


                <div id="school-details" class="main-div" style="display: none">
                    <div class="main-header"><strong><span id="del-heading">School Details</span></strong></div>

                    <label class="input_label">School Name:</label>&nbsp;
                    <input type="text" name='school_name' id="school-name" size="40" class="input_field" readonly/><br/>

                    <label class="input_label">School Type:</label>&nbsp;
                    <input type="text" name='school_type' id="school-type" size="40" class="input_field" readonly/><br/>

                    <label class="input_label">Address 1:</label>&nbsp;
                    <input type="text" name='address_1' id="address-1" size="40" class="input_field" readonly/><br/>

                    <label class="input_label">School Email:</label>&nbsp;
                    <input type="text" name='school_email' id="school-email" size="40" class="input_field" readonly/><br/>

                    <br/>
                    <input type="button" id="run-school-report" class="button art-button" value="Run Report">
                    <input type="button" id="email-school-report" class="button art-button" value="Email Correspondance">
                     <input type="button" name="sch_correspondance" id="sch-correspondance" value="Email School Correspondance" class="button art-button" style="display: none"><br/><br/>


                </div>

                <!--Evaluator search-->

                <div id="search-evaluator" style="width: auto; height: auto; display: none; margin-bottom: 5px">
                    <fieldset class="input_fieldset">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                        <label class="input_label">Choose Campus:</label>
                        <select name="campus" id="ecampus" size="1" style="width: 200px" class="input_select">
                            <option value="All" selected="selected">All</option>
                            <option value="Mowbray Campus">Mowbray Campus</option>
                            <option value="Wellington Campus">Wellington Campus</option>
                            <br/>
                        <input type="button" name="full_evaluators_report" id="full-evaluators-report" value="Run Full Evaluators Report" class="button art-button" ><br/><br/>
                        Search Evaluator<br />
                        <input type="text" size="30" name="srch_evaluator" id="srch-evaluator" maxlength="30" class="input_field" />
                        <input type="button" id="get-evaluator" class="button art-button" value="Get Evaluator Details">
                        <div id="list-evaluator" style="width: 100px; height: auto; display: none">
                            <select name="school_evaluator" id="evaluator-list" size="10" class="input_select" style="width: 300px">
                            </select>
                        </div>
                    </fieldset>
                </div>

                <div id="evaluator-details" class="main-div" style="display: none">
                    <div class="main-header"><strong><span id="del-heading">Evaluator Details</span></strong></div>


                    <label class="input_label">Name:</label>&nbsp;
                    <input type="text" name='fname' id="f-name" size="40" class="input_field" readonly/><br/>

                    <label class="input_label">Surname:</label>&nbsp;
                    <input type="text" name='sname' id="s-name" size="40" class="input_field" readonly/><br/>

                    <label class="input_label">Email Address:</label>&nbsp;
                    <input type="text" name='email' id="email" size="40" class="input_field" readonly/><br/>

                    <label class="input_label">Category:</label>&nbsp;
                    <input type="text" name='category' id="category" size="40" class="input_field" readonly/><br/>

                    <label class="input_label">No of visits:</label>&nbsp;
                    <input type="text" name='visits' id="visits" size="5" class="input_field" readonly/><br/>
                    <br/>
                    <input type="button" id="run-evaluator-report" class="button art-button" value="Run Report">
                    <input type="button" id="email-evaluator-report" class="button art-button" value="Email Evaluator">

                </div>

                <!--Student search-->

                <div id="search-student" style="width: auto; height: auto; display: none; margin-bottom: 5px">
                    <fieldset class="input_fieldset">
                        <div style="position: relative; clear: both; display: none; margin: 10px 0 0 0"></div>
                        <label class="input_label">Choose Campus:</label>
                        <select name="campus" id="scampus" size="1" style="width: 200px" class="input_select">
                            <option value="All" selected="selected">All</option>
                            <option value="Mowbray Campus">Mowbray Campus</option>
                            <option value="Wellington Campus">Wellington Campus</option>
                            <br/>
                        <input type="button" name="full_students_report" id="full-students-report" value="Run Full Students Report" class="button art-button" ><br/><br/>
                        Search Student<br />
                        <input type="text" size="30" name="srch_student" id="srch-student" maxlength="30" class="input_field" />
                        <input type="button" id="get-student" class="button art-button" value="Get Student Details">
                        <div id="list-student" style="width: 100px; height: auto; display: none">
                            <select name="student" id="student-list" size="10" class="input_select" style="width: 300px">
                            </select>
                        </div>
                    </fieldset>
                </div>

                <div id="student-details" class="main-div" style="display: none">
                    <div class="main-header"><strong><span id="del-heading">Student Details</span></strong></div>

                    <label class="input_label">Student Number:</label>&nbsp;
                    <input type="text" name='student_number' id="student-number" size="40" class="input_field" readonly/><br/><br/>

                    <label class="input_label">Full Name:</label>&nbsp;
                    <input type="text" name='student_name' id="student-name" size="40" class="input_field" readonly/><br/><br/>

                    <label class="input_label">Student Email:</label>&nbsp;
                    <input type="text" name='student_email' id="student-email" size="40" class="input_field" readonly/><br/><br/>

                    <input type="button" id="run-student-report" class="button art-button" value="Run Report">
                    <input type="button" id="email-student-report" class="button art-button" value="Email Student">

                </div>

            </div>
        </div>
    </div>
</div>
