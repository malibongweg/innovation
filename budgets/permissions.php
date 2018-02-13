<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
$dbo =& JFactory::getDBO();
?>

<style type="text/css" media="screen">
p { font-family: verdana,arial;
	  font-size: 11px;
	  color: #000000;
}
p.heading {
	text-align: center; font-weight: bold; border: 2px solid #bebebe; background-color: #eeeeee; padding: 3px; margin: 3px 0 0 0;
}
td {
	border: 1px solid gray;
	background-color: #ececec;
	font-family: Tahoma,Verdana,Arial;
	font-size: 11px;
}
</style>

<input type="hidden" id="fac" value="<?php echo $_GET['fac']; ?>" />
<input type="hidden" id="empno" value="<?php echo $_GET['empno']; ?>" />
<input type="hidden" id="lg" value="<?php echo $_GET['lg']; ?>" />

<div id="ajax-budget" style="position: absolute; top: 0px; left: 0px; width: auto; height: auto; background-color: #bddeff; border: 2px solid #6eb7ff; padding: 2px">
<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading...
</div>
<div id="ajax-budget-save" style="display: none; position: absolute; top: 0px; left: 0px; width: auto; height: auto; background-color: #bddeff; border: 2px solid #6eb7ff; padding: 2px">
<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;<span id="msg">Saving assignment...</span>
</div>
<p class="heading">BUDGET COST CENTRE ALLOCATION</p>
<p>Click on checkbox(es) to assign cost centre(s) or uncheck to remove assignment. Click on 'Save' button when done.</p>
<p><input type="button" value="Save" id="save-assignment" /></p>
<div id="centres" style="position: relative; width: auto; height: auto">
</div>


<script type="text/javascript">
window.addEvent('domready',function(){

	$('save-assignment').addEvent('click',function(){
		var lg = $('lg').get('value');
		saveAssignment(lg);
	});

	window.parent.$j.colorbox.resize({ 'height': 400, 'width': 600 });
	$('save-assignment').set('disabled',true);

	var fac = $('fac').get('value');
	var html = '<table border="0" width="100%">';
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=93&action=load_centres&fac='+fac,
		method: 'get',
		noCache: true,
		onComplete: function(response){
				var row = json_parse(response,function(data,text){
					if (typeof text == 'string') {
						var r = text.split(';');
						html = html + '<tr>';
						html = html + '<td width="90%">'+r[1]+' - '+r[2]+'</td>';
						html = html + '<td width="10%"><input type="checkbox" name="x_'+r[1]+'" id="'+r[1]+'" </td>';
						html = html + '</tr>';
					}
				});
				html = html + '</table>';
				var lg = $('lg').get('value');
				$('centres').set('html',html);
						var y = new Request({
							url: 'index.php?option=com_jumi&fileid=93&action=load_assignment&lg='+lg,
							noCache: true,
							method: 'get',
							onComplete: function(response){
									var row = json_parse(response,function(data,text){
										if (typeof text == 'string') {
											if (parseInt(text) == 0)	{
											} else {
												$$('input[type=checkbox]').each(function(el){
													if (el.id == text)	{
														el.checked = true;
													}
												});
											}
										}
									});
								$('save-assignment').set('disabled',false);
								$('ajax-budget').setStyle('display','none');
							}
						}).send();
		}
	}).send();

});

function saveAssignment(lg){
	$('ajax-budget-save').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=93&action=clear_assignment&lg='+lg,
		noCache: true,
			method: 'get',
			async: false,
			onComplete: function(){
				$$('input[type=checkbox]:checked').each(function(el){
						var y = new Request({
							url: 'index.php?option=com_jumi&fileid=93&action=save_assignment&cc='+el.id+'&lg='+lg,
							async: false,
							method: 'get',
							noCache: true,
							onComplete: function(){
									$('msg').set('html','Saving assignment '+el.id);
							}
						}).send();
				});
				$('ajax-budget-save').setStyle('display','none');
				if (confirm('Allocations saved. Close window?'))
				{
					window.parent.$j.colorbox.close();
					window.parent.loadCostCodes(lg);
				}
		}
	}).send();
}
</script>
