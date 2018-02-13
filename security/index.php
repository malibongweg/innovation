<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addScript("scripts/security/security.js");
$doc->addScript("scripts/json.js");
$doc->addScript('scripts/fav_apps.js');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$dbo->setQuery("call proc_pop_application('User Maintenance')");
$dbo->query();
?>

<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="User Management" />
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
       User Management</h3>
        </div>
            <div class="art-blockcontent">
            <div class="art-blockcontent-body">
<!------- -->

<div style="width: auto; height: auto; display: block; margin-bottom: 5px">
<!--div style="position: relative; padding: 3px 0 0 5px; width: auto; height: auto; border: 1px solid #C0C6BA; background-color: #E7E9E5; -moz-border-radius: 5px; border-radius: 5px"-->
<fieldset class="input_fieldset">
	Search<br />
	<input type="text" size="30" name="srch" id="srch" maxlength="30" class="input_field" />
	<input type="button" id="getUser" class="button art-button" value="Get User Details">
	<div id="list-users" style="width: 100px; height: auto; display: none">
		<select name="userList" id="userList" size="10" class="input_select" style="width: 300px">
		</select>
	</div>
</fieldset>
</div>

<div id="ajax-loader" style="width: auto; height: auto; display: none">
<img src="images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Searching...
</div>

<div id="user-details" class="main-div" style="display: none">
	<div class="main-header"><strong><span id="del-heading">User Details</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<table border="0" width="100%">
<tr>
<td width="100%"><strong>User Id</strong><br /><input type="text" name="uid" id="uid" size="5" readonly class="input_field" /></td>
</tr><tr>
<td width="100%"><strong>Login Name</strong><br /><input type="text" name="lname" id="lname" size="50" readonly class="input_field"/></td>
</tr><tr>
<td width="100%"><strong>User Name</strong><br /><input type="text" name="uname" id="uname" size="50" readonly class="input_field"/></td>
</tr><tr>
<td width="100%"><strong>Email</strong><br /><input type="text" name="email" id="email" size="50" readonly class="input_field"/></td>
</tr>
<tr>
<td width="100%"><span id="crp" style="display: none"><strong>ID Card Re-Print Blocked?</strong>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="card_reprint" id="card-reprint"/></span></td>
</tr>
</table>

<span style="margin-top: 3px">&nbsp;</span>

<!--Security Levels-->
<div id="user-details" class="main-div">
	<div class="main-header"><strong><span id="del-heading">Security Levels</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	<form name="security_levels" id="security_levels" method="get" action="index.php">
	<input type="hidden" name="user_id" id="user_id" />
	<input type="hidden" name="option" value="com_jumi" />
	<input type="hidden" name="fileid" value="4" />
	<input type="hidden" name="func" value="updateSecurity" />
	<table border="0" width="100%">
	<?php
		$sql = sprintf("select id,title from #__usergroups where lower(title) not in ('author','editor','registered','publisher','manager','public','administrator') order by id");
		$dbo->setQuery($sql);
		$row = $dbo->loadObjectList();
		foreach($row as $item) {
			echo '<tr>';
			echo '<td width="5%" style="padding-left: 5px">';
			echo '<input type="checkbox" name="chk'.$item->id.'" id="chk'.$item->id.'">';
			echo '</td>';
			echo '<td width="95%">'.$item->title.'</td>';
			echo '</tr>';
		}
	?>
	<tr>
	<td width="100%" colspan="2">
	<input type="submit" class="button art-button" value="Update Security Levels" />
	</td>
	</tr>
	</table>
	</form>
</div>
</div>


<span style="margin-top: 3px">&nbsp;</span>


<!----Faculty---->
<div  id="div-mas-admin" style="width: auto; height: auto; display: none">
<!--div id="div-mas-admin" style="width: 100%; height: auto: position: relative; display: none"-->
<form name="fac_form" id="fac-form" method="get" action="index.php">
<input type="hidden" name="fuid" id="fuid" />
	<input type="hidden" name="option" value="com_jumi" />
	<input type="hidden" name="fileid" value="4" />
	<input type="hidden" name="func" value="setfac" />
<!--Faculty-->
<div id="user-details" class="main-div">
	<div class="main-header"><strong><span id="del-heading">MAS Administration</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<table border="0" width="100%">
	<tr>
		<td width="100%"><strong>Faculty Officer</strong><br />
			<input type="checkbox" name="fac_officer" id="fac-officer">
			<div id="fac-loader" style="width: auto; height: auto; display: none">
				<img src="scripts/ajax-loader.gif" width="160" height="24" />
			</div>
		</td>
	</tr>
</table>
<div id="div-faculty" style="position: relative; width: 100%; height: auto; display: none">
<table border="0" width="100%">
	<tr>
		<td width="100%"><strong>Faculty</strong><br />
			<select name="faculty" id="faculty" class="input_select" size="1" style="width: 50%">
			</select>&nbsp;<input type="submit" class="button art-button" value="Update Faculties" />
		</td>
	</tr>
</table>	
</div>
</div>
</form>
</div>

<span style="margin-top: 5px">&nbsp;</span>

<!--Fleet Access-->
<div  id="div-fleet" class="main-div" style="display: none">
	<div class="main-header"><strong><span id="del-fleet">Fleet Management Access</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
<table border="0" width="100%">
	<tr>
		<td width="100%"><strong>Grant/Revoke Access</strong><br />
			<input type="checkbox" name="fleet_checkbox" id="fleet-checkbox">
		</td>
	</tr>
</table>
<div id="div-fleet-access" style="position: relative; width: 100%; height: auto; display: none">
<table border="0" width="100%">
	<tr>
		<td width="100%"><strong>Assigned Campus</strong><br />
			<select name="fleet_campus" id="fleet-campus" class="input_select" size="5" multiple style="width: 50%">
			</select>&nbsp;<input type="button" id="save-fleet" class="button art-button" value="Update Access" />
		</td>
	</tr>
</table>	
</div>
</div>

<!--ITS Claim Systems-->
<div  id="div-claims" class="main-div" style="display: none">
	<div class="main-header"><strong><span id="del-claims">ITS Claim Systems</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
		<div id="div-claims-access" style="position: relative; width: 100%; height: auto; display: block">
		<table border="0" width="100%">
			<tr>
				<td width="100%"><strong>Assigned System</strong><br />
					<select name="claim_system_name" id="claim-system-name" class="input_select" size="1" style="width: 50%">
					</select>&nbsp;<input type="button" id="save-claim-system" class="button art-button" value="Update System Access" />
				</td>
			</tr>
		</table>	
		</div>
</div>
</div></div></div></div>