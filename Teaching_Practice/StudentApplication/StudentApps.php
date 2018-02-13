<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/TeachingPractice/jquery.js");
$doc->addScript("scripts/TeachingPractice/jquery-ui.js");
$doc->addScript("scripts/TeachingPractice/StudentApplication/student.js");
$doc->addStyleSheet("scripts/TeachingPractice/jquery-ui.css");
?>

<form id="app-details">
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="login-name" value="<?php echo $user->username; ?>" />
<input type="hidden" id="app-name" value="Profile" />
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
            Student Application</h3>
        </div>

        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                <div class="main-div" id="table-header">
	                <div class="main-header"><strong><span id="mtitle">Student Details</span></strong></div>
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                    <div style="position: relative; display: none" id="ajax">
                    <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
                    </div>
<!--This is where the entire form starts -->
		    <div style="position: relative; overflow: hidden" id="general-div">
                  <form id="criteria-form">
                        <label class="input_label">Student Number:</label>&nbsp;
                        <input type="text" name='stud_nr' id="stud-nr" size="10" maxlength="9" class="input_field" required/><br/>

                        <label class="input_label"> Full Name:</label>&nbsp;
                        <input type="text" name='f_name' id="f-name" size="40" maxlength="9" class="input_field"/><br/>

                        <label class="input_label">Email Address:</label>&nbsp;
                        <input type="text" name='email_address' id="email-address" size="20" maxlength="9" class="input_field"/><br/>
                        <label class="input_label">Qualification:</label>&nbsp;
                        <input type="text" name='s_qual' id="s-qual" size="40" maxlength="9" class="input_field"/><br/>

                      <label class="input_label">Type:</label>&nbsp;
                      <input type="text" name='s_type' id="s-type" size="40" maxlength="9" class="input_field"/><br/>
                      <label class="input_label">Phase:</label>&nbsp;
                      <input type="text" name='s_phase' id="s-phase" size="40" maxlength="9" class="input_field"/><br/>
                      <label class="input_label">Level of Study:</label>&nbsp;
                      <input type="text" name='s_level' id="s-level" size="40" maxlength="9" class="input_field"/><br/>

                        <label class="input_label">Registration Date:</label>&nbsp;
                        <input type="text" name='r_date' id="r-date" size="40" maxlength="20" class="input_field"/><br/><br/>
                        <div class="main-div" id="table-header">
                        <div class="main-header"><strong><span id="mtitle">Select Placement Criteria </span></strong></div><br/>

                        <!--		<div style="position: relative; overflow: hidden" id="general-div">	-->

                        <label class="input_label">Schools:</label>&nbsp;
                            <select name="select_school" id="select-school" size="1" style="width: 200px" class="input_select">
                                <option value="Choose school" selected="selected">Choose school</option>
                           
                                    <?php
                                //Listing the schoolnames in the dropdown from the database
                                    $dbo->setQuery("select schoolname from teaching_practice.SchoolTab  where num_students <> 0  order by schoolname asc");
                                    $result = $dbo->loadObjectList();
                                    foreach($result as $row){
                                        echo "<option value='".$row->schoolname."'>".$row->schoolname."</option>";
                                    }
					            ?>
                             </select><br/> 

                        <label class="input_label">Subjects Offered:</label>&nbsp;
                        <select name="subs_offered" id="subs-offered" size="1" style="width: 200px" class="input_select">
                            <option value="Choose Subjects" selected="selected">Choose Subjects</option>
                        </select><br/>
<!--                                       <input type="checkbox" name="lang" value="English">English
                                      <input type="checkbox" name="lang" value="Afrikaans">Afrikaans 
                                      <input type="checkbox" name="lang" value="Xhosa">Xhosa 
                                     <br/> -->

                        <!--<label class="input_label">Grade:</label>&nbsp;
                            <select name="grade" id="grade" size="1" style="width: 200px" class="input_select">
                                <option value="Choose Grade" selected="selected">Choose Grade</option>
                        </select><br/>-->


                            <label class="input_label">Grade:</label>&nbsp;
                            <select name="grade" id="grade" size="1" style="width: 200px" class="input_select">
                                <option value="Choose grade" selected="selected">Choose grade</option>
                                <?php
                                //Listing the schoolnames in the dropdown from the database
                                $dbo->setQuery("select GradeDesc from teaching_practice.Grades  order by GradeDesc asc");
                                $result = $dbo->loadObjectList();
                                foreach($result as $row){
                                    echo "<option value='".$row->GradeDesc."'>".$row->GradeDesc."</option>";
                                }
                                ?>
                                <br/>

                            </select><br/>





                            <label class="input_label">Period:</label>&nbsp;
                             <input type ="text" name ="time_period" id="time-period" size="20" value ="April 2015 " maxlength="9" class="input_field" />&nbsp;&nbsp;

                             <label>All students except B.Ed4 starts in April </label><br/> <br/>

                            <div id="subs-offered" style="display:block">
                                 <table style="width:100%" border ="0" style="table-layout: fixed">
                                      <tr>
                                            <th style="width: 50%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">Subject</th>
                                            <th style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">Grade</th>                                           
                                            <th style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">Delete</th>
                                      </tr>
                                </table>
                            </div>
   
                                                    
                            <div id="sub-list-data" style="position: relative; width: auto; height: auto;  display: block;"></div>
                             <!--buttons 
                             1. Once the button is clicked it goes to the the js that looks at the event that was envoked in this case its submit
                             -->
                             <input type="submit" name="list_criteria" id="list-criteria" object align ="right" class="button art-button" value="List Selection" />
                            <input type="button" name="add_school" id="add-school" object align ="right" class="button art-button" value="Finish" />
                          	<!--    	<input type="cancel" name="cancel_selec" id="cancel-selec" object align ="right" class="button art-button" value="Cancel" /> -->

                   </form>
            </div>
        </div>
            </div>
        </div>
    </div>
 </div>
</div>


                            