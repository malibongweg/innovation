<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addStyleSheet("scripts/idphoto/card.css","text/css","print");
$doc->addStyleSheet("scripts/idphoto/display.css","text/css","screen");
?>
<div class="noprint" style="position: relative; border: 2px solid #0074e8; background-color: #97cbff; color: #000000; font-weight: bold; text-align: center">
<img src="/images/auth.gif" width="100" height="15" border="0" alt="" style="vertical-align: middle">...ENCODING...
</div>

<div class="nodisplay">
<span id="magstrip">~2;<?php echo $_GET['mag']."?\r\n";?></span>
</div>





<script type="text/javascript">

	window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({ 'height': 150, 'width': 200 });
	setTimeout('startPrint()',3000);
});

function startPrint(){
	window.print();
	setTimeout('closeScreen()',10000);
	window.parent.$j.colorbox.close();
}
</script>