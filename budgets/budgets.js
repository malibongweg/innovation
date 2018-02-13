window.addEvent('domready',function(){

	$('grant-permission').addEvent('click',function(){
		var fac = $('fac').get('value');
		var empno = $('empno').get('value');
		var lg = $('username').get('value');
		$('show-permissions').set('href','index.php?option=com_jumi&tmpl=component&fileid=95&fac='+fac+'&empno='+empno+'&lg='+lg);
		$('show-permissions').click();
	});

	var lg = $('username').get('value');
	checkData();
	loadCostCodes(lg);
});

function checkData(){
	var lg = $('username').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=check_data_empno&lg='+lg,
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response){
			if (parseInt(response) == 0)
			{
				alert('Application only available after login name/staff number validation.'+'\n'+'Verify details under the Utilites menu option.');
				window.location.href='/index.php';
			} else {
				$('empno').set('value',parseInt(response));
				checkPermissions(parseInt(response));
			}
		}
	}).send();
}

function checkPermissions(empno){
	var ab = parseInt($('allow-budget').get('value'));
	if (ab == 1){
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=93&action=check_user_faculty&empno='+empno,
			noCache: true,
			method: 'get',
			onComplete: function(response){
				if (parseInt(response) == -1){
					alert('Unable to allocate user to faculty.'+'\n'+'Unable to set cost centre permissions.'+'\n'+'Please consult CTS helpdesk.');
				} else {
				var r = response.split(';');
				$('fac').set('value',r[0]);
				$('grant-permission').set('value','Grant/Display Permissions ['+r[1]+']');
				$('permissions').setStyle('display','block');
			  }
			}
		}).send();
	}
}

function loadCostCodes(lg){
	$('cc-name').empty();
	$('budget-busy').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=93&action=load_cost_centres&lg='+lg,
			method: 'get',
			noCache: true,
			onComplete: function(response){
				var row = json_parse(response,function(data,text){
						if (typeof text == 'string') {
							if (parseInt(text) == 0)	{
								alert('You do not have any cost centres assigned to you login name.');
								return false;
							} else {
								var r = text.split(';');
								new Element('option',{ 'value':r[0],'text':r[0]+' - ' + r[1]}).inject($('cc-name'));
							}
						}
				});
				$('budget-busy').setStyle('display','none');
		}
	}).send();
}