var tref;


window.addEvent('domready',function() {


if ($('sms-credit-form')) {
	$('sms-credit-form').addEvent('submit',function(e) {
		e.stop();
			if ($('ref').get('value').length <=3) {
				alert('Reference# required.');
				$('ref').focus();
			} else {
				var Req = new Request({
							url: this.get('action'),//+'&at='+new Date().getTime(),
							method: 'get',
							data: this,
							onComplete: function (response) {
								if (parseInt(response) == 1) {
									alert('The user account was credited.');
								} else { alert('Error updating record...report to CTS department.'); }
								$('credit-cancel').fireEvent('click');
							}
				}).send();
			}
	});
}

if ($('credit-cancel')) {
	$('credit-cancel').addEvent('click',function() {
		$('list-users').setStyle('display','none');
		$('credit-div').setStyle('display','none');
		$('srch').focus();
	});
}

if ($('srch')) {
  $('srch').addEvent('keydown',function() {
		$('list-users').setStyle('display','none');
		$('credit-div').setStyle('display','none');
		clearTimeout(tref);
		tref = setTimeout('srchUser()',500);
	});
}


	$('getUser').addEvent('click',function() {
		var uid = parseInt($('userList').getSelected().get('value'));
				if (typeof uid == 'number' && uid > 0) {
					$('list-users').setStyle('display','none');
					$('srch').value = '';
					displayInfo(uid);
				} else {
					alert('Please select user object.');
					$('srch').value = '';
					$('srch').focus();		
				}
	});

	$('srch').focus();
	
});

function srchUser() {
	$('ajax-loader').setStyle('display', 'block');
	var url = 'index.php?option=com_jumi&fileid=4&user='+$('srch').value+'&func=srch&dt='+new Date().getTime();
	var x = new Request({
		url: url,
		method: 'get',
		onComplete: function(response) {
				$('userList').empty();
				var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					var r = text.split(';');
					new Element('option',{ 'value':r[0],'text':r[1]+' ['+r[2]+']'}).inject($('userList'));
				}
			});
			$('ajax-loader').setStyle('display','none');
			$('list-users').setStyle('display','block');
		}
	}).send();
}

function displayInfo(uid) {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=4&uid='+uid+'&func=info&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
				$('userList').empty();
					var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						switch (data) {
							case 'id': $('luid').value = text; break;
							case 'name': $('uname').value = text; break;
							case 'username': $('xusername').value = text; break;
							case 'email': $('email').value = text; break;
						}
					}
				});
				$('credit-div').setStyle('display','block');
				$('ref').focus();
		}
	}).send();
	
}
