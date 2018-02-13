window.addEvent('domready',function() {
	$('cost-code').addEvent('click',function() {
		$('details').set('html','');
		$('cost-code').set('value','');
		$('details').setStyle('display','none');
	});

	$('run-report').addEvent('click',function() {
		var url = 'index.php?option=com_jumi&fileid=100&tmpl=component';
		if ($('summary').checked) {
			url = url + '&action=summary';
		} else {
			url = url + '&action=actuals';
		}
		url = url + '&ccode='+ $('cost-code').get('value');
		url = url + '&cyear='+ $('year-select').getSelected().get('value');
		$('lnk').set('href',url);
		$('lnk').click();
	});

	$('cost-code').addEvent('keyup',function() {
		this.value = this.value.toUpperCase();
	});

	$('details').set('html','');
	$('details').setStyle('display','none');
	$('ajax-budget').setStyle('display','none');
});
