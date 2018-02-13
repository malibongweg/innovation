window.addEvent('domready',function() {

	$('request-desc').focus();

	new DatePicker($('pdate'), {
	pickerClass: 'datepicker',
	format: '%Y-%m-%d',
	timePicker: false,
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler1',
    useFadeInOut: !Browser.ie,
		debug: true
	});

	new DatePicker($('sdate'), {
	pickerClass: 'datepicker',
	timePicker: true,
	format: '%Y-%m-%d %H:%M',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler2',
    useFadeInOut: !Browser.ie
	});

	new DatePicker($('edate'), {
	pickerClass: 'datepicker',
	timePicker: true,
	format: '%Y-%m-%d %H:%M',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler3',
    useFadeInOut: !Browser.ie
	});
	new DatePicker($('rdate'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_togglerr',
    useFadeInOut: !Browser.ie
	});

	$('add-resource').addEvent('click',function() {
		var line = $('resource').get('value')+';'+$('hsr').getSelected().get('value')+';'+$('rdate').get('value');
		new Element('option',{ 'value':line,'text':line}).inject($('resources-used'));
		$('resource').set('value','');
		$('rdate').set('value','');
		$('resource').focus();
	});

	$('resources-used').addEvent('dblclick',function() {
		if (confirm('Delete entry?'))
		{
			$('resources-used').getSelected().dispose();
		}
	});

	$('submit-request').addEvent('click',function() {
		checkRequiredFields();
	});

	
	/*$('my-requests').addEvent('click',function() {
		$('my-requests').set('href','index.php?option=com_jumi&fileid=53&view=application');
		$('my-requests').click();
	});

	$('all-requests').addEvent('click',function() {
		$('all-requests').set('href','index.php?option=com_jumi&fileid=54&view=application');
		$('all-requests').click();
	});*/

});

function selectAllCampus() {
		$$('input[name=campus]').setProperty('checked',true);
}

function checkRequiredFields() {
	if ($('system-no').getSelected().get('value').length == 0)
	{
		alert('Affected system is a required field.');
		return false;
	}
	if ($('request-desc').get('value').length < 10)
	{
		alert('Enter a more descriptive change request.');
		$('request-desc').focus();
		return false;
	}
	if ($('leader').getSelected().get('value').length == 0)
	{
		alert('Please selecta project leader.');
		return false;
	}
	
	var v = 0;
	$$('input[name=campus]:checked').each(function(e) {
		v = e.value;
	});
	if (v == 0)
	{
		alert('Please select a campus.');
		return false;
	}

	if ($('details-change').get('value').length < 1)
	{
		alert('Please specify details of change.');
		$('details-change').focus();
		return false;
	}

	if ($('reasons-change').get('value').length < 1)
	{
		alert('Please specify reasons of change.');
		$('reasons-change').focus();
		return false;
	}

	saveRequest();
}

function saveRequest() {
	$('submit-request').setStyle('display','none');
	$('msg-details').set('html','Saving Request...Please Wait...');
	$('msg').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=42&action=save_request&dt='+new Date().getTime(),
		data: $('new-request'),
		method: 'post',
		onComplete: function(response) {
			if (parseInt(response) <= 0)
			{
				alert('Error updating database.');
			} else {
				saveAffectedServices(response);
				saveCampuses(response);
				saveResources(response);
				$('msg-details').set('html','Sending Emails....Please Wait...');
					var q = new Request({
						url: 'index.php?option=com_jumi&fileid=50&req='+response+'&dt='+new Date().getTime(),
						method: 'get',
						timeout: 10000,
						onTimeout: function() {
							alert('Email timeout error received...No notifications sent.');
							q.cancel();
						},
						onComplete: function() {
						}
					}).send();
				$('msg').setStyle('display','none');
				alert('Request saved.');
				window.location.href='index.php?option=com_jumi&fileid=41';
			}
		}

	}).send();
}

function saveAffectedServices(req) {
	$('service-affected').getSelected().each(function(e) {
		var y = new Request({
			url: 'index.php?option=com_jumi&fileid=42&action=save_affected&req='+req+'&service='+e.value+'&dt='+new Date().getTime(),
			method: 'get',
			async: false,
			onComplete: function() {

			}
		}).send();
	});
}

function saveCampuses(req) {
	$$('input[name=campus]:checked').each(function(e) {
		var y = new Request({
			url: 'index.php?option=com_jumi&fileid=42&action=save_campus&req='+req+'&cmp='+e.value+'&dt='+new Date().getTime(),
			method: 'get',
			async: false,
			onComplete: function() {

			}
		}).send();
	});
}

function saveResources(req) {
	var lst = $('resources-used');
	for (var i=0;i<lst.options.length ;++i )
	{
		var y = new Request({
			url: 'index.php?option=com_jumi&fileid=42&action=save_resources&req='+req+'&res='+lst.options[i].value+'&dt='+new Date().getTime(),
			method: 'get',
			async: false,
			onComplete: function() {

			}
		}).send();
	}
}