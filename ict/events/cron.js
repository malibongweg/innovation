var t
window.addEvent('domready',function() {

	$('mth').addEvent('change',function() {
		display_log();
	});

	$('expire-event').addEvent('click',function() {
		var id = $$('input[name=id]:checked').get('value');
		if (isNaN(id))
		{
			alert('Please select entry.');
		} else {
			expireEvent(id);
		}
	});

	$('edit-event').addEvent('click',function() {
		var id = $$('input[name=id]:checked').get('value');
		if (isNaN(id))
		{
			alert('Please select entry.');
		} else {
			editEntry(id);
		}
	});

	$('delete-event').addEvent('click',function() {
		var id = $$('input[name=id]:checked').get('value');
		if (isNaN(id))
		{
			alert('Please select entry.');
		} else {
			if (confirm('Are you sure?')){
				deleteEntry(id);
			}
		}
	});

	$('cancel-button').addEvent('click',function(){
		$('display-log').set('html','');
		$('log-details').setStyle('display','block');
	$('event-form').setStyle('display','none');
		display_log();
	});

	new DatePicker($('start-date'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler1',
    useFadeInOut: !Browser.ie
});

	new DatePicker($('end-date'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler2',
    useFadeInOut: !Browser.ie
});

new DatePicker($('publish-date'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler3',
    useFadeInOut: !Browser.ie
});

	$('new-event').addEvent('click',function() {
		newEvent();
	});

	$('event-form').addEvent('submit',function(e) {
		e.stop();
		if ($('event-description').length < 5)
		{
			alert('Not enough information on event.');
			return false;
		} else {
				var ev = $('form-action').get('value');
				if (ev == 'new'){
					var x = new Request({
						url: 'index.php?option=com_jumi&fileid=67&action=save_event',
						noCache: true,
						method: 'post',
						data: this,
						onComplete: function(response) {
							if (parseInt(response) == -1)
							{
								alert('Error saving event.');
							} else {
								alert('Event saved.');
							}
							$('event-form').setStyle('display','none');
							$('log-details').setStyle('display','block');
							display_log();
						}
					}).send();
				} else {
					var x = new Request({
						url: 'index.php?option=com_jumi&fileid=67&action=save_edit',
						noCache: true,
						method: 'post',
						data: this,
						onComplete: function(response) {
							if (parseInt(response) == -1)
							{
								alert('Error saving event.');
							} else {
								alert('Event saved.');
							}
							$('event-form').setStyle('display','none');
							$('log-details').setStyle('display','block');
							display_log();
						}
					}).send();
				}
		}
	});

	var h = window.getSize().y - 380;
	$('log-details').setStyles({'height':parseInt(h) +'px'});
	$('display-log').setStyles({'height':parseInt(h-100)+'px'});

	display_log();

});

function display_log() {
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	$('display-log').set('html','');
	var html = '<table border="0" width="100%" style="table-layout: fixed">';
	var fnd = true;
	$('ajax-ev').setStyle('display','block');
	var mth = $('mth').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=67&mth='+mth+'&action=show_log',
		noCache: true,
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
								html = html + '<td style="overflow: hidden; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="id" value="'+d[0]+'" /></td>';
								html = html + '<td style="overflow: hidden; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[1]+'</td>';
								html = html + '<td style="overflow: hidden; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[2]+'</td>';
								html = html + '<td style="overflow: hidden; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[3]+'</td>';
								html = html + '<td style="overflow: hidden; width: 35%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[4]+'</td>';
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
			$('ajax-ev').setStyle('display','none');
		}
	}).send();
}

function expireEvent(id) {
if (confirm('Are you sure?'))
{
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=67&action=expire_event&id='+id,
		noCache: true,
		method: 'get',
		onComplete: function(response) {
			if (parseInt(response) == -1)
			{
				alert('Could not update event status.');
			} else {
				alert('Event expired.');
				display_log();
			}
		}
	}).send();
}
}

function newEvent() {
	$('form-action').set('value','new');
	$('start-date').set('value','');
	$('end-date').set('value','');
	$('event-description').set('value','');
	$('log-details').setStyle('display','none');
	$('event-form').setStyle('display','block');
}

function editEntry(id){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=67&action=edit_entry&id='+id,
			method: 'get',
			noCache: true,
			onComplete: function(response){
			var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							var r = text.split(';');
							$('start-date').set('value',r[0]);
							$('publish-date').set('value',r[1]);
							$('end-date').set('value',r[2]);
							$('event-description').set('value',r[3]);
							$('event-details').set('value',r[4]);
						}
			});
		}
	}).send();
	$('form-action').set('value','edit');
	$('form-id').set('value',id);
	$('log-details').setStyle('display','none');
	$('event-form').setStyle('display','block');
}

function deleteEntry(id){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=67&action=delete_entry&id='+id,
			method: 'get',
			noCache: true,
			onComplete: function(response){
			display_log();
		}
	}).send();
}