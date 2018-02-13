window.addEvent('domready',function() {


	$('submit-bug').addEvent('click',function() {
		saveBug();
	});

	$('cancel-track').addEvent('click',function() {
		window.parent.$j.colorbox.close();
	});

	window.parent.$j.colorbox.resize({width: 420, height: 370});

});

function saveBug() {
	var mail = $('uemail').get('value');
	if (mail.length < 5)
	{
		alert('Email address required.');
		$('uemail').focus();
		return false;
	}

	var desc = $('bug-desc').get('value');
	if (desc.length < 20)
	{
		alert('Please enter a more descriptive request.');
		$('bug-desc').focus();
		return false;
	}
	$('submit-bug').setStyle('display','none');
		$('ajax').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=49&action=save_bug&app='+$('appname').get('value')+'&email='+$('uemail').get('value')+'&btype='+$('bug-type').getSelected().get('value')+'&uid='+$('uid').get('value')+'&details='+$('bug-desc').get('value')+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			if (parseInt(response) == -1)
			{
				alert('Error saving report.');
			} else {
				$('ajax').setStyle('display','none');
				window.parent.$j.colorbox.close();
				alert ('Thank you for your participation'+'\n'+'A confirmation email was sent to your mailbox.');
			}
		}
	}).send();

}