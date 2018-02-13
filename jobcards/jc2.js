var tableFlag = false;
var staffno = 0;

window.addEvent('domready',function(){
	jt( "#tabs" ).tabs();
	jt("#job-campus").menu();
	
	$('job-campus').addEvent('change',function(){
		if (tableFlag == true){
			jt('#tableJobcards').jtable('destroy');
			displayJobcards();
		}
	});
	
		
	jt("#report-artisan-form").submit(function(e){
		e.preventDefault();
	    jt.post(
	      jt(this).attr('action'),
	      jt(this).serialize(),
	      function(data){
	       // $j.colorbox({html: data, onComplete: function(){
	        	$j.colorbox({
	        			html:data,
	        			width: '500',
	        			height: '600'
	        		});
	       });
	  });
	
	$('filter-button').addEvent('click',function(e){
		e.stop();
		if (tableFlag == true){
			jt('#tableJobcards').jtable('destroy');
			displayJobcards();
		}
	});
	
	var uname = $('username').get('value');
	getUserStaffNo(uname);
	displaySuppliers();
});

function getUserStaffNo(uname){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=getStaffNo&login='+uname,
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			if (parseInt(response) == 0 ){
				alert('No login/staff number validation found.');
				return false;
			} else {
				staffno = response;
				displayCampus(staffno);
				displayJobcards();
			}
		}	
		}).send();
}

function displaySuppliers(){
	jt('#tableSuppliers').jtable({
            title: 'SUPPLIERS',
			paging: false, // Enable paging
            pageSize: 15, // Set page size (default: 10)
            sorting: false, // Enable sorting
			selecting: true, // Enable selecting,
			dialogWidth: 300,
			loadingAnimationDelay: 0,
			jqueryuiTheme: true,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=104&action=listSuppliers',
                createAction: 'index.php?option=com_jumi&fileid=104&action=createSuppliers',
				updateAction: 'index.php?option=com_jumi&fileid=104&action=updateSuppliers',
				deleteAction: 'index.php?option=com_jumi&fileid=104&action=deleteSuppliers'
            },
            fields: {
				id: {
					list: false,
					key: true
				},
				supplier_name: {
					list: true,
					title: 'SUPPLIER NAME',
					width: '100%',
					create: true,
					edit: true,
					input: function (data) {
				        if (data.record) {
				            	return '<input type="text" name="supplier_name" size="50" value="' + data.record.supplier_name + '" onkeyup="javascript: this.value=this.value.toUpperCase();" />';
				        	} else {
				        		return '<input type="text" name="supplier_name" size="50"  value="" onkeyup="javascript: this.value=this.value.toUpperCase();" />';
				        	}
				    	}
				}
            },
            	rowUpdated: function (event, data) {
            		jt('#tableSuppliers').jtable('reload');
            	},
            	formCreated: function (event, data) {
                data.form.find('input[name="supplier_name"]').addClass('validate[required]');
                data.form.validationEngine({promptPosition : 'topLeft', scroll: false});
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
		jt('#tableSuppliers').jtable('load');
}

function displayCampus(localStaffno){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=displayCampus&staffno='+localStaffno,
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			$('job-campus').empty();
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					var r = text.split(';');
					new Element('option',{ 'value':r[0],'text':r[1]}).inject($('job-campus'));
				}
			});
			var y = new Request({
				url: 'index.php?option=com_jumi&fileid=104&action=setDefaultCampus&staffno='+localStaffno,
				method: 'get',
				noCache: true,
				onComplete: function(response){
					var res = parseInt(response);
					if (res > 0){
						var cmpObj = $('job-campus');
						for (var i = 0; i < cmpObj.options.length; i++) {
					        if (cmpObj.options[i].value == res) {
					            cmpObj.options[i].selected = true;
					            if (tableFlag == true){
					    			jt('#tableJobcards').jtable('destroy');
					    			displayJobcards();
					    		}
					        }
					    }
					}
				}
			}).send();
		}
	}).send();
}

