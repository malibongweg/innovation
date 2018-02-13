window.addEvent('domready',function() {

	$('month-select').addEvent('change',function() {
		$('data-div').set('html','');
		displayStatement();
	});

	var h = window.getSize().y - 380;
	$('table-header').setStyles({'height':parseInt(h) +'px'});
	$('data-div').setStyles({'height':parseInt(h-100)+'px'});

	getCredentials();
	displayStatement();
});

function getCredentials() {
	$('ajax-meals').setStyle('display','block');
	var id = $('uid').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=74&action=get_credentials&id='+id,
		method: 'get',
		noCache: true,
		timeout: 15000,
		onTimeout: function() {
			$('data-div').set('html','No data available...Please try later.');
			$('ajax-meals').setStyle('display','none');
			x.cancel();
		},
		onComplete: function(response) {
						if (parseInt(response) <= 0)
						{
							$('data-div').set('html','No data available...Please try later.');
							$('ajax-meals').setStyle('display','none');
						} else {
							var rec = response.split(';');
							$('mtitle').set('html','Current Transactions for '+rec[0]);
							$('show-balance').set('html','Current Balance: R'+rec[1]);
						}
		$('ajax-meals').setStyle('display','none');
		$('user-balance').setStyle('display','block');
		}
	}).send();
}

function displayStatement() {
	var id = $('uid').get('value');
	$('ajax-meals').setStyle('display','block');
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var mth = $('month-select').getSelected().get('value');
	var html = '<table style="width: 100%; position: relative; height: auto; border: 0px; table-layout: fixed" id="data-table">';
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=74&action=display_data&mth='+mth+'&id='+id,
		method: 'get',
		timeout: 15000,
		noCache: true,
		onTimeout: function() {
			$('data-div').set('html','No data available...Try again later.');
			x.cancel();
		},
		onComplete: function(response) {
				var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						if (parseInt(text) == -1)
						{
							$('data-div').set('html','No data available.');
							fnd = false;
						} else {
							var rec = text.split(';');
							var m = cnt % 2;
							if (m == 1)
							{
								var color = color1;
							} else {
								var color = color2;
							}
							html = html + '<tr>';
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[0] + '</td>';
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[1] + '</td>';
							html = html + '<td style="width: 40%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[3] + '</td>';
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: right">';
							html = html + rec[2] + '</td>';
							html = html + '</tr>';
							++cnt;
						}
					}
				});
		$('ajax-meals').setStyle('display','none');	
		html = html + '</table>';
		if (fnd == true)
				{
					$('data-div').set('html',html);
				}
		}
	}).send();
}