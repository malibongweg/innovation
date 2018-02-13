<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
?>
<input type="hidden" id="html" value="<?php echo $_GET['html']; ?>" />
<div id="html" style="position:relative; width: 100%; height: 100%">

</div>

<script type="text/javascript">

window.addEvent('domready',function(){
	$('html').set('html',$('html').get('value');
});

</script>