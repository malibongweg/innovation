window.addEvent('domready',function() {

	$('month-select').addEvent('change',function() {
		$('data-div').set('html','');
		displayStatement();
	});

	var h = window.getSize().y - 380;
	$('table-header').setStyles({'height':parseInt(h) +'px'});
	$('data-div').setStyles({'height':parseInt(h-100)+'px'});

	
	checkData();
	
});

function checkData(){
	var lg = $('uid').get('value');
	var rm = new RegExp('^[0-9]+$');
			var r = rm.exec(lg);
				if (r != null)
				{
					displayStatement(lg);
				} else {
					var x = new Request({
						url: 'index.php?option=com_jumi&fileid=76&action=check_data&lg='+lg,
							method: 'get',
							noCache: true,
							onComplete: function(response){
							if (parseInt(response) == 0)
							{
								alert('Application only available after login name/staff number validation.'+'\n'+'Verify details under the Utilites menu option.');
								window.location.href='/index.php';
							} else {
								displayStatement(response);
							}
						}
					}).send();
				}
}

function displayStatement(id) {
	$('ajax-copy').setStyle('display','block');
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var mth = $('month-select').getSelected().get('value');
	var html = '<table style="width: 100%; position: relative; height: auto; border: 0px; table-layout: fixed" id="data-table">';
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=76&action=display_data&mth='+mth+'&id='+id,
		method: 'get',
		timeout: 15000,
		noCache: true,
		onTimeout: function() {
			$('ajax-copy').setStyle('display','none');
			$('data-div').set('html','Data no available...try again later.');
			x.cancel();
		},
		onComplete: function(response) {
				var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						if (parseInt(text) == -1)
						{
							$('ajax-copy').setStyle('display','none');
							$('data-div').set('html','Error connecting to Photocopy system.');
							fnd = false;
						}
						else if (parseInt(text) == -2)
						{
							$('ajax-copy').setStyle('display','none');
							$('data-div').set('html','Unable to locate user id.');
							fnd = false;
						}
						else if (parseInt(text) == -3)
						{
							$('ajax-copy').setStyle('display','none');
							$('data-div').set('html','No data available...');
							fnd = false;
						}else {
							var rec = text.split(';');
							var m = cnt % 2;
							if (m == 1)
							{
								var color = color1;
							} else {
								var color = color2;
							}
							html = html + '<tr>';
							html = html + '<td style="text-align: left; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[0] + '</td>';
							html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[3] + '</td>';
							html = html + '<td style="width: 40%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[2] + '</td>';
							html = html + '<td style="width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: right">';
							html = html + rec[1] + '</td>';
							html = html + '</tr>';
							++cnt;
						}
					}
				});
		$('ajax-copy').setStyle('display','none');	
		html = html + '</table>';
		if (fnd == true)
				{
					$('data-div').set('html',html);
				}
		}
	}).send();
}