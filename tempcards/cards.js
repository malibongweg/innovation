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

	$('prn').addEvent('click',function(){
		$('prn-click').set('href','index.php?option=com_jumi&tmpl=component&fileid=93&uid='+$('sno').get('value'));
		$('prn-click').click();
	});

	//$('get-details').addEvent('click',function() {
	//	list_users();
	//	$('busy').setStyle('display','none');
	//});
	
	$('srch-details-form').addEvent('submit',function(e){
		e.stop();
		console.log(this.toQueryString());
		list_users(this.toQueryString());
		//var svalue = $_POST['svalue'];
		//list_users(data);
	});

	$('srch').addEvent('click',function() {
		$('srch').set('value','');
		$('ajax').setStyle('display','none');
		$('display-users').setStyle('display','none');
		//$('display-details').setStyle('display','none');
		$('busy-ajax-temp').set('html','<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">Getting additional data...Please wait.');
		$('busy-ajax-temp').setStyle('display','none');
		$('busy').setStyle('display','none');
		//clearTimeout(t);
	});

	$('sel-user').addEvent('dblclick',function() {
		var id = $('sel-user').getSelected().get('value');
		display_photo(id);
	});

	$('srch').focus();


});

function list_users(data) {
	var q = data.split('&');
	var qq = q[0].split('=');
	var qqq = q[1].split('=');
	var scond = qqq[1];
	var id = qq[1];
	$('ajax').setStyle('display','block');
	$('display-users').setStyle('display','none');
	$('sel-user').empty();
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=56&action=list_users&id='+id+'&scond='+scond+'&dt='+new Date().getTime(),
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
			if (parseInt(response) == -1)
			{
				alert('Error retrieving image.');
			} else if (parseInt(response) == -2)
			{
				alert('No image data for student.');
			} else {
				$('photo').set('src',response);
				$('display-details').setStyle('display','block');
			}
				$('busy-ajax-temp').setStyle('display','block');
				var y = new Request({
					url: 'index.php?option=com_jumi&fileid=56&action=get_user_details&id='+id+'&dt='+new Date().getTime(),
					method: 'get',
					timeout: 15000,
					onTimeout: function() {
						y.cancel();
						$('busy-ajax-temp').set('html','Database time-out error.');
					},
					onComplete: function(response) {
						if (parseInt(response) <= 0)
						{
							$('sno').set('html','Error retrieving additional information.');
						} else {
							var rec = response.split(';');
							$('busy-ajax-temp').setStyle('display','none');
							$('sno').set('value',rec[0]);
							$('sname').set('value',rec[1]);
							$('qual').set('value',rec[2]);
						}
					}
				}).send();
		}
	}).send();
}
