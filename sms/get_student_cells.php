<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addScript("scripts/sms/student_cells.js"); 
$doc->addScript("scripts/json.js");
$doc->addScript('scripts/fav_apps.js');
$user = & JFactory::getUser();
$dbo =& JFactory::getDBO();
?>

<a href="#" class="modalizer" id="show-sheet"></a>
<a href="#" class="modalizer" id="show-classlist"></a>
<a href="#" class="modalizer" id="show-celllist"></a>
.
<input type="hidden" id="system-mode" />
<input type="hidden" id="system-log" />

<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Student Cellphone System" />
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
                <a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>iStudent CellPhone System
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
                                <label class="input_label" for="subj_search">Subject Code:</label>
                                <input type="text" name="subj_search" id="subj-search" size="30" maxlength="30" class="input_field" />
                                    <div id="loader-subject" style="position: relative; left: 100px; width: auto; height:auto; display:none">
                                        <img src="images/kit-ajax.gif" width="24" height="11" alt="" style="vertical-align: middle" />&nbsp;<b>Searching for subject information...Please wait.</b>
                                    </div>
                                    <div id="loader-subject-all" style="width: auto; height:auto; left: 130px; display:none">
                                      <img src="images/kit-ajax.gif" width="24" height="11" alt="" style="vertical-align: middle" />&nbsp;<b>Searching for subject criteria...Please wait.</b>
                                    </div>

                                    <div id="div-subject" style="position: relative;left: 130px; width: 100%; height: auto; display: none">
                                        <select name="list_subj" id="list-subj" size="7" class="input_select_big" style="font-size: 80%; width: 300px"></select>
                                    </div>
                        </div>

                        <!---QUALIFICATION CODE-->
                        <div id="level-3" style="position: relative; width:100%;margin-left: 5px; height: auto; display: none">
                            <label class="input_label" for="subj_qual">Qualification:</label>
                            <select name="subj_qual" id="subj-qual" class="input_select" style="width: 258px"></select>
                            <div id="loader-qual" style="width: auto; height:auto; display:none">
                                <img src="scripts/ajax-loader.gif" width="160" height="24" />
                            </div>
                        </div>

                        <!---OFFERING TYPE-->
                        <div id="level-4" style="position: relative; width:100%;margin-left: 5px; height: auto; display: none">
                            <label for="subj_offer" class="input_label">Offering Type:</label>
                            <select name="subj_offer" id="subj-offer" class="input_select" style="width: 258px"></select>
                            <div id="loader-offer" style="width: auto; height:auto; display:none">
                                 <img src="scripts/ajax-loader.gif" width="160" height="24" />
                            </div>
                        </div>

                        <div>
                            <input type="button" value="test" id="test" style="display:none"/>
                        </div>
                        <!---Button-->
                        <div id="level-5" style="position: relative; width:100%;margin-left: 5px; height: auto; display: none">
                            <div style="position: relative">
                                <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
                                <input type="submit" value="Extract Student Cellphones" id="go" class="button" onclick="$('action').value='celllist';" />&nbsp;
                                <input type="button" value="Get Student Cellphones" class="button" id="show" />
                                <input type="button" value="Home" class="button" onclick="window.location.href='/index.php'" />
                            </div>
                        </div>
                        
                    </div>
                </form>
                
                <div style="position: relative; height: auto; width: auto; margin: 0 16px 0 0" id="header">

                    <table border="0" width="100%" style="table-layout: fixed">
                        <tr>
                            <td style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>Student Name</strong>
                            <td style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>Student Number</strong></td>
                            <td style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>Cellphone Number</strong></td>
                        </tr>
                    </table>
                </div>					
                <!--Transaction details list-->
                <div id="view-data" style="position: relative; height: auto; width: auto; overflow: scroll"></div>
            </div>
       </div>
    </div>
</div>