<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
//$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/finance/credit_man/index.js');
$doc->addScript('scripts/jtable/jquery.min.js');
$doc->addScript('scripts/jtable/jquery-ui.js');
$doc->addScript('scripts/jtable/jquery.jtable.js');
$doc->addScript('scripts/jtable/jquery.validationEngine.js');
$doc->addScript('scripts/jtable/jquery.validationEngine-en.js');
$doc->addScript('scripts/json.js');
$doc->addStyleSheet('scripts/jtable/themes/metro/blue/jtable.css');
$doc->addStyleSheet('scripts/jtable/jquery-ui.css');

?>
<a href="#" class="modalizer" id="rep-clearance"></a>
<input type="hidden" id="uid-cm" value="<?php echo $user->id; ?>" />
<input type="hidden" id="login-name-cm" value="<?php echo $user->username; ?>" />

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
       Student Credit Management <span id="hr-details"></span></h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
            	
          <div style="padding: 3px; border: 1px solid #87c6fa; background-color: #aae3fc">
          	<label style="display: inline-block; width: 80px">Student#</label>
			<input type="text" class="input_field" size="9" maxlength="9" id="idsearch" />&nbsp;
          	<input type="button" id="search-button" value="Search" class="button" />&nbsp;
			<input type="button" id="prn-button" value="Clearance Report" class="button" />
			<div id="credit-ajax" style="display: none"><img src="/images/kit-ajax.gif" width="16" height="11" border="0" style="vertical-align: middle">&nbsp;Searching...</div>
          </div>
	
	<div id="data-div" style="padding: 3px">
		<div id="student-data">
			<label style="display: inline-block; width: 80px">Surname:</label>
			<input type="text" id="student-surname" disabled=true size="15" />
			&nbsp;&nbsp;

			<label style="display: inline-block; width: 55px">Initials:</label>
			<input type="text" id="student-initials" disabled=true size="4" />
			&nbsp;&nbsp;

			<label style="display: inline-block; width: 157px">ID#:</label>
			<input type="text" id="student-idno" disabled=true size="13" />
			<br style="clear: both" />

			<label style="display: inline-block; width: 80px">Qualification:</label>
			<input type="text" id="student-qual" disabled=true size="15" />
			&nbsp;&nbsp;

			<label style="display: inline-block; width: 55px">Year:</label>
			<input type="text" id="student-year" disabled=true size="4" style="text-align: right" />
			&nbsp;&nbsp;

			<label style="display: inline-block; width: 157px">Subj Registered:</label>
			<input type="text" id="student-subject" disabled=true size="13" style="text-align: right" />
			<br style="clear: both" />

			<label style="display: inline-block; width: 80px">Subj Passed:</label>
			<input type="text" id="student-subject-passed" disabled=true size="15" style="text-align: right" />
			&nbsp;&nbsp;

			<label style="display: inline-block; width: 55px">% Passed</label>
			<input type="text" id="student-percent" disabled=true size="4" style="text-align: right" />
			&nbsp;&nbsp;

			<label style="display: inline-block; width: 157px">Balance:</label>
			<input type="text" id="student-balance" disabled=true size="13" style="text-align: right" />
			<br style="clear: both" />

			<label style="display: inline-block; width: 80px">Min Due:</label>
			<input type="text" id="student-mindue" disabled=true size="15" style="text-align: right" />
			&nbsp;&nbsp;

			<label style="display: inline-block; width: 55px">% Due</label>
			<input type="text" id="student-perdue" disabled=true size="15"  />
			&nbsp;&nbsp;

			<label style="display: inline-block; width: 80px">Payments:</label>
			<input type="text" id="student-payments" disabled=true size="13" style="text-align: right" />
			<br style="clear: both" />

		</div>		 
				<div id="tableData" style="position: relative; width: auto; height: auto; margin-top: 5px"></div>
    </div>
	
</div></div></div></div>
