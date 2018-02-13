var balance = 0;
var trans = 0;
HTMLElement.prototype.click=function() {
var evt = this.ownerDocument.createEvent('MouseEvents');
evt.initMouseEvent('click', true, true, this.ownerDocument.defaultView, 1, 0, 0, 0, 0, false, false, false, false, 0, null);
this.dispatchEvent(evt);
}

window.addEvent('domready',function() {

	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=25&action=fee_account&uid='+$('uid').get('value')+'&dt='+new Date().getTime(),
		//url: 'index.php?option=com_jumi&fileid=25&action=fee_account&uid=208176411&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			$('cajax').set('html','Error retrieving account details...');
			x.cancel();
		},
		onComplete: function(response) {
			if (parseInt(response) == 99999)
							{
								$('cajax').set('html','Error retrieving account details...');
								return false;
							}
					var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
								$('cajax').set('html','<b>Balance brought foward: R'+parseFloat(text).toFixed(2)+'</b>');
								balance = parseFloat(text);
							}
					});
			accountTransaction();
		}
	}).send();

});

function accountTransaction() {
	var h = '<table border="0" width="90%" style="padding: 0; margin: 0">';
	var y = new Request({
		url: 'index.php?option=com_jumi&fileid=25&action=transactions&&uid='+$('uid').get('value')+'&dt='+new Date().getTime(),
		//url: 'index.php?option=com_jumi&fileid=25&action=transactions&uid=208176411&dt='+new Date().getTime(),
		method: 'get',
		timeout: 15000,
		onTimeout: function() {
			$('cajax').set('html','Error retrieving account details...');
			y.cancel();
		},
		onComplete: function(response) {
			if (parseInt(response) == 99999)
							{
								//$('cajax').set('html','Error retrieving account details...');
								return false;
							}
				var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
								var s = text.split('*');
								h = h + '<tr>';
								h = h + '<td width="15%" align="left">' + s[0];
								h = h + '<td width="15%" align="left">' + s[1];
								h = h + '<td width="45%" align="left">' + s[2];
								h = h + '<td width="15%" align="right">' + parseFloat(s[3]).toFixed(2);
								trans = trans + parseFloat(s[3]);
								h = h + '</tr>';
							}
						
					});
					var bal = balance + trans;
					h = h + '<tr><td width="90%" colspan="4"><b>Balance: R'+ bal.toFixed(2)+'</b></td></tr>';
					h = h + '</table>';
					$('showDiv').setStyle('display','block');
					$('trans').setStyle('display','block');
					$('transx').setStyle('display','block');
					$('transx').set('html',h);
		}
	}).send();
}