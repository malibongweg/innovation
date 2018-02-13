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
                listAction: 'index.php?option=com_jumi&fileid=148&action=displayMenus',
                //createAction: 'index.php?option=com_jumi&fileid=148&action=createMenus',
				updateAction: 'index.php?option=com_jumi&fileid=148&action=updateMenus',
				//deleteAction: 'index.php?option=com_jumi&fileid=148&action=updateMenus'
            },
            fields: {
				id: {
					key: true,
					list: false,
					create: false,
					edit: false
					//create: true,
					//options: 'index.php?option=com_jumi&fileid=148&action=listMenuTitles'
                },
				id_title: {
					width: '20%',
					title: 'Menu Title',
					list: true,
					create: false,
					edit: false,
					options: 'index.php?option=com_jumi&fileid=148&action=listMenuTitles'
				},
				url_link: {
					title: 'Url Link',
					width: '40%',
					list: true,
					create: true,
						input: function(data){
						if (data.record){
							return '<input type="text" size="50" name="url_link" value="'+data.record.url_link+'" onKeyUp="this.value=this.value.toLowerCase();" />';
						} else {
							return '<input type="text" size="50" name="url_link" value="" onKeyUp="this.value=this.value.toLowerCase();" />';
						}
					}
				},
				disaster_link: {
					title: 'Disaster Recovery Link',
					width: '40%',
					list: true,
					create: true,
					input: function(data){
						if (data.record){
							return '<input type="text" size="50" name="disaster_link" value="'+data.record.disaster_link+'" onKeyUp="this.value=this.value.toLowerCase();" />';
						} else {
							return '<input type="text" size="50" name="disaster_link" value="" onKeyUp="this.value=this.value.toLowerCase();" />';
						}
					}
				},
			},
				
            
	
																formCreated: function (event, data) {
																	data.form.find('input[name="id"]').addClass('validate[required]');
																	data.form.find('input[name="url_link"]').addClass('validate[required,custom[url]]');
																	data.form.find('input[name="disaster_link"]').addClass('validate[custom[url]');
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

