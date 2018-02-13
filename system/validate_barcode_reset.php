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
	 body{font: 11px verdana,sans-serif,arial; color: #000000; text-align:center; background: #ffffff}
    </style>

<input type="hidden" id="st" value="<?php echo $_GET['stno']; ?>" />

<script type="text/javascript">

window.addEvent('domready',function() {
	window.parent.$j.colorbox.resize({ width: '250px', height: '120px'});

	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=71&stno='+$('st').get('value'),
		noCache: true,
		timeout: 15000,
		onTimeout: function() {
			$('msg').set('html','<img src="/images/krider.gif" width="160" height="24" border="0" alt=""><br />DB Timeout Error.');
			x.cancel();
		},
		method: 'get',
		onComplete: function(response) {
			if (parseInt(response) == -1)
			{
				$('msg').set('html','<img src="/images/krider.gif" width="160" height="24" border="0" alt=""><br />Oracle Error.');
			} else {
				$('msg').set('html','<img src="/images/krider.gif" width="160" height="24" border="0" alt=""><br />DONE!!!');
			}
		}
	}).send();

});

</script>

<div style="position: relative; text-align: center" id="msg">
	<img src="/images/krider.gif" width="160" height="24" border="0" alt=""><br />
	Validating Request...Please Wait.
</div>