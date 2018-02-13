window.addEvent('domready',function(){
	
	$('conf-form').addEvent('submit',function(e) {
		e.stop();
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=136&action=saveConfig',
				noCache: true,
				data: this,
				method: 'post',
				onComplete: function(response) {
				alert('Configuration updated.');
			}
		}).send();
	});

	getConfig();
	popData();

});

function getConfig() {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=136&action=getConfig',
			noCache: true,
			method: 'get',
			onComplete: function(response) {
			var r = response.split(';');
			$('admin-email').set('value',r[0]);
			$('cutoff-date').set('value',r[1]);
			$('approval-date').set('value',r[2]);
			var s = parseInt(r[3]);
				if (s == 1)	{
					$('super-users').set('checked',true);
				} else {
					$('super-users').set('checked',false);
				}
					var d = new Date();
					var y = d.getFullYear(); 
					for (var i=y; i<y+2 ;++i) {
						if (parseInt(r[4]) == i){
							new Element('option',{ 'value':i,'text':i,'selected': true}).inject($('budget-cycle'));
						} else {
							new Element('option',{ 'value':i,'text':i}).inject($('budget-cycle'));
						}
					}
			}
		}).send();

}

function popData(){
	jt('#tableData').jtable({
            title: 'Super Users',
			paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'userid ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=136&action=displaySuperUsers',
                createAction: 'index.php?option=com_jumi&fileid=136&action=createSuperUsers',
				deleteAction: 'index.php?option=com_jumi&fileid=136&action=deleteSuperUsers'
            },
            fields: {
				id: {
					list: false,
					key: true
                },
				userid: {
					title: 'Username',
					width: '60%',
					edit: false,
					create: true,
					options: 'index.php?option=com_jumi&fileid=136&action=displayStaff'
				}
				
            },
				formCreated: function (event, data) {
                data.form.find('input[name="userid"]').addClass('validate[required]');
                data.form.validationEngine();
				},
				//Validate form when it is being submitted
				formSubmitting: function (event, data) {
					return data.form.validationEngine('validate');
				},
				//Dispose validation logic when form is closed
				formClosed: function (event, data) {
					data.form.validationEngine('hide');
					data.form.validationEngine('detach');
				}
        });
		
		jt('#tableData').jtable('load');
}