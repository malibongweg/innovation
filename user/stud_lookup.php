<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/user/lookup.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Student Lookup')");
$dbo->query();
?>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="login-name" value="<?php echo $user->username; ?>" />
<input type="hidden" id="app-name" value="Student Lookup" />
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
      Student Number Lookup</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">


<p>Use any combination of fields to retrieve your results. Field parameters can be partial or complete. A maximum of 100 rows will be returned in you result set.</p>
<div class="main-div" id="table-header">
	<div class="main-header"><strong><span id="mtitle">Parameters</span></strong></div>
	
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
		<div style="position: relative; display: none" id="lookup-ajax">
		<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Searching...
		</div>

		<div style="position: relative; overflow: hidden" id="general-div">
			<form name="std_lookup" id="student-lookup">
			<label class="input_label">Student#</label>
			<input type="text" name="studno" id="student-number" size="9" class="input_field" ><br />

			<label class="input_label">Surname:</label>
			<input type="text" name="sname" id="student-surname" size="25" class="input_field" onKeyUp="this.value=this.value.toUpperCase();"  /><br />

			<label class="input_label">First Name:</label>
			<input type="text" name="fname" size="25" id="student-firstname" class="input_field" onKeyUp="this.value=this.value.toUpperCase();" /><br />

			<label class="input_label">ID#</label>
			<input type="text" name="idno" size="13" id="student-idno"  class="input_field" /><br >

			<label class="input_label">DOB: (YYYY-MM-DD)</label>
			<input type="text" name="dob" size="10" id="student-dob"  class="input_field" /><br >

			<label class="input_label">&nbsp;</label>
			<input type="submit" value="Search" class="button" />&nbsp;
			<input type="button" value="Clear Fields" id="clr" class="button" />&nbsp;
			<input type="button" value="Home" class="button" onclick="window.location.href='/index.php'" />
			</form>
		</div>

		<div id="lookup-data" style="position: relative; width: auto; height: auto; display: block; margin-top: 5px">
		</div>

				
</div>

</div></div></div></div>