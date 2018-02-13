window.addEvent('domready',function(){
	

	$('ajax').setStyle('display: block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=38&action=get_receipt&receipt='+$('receipt').get('value')+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response){
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					if (parseInt(text) == -1)
					{
						$('receipt-no').set('html','No entries found.');
					} else {
						var r = text.split(';');
						$('receipt-no').set('html','Receipt# '+r[6]);
						$('receipt-date').set('html','Date: '+r[1]);
						$('receipt-proc').set('html','Processed: '+r[2]);
						$('receipt-acc').set('html','Account: '+r[0]);
						switch(parseInt(r[3])) {
								case 33: $('receipt-type').set('html','Printing Credit Receipt Amount: R'+r[4]); break;
								case 121: $('receipt-type').set('html','Printing Credit Receipt Amount: R'+r[4]); break;
								case 212: $('receipt-type').set('html','Printing Credit Receipt Amount: R'+r[4]); break;
								case 224: $('receipt-type').set('html','Photocopy Credit Receipt Amount: R'+r[4]); break;
								case 21: $('receipt-type').set('html','Photocopy Credit Receipt Amount: R'+r[4]); break;
								case 190: $('receipt-type').set('html','Internet Credit Receipt Amount: R'+r[4]); break;
								default: $('receipt-type').set('html','Unknown Credit Receipt Amount: R'+r[4]); break;
							}
						if (r[0] != r[7])
						{
							$('receipt-msg').set('html','<span style="color: red">Account info does not match user profile.</span><br />'+
							'<input type="button" value="Rectify" onclick="rectify(\''+$('receipt').get('value')+'\',\''+r[7]+'\')" />');
						}
					}
				}
			});
			$('ajax').setStyle('display: none');
		}
	}).send();

});

function rectify(receipt,usr) {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=38&action=fix_receipt&receipt='+receipt+'&usr='+usr+'&dt='+new Date().getTime(),
		onComplete: function(response) {
			if (parseInt(response) == -1)
			{
				alert('Error updating receipt details.');
			} else {
				alert('Receipt updated. Will be processed as soon as possible.');
			}
		}
	}).send();
}