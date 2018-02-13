var cutoff = '';
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
				cutoff = data.Cutoff;
				popForms(data.Manager,data.StaffNumber,data.Section);
			}
		}
	}).send();
}

function popForms(manager,staffno,section){
	jt('#tableForms').jtable({
            title: 'Overtime Claims - Cutoff date for submission to payroll office is '+cutoff,
			paging: true, //Enable paging
            pageSize: 25, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'claim_date DESC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 500,
            actions: {
                listAction: '/scripts/overtime/ajax.php?action=listClaims&manager='+manager+'&staffno='+staffno+'&section='+section
                //createAction: '/scripts/overtime/ajax.php?action=createClaims',
				//updateAction: '/scripts/overtime/ajax.php?action=editClaims'
				//deleteAction: '/scripts/overtime/ajax.php?action=deleteForms',
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
														if (formno.record.stat == 'D'){
															alert('The authorization for this claim is still outstanding.\nPlease contact your supervisor.');
														} else {
																jt('#tableForms').jtable('openChildTable',
																$img.closest('tr'), //Parent row
																{
																title: 'Claim Form Details',
																sorting: false,
																dialogWidth: 500,
																selecting: true,
																defaultSorting: 'id ASC',
																recordUpdated: (function (event, data) { jt('#tableForms').find('.jtable-child-table-container').jtable('reload'); }),
																recordDeleted: (function (event, data) { jt('#tableForms').find('.jtable-child-table-container').jtable('reload'); }),
																recordAdded: (function (event, data) { jt('#tableForms').find('.jtable-child-table-container').jtable('reload'); }),
																//closeRequested: (function (event, data) { jt('#tableData').jtable('reload'); }),
																actions: {
																	listAction: '/scripts/overtime/ajax.php?action=listClaimItems&formno='+formno.record.form_no,
																	createAction: '/scripts/overtime/ajax.php?action=createClaimItems',
																	deleteAction: '/scripts/overtime/ajax.php?action=deleteClaimItems',
																	updateAction: '/scripts/overtime/ajax.php?action=updateClaimItems'
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
																	claim_date_item: {
																		title: 'Claim Date',
																		width: '15%',
																		create: true,
																		list: true,
																		edit: true,
																		sorting: true,
																		defaultDateFormat: 'yy-mm-dd',
																		input: function (data) {
																			if (data.record) {
																				return '<input type="text" name="claim_date_item" id="claim-date-item" size="10" value="' + data.record.claim_date_item + '" />';
																			} else {
																				return '<input type="text" name="claim_date_item" size="10" id="claim-date-item" value="" />';
																			}
																		}
																	},
																	start_time: {
																		title: 'Start Time',
																		width: '15%',
																		create: true,
																		list: true,
																		edit: true,
																		sorting: false,
																		input: function (data) {
																			if (data.record) {
																				return '<input type="text" name="start_time" id="start-time" size="10" value="' + data.record.start_time + '"/>';
																			} else {
																				return '<input type="text" name="start_time" size="10" id="start-time" value="" />';
																			}
																		}
																	},
																	end_time: {
																		title: 'End Time',
																		width: '15%',
																		create: true,
																		list: true,
																		edit: true,
																		sorting: false,
																		input: function (data) {
																			if (data.record) {
																				return '<input type="text" name="end_time" id="end-time" size="10" value="' + data.record.end_time + '" />';
																			} else {
																				return '<input type="text" name="end_time" size="10" id="end-time" value="" />';
																			}
																		}
																	},
																	day_of_week: {
																		title: 'Day',
																		width: '30%',
																		list: true,
																		create: false,
																		edit: false,
																		sorting: false
																	},
																	hours: {
																		title: 'Hours',
																		width: '10%',
																		list: true,
																		create: false,
																		edit: false,
																		sorting: false
																	},
																	duties: {
																		title: 'Duties',
																		create: true,
																		edit: true,
																		list: false,
																		sorting: false,
																			input: function (data) {
																			if (data.record) {
																				return '<textarea cols="40" rows="2" name="duties" id="id-duties">' + data.record.duties + '</textarea>';
																			} else {
																				return '<textarea cols="40" rows="2" name="duties" id="id-duties"></textarea>';
																			}
																		}
																	}
																	
																},
																	formCreated: function (event, data) {
																			jt('#claim-date-item').datepicker({
																				dateFormat: 'yy-mm-dd'
																			});
																		
																			jt('#start-time').timepicker({
																				timeFormat: 'HH:mm'
																			});

																			jt('#end-time').timepicker({
																				timeFormat: 'HH:mm'
																			});
																		if (parseInt(manager) == 0){
																			jt('#id-duties').prop('disabled',true);
																		}

																		data.form.find('input[name="claim_date_item"]').addClass('validate[required]');
																		data.form.find('input[name="start_time"]').addClass('validate[required]');
																		data.form.find('input[name="end_time"]').addClass('validate[required]');
																		data.form.find('input[name="auth_hours"]').prop('disabled',true);
																		data.form.validationEngine();
																		},
																		//Validate form when it is being submitted
																		formSubmitting: function (event, data) {
																			if (jt('#id-duties').prop('disabled')){
																				jt('#id-duties').prop('disabled',false);
																			}
																			return data.form.validationEngine('validate');
																		},
																		//Dispose validation logic when form is closed
																		formClosed: function (event, data) {
																			data.form.validationEngine('hide');
																			data.form.validationEngine('detach');
																		},
																		rowInserted: function(event, data){
																			if (parseInt(manager) == 0){
																					data.row.find('.jtable-delete-command-button').hide();
																					jt('.jtable-toolbar-item-add-record').hide();
																			} else {
																				data.row.find('.jtable-delete-command-button').show();
																				jt('.jtable-toolbar-item-add-record').show();
																			}

																			if (formno.record.stat == 'F'){
																					data.row.find('.jtable-delete-command-button').hide();
																					data.row.find('.jtable-edit-command-button').hide();
																					jt('.jtable-toolbar-item-add-record').hide();
																			}
																			
																		}
																},
																	function (data) { //opened handler
																		data.childTable.jtable('load');
																});
														}
											
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
				auth_no: {
					title: 'Auth#',
					width: '10%',
					list: true,
					edit: false,
					create: false,
					sorting: false
				},
				claim_date: {
					title: 'Date',
					width: '20%',
					list: true,
					edit: true,
					defaultDateFormat: 'yy-mm-dd',
					create: true,
					sorting: true,
						input: function (data) {
					        if (data.record) {
					            	return '<input type="text" name="claim_date" id="claim-date" size="10" value="' + data.record.claim_date + '" />';
					        	} else {
					        		return '<input type="text" name="claim_date" size="10" id="claim-date" value="" />';
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
				printButton: {
					list: true,
					width: '5%',
					create: false,
					edit: false,
					display: function (data) {
							return '<input type="image" src="/images/print.png" onclick="printAuth('+data.record.form_no+',\''+data.record.stat+'\');" />';
					}
				}
				
            },
				recordUpdated: function(event, data){
					if (parseInt(data.serverResponse.Email) == 1){
						alert('A status update email has been sent to recipient.');
					}
				},
				formCreated: function (event, data) {
				data.form.find('input[name="claim_date"]').click(function(){
					jt('#claim-date').datepicker({
						dateFormat: 'yy-mm-dd'
					});
				});

                data.form.find('input[name="claim_date"]').addClass('validate[required]');
                data.form.validationEngine();
				data.form.find('input[name="claim_date"]').click();
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

function printAuth(id,stat){
	if (stat == 'D'){
		alert('Claim not authorized. Please contact your supervisor.');
	} else {
			if (confirm('Print document?') == true){
					var x = new Request({
						url: '/scripts/overtime/ajax.php?action=freezeClaim&id='+id,
						method: 'get',
						noCache: true,
						onComplete: function(response){
							jt('#tableForms').jtable('reload');
						}
					}).send();
					$('print-auth').set('href','/scripts/overtime/form_claim.php?id='+id);
					$('print-auth').click();
			}
			
		}
}
