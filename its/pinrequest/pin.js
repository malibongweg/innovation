window.addEvent('domready',function(){

$('search-pin').setStyle('display','none');
$('its-lookup').set('value','');

$('its-lookup').focus();

$('get-pin').addEvent('click',function(){
	var stdno = $('its-lookup').get('value');
	var op = $('uname').get('value');
	showPin(stdno,op);
});

});


function showPin(stdno,op){

	if (stdno.length < 9) {
		alert('Enter valid student#');
		$('its-lookup').set('value','');
		$('its-lookup').focus();
		return false;
	}

	$('search-pin').setStyle('display','inline');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=155&stdno='+stdno+'&op='+op,
		method: 'get',
		noCache: true,
		onComplete: function(response) {
		$('search-pin').setStyle('display','none');
			if (response.length == 0) {
				alert('Could not locate pin.');
			} else {
			alert('Pin for student# '+stdno+' is '+response);
			$('its-lookup').set('value','');
			$('its-lookup').focus();
			}
		}
	}).send();

}
