window.addEvent('domready',function() {

	new DatePicker($('start-date'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler1',
    useFadeInOut: !Browser.ie
	});

	new DatePicker($('action-date'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler2',
    useFadeInOut: !Browser.ie
	});



	$('new-record').addEvent('click',function() {
		newEntry();
	});

	$('save-record').addEvent('click',function(e) {
		saveRecord(e);
	});

	$('mth').addEvent('change',function() {
		var m = $('mth').getSelected().get('value');
		displayLog(m)
	});

	$('action-form').addEvent('submit',function(e){
		e.stop();
		saveAction(this);
	});

	$('cancel-record').addEvent('click',function() {
		$('log-details').setStyle('display','block');
		$('log-form').setStyle('display','none');
		$('update-form').setStyle('display','none');
		$('start-date').set('disabled',false);
	$('system-name').set('disabled',false);
	$('failure-code').set('disabled',false);
	$('severity-code').set('disabled',false);
	$('failure-message').set('disabled',false);
		var m = $('mth').getSelected().get('value');
		displayLog(m);
	});

	var h = window.innerHeight - 380;
	$('log-details').setStyle('height',h +'px');
	$('display-log').setStyle('height',h-100+'px');

	var m = $('mth').getSelected().get('value');
	displayLog(m);

});

function displayLog(mth) {
	var color1 = '#a5badc';
	var color2 = '#ffffff';
	var cnt = 1;
	var fnd = true;
	var html = '<table border="0" width="100%">';
	var fnd = true;
	$('ajax-el').setStyle('display','block');
	var mth = $('mth').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=65&mth='+mth+'&action=show_log&mth='+mth+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								$('display-log').set('html','No data to display.');
								fnd = false;
							} else {
								var d = text.split(';');
								var m = cnt % 2;
										if (m == 0)
										{
											var color = color1;
										} else {
											var color = color2;
										}
								html = html + '<tr>';
								html = html + '<td style="width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="id" value="'+d[4]+'" onclick="editEntry(this.value)" /></td>';
								html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[0]+'</td>';
								html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[1]+'</td>';
								html = html + '<td style="width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[2]+'</td>';
								html = html + '<td style="width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+d[5]+'; color: #000000">'+d[3];
									if (d[6] == 1)
									{
										html = html + '&nbsp;<img src="/images/ok.png" width="14" height="14" border="0" alt="">';
									}
								html = html + '</std>';
								html = html + '</tr>';
								++cnt;
							}
						}
						
			});
			html = html + '</table>';
						if (fnd == true)
						{
							$('display-log').set('html',html);
						}
			$('ajax-el').setStyle('display','none');
		}
	}).send();
}

function editEntry(id) {
	$('log-details').setStyle('display','none');
	$('log-form').setStyle('display','block');
	$('update-form').setStyle('display','block');
	$('start-date').set('disabled',true);
	$('system-name').set('disabled',true);
	$('failure-code').set('disabled',true);
	$('severity-code').set('disabled',true);
	$('failure-message').set('disabled',true);
	$('act-id').set('value',id);
	$('action-date').set('value','');
	$('action-taken').set('value','');
	popHistory(id);
	popOldRecord(id);
}

function popHistory(id){
	$('action-history').set('disabled',false);
	$('action-history').set('value','');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=65&action=pop_history&id='+id,
			noCahce: true,
			method: 'get',
			onComplete: function(response){
			var line = '';
					var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								line = '';
								$('action-history').set('disabled',true);
							} else {
								var r = text.split(';');
								line = line + r[0]+ '- ' + r[1] + '\r';
							}
						}
					});
						$('action-history').set('value',line);
					$('action-history').set('disabled',true);
		}
	}).send();
}

