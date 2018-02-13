window.addEvent('domready',function(){
	
	
	popData();

});



function popData(){
	jt('#tableData').jtable({
            title: 'Database Configurations',
			paging: false, //Enable paging
            pageSize: 20, //Set page size (default: 10)
            sorting: false, //Enable sorting
            defaultSorting: 'staff_sname ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=146&action=displaySystems',
                createAction: 'index.php?option=com_jumi&fileid=136&action=createAccessCostCodes',
				updateAction: 'index.php?option=com_jumi&fileid=146&action=updateSystems'
            },
            fields: {
				id: {
					key: true,
					list: false,
                },
				system_name: {
					title: 'System Name',
					list: true,
					create: true,
					input: function(data){
						if (data.record){
							return '<input type="text" size="30" name="system_name" value="'+data.record.system_name+'" readonly onKeyUp="this.value=this.value.toLowerCase();" />';
						} else {
							return '<input type="text" size="30" name="system_name" value="" readonly onKeyUp="this.value=this.value.toLowerCase();" />';
						}
					}
				},
				host: {
					title: 'PR Host',
					list: true,
					create: true,
				},
								
				connect_string: {
					title: 'PR Connect String',
					list: false,
					create: true,
					input: function(data){
						if (data.record){
							return '<textarea name="connect_string" rows="3" cols="50" onKeyUp="this.value=this.value.toLowerCase();">'+data.record.connect_string+'</textarea>'; 
						} else {
							return '<textarea name="connect_string" rows="3" cols="50" onKeyUp="this.value=this.value.toLowerCase();"></textarea>'; 
						}
					}
				},
				user_name: {
					title: 'PR User Name',
					list: false,
					create: true,
					input: function(data){
						if (data.record){
							return '<input type="text" size="25" name="user_name" value="'+data.record.user_name+'" onKeyUp="this.value=this.value.toLowerCase();" />';
						} else {
							return '<input type="text" size="25" name="user_name" value="" onKeyUp="this.value=this.value.toLowerCase();" />';
						}
					}
				},
				password: {
					title: 'PR Password',
					list: false,
					create: true,
					input: function(data){
						if (data.record){
							return '<input type="password" size="25" name="password" value="'+data.record.password+'" onKeyUp="this.value=this.value.toLowerCase();" />';
						} else {
							return '<input type="password" size="25" name="password" value="" onKeyUp="this.value=this.value.toLowerCase();" />';
						}
					}
				},
				database_name: {
					title: 'PR Database Name',
					list: false,
					create: true,
					input: function(data){
						if (data.record){
							return '<input type="text" size="30" name="database_name" value="'+data.record.database_name+'" onKeyUp="this.value=this.value.toLowerCase();" />';
						} else {
							return '<input type="text" size="30" name="database_name" value="" onKeyUp="this.value=this.value.toLowerCase();" />';
						}
					}
				},
				disaster_host: {
					title: 'DR Host',
					list: true,
					create: true,
				},
				disaster_connect_string: {
					title: 'DR Connect String',
					list: false,
					create: true,
					input: function(data){
					if (data.record){
							return '<textarea name="disaster_connect_string" rows="3" cols="50" onKeyUp="this.value=this.value.toLowerCase();">'+data.record.disaster_connect_string+'</textarea>'; 
						} else {
							return '<textarea name="disaster_connect_string" rows="3" cols="50" onKeyUp="this.value=this.value.toLowerCase();"></textarea>'; 
						}
					}
				},
				disaster_user_name: {
					title: 'DR User Name',
					list: false,
					create: true,
					input: function(data){
						if (data.record){
							return '<input type="text" size="25" name="disaster_user_name" value="'+data.record.disaster_user_name+'" onKeyUp="this.value=this.value.toLowerCase();" />';
						} else {
							return '<input type="text" size="25" name="disaster_user_name" value="" onKeyUp="this.value=this.value.toLowerCase();" />';
						}
					}
				},
				disaster_password: {
					title: 'DR Password',
					list: false,
					create: true,
					input: function(data){
						if (data.record){
							return '<input type="password" size="25" name="disaster_password" value="'+data.record.disaster_password+'" onKeyUp="this.value=this.value.toLowerCase();" />';
						} else {
							return '<input type="password" size="25" name="disaster_password" value="" onKeyUp="this.value=this.value.toLowerCase();" />';
						}
					}
				},
				disaster_database_name: {
					title: 'DR Database Name',
					list: false,
					create: true,
					input: function(data){
						if (data.record){
							return '<input type="text" size="30" name="disaster_database_name" value="'+data.record.disaster_database_name+'" onKeyUp="this.value=this.value.toLowerCase();" />';
						} else {
							return '<input type="text" size="30" name="disaster_database_name" value="" onKeyUp="this.value=this.value.toLowerCase();" />';
						}
					}
				},
				system_type: {
					title: 'System Type',
					list: true,
					create: true,
					options: 'index.php?option=com_jumi&fileid=146&action=listSystemTypes'
				},
				system_mode: {
                    title: 'System Mode',
                    type: 'checkbox',
                    values: { '0': 'Production Mode', '1': 'Disaster Mode' },
                    defaultValue: '0'
                },
				log_only: {
                    title: 'DR Log Only',
                    type: 'checkbox',
                    values: { '0': 'No', '1': 'Yes' },
                    defaultValue: '0'
                },
				
            },
	
																formCreated: function (event, data) {
																	data.form.find('input[name="system_name"]').addClass('validate[required]');
																	data.form.find('input[name="host"]').addClass('validate[required,custom[ipv4]]');
																	data.form.find('input[name="disaster_host"]').addClass('validate[custom[ipv4]');
																	data.form.find('input[name="user_name"]').addClass('validate[required]');
																	data.form.find('input[name="password"]').addClass('validate[required]');
																	data.form.validationEngine({promptPosition : "topLeft", scroll: false});
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

        });
		
		jt('#tableData').jtable('load');
}

