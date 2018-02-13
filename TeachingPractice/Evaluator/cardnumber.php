<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/json.js");
?>

<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />

<!--Define app name here-->
<form id="app-details">
    <input type="hidden" id="app-name" value="Uniflow Card Number Activation" />
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
                Uniflow Card Number Activation
            </h3>
        </div>

        <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

                <div class="main-div" id="mn" >
                    <div class="main-header"><span id="frame-title" style="font-weight: bold">User Details</span></div>
                    <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

                    <!--form-->
                    <div id="claim-data" class="table-header" style="position: relative; width: auto; height: auto; ">
                        <div id="frm-ajax" style="position: absolute; background-color: #dbdbdb; top: 5px; left: 0px; width: auto; height: auto; z-index: 1000; padding: 3px; border: 2px solid #cdcdcd; display: none">
                            <img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Populating form...please wait.
                        </div>

                        <label class="input_label">Student Number:</label>
                        <input type="text" name='student_number' id="student-number" size="40" class="input_field"/><br/>

                        <label class="input_label">Card Number:</label>&nbsp;
                        <input type="text" name='card_number' id="card-number" size="40" class="input_field"/><br/>

                        <input type="button" value="Add Card Number" id="add-card-no" class="button art-button"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




