window.addEvent('domready',function(){

	var uid = $('login-name').get('value');

	checkForManager(uid);
	
});

function checkForManager(uid){
	var x = new Request({
		url: '/scripts/overtime/ajax.php?action=checkForManager&uname='+uid,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			var data = json_parse(response);
			if (parseInt(data.Manager == -1)){
				alert('Could not locate user staff number. Please confirm your account on OPA.');
			} else {
				popForms(data.Manager,data.StaffNumber,data.Section);
			}
		}
	}).send();
}

function popForms(manager,staffno,section){
	jt('#tableForms').jtable({
            title: 'Overtime Authorizations',
			paging: true, //Enable paging
            pageSize: 25, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'form_date DESC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 500,
			recordUpdated: (function (event, data) { jt('#tableForms').jtable('reload'); }),
			recordDeleted: (function (event, data) { jt('#tableForms').jtable('reload'); }),
			recordAdded: (function (event, data) { jt('#tableForms').jtable('reload'); }),
            actions: {
                listAction: '/scripts/overtime/ajax.php?action=listForms&manager='+manager+'&staffno='+staffno+'&section='+section,
                createAction: '/scripts/overtime/ajax.php?action=createForms',
				updateAction: '/scripts/overtime/ajax.php?action=editForms',
				deleteAction: '/scripts/overtime/ajax.php?action=deleteForms'
            },
            fields: {
				form_img: {
					width: '5%',
					key: true,
					list: true,
					create: false,
					edit: false,
					sorting: false,
					title: '',
					display: function(formno){
									var $img = jt('<img src="/images/arrow1.png" title="" />');
									$img.click(function () {
														jt('#tableForms').jtable('openChildTable',
														$img.closest('tr'), //Parent row
														{
														title: 'Authorization Details',
														sorting: false,
														dialogWidth: 500,
														selecting: true,
														defaultSorting: 'id ASC',
														recordUpdated: (function (event, data) { jt('#tableForms').find('.jtable-child-table-container').jtable('reload'); }),
														recordDeleted: (function (event, data) { jt('#tableForms').find('.jtable-child-table-container').jtable('reload'); }),
														recordAdded: (function (event, data) { jt('#tableForms').find('.jtable-child-table-container').jtable('reload'); }),
														//closeRequested: (function (event, data) { jt('#tableData').jtable('reload'); }),
														actions: {
															listAction: '/scripts/overtime/ajax.php?action=listFormItems&formno='+formno.record.form_no,
															createAction: '/scripts/overtime/ajax.php?action=createFormItems',
															deleteAction: '',
															updateAction: ''
														},
														fields: {
															id: {
																key: true,
																list: false
															},
															form_number: {
																type: 'hidden',
																list: false,
																create: true,
																defaultValue: formno.record.form_no
															},
															start_date: {
																title: 'Start Date',
																width: '15%',
																create: true,
																list: true,
																edit: true,
																sorting: false,
																defaultDateFormat: 'yy-mm-dd',
																input: function (data) {
																	if (data.record) {
					            										return '<input type="text" name="start_date" id="start-date" size="10" value="' + data.record.start_date + '" />';
					        										} else {
					        											return '<input type="text" name="start_date" size="10" id="start-date" value="" />';
					        										}
																}
															},
															end_date: {
																title: 'End Date',
																width: '15%',
																create: true,
																list: true,
																edit: true,
																sorting: false,
																defaultDateFormat: 'yy-mm-dd',
																input: function (data) {
																	if (data.record) {
					            										return '<input type="text" name="end_date" id="end-date" size="10" value="' + data.record.start_date + '" />';
					        										} else {
					        											return '<input type="text" name="end_date" size="10" id="end-date" value="" />';
					        										}
																}
															},
															day_type: {
																title: 'Day Type',
																width: '20%',
																list: true,
																create: true,
																edit: true,
																sorting: false,
																options: {'1':'Weekday','2':'Saturday','3':'Sunday','4':'Holiday'}
															},
															duties: {
																title: 'Duties',
																width: '40%',
																create: true,
																edit: true,
																list: true,
																sorting: false,
																type: 'textarea'
															},
															auth_hours: {
																title: 'Hours',
																width: '10%',
																create: true,
																edit: true,
																list: true,
																sorting: false,
																	input: function (data) {
																	if (data.record) {
					            										return '<input type="text" name="auth_hours"  size="4" value="' + data.record.auth_hours + '" />';
					        										} else {
					        											return '<input type="text" name="auth_hours" size="4"  value="" />';
					        										}
																}
																}
														},
																formCreated: function (event, data) {
																	jt('#start-date').datepicker({
																		dateFormat: 'yy-mm-dd'
																	});

																	jt('#end-date').datepicker({
																		dateFormat: 'yy-mm-dd'
																	});

																data.form.find('input[name="start_date"]').addClass('validate[required]');
																data.form.find('input[name="end_date"]').addClass('validate[required]');
																data.form.find('input[name="auth_hours"]').addClass('validate[required]');
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
																},
																	rowInserted: function(event, data){
																		if (formno.record.stat == 'A'){
																			data.row.find('.jtable-delete-command-button').hide();
																			data.row.find('.jtable-edit-command-button').hide();
																			jt('.jtable-toolbar-item-add-record').hide();
																			}
																}
														},
															function (data) { //opened handler
																			data.childTable.jtable('load');
														});
											
									});
									return $img;
					}
				},
					form_no: {
					width: '5%',
					key: true,
					list: true,
					create: false,
					edit: false,
					sorting: false,
					title: 'ID#'
				},
				form_date: {
					title: 'Date',
					width: '20%',
					list: true,
					edit: true,
					defaultDateFormat: 'yy-mm-dd',
					create: true,
					sorting: true,
						input: function (data) {
					        if (data.record) {
					            	return '<input type="text" name="form_date" id="form-date" size="10" value="' + data.record.form_date + '" />';
					        	} else {
					        		return '<input type="text" name="form_date" size="10" id="form-date" value="" />';
					        	}
					    }
				},
				applicant: {
					title: 'Applicant',
					width: '60%',
					list: true,
					edit: false,
					create: false,
					sorting: true
				},
				staff_no: {
					title: '',
					list: false,
					edit: true,
					create: true,
					defaultValue: staffno,
					type: 'hidden'
				},
				manager: {
					title: 'Manager',
						list: false,
					edit: true,
					create: true,
					sorting: false,
					options: '/scripts/overtime/ajax.php?action=listManagers'
				},
				stat: {
					title: 'Status',
					list: false,
					edit: true,
					create: true,
					sorting: false,
					options: {'A':'Accepted','D':'Declined'}
				},
				stat_img: {
					title: 'Status',
					width: '5%',
					edit: false,
					create: false,
					list: true,
					sorting: false,
					display: function (data) {
							if (data.record.stat == 'A') {
								return '<input type="image" src="/images/accepted.png" />';
							}	else if (data.record.stat == 'D') {
								return '<input type="image" src="/images/declined.png" />';
							}
					}
				},
				printButton: {
					list: true,
					width: '5%',
					create: false,
					edit: false,
					display: function (data) {
							return '<input type="image" src="/images/print.png" onclick="printAuth('+data.record.form_no+');" />';
					}
				}
				
            },
				recordUpdated: function(event, data){
					if (parseInt(data.serverResponse.Email) == 1){
						alert('A status update email has been sent to recipient.');
					}
				},
				formCreated: function (event, data) {
					jt('#form-date').datepicker({
						dateFormat: 'yy-mm-dd'
					});

				if (parseInt(manager) == 0){
					jt('#Edit-stat').empty();
					jt('#Edit-stat').append(new Option("Not available...", ""));
				}
                data.form.find('input[name="form_date"]').addClass('validate[required]');
                data.form.validationEngine();
				data.form.find('input[name="form_date"]').click();
				},
				//Validate form when it is being submitted
				formSubmitting: function (event, data) {
					return data.form.validationEngine('validate');
				},
				//Dispose validation logic when form is closed
				formClosed: function (event, data) {
					data.form.validationEngine('hide');
					data.form.validationEngine('detach');
				},
				rowInserted: function(event, data){
					if (parseInt(manager) == 0 || data.record.stat == 'A'){
					data.row.find('.jtable-delete-command-button').hide();
					data.row.find('.jtable-edit-command-button').hide();
				  }
	            }
        });
		
	
		jt('#tableForms').jtable('load');
}

function emailForm(id,staffno) {
	var x = new Request({
		url: '/scripts/overtime/ajax.php?action=getEmail&user='+staffno,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			if (response == 'ERR'){
			} else {
				var email = response;
				if (confirm('Send document to user '+email+' ?') == true){
					var x = new Request({
						url: '/scripts/overtime/form.php?id='+id+'&email='+email,
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

function printAuth(id){
	var x = new Request({
		url: '/scripts/overtime/ajax.php?action=checkStatus&id='+id,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			if (parseInt(response) == 1) {
				if (confirm('Print document?') == true){
					$('print-auth').set('href','/scripts/overtime/form.php?id='+id);
					$('print-auth').click();
				}
			} else {
				alert('Form not authorized. Please contact your supervisor.');
			}
		}
	}).send();
}
