<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/staff_profile/profile.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Staff Profile')");
$dbo->query();
?>
<!--style type="text/css">
a:link {
	color: #ff4444 !important;
}
a:visited {
	color: #ff4444 !important;
}
</style-->
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="login-name" value="<?php echo $user->username; ?>" />
<input type="hidden" id="app-name" value="Staff Profile" />
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
      Staff Profile</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div" id="table-header" style="background-color: #e7feeb">
		<div class="main-header"><strong><span id="mtitle">Human Resource Details</span></strong></div>
			<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
		<div style="position: relative; display: none" id="ajax-sp">
			<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;<span id="ajax-title">Loading data...</span>
		</div>

		<div style="position: relative; overflow: hidden" id="general-div">
		<p style="padding: 3px; background-color: #8effab; border: 2px solid #64ff64">
				<span style="font-bold; color: #ff3300; font-size: 14px">*</span> Indicates ITS related data and may take longer to be updated by the Human Resource Department. To make amendments to the data, click on the text box and enter the correct information in the new text box provided. The amendments will be sent to the Human Resource Department. <b>To update the current information, click <input type="button" class="button art-button" value="Here..." onclick="javascript: saveProfileView();" /></a></b>
				</p>
		<div id="key" style="display: none">
		<p style="padding: 3px; border: 2px solid #64ff64" >
		<input type="text" class="input_field" style="background-color: #ffffb9" readonly size="1" />&nbsp;Submitted to HR.&nbsp;&nbsp;&nbsp;
		<input type="text" class="input_field" style="background-color: #d2ffa6" readonly size="1" />&nbsp;Updated by HR.&nbsp;&nbsp;&nbsp;
		<input type="text" class="input_field" style="background-color: #ffa6a6" readonly size="1" />&nbsp;Rejected by HR.
		</p></div>
			<label class="input_label">Login Name:</label>
			<input type="text" name="lname" id="staff-lname" size="15" class="input_field" readonly value="<?php echo $user->username; ?>" />&nbsp;
			<input type="button" value="Send Amendments" id="send-changes" class="button art-button" style="display: none" />
			<br style="clear: both" />

			<label class="input_label">User Name:</label>
			<input type="text" name="uname" id="staff-uname" size="25" class="input_field" readonly value="<?php echo $user->name; ?>" /><br style="clear: both" />

			<label class="input_label">Email:</label>
			<input type="text" name="email" size="30" id="staff-email" class="input_field" readonly value=""/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_email" size="25" id="new-email" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toLowerCase();" />
			<input type="text" class="input_field" id="email-yellow" style="background-color: #ffffb9; display: none" readonly size="25" />
			<input type="text" class="input_field" id="email-green" style="background-color: #d2ffa6; display: none" readonly size="20"  />
			<input type="text" class="input_field" id="email-red" style="background-color: #ffa6a6; display: none" readonly size="20"  />
			<br style="clear: both" />

			<label class="input_label">Staff#</label>
			<input type="text" name="stfno" size="10" id="staff-stfnumber"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_stfnumber" size="9" maxlength="9" id="new-stfnumber" class="input_field" style="display: none; border: 1px solid #fb5e4c" />&nbsp;
			<input type="text" class="input_field" id="stfnumber-yellow" style="background-color: #ffffb9; display: none" readonly size="9" />
			<input type="text" class="input_field" id="stfnumber-green" style="background-color: #d2ffa6; display: none" readonly size="9"  />
			<input type="text" class="input_field" id="stfnumber-red" style="background-color: #ffa6a6; display: none" readonly size="9"  />
			<br style="clear: both" />

			<label class="input_label">Title:</label>
			<input type="text" name="title" size="5" id="staff-title"  class="input_field" readonly />
			<select name="new_title" id="new-title" size="1" class="input_select" style="width: 50px; border: 1px solid #fb5e4c; display: none" />
				<option value="MR">MR</option>
				<option value="MISS">MISS</option>
				<option value="MS">MS</option>
				<option value="MRS">MRS</option>
			</select>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" class="input_field" id="title-yellow" style="background-color: #ffffb9; display: none" readonly size="5" />
			<input type="text" class="input_field" id="title-green" style="background-color: #d2ffa6; display: none" readonly size="5"  />
			<input type="text" class="input_field" id="title-red" style="background-color: #ffa6a6; display: none" readonly size="5"  />
			<br style="clear: both" />

			<label class="input_label">Initials:</label>
			<input type="text" name="initials" size="5" id="staff-initials"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_initials" size="5" id="new-initials" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<input type="text" class="input_field" id="initials-yellow" style="background-color: #ffffb9; display: none" readonly size="5" />
			<input type="text" class="input_field" id="initials-green" style="background-color: #d2ffa6; display: none" readonly size="5"  />
			<input type="text" class="input_field" id="initials-red" style="background-color: #ffa6a6; display: none" readonly size="5"  />
			<br style="clear: both" />

			<label class="input_label">Surname:</label>
			<input type="text" name="surname" size="30" id="staff-surname"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_surname" size="25" id="new-surname" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<input type="text" class="input_field" id="surname-yellow" style="background-color: #ffffb9; display: none" readonly size="25" />
			<input type="text" class="input_field" id="surname-green" style="background-color: #d2ffa6; display: none" readonly size="25"  />
			<input type="text" class="input_field" id="surname-red" style="background-color: #ffa6a6; display: none" readonly size="25"  />
			<br style="clear: both" />

			<label class="input_label">Name:</label>
			<input type="text" name="fname" size="30" id="staff-fname"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_name" size="25" id="new-name" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<input type="text" class="input_field" id="name-yellow" style="background-color: #ffffb9; display: none" readonly size="15" />
			<input type="text" class="input_field" id="name-green" style="background-color: #d2ffa6; display: none" readonly size="15"  />
			<input type="text" class="input_field" id="name-red" style="background-color: #ffa6a6; display: none" readonly size="15"  />
			<br style="clear: both" />

			<form name="aka_form" id="aka-form">
			<input type="hidden" name="aka_staff" id="aka-staff" />
			<label class="input_label">AKA:</label>
			<input type="text" name="aka" size="30" id="staff-aka"  class="input_field" readonly onKeyUp="this.value=this.value.toUpperCase();" />
			<input type="text" name="n_aka" size="25" id="n-aka" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<br style="clear: both" />

			<label class="input_label">Faculty:</label>
			<input type="text" name="faculty" size="30" id="staff-faculty"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<select name="new_faculty" id="new-faculty" size="1" class="input_select" style="width: 200px; border: 1px solid #fb5e4c; display: none" />
				<?php
					$dbo->setQuery("select distinct fac_code,fac_name from budgets.costcodes where active = 'Y' order by fac_name");
					$result = $dbo->loadObjectList();
					foreach($result as $row){
						echo "<option value='".$row->fac_code."'>".$row->fac_name."</option>\n";
					}
				?>
			</select>
			<input type="text" class="input_field" id="faculty-yellow" style="background-color: #ffffb9; display: none" readonly size="25" />
			<input type="text" class="input_field" id="faculty-green" style="background-color: #d2ffa6; display: none" readonly size="25"  />
			<input type="text" class="input_field" id="faculty-red" style="background-color: #ffa6a6; display: none" readonly size="25"  />
			<br style="clear: both" />

			<label class="input_label">Department:</label>
			<input type="text" name="stf_dept" size="30" id="staff-department"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<select name="new_department" id="new-department" size="1" class="input_select" style="width: 200px; border: 1px solid #fb5e4c; display: none" />
				<?php
					$dbo->setQuery("select distinct dept_code,dept_name from budgets.costcodes where active = 'Y' order by dept_name");
					$result = $dbo->loadObjectList();
					foreach($result as $row){
						echo "<option value='".$row->dept_code."'>".$row->dept_name."</option>\n";
					}
				?>
			</select>
			<input type="text" class="input_field" id="department-yellow" style="background-color: #ffffb9; display: none" readonly size="25" />
			<input type="text" class="input_field" id="department-green" style="background-color: #d2ffa6; display: none" readonly size="25"  />
			<input type="text" class="input_field" id="department-red" style="background-color: #ffa6a6; display: none" readonly size="25"  />
			<br style="clear: both" />

			<label class="input_label">Cellular#</label>
			<input type="text" name="cellno" size="15" id="staff-cellno"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_cellno" size="12" id="new-cellno"  class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<input type="text" class="input_field" id="cellno-yellow" style="background-color: #ffffb9; display: none" readonly size="12" />
			<input type="text" class="input_field" id="cellno-green" style="background-color: #d2ffa6; display: none" readonly size="12"  />
			<input type="text" class="input_field" id="cellno-red" style="background-color: #ffa6a6; display: none" readonly size="12"  />
			<br style="clear: both" />

			<label class="input_label">Designation:</label>
			<input type="text" name="job_title" size="30" id="staff-jobtitle"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<select name="new_jobtitle" id="new-jobtitle" size="1" class="input_select" style="width: 200px; border: 1px solid #fb5e4c; display: none" />
				<?php
					$dbo->setQuery("select distinct staff_post from staff.staff order by staff_post");
					$result = $dbo->loadObjectList();
					foreach($result as $row){
						echo "<option value='".$row->staff_post."'>".$row->staff_post."</option>\n";
					}
				?>
			</select>
			<input type="text" class="input_field" id="jobtitle-yellow" style="background-color: #ffffb9; display: none" readonly size="25" />
			<input type="text" class="input_field" id="jobtitle-green" style="background-color: #d2ffa6; display: none" readonly size="25"  />
			<input type="text" class="input_field" id="jobtitle-red" style="background-color: #ffa6a6; display: none" readonly size="25"  />
			<br style="clear: both" />

			<label class="input_label">Line Manager:</label>
			<input type="text" name="linemanager" size="30" id="staff-linemanager"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<select name="new_linemanager" id="new-linemanager" size="1" class="input_select" style="width: 200px; border: 1px solid #fb5e4c; display: none" />
				<?php
						$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname) as sec from staff.staff a where a.staff_resign = '0000-00-00' or a.staff_resign > date(now()) order by a.staff_sname");
						$result = $dbo->loadObjectList();
						foreach($result as $row){
							echo "<option value='".$row->staff_no."'>".$row->sec."</option>";
						}
					?>
			</select>
			<input type="text" class="input_field" id="linemanager-yellow" style="background-color: #ffffb9; display: none" readonly size="25" />
			<input type="text" class="input_field" id="linemanager-green" style="background-color: #d2ffa6; display: none" readonly size="25"  />
			<input type="text" class="input_field" id="linemanager-red" style="background-color: #ffa6a6; display: none" readonly size="25"  />
			<br style="clear: both" />

			<label class="input_label">HOD:</label>
			<select name="hod2" id="staff-hod2" size="1" style="width: 200px" class="input_select">
				<?php
						$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname) as sec from staff.staff a where a.staff_resign = '0000-00-00' or a.staff_resign > date(now()) order by a.staff_sname");
						$result = $dbo->loadObjectList();
						foreach($result as $row){
							echo "<option value='".$row->staff_no."'>".$row->sec."</option>";
						}
					?>
			</select>
			</form>
			<br style="clear: both" />
			</div>

