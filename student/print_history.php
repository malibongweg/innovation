<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
?>
<input type="hidden" id="html-value" size="1000" value='<?php echo $_GET['html_v']; ?>' />

<div id="html-div" style="position: relative; width: 100%; height: 100%">
</div>
<input type="hidden" id="student-no" value="<?php echo $_GET['stno'];?>"/>

<script type="text/javascript">

window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({ 'height': 600, 'width': 800 });

	var sname = '';
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=34&action=getStudentDetails&stno='+$('student-no').get('value'),
		noCache: true,
		method: 'get',
		async: false,
		onComplete: function(response){
		 sname = response;
	}
	}).send();
	var h = 'Student History Report For '+sname+'&nbsp;Year: <?php echo $_GET["yr"]; ?><br />';
	var hh = window.parent.html;
	var loc_html = h + hh;
	$('html-div').set('html',loc_html);
	window.print();
});

</script>