window.addEvent('domready',function(){
	
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

	$('get-receipt').addEvent('click',function(){
		$('get-receipt').set('disabled',true);
		var rec = $('receipt-no').get('value');
		if (rec.length < 5)
		{
			alert('Invalid receipt no. To short.');
			$('get-receipt').set('disabled',false);
		} else {
			$('receiptWND').set('href','index.php?option=com_jumi&fileid=39&tmpl=component&receipt='+rec);//&action=get_receipt&receipt='+rec+'&dt='+new Date().getTime());
			$('receiptWND').click();
			$j.colorbox.resize({width: 400, height: 400});
		}
		$('get-receipt').set('disabled',false);
	});

	$('receipt-no').addEvent('focus',function(){
		$('receipt-display').setStyle('display','block');
		$('receipt-no').set('value','');
	});

	$('display-month').addEvent('change',function() {
		displayActivity();
	});

	var h = window.getSize().y - 380;
	$('table-header').setStyles({'height':parseInt(h) +'px'});
	$('receipt-display').setStyles({'height':parseInt(h-100)+'px'});

	displayActivity();

});

function displayActivity(){
	var fnd = true;
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var html = '<table border="0" width="100%" style="table-layout: fixed">';
	$('ajax1').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=38&action=show_activity&id='+$('uname').get('value')+'&mth='+$('display-month').getSelected().get('value')+'&dt='+new Date().getTime(),
		//url: 'index.php?option=com_jumi&fileid=38&action=show_activity&id=212084089&mth='+$('display-month').getSelected().get('value')+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response){
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					if (parseInt(text) == -1)
					{
						$('receipt-display').set('html','No data to display.');
						fnd = false;
					} else {
						var r = text.split(';');
						var rec = text.split(';');
							var m = cnt % 2;
							if (m == 0)
							{
								var color = color1;
							} else {
								var color = color2;
							}

						html = html + '<tr>';
						if (parseInt(r[5]) == 2)
						{
							html = html + '<td style="width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000" value="'+r[7]+' onclick="rstTrans('+r[7]+')" </td>'; 
						} else {
							html = html + '<td style="width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">&nbsp;</td>'; 
						}
						html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+r[1]+'</td>';
							switch(parseInt(r[3])) {
								case 33: html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">PRINTING</td>'; break;
								case 212: html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">PRINTING</td>'; break;
								case 224: html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">PHOTO COPY</td>'; break;
								case 121: html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">PHOTO COPY</td>'; break;
								case 190: html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">INTERNET</td>'; break;
								case 11111: html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">BARCODE UPDATE(COPIES)</td>'; break;
								case 11112: html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">BARCODE UPDATE(MEALS)</td>'; break;
								default: html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">UNKNOWN</td>'; break;
							}
						html = html + '<td style="width: 25%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+r[4]+'</td>';
						switch(parseInt(r[5])){
							case 0: html = html + '<td style="width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">Busy</td>'; break;
							case 1: html = html + '<td style="width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">OK</td>'; break;
							case 2: html = html + '<td style="width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">Error</td>'; break;
						}
						html = html + '</tr>';
						++cnt;
					}
				}
			});
			if (fnd == true)
			{
				html = html + '</table>';
				$('receipt-display').set('html',html);
			}
			$('ajax1').setStyle('display','none');
			$('receipt-display').setStyle('display','block');
		}
	}).send();
}

function rstTrans(id){
	if (confirm('Reset requested. Continue?'))
	{
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=38&action=rst&id='+id+'&dt='+new Date().getTime(),
			method: 'get',
			onComplete: function(response){
				if (parseInt(response) == 1)
				{
					displayActivity();
				} else {
					alert('Error resetting transaction. Please try again later.');
					displayActivity();
				}
			}
		}).send();
	}
}