function displayManagers(){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=get_esc_hours',
		method: 'get',
		noCache: true,
		onComplete: function(response){
			$('esc-hours').set('value',response);
		}
	}).send();
	
	tableFlag = true;
	jt('#tableManagers').jtable({
            title: ' ',
			paging: false, // Enable paging
            pageSize: 15, // Set page size (default: 10)
            sorting: false, // Enable sorting
            defaultSorting: 'staff_sname ASC', // Set default sorting
			selecting: true, // Enable selecting,
			dialogWidth: 500,
			loadingAnimationDelay: 0,
			jqueryuiTheme: true,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=104&action=listManagers',
                createAction: 'index.php?option=com_jumi&fileid=104&action=createManager',
                updateAction: 'index.php?option=com_jumi&fileid=104&action=updateManager',
				deleteAction: 'index.php?option=com_jumi&fileid=104&action=deleteManager'
            },
            fields: {
				id: {
					key: true,
					list: false
				},
				staffno: {
					title: 'STAFF#',
					list: false,
					create: true,
					options: 'index.php?option=com_jumi&fileid=104&action=listStaff'
				},
				campus: {
					title: 'CAMPUS',
					list: false,
					create: true,
					options: 'index.php?option=com_jumi&fileid=104&action=listCampus'
				},
				fullname: {
					title: 'FULL NAME',
					list: true,
					create: false,
					edit: false,
					width: '60%'
				},
				campus_name: {
					title: 'CAMPUS',
					width: '40%',
					create: false,
					edit: false,
					list: true
				}
            },
            	rowUpdated: function (event, data) {
            		jt('#tableManagers').jtable('reload');
            	}
        });
		
		jt('#tableManagers').jtable('load');
}

function setEmail(staffno){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=getEmail&staffno='+staffno,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			if (parseInt(response) != 0){
				$('app-email').set('value',response);
				$('contact-no').focus();
			} else {
				$('app-email').set('value','');
				alert('No validated email address found. Please request email address.');
				$('contact-no').focus();
			}
		}
	}).send();
}

