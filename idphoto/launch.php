<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
?>
<a id="launch_photo" href="/index.php?option=com_jumi&fileid=125&tmpl=component" class="modalizer"></a>

<div id="idphoto_busy" style="position: relative; display: block">
<img src="images/kit-ajax.gif" width="32" height="11" border="0" alt=""><br />
<span style="font-family: sans-serif arial; font-weight: bold; font-size: 14px">Loading...</span>
</div>

<script type="text/javascript">

window.addEvent('domready',function(){
	setTimeout('busy()',1000);
});
function busy(){
	$('launch_photo').click();
}
</script>

