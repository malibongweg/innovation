window.addEvent('domready',function(){
	jt( "#tabs" ).tabs();
	
	$('save-esc').addEvent('click',function(){
		var hours = $('esc-hours').get('value');
		var email = $('esc-email').get('value');
		var email2 = $('esc-email2').get('value');
		var send = 0;
		if ($('esc-send').checked) { send = 1; } else { send = 0; }
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=104&action=save_general',
			method: 'post',
			noCache: true,
			onComplete: function(){
				alert('Updated...');
			}
		}).send('esc='+hours+'&email='+email+'&email2='+email2+'&send='+send);
	});
	
	displayManagers();
	displayForemans();
	displayTrades();
	displayArtisans();
});


function displayManagers(){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=get_esc_hours',
		method: 'get',
		noCache: true,
		onComplete: function(response){
			var data = response.split(';');
			$('esc-hours').set('value',data[0]);
			$('esc-email').set('value',data[1]);
			$('esc-email2').set('value',data[2]);
			if (parseInt(data[3]) == 1) { $('esc-send').checked = true; } else {$('esc-send').checked = false;}
		}
	}).send();
	
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

function displayForemans(){
	jt('#tableForeman').jtable({
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
                listAction: 'index.php?option=com_jumi&fileid=104&action=listForeman',
                createAction: 'index.php?option=com_jumi&fileid=104&action=createForeman',
                //updateAction: 'index.php?option=com_jumi&fileid=104&action=updateForeman',
				deleteAction: 'index.php?option=com_jumi&fileid=104&action=deleteForeman'
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
					width: '40%'
				},
				campus_name: {
					title: 'MAIN CAMPUS',
					width: '60%',
					create: false,
					edit: false,
					list: true
				}
            },
            	rowUpdated: function (event, data) {
            		jt('#tableForeman').jtable('reload');
            	},
            	formCreated: function(event, data){
                    //console.log('formCreated event fired: ',event, data);
                    if(data.formType === "create"){
                        var select = data.form.find('select[name=campus]');//your select id here
                        var selectName = select.prop('campus');
                        select.prop('multiple', "multiple");
                        select.prop('name', "campus[]");

                    }
                }
        });
		
		jt('#tableForeman').jtable('load');
}

function displayTrades(){
	jt('#tableTrades').jtable({
            title: ' ',
			paging: true, // Enable paging
            pageSize: 15, // Set page size (default: 10)
            sorting: true, // Enable sorting
            defaultSorting: 'trade_description ASC', // Set default sorting
			selecting: true, // Enable selecting,
			dialogWidth: 700,
			loadingAnimationDelay: 0,
			jqueryuiTheme: true,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=104&action=listTrades',
                createAction: 'index.php?option=com_jumi&fileid=104&action=createTrades',
                updateAction: 'index.php?option=com_jumi&fileid=104&action=updateTrades',
				deleteAction: 'index.php?option=com_jumi&fileid=104&action=deleteTrades'
            },
            fields: {
				id: {
					key: true,
					list: false
				},
				trade_description: {
					title: 'TRADE DESCRIPTION',
					list: true,
					create: true,
					edit: true,
					width: '50%',
					input: function (data) {
				        if (data.record) {
				            return '<input type="text" name="trade_description" size="50" maxlength="100" value="' + data.record.trade_description + '" onKeyUp="this.value=this.value.toUpperCase();" />';
				        } else {
				            return '<input type="text" name="trade_description" size="50" maxlength="100" value="" placeholder="Enter description here..." onKeyUp="this.value=this.value.toUpperCase();" />';
				        }
				    }
				},
				labour_cost: {
					title: 'LABOUR COST/HOUR',
					list: true,
					create: true,
					edit:true,
					sorting: false,
					width: '25%',
					input: function (data) {
				        if (data.record) {
				            return '<input type="text" name="labour_cost"  size="6" maxlength="6" value="' + data.record.labour_cost + '" />';
				        } else {
				            return '<input type="text" name="labour_cost" size="6"  maxlength="6" value="" />';
				        }
				    }
				},
				display_web: {
					title: 'DISPLAY ON WEB',
					list: true,
					create: true,
					edit: true,
					sorting: false,
					type: 'checkbox',
                    values: { '0': 'No', '1': 'Yes' },
                    defaultValue: '0',
					width: '25%'
				}
            },
            	rowUpdated: function (event, data) {
            		jt('#tableTrades').jtable('reload');
            	},
            	formCreated: function (event, data) {
					data.form.find('input[name="trade_description"]').addClass('validate[required]');
					data.form.find('input[name="labour_cost"]').addClass('validate[custom[number]]');
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
		
		jt('#tableTrades').jtable('load');
}

function displayArtisans(){
	jt('#tableArtisans').jtable({
            title: ' ',
			paging: true, // Enable paging
            pageSize: 15, // Set page size (default: 10)
            sorting: true, // Enable sorting
            defaultSorting: 'fullname ASC', // Set default sorting
			selecting: true, // Enable selecting,
			dialogWidth: 500,
			loadingAnimationDelay: 0,
			jqueryuiTheme: true,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=104&action=listArtisan',
                createAction: 'index.php?option=com_jumi&fileid=104&action=createArtisan',
                updateAction: 'index.php?option=com_jumi&fileid=104&action=updateArtisan',
				deleteAction: 'index.php?option=com_jumi&fileid=104&action=deleteArtisan'
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
					sorting: false,
					options: 'index.php?option=com_jumi&fileid=104&action=listStaff'
				},
				trade_code: {
					title: 'ARTISAN TRADE',
					list: false,
					create: true,
					edit: true,
					options: 'index.php?option=com_jumi&fileid=104&action=listArtisanTrades'
				},
				campus: {
					title: 'CAMPUS',
					list: false,
					create: true,
					sorting: false,
					options: 'index.php?option=com_jumi&fileid=104&action=listCampus'
				},
				fullname: {
					title: 'FULL NAME',
					list: true,
					create: false,
					edit: false,
					width: '50%'
				},
				trade_description: {
					title: 'ARTISAN TRADE',
					list: true,
					create: false,
					edit: false,
					width: '20%'
				},
				campus_name: {
					title: 'CAMPUS',
					width: '30%',
					create: false,
					edit: false,
					list: true
				}
            },
            	rowUpdated: function (event, data) {
            		jt('#tableArtisans').jtable('reload');
            	}
        });
		
		jt('#tableArtisans').jtable('load');
}
