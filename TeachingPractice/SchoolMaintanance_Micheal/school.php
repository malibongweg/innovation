<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/TeachingPractice/jquery.js");
$doc->addScript("scripts/TeachingPractice/jquery-ui.js");
$doc->addScript("scripts/TeachingPractice/SchoolMaintanance/school.js");
$doc->addStyleSheet("scripts/TeachingPractice/jquery-ui.css");
?>


<!---TABS---------------------------------------------------------------------------------------->
			<div id="navMain" style="display: none">
				<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Schools</a></li>
					<li><a href="#tabs-2">Applications</a></li>
					<li><a href="#tabs-3">Evaluators</a></li>
				</ul>

						<div id="tabs-1">
							<div id="schoolListDiv"></div>
						</div>

						<div id="tabs-2">
							<p>This is TAB 2</p>
						</div>

						<div id="tabs-3">
							<p>This is TAB 3</p>
						</div>
				</div>
			</div>
<!----END OF TABS--------------------------------------------------------------------------------->
             
			<div id="displayForm1" style="display: none">
                <div class="main-div" style="display: block">
                    <div id="main-header-title" class="main-header"><span id="frame-title" style="font-weight: bold">Add School</span></div>
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                            <div id="pin-data" style="display: block">
							<form id="schoolForm1edits">
                            <label class="input_label">School Name:</label>&nbsp;
                            <input type="text" name='school_name' id="school-name" size="40" maxlength="9" class="input_field" required/><br/><br/>
                            
                            <label class="input_label">Address 1:</label>&nbsp;
                            <input type="text" name='address_1' id="address-1" size="40" maxlength="9" class="input_field"/><br/><br/>
                            
                            <label class="input_label">Postal Code:</label>&nbsp;
                            <input type="text" name='postal_Code' id="postal-code" size="40" maxlength="9" class="input_field"/><br/><br/>
                                                        
                            <label class="input_label">Telephone Number:</label>&nbsp;
                            <input type="text" name='telephone_number' id="telephone-number" size="40" maxlength="9" class="input_field"/><br/><br/>
                            
                            <label class="input_label">Fax Number:</label>&nbsp;
                            <input type="text" name='fax_number' id="fax-number" size="40" maxlength="9" class="input_field"/><br/><br/>
                            
                            <label class="input_label">Email Address:</label>&nbsp;
                            <input type="text" name='email_address' id="email-address" size="40" maxlength="9" class="input_field"/><br/><br/>
                            
                            <label class="input_label">Principal Name:</label>&nbsp;
                            <input type="text" name='principal_name' id="principal-name" size="40" maxlength="9" class="input_field"/><br/><br/>
                            
                            <label class="input_label">No of Students:</label>&nbsp;
                            <input type="text" name='number_of_students' id="number-of-students" size="40" maxlength="9" class="input_field"/><br/><br/>
                            
                            
                            <table border="0" width="20%">
                            <label class="input_label">Grades Offered:</label><br/><br/>
                            
                           
                            <br/><br/>
                            <input type="submit" name="add_school" id="add-school" class="button art-button" value="Add School" />
							</form>
                            
                        </div>
                </div>
