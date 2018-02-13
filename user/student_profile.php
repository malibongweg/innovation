<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/user/student_profile.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Profile Update')");
$dbo->query();
?>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="login-name" value="<?php echo $user->username; ?>" />
<input type="hidden" id="app-name" value="Profile Update" />
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
      Student Profile</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div" id="table-header">
	<div class="main-header"><strong><span id="mtitle">General Details</span></strong></div>
	
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
		<div style="position: relative; display: none" id="ajax">
		<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
		</div>

		<div style="position: relative; overflow: hidden" id="general-div">
			<form name="profile">
			<label class="input_label">Login Name:</label>
			<input type="text" name="lname" id="student-lname" size="15" class="input_field" readonly value="<?php echo $user->username; ?>" /><br />

			<label class="input_label">User Name:</label>
			<input type="text" name="uname" id="student-uname" size="25" class="input_field" readonly value="<?php echo $user->name; ?>" /><br />

			<label class="input_label">Email:</label>
			<input type="text" name="email" size="40" id="student-email" class="input_field"" readonly value="<?php echo $user->email; ?>"/><br />

			<label class="input_label">Student#</label>
			<input type="text" name="stno" size="10" id="student-number"  class="input_field" readonly/><br >

			<label class="input_label">Full Name:</label>
			<input type="text" name="fname" size="40" id="student-fullname"  class="input_field" readonly/><br >

			<label class="input_label">Reg Date:</label>
			<input type="text" name="regdate" size="15" id="student-regdate"  class="input_field" readonly/><br >

			<label class="input_label">Qualification:</label>
			<input type="text" name="qual" size="40" id="student-qual"  class="input_field" readonly/><br >

			<label class="input_label">Offering Type:</label>
			<input type="text" name="ot" size="40" id="student-ot"  class="input_field" readonly/><br >

			<label class="input_label">Block Code:</label>
			<input type="text" name="bc" size="10" id="student-bc"  class="input_field" readonly/><br >

			<label class="input_label">Faculty:</label>
			<input type="text" name="fac" size="40" id="student-fac"  class="input_field" readonly/><br >

			<label class="input_label">Department:</label>
			<input type="text" name="dept" size="40" id="student-dept"  class="input_field" readonly/><br >

			<label class="input_label">Cellular#</label>
			<input type="text" name="cellno" size="15" id="student-cellno"  class="input_field" readonly/><br >
			<label class="input_label">&nbsp;</label>
			<span id="cell-msg" style="display: none"><a href="/index.php?option=com_jumi&fileid=81" class="modalizer" style="color: red" id="msg-click">!Important Message...Click here.</a></span>
			</form>
		</div>

				
</div>

</div></div></div></div>