var html;
window.addEvent('domready', function() {
	var numbers = [8,48,49, 50, 51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105];
	$$('.numeric').each(function(item) {
		item.addEvent('keydown', function(key) {
			for (i = 0; i < numbers.length; i++) {
				if(numbers[i] == key.code) {
					return true;
				}
			}
			return false;
		});
	});

	$('get-info').addEvent('click',function(){
		display_info();
		//$('header').setStyle('display','none');
		display_results();
	});

	$('yr').addEvent('change',function(){
		display_results();
	});

	$('prn-history').addEvent('click',function(){
		var stno = $('stnumber').get('value');
		html = $('header').get('html');
		html = html + $('display-details').get('html');
		$('print-history').set('href','index.php?option=com_jumi&fileid=107&yr='+$('yr').get('value')+'&stno='+stno+'&tmpl=component&html_v="'+html+'"');
		$('print-history').click();
	});

	$('stnumber').addEvent('click',function(){
		$('stnumber').set('value','');
		/*$('display-history').setStyle('display','none');*/
	});
	/*var h = window.getSize().y - 380;
	$('display-history').setStyles({'height':parseInt(h) +'px'});
	$('display-details').setStyles({'height':parseInt(h-100)+'px'});*/


	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=34&action=whoami&uid='+$('sysid').get('value'),
			noCache: true,
			method: 'get',
			onComplete: function(response) {
				if (parseInt(response) == 0)
				{
					$('stnumber').set('disabled',true);
					$('get-info').setStyle('display','none');
					$('stnumber').set('value',$('uid').get('value'));
						display_info();
						//$('header').setStyle('display','none');
						display_results();
				}
		}
	}).send();

});
	


function display_info(){
	$('yr').empty();
	var uid = $('stnumber').get('value');
	if (uid.length <9)
	{
		alert('Looks like an invalid student number. Please try again.');
		return false;
	}

	var syear = parseInt(uid.substring(0,1));
	switch (syear)
	{
	case 1: syear = 19; break;
	case 2: syear = 20; break; 
	}
	var eyear = uid.substring(1,3);
	var reg_year = syear.toString() + eyear.toString();
	reg_year = parseInt(reg_year);
	new Element('option',{ 'data':'-1','text':'Complete History'}).inject($('yr'));
	var d = new Date();
	var y = d.getFullYear();
	for (var x=y; x >= reg_year; x--)
	{
		new Element('option',{ 'data':x,'text':x}).inject($('yr'));
	}
	/*$('display-history').setStyle('display','block');*/
}

function display_results(){
	//$('header').setStyle('display','none');
	var color1 = '#a5badc';
						var color2 = '#FFFFFF';
						var cnt = 1;
						var fnd = true;
	$('display-details').set('html','');

	var uid = $('stnumber').get('value');
	var syear = parseInt(uid.substring(0,1));
	switch (syear)
	{
	case 1: syear = 19; break;
	case 2: syear = 20; break; 
	}
	var eyear = uid.substring(1,3);
	var reg_year = syear.toString() + eyear.toString();
	reg_year = parseInt(reg_year);

	//var syear = uid.substring(0,2);
	//var eyear = uid.substring(1,3);
	//var reg_year = syear+eyear;//Stud reg year
	var d = new Date();
	var byr = d.getFullYear();//Begin year
	var html = '';
	var err = false;
	var stnumber = $('stnumber').get('value');
	var yr = $('yr').getSelected().get('data');
	if (yr > 0)
	{
					var html = '<table border="0" width="100%" cellspacing="0" style="table-layout: fixed">';
					var x = new Request({
						url: 'index.php?option=com_jumi&fileid=34&action=displayyear&stno='+stnumber+'&yr='+yr+'&dt='+new Date().getTime(),
						method: 'get',
						timeout: 5000,
						onTimeout: function() {
							$('display-details').set('html','Could not retrieve results.');
							x.cancel();
						},
						onComplete: function(response){					
							var row = json_parse(response,function(data,text) {
								if (typeof text == 'string') {
									if (parseInt(text) == -1)
									{
										$('display-details').set('html','No data found.');
										err = true;
									} else {
										var m = cnt % 2;
										if (m == 0)
										{
											var color = color1;
										} else {
											var color = color2;
										}
										var rec = text.split(';');
										html = html + '<tr><td width="10%" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[0]+"/"+rec[1]+'</td>';
										html = html + '<td width="10%" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[2]+'</td>';
										html = html + '<td width="15%" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[3]+'</td>';
										html = html + '<td width="7%" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[4]+'</td>';
										html = html + '<td width="15%" clas="twrap" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[5]+" " +rec[6]+'</td>';
										if (rec[12] == 'Y')
										{
											rec[7] = '';rec[8] = '';rec[9] = '';rec[10] = '';rec[11] = '';
										}
										html = html + '<td width="5%" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[7]+'</td>';
										html = html + '<td width="7%" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[8]+'</td>';
										html = html + '<td width="10%" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[9]+'</td>';
										html = html + '<td width="11%" class="twrap" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[10]+'</td>';
										html = html + '<td width="7%" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[11]+'</td></tr>';
										++cnt;
									}
								}
							});
							if (err == false)
							{
								html = html + "</table>";
								$('display-details').set('html',html);
							}
							cnt++;
							
						}
					}).send();
	} else if (yr < 0)
	{
				var html = '<table width="100%" border="0" style="table-layout: fixed">';
				var x = new Request({
						url: 'index.php?option=com_jumi&fileid=34&action=displayall&stno='+stnumber+'&byr='+byr+'&eyr='+reg_year+'&dt='+new Date().getTime(),
						method: 'get',
						timeout: 5000,
						onTimeout: function() {
							$('display-details').set('html','Could not retrieve results.');
							x.cancel();
						},
						onComplete: function(response) {
							var row = json_parse(response,function(data,text) {
								if (typeof text == 'string') {
									if (parseInt(text) == -1)
									{
										$('display-details').set('html','No data found.');
										err = true;
									} else {
										var m = cnt % 2;
										if (m == 0)
										{
											var color = color1;
										} else {
											var color = color2;
										}
										var rec = text.split(';');
										html = html + '<tr><td width="10%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[0]+"/"+rec[1]+'</td>';
										html = html + '<td width="10%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[2]+'</td>';
										html = html + '<td width="15%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[3]+'</td>';
										html = html + '<td width="7%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[4]+'</td>';
										html = html + '<td width="15%" class="twrap" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[5]+" " +rec[6]+'</td>';
										if (rec[12] == 'Y')
										{
											rec[7] = '';rec[8] = '';rec[9] = '';rec[10] = '';rec[11] = '';
										}
										html = html + '<td width="5%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[7]+'</td>';
										html = html + '<td width="7%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[8]+'</td>';
										html = html + '<td width="10%" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[9]+'</td>';
										html = html + '<td width="11%" class="twrap" style="border: 1px solid '+color+'; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[10]+'</td>';
										html = html + '<td width="7%" style="height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+rec[11]+'</td></tr>';
										++cnt;
									}
								}
							});
							if (err == false)
							{
								html = html + "</table>";
								$('display-details').set('html',html);
							}
							
						}
					}).send();
	}
}