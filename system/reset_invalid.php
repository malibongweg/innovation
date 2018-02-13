<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
?>
 <style>
     html,body{margin:0;padding:0; height: 100%}
	 body{font: 11px verdana,sans-serif,arial; color: #000000; text-align:center; background-image: url(/images/reset_button.png); background-repeat: no-repeat }
	 .numeric {
	background-image: url(/images/field_bg.png);
	background-repeat: repeat-x;
	border: 1px solid #a8a8a8;
	height: 18px;
	line-height: 16px;
	font-size: 13px;
	padding: 2px;
	font-weight: bold;
	color: #000000;
	opacity: 0.7;
	filter: alpha(opacity=70);
	-moz-opacity:0.7;
	-khtml-opacity: 0.7;
}

    </style>
<input type="hidden" id="rec-id" value="<?php echo $_GET['id']; ?>" />
<div style="position: relative; text-align: center">
<p>Account modification requested. Please make sure that you enter a valid account/student number.</p>

	<strong>Enter Valid Account#</strong><br />
	<input type="text" name="acc" id="accountno" size="9" maxlength="9"  class="numeric"/><br />
	<input type="button" value="Modify Now" id="modify-button" />&nbsp;
	<input type="button" value="Cancel" id="cancel-button" />

</div>





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

		window.parent.$j.colorbox.resize({ width: '280px', height: '260px'});

		$('cancel-button').addEvent('click',function() {
			window.parent.$j.colorbox.close();
		});

		$('modify-button').addEvent('click',function() {
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=63&action=modify_account&id='+$('rec-id').get('value')+'&acc='+$('accountno').get('value'),
				noCache: true,
				method: 'get',
				onComplete: function(response) {
					if (parseInt(response) == -1)
					{
						alert('Error resetting record.');
					} else {
						window.parent.displayLog();
						window.parent.$j.colorbox.close();
					}
				}
			}).send();
		});

		$('accountno').focus();
});
</script>