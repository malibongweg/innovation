window.addEvent('domready',function() {

	window.parent.$j.colorbox.resize({width:400, height:450});
	$('feedback-area').focus();


	$('close-button').addEvent('click',function() {
		window.parent.$j.colorbox.close();
	});

	$('save-bug').addEvent('submit',function(e) {
		new Event(e).stop();
		$('resolved-button').setStyle('display','none')
					var mail = 0;
					$$('input[name=send_email]:checked').each(function(c) {
						mail = c.value;
					});
					if (mail == 1)
					{
						$('show-email').setStyle('display','block');
						var y = new Request({
							url: 'index.php?option=com_jumi&fileid=49&action=send_mail&dt='+new Date().getTime(),
							method: 'post',
							async: false,
							data: this,
							timeout: 10000,
							onTimeout: function() {
								y.cancel();
								alert('Error sending email to requester.');
							},
							onComplete: function() {
								$('show-email').setStyle('display','none');
							}
						}).send();
					}
		
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=49&action=save_feedback',
			method: 'post',
			data: this,
			onComplete: function(response) {
				if (parseInt(response) == -1)
				{
					alert('Error updating application tracker entry.');
				} else {
					alert('Update saved.');
				}
				window.parent.location.reload();
				window.parent.$j.colorbox.close();
			}
		}).send();
	});
});