function displayJobcards(){
	var cmp = $('job-campus').getSelected().get('value');
	var operator = $('uid').get('value');
	var jobno = $('job-filter').get('value');
	jt('#tableJobcards').jtable({
            title: ' ',
			paging: true, // Enable paging
            pageSize: 25, // Set page size (default: 10)
            sorting: true, // Enable sorting
            defaultSorting: 'capture_date DESC', // Set default sorting
			selecting: true, // Enable selecting,
			dialogWidth: 700,
			loadingAnimationDelay: 0,
			jqueryuiTheme: true,
			recordAdded: (function (event, data) {
					if (confirm('Do you wish to send an acknowledgment email?')){
            			sendConfEmail(data.record.id);
            		}
			}),
			rowInserted: (function (event, data) {
				if (parseInt(data.record.job_status) == 3 || parseInt(data.record.job_status) == 6) {
					data.row.find('.jtable-edit-command-button').hide(); 
				}
				switch (parseInt(data.record.job_status))	{
				case 1: 
					jt('#tableJobcards').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#66ff66");
					break;
				case 2: 
					jt('#tableJobcards').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#ffff99");
					break;
				case 3:
					jt('#tableJobcards').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#ffffff");
					break;
				case 4:
					jt('#tableJobcards').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#5983ff");
					break;
				case 5:
					jt('#tableJobcards').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#ff8888");
					break;
				case 6:
					jt('#tableJobcards').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#000000");
					break;
				default:
					break;
				}
			}),
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=104&action=listJobcards&cid='+cmp+'&jid='+jobno,
                createAction: 'index.php?option=com_jumi&fileid=104&action=createJobcards',
                updateAction: 'index.php?option=com_jumi&fileid=104&action=updateJobcards'
                },
                ///////Toolbar////
                toolbar: {
					items: [{
						icon: '/scripts/jobcards/images/complete.png',
						text: 'Completion',
						click: function () {
								var $selectedRows = jt('#tableJobcards').jtable('selectedRows');
								if ($selectedRows.length > 0) {
									$selectedRows.each(function () {
										var record = jt(this).data('record');
										if (parseInt(record.job_status) != 2) {
											alert('Only assigned jobs can be completed.');
										} else {
							    				jt('#prn-link').attr('href','index.php?option=com_jumi&fileid=168&tmpl=component&jid='+record.id);
											    jt('#prn-link')[0].click();
										}
									});
								}	else {
									alert('Please select record.');
								}
						}
				},
				{
					icon: '/scripts/jobcards/images/delay.png',
					text: 'Delay',
					click: function () {
							var $selectedRows = jt('#tableJobcards').jtable('selectedRows');
							if ($selectedRows.length > 0) {
								$selectedRows.each(function () {
									var record = jt(this).data('record');
									if (parseInt(record.job_status) != 1 && parseInt(record.job_status) != 6) {
										delayAssign(record.id);
									}
									/*if (parseInt(record.job_status) == 2) {
											jt('#prn-link').attr('href','index.php?option=com_jumi&fileid=166&tmpl=component&jid='+record.id);
											jt('#prn-link')[0].click();
									 } else if (parseInt(record.job_status) == 4) {
										 	if (confirm('This action will put the job status to assigned. Are you sure?')) {
										 		jt.ajax({
													url: 'index.php?option=com_jumi&fileid=104&action=undoDelayed&jid='+record.id,
													dataType: 'text',
													type:     'GET',
													async: false,
													success:  function(data){
														jt('#tableJobcards').jtable('reload');  
													}
												});	
										 	}
									 } else {
										 alert('You can only delay assigned jobcards or recommence delayed jobcards.');
									 }*/
								});
							} else {
								alert('Please select record.');
							}
					}
				},
				{
					icon: '/scripts/jobcards/images/print.png',
					text: 'Print',
					click: function () {
							var $selectedRows = jt('#tableJobcards').jtable('selectedRows');
							if ($selectedRows.length > 0) {
								$selectedRows.each(function () {
									var record = jt(this).data('record');
									if (record.job_status != 1 && record.job_status != 6) {
										 jt('#prn-link').attr('href','index.php?option=com_jumi&fileid=105&tmpl=component&jid='+record.id);
					                     jt('#prn-link')[0].click();
									} else {
										alert('Jobcard not assigned or cancelled.');
									}
								});
							} else {
								alert('Please select record.');
							}
					}
				},
				{
					icon: '/scripts/jobcards/images/assign.png',
					text: 'Assign',
					click: function () {
							var $selectedRows = jt('#tableJobcards').jtable('selectedRows');
							if ($selectedRows.length > 0) {
								$selectedRows.each(function () {
									var record = jt(this).data('record');
									if (record.job_status != 3 && record.job_status != 6) {
										 jt('#jobcard-artisans').attr('href','index.php?option=com_jumi&fileid=165&tmpl=component&jid='+record.id);
					                     jt('#jobcard-artisans')[0].click();
									} else {
										alert('Current job status cannot allow action.');
									}
								});
							} else {
								alert('Please select record.');
							}
					}
				},
				{
					icon: '/scripts/jobcards/images/cancel.png',
					text: 'Cancel',
					click: function () {
							var $selectedRows = jt('#tableJobcards').jtable('selectedRows');
							if ($selectedRows.length > 0) {
								$selectedRows.each(function () {
									var record = jt(this).data('record');
											if (record.job_status != 3 && record.job_status != 6){
												////Do email notification and change status
											  if (confirm('Are you sure?')) {
													////Send emails
													jt.ajax({
													url:      'index.php?option=com_jumi&fileid=104&action=cancelJobcard&jid='+record.id,
													dataType: 'text',
													type:     'GET',
													async: false,
													success:  function(data){
														jt('#tableJobcards').jtable('reload');  
													}
												});
												alert('Jobcard cancelled.');	
											  }
											} else {
												alert('Cannot cancel completed/cancelled jobcard.');
											}
								});
							} else {
								alert('Please select record.');
							}
					}
				}
			
				]},
            fields: {
				id: {
					key: true,
					list: true,
					title: '#',
					edit: false,
					width: '3%',
					sorting: false
				},
				creator: {
					list: false,
					create: true,
					edit: false,
					type: 'hidden',
					defaultValue: operator
				},
				capture_date: {
					title: 'CAPTURED',
					width: '16%',
					list: true,
					create: false,
					edit: false				},
				applicant: {
					title: 'APPLICANT',
					list: true,
					create: true,
					edit: true,
					options: 'index.php?option=com_jumi&fileid=104&action=listStaff',
					onchange: 'setEmail(this.value)',
					width: '30%'
				},
				contact_no: {
					title: 'CONTACT#',
					list: false,
					create: true,
					edit: true,
					input: function (data) {
				        if (data.record) {
				            	return '<input type="text" name="contact_no" id="contact-no" size="15" value="' + data.record.contact_no + '" />';
				        	} else {
				        		return '<input type="text" name="contact_no" size="15" id="contact-no" value="" />';
				        	}
				    	}
				},
				contact_time: {
					title: 'CONTACT TIME',
					list: false,
					create: true,
					edit: true,
					input: function (data) {
				        if (data.record) {
				            	return '<input type="text" name="contact_time" id="contact-time" size="50" value="' + data.record.contact_time + '" onkeyup="this.value=this.value.toUpperCase();" />';
				        	} else {
				        		return '<input type="text" name="contact_time" size="50" id="contact-time" value="" onkeyup="this.value=this.value.toUpperCase();" />';
				        	}
				    	}
				},
				email: {
					title: 'EMAIL ADDRESS',
					list: false,
					edit: true,
					create: true,
					input: function (data) {
				        if (data.record) {
				            	return '<input type="text" name="email" id="app-email" size="50" value="' + data.record.email + '" />';
				        	} else {
				        		return '<input type="text" name="email" size="50" id="app-email" value="" />';
				        	}
				    	}
				},
				
				campus: {
					title: 'CAMPUS',
					list: true,
					create: true,
					edit: true,
					sorting: false,
					options: 'index.php?option=com_jumi&fileid=104&action=listCampus&cid='+cmp,
					width: '25%'
				},
				building: {
					title: 'BUILDING',
					width: '30%',
					list: true,
					create: true,
					edit: true,
					dependsOn: 'campus',
					sorting: false,
					options: function (data) {
                        if (data.source == 'list') {
                            return 'index.php?option=com_jumi&fileid=104&action=listBuildings';
                        }
                         return 'index.php?option=com_jumi&fileid=104&action=listBuildings&cid=' + data.dependedValues.campus;
                    }
				},
				roomno: {
					title: 'ROOM#',
					list: false,
					create: true,
					edit: true,
					input: function (data) {
				        if (data.record) {
				            return '<input type="text" name="roomno" size="10" onkeyup="this.value=this.value.toUpperCase();" value="' + data.record.roomno + '" />';
				        } else {
				            return '<input type="text" name="roomno" size="10" onkeyup="this.value=this.value.toUpperCase();" value="" />';
				        }
				    }
				},
				vandalism: {
					title: 'VANDALISM',
					type: 'checkbox',
                    values: { '0': 'No', '1': 'Yes' },
                    defaultValue: '0',
                    create: true,
                    edit: true,
                    list: false
				},
				job_details: {
					title: 'JOB DETAILS',
					type: 'textarea',
					create: true,
					edit: true,
					list: false
				},
				urgent: {
					title: 'URGENT?',
					type: 'checkbox',
                    values: { '0': 'No', '1': 'Yes' },
                    defaultValue: '0',
                    create: true,
                    edit: true,
                    list: false
				}
            },
            	
            	rowUpdated: function (event, data) {
            		jt('#tableJobcards').jtable('reload');
            	},
            	recordsLoaded: function(event, data) {
                    jt('.jtable-data-row').dblclick(function() {
                        var row_id = jt(this).attr('data-record-key');
                        jt('#jobcard-details-link').attr('href','index.php?option=com_jumi&fileid=164&tmpl=component&jid='+row_id+'&prn=1');
                        jt('#jobcard-details-link')[0].click();
                    });
                },
            	formCreated: function (event, data) {
					data.form.find('input[name="email"]').addClass('validate[required]');
					data.form.find('input[name="contact_no"]').addClass('validate[required]');
					data.form.find('input[name="job_details"]').addClass('validate[required]');
	                data.form.validationEngine({promptPosition : 'topLeft', scroll: false});
					},
					// Validate form when it is being submitted
				formSubmitting: function (event, data) {
						return data.form.validationEngine('validate');
					},
					// Dispose validation logic when form is closed
				formClosed: function (event, data) {
						data.form.validationEngine('hide');
						data.form.validationEngine('detach');
					}
        });
		jt('#tableJobcards').jtable('load');
		tableFlag = true;
}

