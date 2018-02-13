window.addEvent('domready',function() {

	$('service-name').addEvent('change',function() {
		if (parseInt($('service-name').getSelected().get('value')) != -1)
		{
			displaySubService($('service-name').getSelected().get('value'));
		} else {
			$('sub-service').empty();
			new Element('option',{ 'value':'-1','text':'Waiting on user selection.'}).inject($('sub-service'));
		}
	});

	$('hlp-data').addEvent('submit',function(e) {
		new Event(e).stop();
		if ($('contact').get('value').length < 4)
		{
			alert('Please enter a contact number.');
			//return false;
		}
		if ($('details').get('value').length < 10)
		{
			alert('Please enter a more descriptive request.');
			//return false;
		} else {
		$('log-call').setStyle('display','none');
		$('cancel-call').setStyle('display','none');
		$('save-ajax').setStyle('display','block');
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=47&action=save_data&dt='+new Date().getTime(),
			method: 'post',
			timeout: 10000,
			onTimeout: function() {
				alert('Time-out occured. Error saving data.');
				x.cancel();
			},
			data: this,
			onComplete: function(response) {
				if (parseInt(response) == -1)
				{
					$('save-ajax').setStyle('display','none');
					alert('Error saving your request. Please report to CTS department.');
				} else {
					$('save-ajax').setStyle('display','none');
					alert('Your call was logged successfully.');
				}
				$('contact').set('value','');
				$('details').set('value','');
				displaySubService($('service-name').getSelected().get('value'));
				$('log-call').setStyle('display','block');
				$('cancel-call').setStyle('display','block');
			}
		}).send();
		}
	});

	checkData();
});

function checkData(){
	var lg = $('uid').get('value');
			var rm = new RegExp('^[0-9]+$');
			var r = rm.exec(lg);
				if (r != null)
				{
					getStaffNum(lg);
				} else {
					var x = new Request({
					url: 'index.php?option=com_jumi&fileid=47&action=check_data&lg='+lg,
						method: 'get',
						noCache: true,
						onComplete: function(response){
						if (parseInt(response) == 0)
						{
							alert('Application only available after login name/staff number validation.'+'\n'+'Verify details under the Utilites menu option.');
							window.location.href='/index.php';
						} else {
							idno = response;
							getStaffNum(response);
						}
					}
				}).send();
				}
}

function getStaffNum(uid) {
				$('staff-no').set('html','Staff/Student# '+uid);
				$('user-number').set('value',uid);
				$('redir').setStyle('display','block');
				$('log').setStyle('display','block');
				list_services();
}

function list_services() {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=47&action=list_services&dt='+new Date().getTime(),
		timeout: 10000,
		method: 'get',
		onTimeout: function() {
			$('service-name').empty();
			new Element('option',{ 'value':'-1','text':'Time-out on getting data.'}).inject($('service-name'));
			x.cancel();
		},
		onComplete: function(response) {
			if (parseInt(response) == -1)
			{
				$('service-name').empty();
				new Element('option',{ 'value':'-1','text':'Error getting data.'}).inject($('service-name'));
			} else {
					$('service-name').empty();
					new Element('option',{ 'value':'-1','text':'Waiting on user selection.'}).inject($('service-name'));
					var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							var r = text.split(';');
							new Element('option',{ 'value':r[0],'text':r[1]}).inject($('service-name'));
						}
					});
			}
			$('sub-service').empty();
			new Element('option',{ 'value':'-1','text':'Waiting on user selection'}).inject($('sub-service'));
		}
	}).send();

	var y = new Request({
		url: 'index.php?option=com_jumi&fileid=47&action=list_campus&dt='+new Date().getTime(),
		timeout: 10000,
		method: 'get',
		onTimeout: function() {
			$('campus').empty();
			new Element('option',{ 'value':'-1','text':'Time-out on getting data.'}).inject($('campus'));
			y.cancel();
		},
		onComplete: function(response) {
			if (parseInt(response) == -1)
			{
				$('campus').empty();
				new Element('option',{ 'value':'-1','text':'Error getting data.'}).inject($('campus'));
			} else {
					$('campus').empty();
					var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							new Element('option',{ 'value':text,'text':text}).inject($('campus'));
						}
					});
			}
			$('redir').setStyle('display','none');
			$('log-call').setStyle('display','block');
			$('cancel-call').setStyle('display','block');
		}
	}).send();
}

function displaySubService(id) {
	$('redir').setStyle('display','block');
	$('sub-service').empty();
	new Element('option',{ 'value':'-1','text':'Refreshing data...please wait.'}).inject($('sub-service'));
	var y = new Request({
		url: 'index.php?option=com_jumi&fileid=47&action=list_subservice&primary='+id+'&dt='+new Date().getTime(),
		timeout: 10000,
		method: 'get',
		onTimeout: function() {
			$('sub-service').empty();
			new Element('option',{ 'value':'-1','text':'Time-out on getting data.'}).inject($('sub-service'));
			y.cancel();
		},
		onComplete: function(response) {
			if (parseInt(response) == -1)
			{
				$('sub-service').empty();
				new Element('option',{ 'value':'-1','text':'Error getting data.'}).inject($('sub-service'));
			} else {
					$('sub-service').empty();
					var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							var r = text.split(';');
							new Element('option',{ 'value':r[0],'text':r[1]}).inject($('sub-service'));
						}
					});
			}
			$('redir').setStyle('display','none');
		}
	}).send();
}