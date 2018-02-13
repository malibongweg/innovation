<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
?>
<style type="text/css">
	body { font-family: Tahoma,Arial; font-size: 12px; color: #000000; }

</style>
<input type="hidden" id="journal-no" value="<?php echo $_GET['journal']; ?>" />

<div id="journal-busy" style="position: relative; display: none; width: auto; height: auto; background-color: #006fdd; border: 2px solid #004080; color: #ffffff; padding: 2px">
	<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;<span id="genmsg">Loading data from ITS...Please wait.</span>
</div>

<div id="prn-result" style="position: relative; display: none"><input type="button" id="button-result" value="Print Result" />&nbsp;
<input type="button" value="Close Window" id="result-close" />
</div>
<div id="journal-header" style="position: relative; display: none; width: 100%; height: auto; padding: 2px">
	<input type="button" id="button-print" value="Print Journal" />&nbsp;
	<input type="button" id="button-process" value="Process Journal" />&nbsp;
	<input type="button" id="button-close" value="Close Window" /><br />
	<span style="padding: 3px; font-weight: bold">Meals Journal Import  (<?php echo Date('d-m-Y'); ?>)<br />Journal Entry:&nbsp;<?php echo $_GET['journal']; ?></span>
	<table border="0" width="100%">
		<tr>
			<td width="30%" style="background-color: #006fdd; font-weight: bold; color: #ffffff; border: 2px solid #0052a4; padding: 1px">STUDENT#</td>
			<td width="40%" style="background-color: #006fdd; font-weight: bold; color: #ffffff; border: 2px solid #0052a4; padding: 1px">POST DATE (MM/DD/YYYY)</td>
			<td width="30%" style="background-color: #006fdd; font-weight: bold; color: #ffffff; border: 2px solid #0052a4; padding: 1px">AMOUNT</td>
		</tr>
	</table>
	<div id="journal-data" style="position: relative; width: 100%; height: auto; padding: 2px"></div>
</div>

<script type="text/javascript">
	window.addEvent('domready',function(){
		
		window.parent.$j.colorbox.resize({ 'height': 600, 'width': 800 });
		$('journal-busy').setStyle('display','block');

		$('button-close').addEvent('click',function(){
			window.parent.$j.colorbox.close();
		});

		$('button-print').addEvent('click',function(){
			window.print();
		});

		$('button-process').addEvent('click',function(){
			var j = $('journal-no').get('value');
			processJournal(j);
		});

		$('button-result').addEvent('click',function(){
			printResult();
		});

		$('result-close').addEvent('click',function(){
			window.parent.$j.colorbox.close();
		});

		var j = $('journal-no').get('value');
		getJournal(j);

	});
function printResult(){
	window.print();
}

function getJournal(j){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=133&action=its_journal&journal='+j,
			method: 'get',
			noCache: true,
			onComplete: function(response){
				if (parseInt(response) ==-1){
					alert('Error connecting to ITS...Please try again.');
					window.parent.$j.colorbox.close();
				} else if (parseInt(response) == 0){
					$('button-process').set('disabled',true);
					$('journal-busy').setStyle('display','none');
					$('journal-header').setStyle('display','block');
					$('journal-data').set('html','No data found or invalid journal number.');
				} else {
						var html = '<table border="0" width="100%">';
						var row = json_parse(response,function(data,text) {
							if (typeof text == 'string') {
									var r = text.split(';');
									html = html + '<tr>';
									html = html + '<td width="30%" style="border: 1px solid #000000">'+r[0]+'</td>';
									html = html + '<td width="40%" style="border: 1px solid #000000">'+r[1]+'</td>';
									html = html + '<td width="30%" style="border: 1px solid #000000">'+r[3]+'</td>';
									html = html + '</tr>';
								}
						});
						html = html + '</table>';
						$('journal-data').set('html',html);
						$('journal-busy').setStyle('display','none');
						$('journal-header').setStyle('display','block');
				}
		}
	}).send();
}

function processJournal(j){
	$('journal-header').set('html','');
	$('genmsg').set('html','Processing....Please wait.');
	$('journal-busy').setStyle('display','block');
	$('prn-result').setStyle('display','none');
	var counter = 0;
	var col1 = '#cacaca';
	var col2 = '#ffffff';
	var col = '#ffffff';
	var cnt = 0;
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=133&action=its_journal2&journal='+j,
		method: 'get',
		noCache: true,
		onComplete: function(response){
				if (parseInt(response) ==-1){
					alert('Error connecting to ITS...Please try again.');
					window.parent.$j.colorbox.close();
				} else if (parseInt(response) == 0){
					$('button-process').set('disabled',true);
					$('journal-busy').setStyle('display','none');
					$('journal-header').setStyle('display','block');
					$('journal-data').set('html','No data found or invalid journal number.');
				} else {
						var html = '<span style="font-weight: bold; padding: 5px 0 5px 0">Meals Journal Import Results</span><br />';
						html = html + '<span style="font-weight: bold; padding: 5px 0 5px 0">Journal# '+j+'</span><br /><br />';
						var row = json_parse(response,function(data,text) {
							if (typeof text == 'string') {
									var r = text.split(';');
											
												var b = new Request({
												url: 'index.php?option=com_jumi&fileid=133&action=proc_journal&stno='+r[0]+'&pdate='+r[1]+'&ref='+r[2]+'&amt='+r[3]+'&usr='+r[4]+'&crd='+r[5],
												method: 'get',
												noCache: true,
												async: true,
												onRequest: function(){
													counter += 1;
												},
												onComplete: function(response){
														counter -= 1;
														cnt = cnt + 1; if ((cnt % 2) == 0){col = col1;} else {col = col2;}
														if (parseInt(response) < 0) {
															html = html + '<span style="color: #ff0000">Error updating entry '+r[0]+', manual intervention required. [Error Code: '+response+']</span><br />';
														} else {
															var q = response.split(';');
															html = html + '<span style="background-color: '+col+'"><b>['+q[0]+ ']</b>Credit Amount: '+q[1]+' Previous Balance: '+q[2]+' Updated Balance: '+q[3]+'</span><br />';
															$('journal-busy').setStyle('display','block');
															$('prn-result').setStyle('display','none');
															$('genmsg').set('html','Processing...'+r[0]+'&nbsp;&nbsp;&nbsp;<span style="color: #ffff00; font-weight: bold">(NB. Do NOT click outside this window or process will be ABORTED!)</span>');
															$('journal-header').set('html',html);
														}
															if (counter == 0){
																$('journal-busy').setStyle('display','none');
																$('prn-result').setStyle('display','block');
															} else {
																$('journal-busy').setStyle('display','block');
																$('prn-result').setStyle('display','none');
																$('genmsg').set('html','Processing...'+r[0]+'&nbsp;&nbsp;&nbsp;<span style="color: #ffff00; font-weight: bold">(NB. Do NOT click outside this window or process will be ABORTED!)</span>');
																$('journal-header').set('html',html)
															}
												}
											}).send();
									
							}
						});
		}
	}
	}).send();
}

</script>
