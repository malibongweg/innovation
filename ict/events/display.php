<?php
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
echo "<input type='hidden' id='eid' value='".$_GET['id']."' />";
?>

<div id="display-div" style="width: auto; height: auto; font-family: Arial; font-size: 12px">

</div>



<script type="text/javascript">

window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize ({ 'width': 300, 'height': 200 });
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=67&action=display_details&id='+$('eid').get('value'),
			method: 'get',
			noCache: true,
			onComplete: function(response){
			$('display-div').set('html',response);
		}
	}).send();
});

</script>