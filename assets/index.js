window.addEvent('domready',function(){
	popForms();
});


function popForms(){
	jt('#tableForms').jtable({
            title: 'Asset replacement requests',
			paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'id DESC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 500,
            actions: {
                listAction: '/scripts/assets/ajax.php?action=listForms',
                createAction: '/scripts/assets/ajax.php?action=createForms',
				deleteAction: '/scripts/assets/ajax.php?action=deleteForms',
            },
            fields: {
				id: {
					width: '5%',
					key: true,
					list: true,
					create: false,
					edit: false,
					sorting: true,
					title: 'ID#'
				},
				date_requested: {
					list: true,
					edit: true,
					create: true,
					sorting: true,
						input: function (data) {
					        if (data.record) {
					            	return '<input type="text" name="date_requested" id="date-requested" size="20" value="' + data.record.date_requested + '" />';
					        	} else {
					        		return '<input type="text" name="date_requested" size="20" id="date-requested" value="" />';
					        	}
					    }
				},
				staffmember: {
					title: 'Requester',
					width: '30%',
					list: true,
					edit: false,
					create: false,
					sorting: false
				},
				campus_name: {
					title: 'Campus',
					width: '30%',
					edit: false,
					create: false,
					list: true,
					sorting: false
				},
				fac_desc: {
					title: 'Facuty',
					width: '30%',
					edit: false,
					create: false,
					list: true,
					sorting: false
				},
				mailButton: {
					list: true,
					create: false,
					edit: false,
					display: function (data) {
						
							return '<input type="image" src="/scripts/assets/email.png" alt="Email form to user." onclick="emailForm('+data.record.id+','+data.record.requester+')" />';
	
					}
				},
				requester: {
					title: 'Requester',
					edit: true,
					create: true,
					list: false,
					sorting: false,
					options: '/scripts/assets/ajax.php?action=listRequesters'
				},
			    campus: {
					title: 'Campus',
					edit: true,
					create: true,
					list: false,
					sorting: false,
					options: '/scripts/assets/ajax.php?action=listCampus'
				},
				building: {
					title: 'Building',
					edit: true,
					create: true,
					list: false,
					sorting: false,
					dependsOn: 'campus',
					options: function (data){
						if (data.source == 'list') {
							return '/scripts/assets/ajax.php?action=listBuildings&campus=0';
						}
						return '/scripts/assets/ajax.php?action=listBuildings&campus=' + data.dependedValues.campus;
					}	
				},
				faculty: {
					title: 'Faculty',
					edit: true,
					create: true,
					list: false,
					sorting: false,
					options: '/scripts/assets/ajax.php?action=listFaculties'
				},
				dept: {
					title: 'Department',
					edit: true,
					create: true,
					list: false,
					sorting: false,
					dependsOn: 'faculty',
					options: function (data){
						if (data.source == 'list') {
							return '/scripts/assets/ajax.php?action=listDepartments&faculty=0';
						}
						return '/scripts/assets/ajax.php?action=listDepartments&faculty=' + data.dependedValues.faculty;
					}	
				}
            },
				formCreated: function (event, data) {
				jt('#date-requested').datepicker({
						dateFormat: 'yy-mm-dd'
				});
                data.form.find('input[name="requester"]').addClass('validate[required]');
				data.form.find('input[name="date_requested"]').addClass('validate[required]');
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
		
		jt('#tableForms').jtable('load');
}

function emailForm(id,staffno) {
	var x = new Request({
		url: '/scripts/assets/ajax.php?action=getEmail&user='+staffno,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			if (response == 'ERR'){
			} else {
				var email = response;
				if (confirm('Send document to user '+email+' ?') == true){
					var x = new Request({
						url: '/scripts/assets/form.php?id='+id+'&email='+email,
						method: 'get',
						noCache: true,
						onComplete: function(response){
							alert('Document(s) sent to user '+email);
						}
					}).send();
				}
			}
		}
	}).send();
}
