<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
?>
<div id="ajax-budget" style="position: relative; width: auto; height: auto; display: block">
	<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Generating report...please wait.
</div>
<div id="report" style="position: relative; width: auto; height: auto">
</div>

<?php
echo "<input type='hidden' id='yr' value='".$_GET['cyear']."' />";
echo "<input type='hidden' id='cc' value='".$_GET['ccode']."' />";
echo "<input type='hidden' id='ac' value='".$_GET['action']."' />";
?>

<script type="text/javascript">

window.addEvent('domready',function(){
	
	window.parent.$j.colorbox.resize({ 'height': 600, 'width': 800 });

	var x = new Request({
							url: 'index.php?option=com_jumi&fileid=83&tmpl=component&action='+$('ac').get('value')+'&cyear='+$('yr').get('value')+'&ccode='+$('cc').get('value'),
							noCache: true,
							method: 'get',
							timeout: 1200000,
							onTimeout: function() {
								$('ajax-budget').set('html','Error generating report (Timeout). Please try again in a few minutes.');
								x.cancel();
							},
							onComplete: function(response) {
								$('report').set('html',response);
								$('ajax-budget').setStyle('display','none');
							}
					}).send();
});

</script>