function popOldRecord(id){
	$('start-date').set('disabled',false);
	$('system-name').set('disabled',false);
	$('failure-code').set('disabled',false);
	$('severity-code').set('disabled',false);
	$('failure-message').set('disabled',false);
	$('system-name').empty();
	$('failure-code').empty();
	$('severity-code').empty();

	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=65&action=pop_old_record&id='+id,
			method: 'get',
			noCache: true,
			onComplete: function(response){
			var rec = response.split(';');
			$('start-date').set('value',rec[0]);
			new Element('option',{ 'value':rec[1],'text': rec[1]}).inject($('system-name'));
			new Element('option',{ 'value':rec[2],'text': rec[2]}).inject($('failure-code'));
			new Element('option',{ 'value':rec[3],'text': rec[3]}).inject($('severity-code'));
			$('failure-message').set('value',rec[4]);
			$('start-date').set('disabled',true);
			$('system-name').set('disabled',true);
			$('failure-code').set('disabled',true);
			$('severity-code').set('disabled',true);
			$('failure-message').set('disabled',true);
		}
	}).send();
}

function newEntry() {
	$$('input[type=text]').each(function(el) {
		el.set('value','');
	});
	$('failure-message').set('value','');
		$('log-details').setStyle('display','none');
		$('log-form').setStyle('display','block');
		popSystems();
		popFailure();
		popSeverity();
}

function saveAction(form){
	$('start-date').set('disabled',false);
	$('system-name').set('disabled',false);
	$('failure-code').set('disabled',false);
	$('severity-code').set('disabled',false);
	$('failure-message').set('disabled',false);
	var dd = $('action-date').get('value');
	var ad = $('action-taken').get('value');
	if (dd.length == 0)
	{
		alert('Action date required.');
		return false;
	}
	if (ad.length < 10)
	{
		alert('More descriptive action required.');
		return false;
	}
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=65&action=save_action',
			method: 'post',
			noCache: true,
			data: form,
			onComplete: function(response){
				if (parseInt(response) == -1)
				{
					alert('Error...Could not insert new action record.');
				} else if (parseInt(response) == -2)
				{
					alert('Error...Could not update current alert failure.');
				} else {
					alert('Entry saved.');
				}
				$('log-details').setStyle('display','block');
				$('log-form').setStyle('display','none');
				$('update-form').setStyle('display','none');
				var m = $('mth').getSelected().get('value');
				displayLog(m);
		}

	}).send();
}

function popSystems() {
	$('ajax1').setStyle('display','block');
	$('system-name').empty();
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=65&action=pop_systems&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					if (parseInt(text) == -1) {
						new Element('option',{ 'value':'-1','text':'Error populating systems.'}).inject($('system-name'));
					} else {
						var rec = text.split(';');
						new Element('option',{ 'value':rec[0],'text': '['+rec[0]+'] '+rec[1]}).inject($('system-name'));
					}
			}
		});
		$('ajax1').setStyle('display','none');
		}
	}).send();
}

function popFailure() {
	$('ajax2').setStyle('display','block');
	$('failure-code').empty();
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=65&action=pop_failure&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					if (parseInt(text) == -1) {
						new Element('option',{ 'value':'-1','text':'Error populating failure codes.'}).inject($('system-name'));
					} else {
						var rec = text.split(';');
						new Element('option',{ 'value':data,'text': text}).inject($('failure-code'));
					}
			}
		});
		$('ajax2').setStyle('display','none');
		}
	}).send();
}

function popSeverity() {
	$('ajax3').setStyle('display','block');
	$('severity-code').empty();
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=65&action=pop_severity&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					if (parseInt(text) == -1) {
						new Element('option',{ 'value':'-1','text':'Error populating failure codes.'}).inject($('severity-code'));
					} else {
						var rec = text.split(';');
						new Element('option',{ 'value':data,'text': text}).inject($('severity-code'));
					}
			}
		});
		$('ajax3').setStyle('display','none');
		}
	}).send();
}

function saveRecord(e) {
	e.stop();
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=65&action=save_record&dt='+new Date().getTime(),
		method: 'post',
		data: $('failure-form'),
		onComplete: function(response) {
				if (parseInt(response) == -1)
				{
					alert('Error saving entry.');
				}
				$('log-details').setStyle('display','block');
		$('log-form').setStyle('display','none');
		var m = $('mth').getSelected().get('value');
		displayLog(m);
		}
	}).send();
}