</div>

<div class="main-div" id="table-header" style="background-color: #e7feeb">
			<div class="main-header"><strong><span id="mtitle">Primary Location</span></strong></div>
			<label class="input_label">Campus:</label>
			<input type="text" name="campus" size="30" id="staff-campus"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<select name="new_campus" id="new-campus" size="1" class="input_select" style="width: 200px; border: 1px solid #fb5e4c; display: none" />
				<?php
					$dbo->setQuery("select campus_code,campus_name from structure.campus order by campus_name");
					$result = $dbo->loadObjectList();
					foreach($result as $row){
						echo "<option value='".$row->campus_code."'>".$row->campus_name."</option>\n";
					}
				?>
			</select>
			<input type="text" class="input_field" id="campus-yellow" style="background-color: #ffffb9; display: none" readonly size="25" />
			<input type="text" class="input_field" id="campus-green" style="background-color: #d2ffa6; display: none" readonly size="25"  />
			<input type="text" class="input_field" id="campus-red" style="background-color: #ffa6a6; display: none" readonly size="25"  />
			<br style="clear: both" />

			<label class="input_label">Building:</label>
			<input type="text" name="building" size="30" id="staff-building"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<select name="new_building" id="new-building" size="1" class="input_select" style="width: 200px; border: 1px solid #fb5e4c; display: none" />
				<?php
					$dbo->setQuery("select build_code,build_name from structure.buildings order by build_name");
					$result = $dbo->loadObjectList();
					foreach($result as $row){
						echo "<option value='".$row->build_code."'>".$row->build_name."</option>\n";
					}
				?>
			</select>
			<input type="text" class="input_field" id="building-yellow" style="background-color: #ffffb9; display: none" readonly size="25" />
			<input type="text" class="input_field" id="building-green" style="background-color: #d2ffa6; display: none" readonly size="25"  />
			<input type="text" class="input_field" id="building-red" style="background-color: #ffa6a6; display: none" readonly size="25"  />
			<br style="clear: both" />

			<label class="input_label">Floor#</label>
			<input type="text" name="floor_no" size="6" id="staff-floor"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_floor" size="6" id="new-floor" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<input type="text" class="input_field" id="floor-yellow" style="background-color: #ffffb9; display: none" readonly size="5" />
			<input type="text" class="input_field" id="floor-green" style="background-color: #d2ffa6; display: none" readonly size="5"  />
			<input type="text" class="input_field" id="floor-red" style="background-color: #ffa6a6; display: none" readonly size="5"  />
			<br style="clear: both" />

			<label class="input_label">Room#</label>
			<input type="text" name="room_no" size="6" id="staff-room"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_room" size="6" id="new-room" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<input type="text" class="input_field" id="room-yellow" style="background-color: #ffffb9; display: none" readonly size="5" />
			<input type="text" class="input_field" id="room-green" style="background-color: #d2ffa6; display: none" readonly size="5"  />
			<input type="text" class="input_field" id="room-red" style="background-color: #ffa6a6; display: none" readonly size="5" />
			<br style="clear: both" />

			<label class="input_label">Ext#</label>
			<input type="text" name="ext_no" size="15" id="staff-ext"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_ext" size="15" id="new-ext" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<input type="text" class="input_field" id="ext-yellow" style="background-color: #ffffb9; display: none" readonly size="10" />
			<input type="text" class="input_field" id="ext-green" style="background-color: #d2ffa6; display: none" readonly size="10"  />
			<input type="text" class="input_field" id="ext-red" style="background-color: #ffa6a6; display: none" readonly size="10"  />
			<br style="clear: both" />

			<label class="input_label">Fax#</label>
			<input type="text" name="fax_no" size="15" id="fax-no"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_fax" size="15" id="new-fax" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<br style="clear: both" />

			<label class="input_label">Speed Dial:</label>
			<input type="text" name="speed_dial1" size="15" id="staff-speeddial1"  class="input_field" readonly/>
			<span style="font-bold; color: #ff3300; font-size: 14px">*</span>
			<input type="text" name="new_speeddial1" size="10" id="new-speeddial1" class="input_field" style="display: none; border: 1px solid #fb5e4c" onKeyUp="this.value=this.value.toUpperCase();" />
			<input type="text" class="input_field" id="speeddial1-yellow" style="background-color: #ffffb9; display: none" readonly size="10" />
			<input type="text" class="input_field" id="speeddial1-green" style="background-color: #d2ffa6; display: none" readonly size="10"  />
			<input type="text" class="input_field" id="speeddial1-red" style="background-color: #ffa6a6; display: none" readonly size="10"  />
			<br style="clear: both" />

			<hr />
			<form name="sec_pri" id="sec-pri">
			<input type="hidden" name="sec_staff" id="sec-staff" />
			<label class="input_label">Secretary:</label>
				<select name="secretary" id="secretary-person" size="1" style="width: 200px" class="input_select">
				<option value="0">Not Applicable</option>
						<?php
						$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname) as sec from staff.staff a where a.staff_resign = '0000-00-00' or a.staff_resign > date(now()) order by a.staff_sname");
						$result = $dbo->loadObjectList();
						foreach($result as $row){
							echo "<option value='".$row->staff_no."'>".$row->sec."</option>";
						}
					?>
				</select><br style="clear: both" />

				<label class="input_label">Sec. Ext#</label>
				<input type="text" name="sec_ext" size="15" id="sec-ext"  class="input_field" maxlength="15"/><br style="clear: both" />

				<label class="input_label">Sec. Fax#</label>
				<input type="text" name="sec_fax" size="15" id="sec-fax"  class="input_field" maxlength="15"/><br style="clear: both" />

				<label class="input_label">Sec. Email:</label>
				<input type="text" name="sec_email" size="30" id="sec-email"  class="input_field" maxlength="40"/><br style="clear: both" />
				</form>
