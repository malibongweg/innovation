window.addEvent('domready',function(){
	var numbers = [8,9,46,48,49,50, 51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105,110,190];
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

	$('fee-data').set('html','');
	var lgname = $('lgname').get('value');

	var rm = new RegExp('^[0-9]+$');
	var lg = $('lgname').get('value');
		var r = rm.exec(lg);
			if (r != null)
				{
					$('srch').setStyle('display','none');
					showFeeAccount(lgname);
				}

	$('display-account').addEvent('click',function(){
		var stdno = $('stdno').get('value');
		$('sid').set('html',stdno);
		showFeeAccount(stdno);
	});

});

function showFeeAccount(account){
	var html = '';
	var balance = 0;
	var cyr = $('cyr').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=91&action=fee_account&acc='+account+'&yr='+cyr,
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			var bf = response;
			html = '<table border="0" width="100%" >';
			html = html + '<tr>';
			html = html + '<td colspan="3" width="70%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #9b9b9b; font-weight: bold">Brought Foward Balance</td>';
			if (bf > 0)
			{
				html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff; text-align: right">'+bf+'</td>';
				html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">&nbsp;</td>';
			} else {
				html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">&nbsp;</td>';
				html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff; text-align: right">'+bf+'</td>';
			}
			html = html + '</tr></table>';
			balance = parseFloat(bf);
		}
	}).send();


	html = html + '<table border="0" width="100%" >';
	$('fee-ajax').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=91&action=transactions&acc='+account+'&yr='+cyr,
			method: 'get',
			noCache: true,
			onComplete: function(response){
					var row = json_parse(response,function(data,text) {
							if (typeof text == 'string') {
								if (parseInt(response) == -1)
								{
									$('fee-data').set('html','Error retrieving account information.');
								}
			
									r = text.split(';');
									html = html + '<tr>';
									html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">'+r[0]+'</td>';
									html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">'+r[1]+'</td>';
									html = html + '<td width="40%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">'+r[2]+'</td>';
									var amt = r[3];
									if (amt > 0)
									{
										html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">'+Math.abs(amt)+'</td>';
										html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">&nbsp;</td>';
									} else {
										html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">&nbsp;</td>';
										html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">'+Math.abs(amt)+'</td>';
									}
									balance = balance + parseFloat(amt);
									html = html + '</tr>';
				
									
							}
					});

			html = html + '<table border="0" width="100%" >';
			html = html + '<tr>';
			html = html + '<td colspan="3" width="70%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #9b9b9b; font-weight: bold">Balance</td>';
			if (parseFloat(balance) > 0)
			{
				html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff; text-align: right">'+balance.toFixed(2)+'</td>';
				html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">&nbsp;</td>';
			} else {
				html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff">&nbsp;</td>';
				html = html + '<td width="15%" style="overflow: hidden; border-right: 1px solid #a6a6a6; background-color: #ffffff; text-align: right">'+balance.toFixed(2)+'</td>';
			}
			html = html + '</tr></table>';
		$('fee-ajax').setStyle('display','none');
		$('fee-data').set('html',html);	
		}

	}).send();
	
}