function sendConfEmail(jid){
	$('new-job-ajax').setStyle('display','block');
	var x = new Request({
	url: 'index.php?option=com_jumi&fileid=104&action=sendAck&jid='+jid,
	method: 'get',
	noCache: true,
	onComplete: function(response){
		$('new-job-ajax').setStyle('display','none');
	}
	}).send();
}

function openArtisanRepParams(){
	jt("#start-date").val("");
	jt("#end-date").val("");
	jt("#start-date").datepicker(
		{
			dateFormat: "yy-mm-dd"
		}
	);
	jt("#end-date").datepicker(
			{
				dateFormat: "yy-mm-dd"
			}
	);
	
	var localCampus = $('job-campus').getSelected().get('value');
	$('select-artisan').set('html','');
	var text = '';
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=repArtisansList&cmp='+localCampus,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			var data = json_parse(response);
			for (var i = 0; i<data.Records.length; ++i){
				text = text + '<input type="checkbox" name="artisan_report_select'+data.Records[i].staffno+'" value="'+data.Records[i].staffno+'">'+data.Records[i].fullname+'<br />';
			}
			$('select-artisan').set('html',text);
		}
	}).send();
	
	
	jt("#dialog-report-artisan").dialog({
        autoOpen: true,
		title: 'Select date range',
        height: 400,
        width: 450,
        modal: true,
		buttons: {
			'Run Report': function() {
				jt( this ).dialog( "close" );
				jt("#report-artisan-form").submit();
			},
			Cancel: function() {
				jt( this ).dialog( "close" );
			}
		}
    });     
}