</div>

		<div class="main-div" id="table-header2">
				<div class="main-header"><strong><span id="mtitle">Secondary Location</span></strong></div>
				<form name="secondary_profile" id="secondary-profile">
				<p style="font-weight: bold; background-color: #d0d0d0; border: 2px solid #c0c0c0; padding: 3px; margin: 5px 0 0 0">Update any incomplete fields in the form below.</p><br style="clear: both" />

				<label class="input_label">Campus:</label>
				<select name="other_campus" id="other-campus" size="1" style="width: 200px" class="input_select">
				<option value="0">Not Applicable</option>
				<?php
						$dbo->setQuery("select campus_code,campus_name from structure.campus order by campus_name");
						$result = $dbo->loadObjectList();
						foreach($result as $row){
							echo "<option value='".$row->campus_code."'>".$row->campus_name."</option>";
						}
					?>
				</select><br style="clear: both" />

				<label class="input_label">Building:</label>
				<select name="other_building" id="other-building" size="1" style="width: 200px" class="input_select">
				<option value="0">Not Applicable</option>
				<?php
						$dbo->setQuery("select build_code,build_name from structure.buildings order by build_name");
						$result = $dbo->loadObjectList();
						foreach($result as $row){
							echo "<option value='".$row->build_code."'>".$row->build_name."</option>";
						}
					?>
				</select><br style="clear: both" />

				<label class="input_label">Floor#</label>
				<input type="text" name="other_floor" size="5" id="other-floor"  class="input_field" maxlength="5"/><br style="clear: both" />

				<label class="input_label">Room#</label>
				<input type="text" name="other_room" size="5" id="other-room"  class="input_field" maxlength="10"/><br style="clear: both" />

				<!-- label class="input_label">Fax#</label>
				<input type="text" name="fax_no" size="15" id="fax-no"  class="input_field" maxlength="15"/><br style="clear: both" /-->

				<!--label class="input_label">Speed Dial#</label-->
				<input type="text" name="speed_dial" size="15" id="speed-dial"  class="input_field" maxlength="15" style="display:none" />

				<label class="input_label">Ext#</label>
				<input type="text" name="other_ext" size="15" id="other-ext"  class="input_field" maxlength="15"/><br style="clear: both" />

				<label class="input_label">Fax#</label>
				<input type="text" name="other_fax" size="15" id="other-fax"  class="input_field" maxlength="15"/><br style="clear: both" />

				<hr />
				<label class="input_label">Secretary:</label>
				<select name="secretary2" id="secretary-person2" size="1" style="width: 200px" class="input_select">
					<option value="0">Not Applicable</option>
						<?php
						$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname) as sec from staff.staff a where a.staff_resign = '0000-00-00' or a.staff_resign > date(now()) order by a.staff_sname");
						$result = $dbo->loadObjectList();
						foreach($result as $row){
							echo "<option value='".$row->staff_no."'>".$row->sec."</option>";
						}
					?>
				</select><br style="clear: both" />

				<label class="input_label">Sec. Ext#</label>
				<input type="text" name="sec_ext2" size="15" id="sec-ext2"  class="input_field" maxlength="15"/><br style="clear: both" />

				<label class="input_label">Sec. Fax#</label>
				<input type="text" name="sec_fax2" size="15" id="sec-fax2"  class="input_field" maxlength="15"/><br style="clear: both" />

				<label class="input_label">Sec. Email:</label>
				<input type="text" name="sec_email2" size="30" id="sec-email2"  class="input_field" maxlength="40"/><br style="clear: both" />


				<label class="input_label">&nbsp;</label>
				<!--input type="submit" value="Update Information" class="button art-button" id="save-profile" /-->
				<input type="hidden" id="hidden-staffno" name="hidden_staffno" />
				<input type="hidden" id="line-manager" name="line_manager" value="" />
			</form>



			<br style="clear: both"/>
</div>
				<p style="padding: 3px; background-color: #8effab; border: 2px solid #64ff64">
				<span style="font-bold; color: #ff3300; font-size: 14px">*</span> Indicates ITS related data and may take longer to be updated by the Human Resource Department. To make amendments to the data, click on the text box and enter the correct information in the new text box provided. The amendments will be sent to the Human Resource Department. <b>To update the current information, click <input type="button" class="button art-button" value="Here..." onclick="javascript: saveProfileView();" /></a></b>
				</p>




</div></div></div></div>
