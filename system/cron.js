var t
window.addEvent('domready',function() {

	$('mth').addEvent('change',function() {
		display_log();
	});

	$('id-filter').addEvent('click',function(){
		$('id-filter').set('value','');
		//display_log();
	});

	$('srch-button').addEvent('click',function(){
		show_filter();
	});

	display_log();

});

function show_filter() {
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	$('display-log').set('html','');
	var html = '<table border="0" width="100%">';
	var fnd = true;
	$('ajax').setStyle('display','block');
	var mth = $('mth').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=36&mth='+mth+'&action=show_log_filter&filter='+$('id-filter').get('value')+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								$('display-log').set('html','No data to display.');
								fnd = false;
							} else {
								var d = text.split(';');
								var m = cnt % 2;
										if (m == 0)
										{
											var color = color1;
										} else {
											var color = color2;
										}
								html = html + '<tr>';
								html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[0]+'</td>';
								html = html + '<td style="width: 25%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[1]+'</td>';
								html = html + '<td style="width: 45%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[2]+'</td>';
								html = html + '<td style="width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[3]+'</td>';
								html = html + '</tr>';
								++cnt;
							}
						}
						
			});
			html = html + '</table>';
						if (fnd == true)
						{
							$('display-log').set('html',html);
						}
			$('ajax').setStyle('display','none');
		}
	}).send();
}

function display_log() {
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	$('display-log').set('html','');
	var html = '<table border="0" width="100%">';
	var fnd = true;
	$('ajax').setStyle('display','block');
	var mth = $('mth').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=36&mth='+mth+'&action=show_log&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								$('display-log').set('html','No data to display.');
								fnd = false;
							} else {
								var d = text.split(';');
								var m = cnt % 2;
										if (m == 0)
										{
											var color = color1;
										} else {
											var color = color2;
										}
								html = html + '<tr>';
								html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[0]+'</td>';
								html = html + '<td style="width: 25%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[1]+'</td>';
								html = html + '<td style="width: 45%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[2]+'</td>';
								html = html + '<td style="width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[3]+'</td>';
								html = html + '</tr>';
								++cnt;
							}
						}
						
			});
			html = html + '</table>';
						if (fnd == true)
						{
							$('display-log').set('html',html);
						}
			$('ajax').setStyle('display','none');
		}
	}).send();
}