<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->query();
?>

<script type="text/javascript">
window.addEvent('domready',function() {
var numbers = [8,48,49, 50, 51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105];
	$$('.numeric').each(function(item) {
		item.addEvent('keydown', function(key) {
			for (i = 0; i < numbers.length; i++) {
				if(numbers[i] == key.code) {
					return true;
				}
			}
			return false;
		});
	});

	$('sync').addEvent('click',function() {
		var st = $('studentno').get('value');
		if (st.length < 9)
		{
			alert('Invalid student number.');
			$('studentno').focus();
		} else {
		$('validate_student').set('href','index.php?option=com_jumi&fileid=72&tmpl=component&stno='+st);
		$('validate_student').click();
		}
	});

	$('studentno').addEvent('click',function() {
		$('studentno').set('value','');
	});

	$('studentno').set('value','');
	$('studentno').focus();

});

function resetAll() {
	$('studentno').set('value','');
	$('studentno').focus();
}

</script>

<a href="#" class="modalizer" id="validate_student"></a>
<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
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
      Barcode Synchronization</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div class="main-div" id="table-header">
	<div class="main-header"><strong>Please enter valid student#</strong></div>
		<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
		<input type="text" name="stno" id="studentno" size="9" class="numeric" maxlength="9" />&nbsp;
		<input type="button" id="sync" class="button art-button" value="Re-Synch" />

</div>

</div></div></div></div>
