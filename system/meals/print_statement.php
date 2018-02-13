<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script src="/media/system/js/mootools-core.js" type="text/javascript"></script>
<style type="text/css">
	body { font-family: Tahoma, Arial; font-size: 12px; }
</style>
</head>
 <body>

<h2>Meals Statement</h2>&nbsp;<input type="button" value="Print Transactions" onclick="javascript: window.print();" /><br />
<div id="s-content"></div>

 </body>
</html>

<script type="text/javascript">
	window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({ 'width':800, 'height':500 });
	var t = window.parent.$('mtitle').get('html');
	var hd =	window.parent.$('table-header2').get('html');
	var html =	window.parent.$('data-div').get('html');
	$('s-content').set('html',t+hd+html);
})
</script>
