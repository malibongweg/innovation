<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addScript("scripts/sms/student_cells.js");
$doc->addScript("scripts/json.js");
$user = & JFactory::getUser();
$doc->addScript('scripts/fav_apps.js');
$dbo =& JFactory::getDBO();
$dbo->setQuery("call proc_pop_application('MAS Downloads')");
$dbo->query();
?>

<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Student Cellsphones" />
<input type="hidden" id="login-name" value="<?php echo $user->username; ?>" />
<input type="hidden" id="login-email" value="<?php echo $user->email; ?>" />
<input type="hidden" id="login-fullname" value="<?php echo $user->name; ?>" />
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
                <a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>Student Cellphones
            </h3>
        </div>
            
        <div class="art-blockcontent" >
            <div class="art-blockcontent-body" >
                <form name="criteria" id="criteria" method="get" action="index.php">
                <input type="hidden" name="view" id="application" />
                <input type="hidden" name="option" value="com_jumi" />
                <input type="hidden" name="fileid" value="8" />
                <input type="hidden" name="action" id="action" />
                <input type="hidden" name="selectedsubj" id="selected-subj">

                <div id="main-frame" class="main-div">
                    <div id="main-header-title" class="main-header"><strong><span id="info-heading">Subject Criteria</span></strong></div>
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                    <div id="level-1" style="position: relative; width: 100%; height: auto; margin-left: 5px">
                        <label class="input_label" for="cyr">Year:</label>
                        <select name="cyr" id="cyr" size="1" class="input_select" style="width: 60px">
                            <?php
                                    $yr = date('Y');
                                    for($i = $yr-3;$i<$yr+1;++$i) {
                                            if ($i == $yr) echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                            else echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                            ?>
			</select>			
                    </div>

                    <!---SUBJECT SEARCH AND SELECTION-->
                    <div id="level-2" style="position: relative; width: 100%; height: auto; margin-left: 5px">
                        <label class="input_label" for="subj_search">Subject:</label>
                            <input type="text" name="subj_search" id="subj-search" size="30" maxlength="30" class="input_field" />
                            <input type="radio" name="search_type" id="search-type" value="1" checked>By Code&nbsp;
                            <input type="radio" name="search_type" id="search-type" value="2">By Description
                            <div id="loader-subject" style="position: relative; left: 100px; width: auto; height:auto; display:none">
                                <img src="images/kit-ajax.gif" width="24" height="11" alt="" style="vertical-align: middle" />&nbsp;<b>Searching for subject information...Please wait.</b>
                            </div>
                            <div id="loader-subject-all" style="width: auto; height:auto; left: 130px; display:none">
                                <img src="images/kit-ajax.gif" width="24" height="11" alt="" style="vertical-align: middle" />&nbsp;<b>Searching for subject criteria...Please wait.</b>
                            </div>

                        <div id="div-subject" style="position: relative;left: 130px; width: 100%; height: auto; display: block">
                            <select name="list_subj" id="list-subj" size="7" class="input_select_big" style="font-size: 80%; width: 300px"></select>
                        </div>
                    </div>

                    <!---QUALIFICATION CODE-->
                    <div id="level-3" style="position: relative; width:100%;margin-left: 5px; height: auto; display: block">
                        <label class="input_label" for="subj_qual">Qualification:</label>
                            <select name="subj_qual" id="subj-qual" class="input_select" style="width: 258px"></select>
                            <div id="loader-qual" style="width: auto; height:auto; display:none">
                                <img src="scripts/ajax-loader.gif" width="160" height="24" />
                            </div>
                    </div>
                    
                    <div style="position: relative">
                        <input type="submit" value="Extract Cell Numbers" id="go" class="button" disabled="true" onclick="$('action').value='marksheet';" />&nbsp;
                        <!--input type="submit" value="Extract Class List" id="go-class" class="button" disabled="true" onclick="$('action').value='classlist';"/-->&nbsp;
                        <input type="button" value="Home" class="button" onclick="window.location.href='/index.php'" />
                    </div>
               </div>
              </form>  
            </div>
        </div>
    </div>
</div>