function openBuildingRepParams(){
	jt("#cost-start-date").val("");
	jt("#cost-end-date").val("");
	jt("#cost-start-date").datepicker(
			{
				dateFormat: "yy-mm-dd"
			}
		);
		jt("#cost-end-date").datepicker(
				{
					dateFormat: "yy-mm-dd"
				}
		);
	var localCampus = $('job-campus').getSelected().get('value');
	jt("#dialog-cost-report").dialog({
        autoOpen: true,
		title: 'Select Campus',
        height: 270,
        width: 450,
        modal: true,
		buttons: {
			'Run Report': function(){
				jt('#prn-link').attr('href','/scripts/jobcards/repCostBuilding.php?sdate='+jt("#cost-start-date").val()+'&edate='+jt("#cost-end-date").val()+'&campus='+localCampus);
				jt('#prn-link')[0].click();
				jt( this ).dialog( "close" );
			},
			Cancel: function() {
				jt( this ).dialog( "close" );
			}
		}
    });     
}

function delayAssign(id){
	jt("#dialog-delay-assign").dialog({
        autoOpen: true,
		title: 'Delay or Re-Assign',
        height: 250,
        width: 450,
        modal: true,
		buttons: {
			'Re-Assign': function() {
				jt.ajax({
					url: 'index.php?option=com_jumi&fileid=104&action=undoDelayed&jid='+id,
					dataType: 'text',
					type:     'GET',
					async: false,
					success:  function(data){
						jt('#tableJobcards').jtable('reload');  
					}
				});	
				jt('#jobcard-artisans').attr('href','index.php?option=com_jumi&fileid=165&tmpl=component&jid='+id);
                jt('#jobcard-artisans')[0].click();
				jt( this ).dialog( "close" );
			},
			'Delay Entry': function(){
				jt('#prn-link').attr('href','index.php?option=com_jumi&fileid=166&tmpl=component&jid='+id);
				jt('#prn-link')[0].click();
				jt( this ).dialog( "close" );
			},
			Cancel: function() {
				jt( this ).dialog( "close" );
			}
		}
    });     
}

function openJobcardsParams(){
	jt("#open-start-date").val("");
	jt("#open-end-date").val("");
	jt("#open-start-date").datepicker(
			{
				dateFormat: "yy-mm-dd"
			}
		);
		jt("#open-end-date").datepicker(
				{
					dateFormat: "yy-mm-dd"
				}
		);
	jt("#dialog-open-report").dialog({
        autoOpen: true,
		title: 'Select Paramaters',
        height: 300,
        width: 450,
        modal: true,
		buttons: {
			'Run Report': function(){
				var sdate = jt("#open-start-date").val();
				var edate = jt("#open-end-date").val();
				var localCampus = $('job-campus').getSelected().get('value');
				var jtype = jt('input[name=jotype]:checked').val();
				var jurgent = 0;
				if (jt("#jo-urgent").is(':checked')){
					jurgent = 1;
				}
				jt('#prn-link').attr('href','/scripts/jobcards/repOpenJobcards.php?sdate='+sdate+'&edate='+edate+'&campus='+localCampus+'&jtype='+jtype+'&urgent='+jurgent);
				jt('#prn-link')[0].click();
				jt( this ).dialog( "close" );
			},
			Cancel: function() {
				jt( this ).dialog( "close" );
			}
		}
    });     
}



