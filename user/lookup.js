window.addEvent('domready',function(){
	$('clr').addEvent('click',function(){
		clearFields();
		$('student-number').focus();
	});
	
	$('student-lookup').addEvent('submit',function(e){
		e.stop();
		$('lookup-ajax').setStyle('display','block');
		var color1 = '#a5badc';
		var color2 = '#FFFFFF';
		var cnt = 1;
		$('lookup-data').set('html','');
		$('lookup-data').setStyle('display','block');
		html = '<table width="100%" border="0" style="table-layout: fixed">';
		html = html +'<tr>';
		html = html + '<td style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>STUDENT#</strong></td>';
		html = html + '<td style="width: 25%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>SURNAME</strong></td>';
		html = html + '<td style="width: 25%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>NAME</strong></td>';
		html = html + '<td style="width: 15%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>ID#</strong></td>';
		html = html + '<td style="width: 15%;height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>DOB</strong></td>';
		html = html +'</tr>';

		 var x = new Request({
			url: 'index.php?option=com_jumi&fileid=79&action=student_lookup',
				noCache: true,
				method: 'post',
				data: this,
				onComplete: function(response){
					var row = json_parse(response,function(data,text){
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								html = html + '<tr><td width="100%">No results found.</td></tr>';
								$('lookup-ajax').setStyle('display','none');
							} else {
								var r = text.split(';');
								var m = cnt % 2;
										if (m == 0)
										{
											var color = color1;
										} else {
											var color = color2;
										}
								html = html + '<tr><td width="20%" style="background-color: '+color+'">'+r[0]+'</td>';
								html = html + '<td width="25%" style="background-color: '+color+'">'+r[1]+'</td>';
								html = html + '<td width="25%" style="background-color: '+color+'">'+r[2]+'</td>';
								html = html + '<td width="15%" style="background-color: '+color+'">'+r[3]+'</td>';
								html = html + '<td width="15%" style="background-color: '+color+'">'+r[4]+'</td></tr>';
							}
						}
						++cnt;
					});
					html = html + '</table>';
					$('lookup-data').set('html',html);
					$('lookup-ajax').setStyle('display','none');
			}
		}).send();
	});

	clearFields();

});

function clearFields(){
	$('lookup-data').setStyle('display','none');
	$('lookup-ajax').setStyle('display','none');
	$('student-number').set('value','');
	$('student-surname').set('value','');
	$('student-firstname').set('value','');
	$('student-idno').set('value','');
	$('student-dob').set('value','');
	$('student-number').focus();
}