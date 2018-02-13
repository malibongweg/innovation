var tref;


window.addEvent('domready',function() {
  getStatus();

/*
if ($('sms-credit-form')) {
	$('sms-credit-form').addEvent('submit',function(e) {
		new Event(e).stop();
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
});

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
	});*/
	
});


function getStatus() {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=20&func=stat&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			alert('An error occured.');
		x.cancel();
		},
		onComplete: function(response) {
					var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							switch(parseInt(data)) {
								case 0:
										if (parseInt(text) == 1) {
											$('sms-stat').set('checked',true); break;
										} else { $('sms-stat').set('checked',false); break; }
										
		
								case 1: $('sms-charge').set('value',text); break;
								case 2: $('sms-notify').set('value',text); break;
								case 3: $('sms-command').set('value',text); break;
								case 4: $('hr-email').set('value',text); break;
								case 5: if (text == 0) { $('s-mode-1').set('checked',true); } else { $('s-mode-2').set('checked',true); }
								
							}
						}
					});
		}
	}).send();
}


function setStatus() {
	if (confirm('Are you sure?')) {
		var smode = $$('input[name=smode]:checked').get('value');
		var x = new Request({ 
		url: 'index.php?option=com_jumi&fileid=20&func=updstat&stat='+$('sms-stat').get('checked')+'&amt='+$('sms-charge').get('value')+
			'&notify='+$('sms-notify').get('value')+'&cmd='+encodeURI($('sms-command').get('value'))+
			'&hr_email='+$('hr-email').get('value')+'&smode='+smode,
		noCache: false,
		method: 'get',
		onComplete: function(response) {
				getStatus();
				if (parseInt(response) == 1) {
					alert('Configuration updated.');
				} else { alert('Error updating configuration...report to CTS department.'); }
		}
		}).send();
	}
}

/*function srchUser() {
	$('ajax-loader').setStyle('display', 'block');
	var url = 'index.php?option=com_jumi&fileid=4&user='+$('srch').value+'&func=srch&dt='+new Date().getTime();
	var x = new Request({
		url: url,
		method: 'get',
		onComplete: function(response) {
				$('userList').empty();
				var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					new Element('option',{ 'value':data,'text':text}).inject($('userList'));
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
	
}*/