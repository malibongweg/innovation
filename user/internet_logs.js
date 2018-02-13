window.addEvent('domready',function(){

	var h = window.getSize().y ;
	$('squid-data').setStyles({'height':parseInt(h) +'px'});

	$('mth').addEvent('change',function(){
		displayLog();
	});

	$('dy').addEvent('change',function(){
		displayLog();
	});

	$('credit-history').addEvent('click',function(){
		var login = $('login-name').get('value');
		$('credit-lnk').set('href','index.php?option=com_jumi&fileid=124&tmpl=component&login='+login);
		$('credit-lnk').click();
	});

	popMonthsAndYears();
	displayLog();

});

function popMonthsAndYears(){
	var d = new Date();
	var n = parseInt(d.getMonth()) + 1;
	var b =parseInt(d.getDate());
	$('mth').empty();
	for (var i=1;i<13;++i ){
		if (i == n)	{
			new Element('option',{ 'value': i,'text':i,'selected':true}).inject($('mth'));
		} else {
			new Element('option',{ 'value': i,'text':i}).inject($('mth'));
		}
	}
	$('dy').empty();
	for (var i=1;i<32;++i ){
		if (i == b)	{
			new Element('option',{ 'value': i,'text':i,'selected':true}).inject($('dy'));
		} else {
			new Element('option',{ 'value': i,'text':i}).inject($('dy'));
		}
	}
}

function displayLog(){
	var color1 = '#e1e8f4';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var mth = $('mth').getSelected().get('value');
	if (parseInt(mth) < 10){
		mth = '0' + mth;
	}
	var day = $('dy').getSelected().get('value');
	var usr = $('login-name').get('value');
	$('squid-data').set('html','');
	$('squid-ajax').setStyle('display','block');
	var html = '<table border="0" style="table-layout: fixed; width: 100%">';
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=111&action=display_log&mth='+mth+'&day='+day+'&user='+usr,
		//	url: 'index.php?option=com_jumi&fileid=111&action=display_log&mth='+mth+'&day='+day+'&user=hamersteend',
			method: 'get',
			noCache: true,
			onComplete: function(response){
					var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						if (parseInt(text) == -1){
							$('squid-data').set('html','No entries to display...');
							fnd = false;
						} else {
							var m = cnt % 2;
										if (m == 0)
										{
											var color = color1;
										} else {
											var color = color2;
										}
							var r = text.split(';');
							html = html + '<tr>';
							html = html + '<td width="15%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+r[0]+'</td>';
							html = html + '<td width="55%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+r[1]+'</td>';
							html = html + '<td width="15%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+r[2]+'</td>';
							html = html + '<td width="15%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">R '+r[3]+'</td>';
							html = html +'</tr>';
							++cnt;
						}
					}
				});
				html = html +'</table>';
				$('squid-ajax').setStyle('display','none');
				if (fnd == true){
					$('squid-data').set('html',html);
				}
		}
	}).send();
}