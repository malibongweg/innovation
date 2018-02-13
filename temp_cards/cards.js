var t;

window.addEvent('domready',function() {


var numbers = [8,48,49, 50, 51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105];
	$$('.input_field').each(function(item) {
		item.addEvent('keydown', function(key) {
			for (i = 0; i < numbers.length; i++) {
				if(numbers[i] == key.code) {
					return true;
				}
			}
			return false;
		});
	});

	$('srch').addEvent('keydown',function() {
		clearTimeout(t);
		if ($('srch').get('value').length > 3)
		{
			t = setTimeout('list_users()',800);
		}
	});

	$('srch').addEvent('click',function() {
		$('srch').set('value','');
		$('ajax').setStyle('display','none');
		$('display-users').setStyle('display','none');
		clearTimeout(t);
	});

	$('sel-user').addEvent('dblclick',function() {
		var id = $('sel-user').getSelected().get('value');
		display_photo(id);
	});




});

function list_users() {
	var id = $('srch').get('value');
	$('ajax').setStyle('display','block');
	$('display-users').setStyle('display','none');
	$('sel-user').empty();
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=56&action=list_users&id='+id+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 10000,
		onTimeout: function() {
			x.cancel();
			$('ajax').setStyle('display','none');
			clearTimeout(t);
			alert('Time-out error from database. Please try again.');
		},
		onComplete: function(response) {
					var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								new Element('option',{ 'value':data,'text':'No macthes found.'}).inject($('sel-user'));
							} else {
							var rec = text.split(';');
							new Element('option',{ 'value':rec[0],'text':rec[0]+' '+rec[1]}).inject($('sel-user'));
							}
						}
					});
			$('ajax').setStyle('display','none');
			$('display-users').setStyle('display','block');
		}
	}).send();
}

function display_photo(id) {
	$('display-users').setStyle('display','none');
	$('busy').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=56&action=display_photo&id='+id+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			$('busy').setStyle('display','none');
			alert(response);
			if (parseInt(response) == -1)
			{
				alert('Error retrieving image.');
			} else {
				alert(response);
			}
		}
	}).send();
}