<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$dbo->setQuery("call proc_pop_application('SMS Monitor')");
$dbo->query();
?>
<a href="#" class="modalizer" id="openGal"></a>
<script type="text/javascript">

window.addEvent('domready',function(){
	setTimeout('showLogs()',2500);
});

function showLogs(){
	$('redir').setStyle('display','none');
	$('openGal').set('href','http://10.47.2.131');
	$('openGal').click();
}
</script>

<div id="redir" style="top: 0px; left: 0px; width: auto; height: auto; position: absolute; z-index: 500; background-color: #ffffff; padding: 5px 5px; border: 3px solid #aeaeae">
<img src="images/kit-ajax.gif" width="32" height="11" border="0" alt=""><br />
Redirecting...Please wait.
</div>