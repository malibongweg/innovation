window.addEvent('domready',function(){
	
	popData();
});

function popData(){
	jt('#tableData').jtable({
            title: 'eStores',
			paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'store_name ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=157&action=browseAdmin',
                createAction: 'index.php?option=com_jumi&fileid=157&action=createStore',
				updateAction: 'index.php?option=com_jumi&fileid=157&action=updateStore',
				deleteAction: 'index.php?option=com_jumi&fileid=157&action=deleteStore'
            },
            fields: {
				store_id: {
					key: true,
					create: false,
					list: true,
					width: '3%',
					title: '',
					display: function(store) {
						var $img = jt('<img src="/images/controller.png" title="" />');
						$img.click(function () {
									jt('#tableData').jtable('openChildTable',
									$img.closest('tr'),{ //Parent row
									title: 'Store Owners',
									sorting: false,
									dialogWidth: 500,
									selecting: true,
									recordUpdated: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
									recordDeleted: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
									recordAdded: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
									closeRequested: (function (event, data) { jt('#tableData').jtable('reload'); }),
										actions: {
											listAction: 'index.php?option=com_jumi&fileid=157&action=displayOwners&storeid='+store.record.store_id,
											createAction: 'index.php?option=com_jumi&fileid=157&action=createOwner',
											deleteAction: 'index.php?option=com_jumi&fileid=157&action=deleteOwner'
										},
										fields: {
												id: {
													key: true,
													list: false,
								
													},
												fullname : {
													width: '100%',
													list: true,
													create: false,
													edit: false
													},
												uid: {
													create: true,
													edit: true,
													list: false,
													options: 'index.php?option=com_jumi&fileid=157&action=listUsers'
													},
												istores_store_id: {
													create: true,
													edit: false,
													list: false,
													type: 'hidden',
													defaultValue: store.record.store_id
													}
											}
								},
									function (data) { //opened handler
									data.childTable.jtable('load');
								}
							);
						
					});
					return $img;
					}
				},
				store_controllers: {
					key: false,
					create: false,
					list: true,
					width: '3%',
					title: '',
					display: function(store) {
						var $img = jt('<img src="/images/finance.png" title="" />');
						$img.click(function () {
									jt('#tableData').jtable('openChildTable',
									$img.closest('tr'),{ //Parent row
									title: 'Store Controllers',
									sorting: false,
									dialogWidth: 500,
									selecting: true,
									recordUpdated: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
									recordDeleted: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
									recordAdded: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
									closeRequested: (function (event, data) { jt('#tableData').jtable('reload'); }),
										actions: {
											listAction: 'index.php?option=com_jumi&fileid=157&action=displayControllers&storeid='+store.record.store_id,
											createAction: 'index.php?option=com_jumi&fileid=157&action=createController',
											deleteAction: 'index.php?option=com_jumi&fileid=157&action=deleteController'
										},
										fields: {
												id: {
													key: true,
													list: false,
												},
												uid: {
													create: true,
													edit: true,
													list: false,
													options: 'index.php?option=com_jumi&fileid=157&action=listUsers'
													},
												store_id: {
													create: true,
													edit: false,
													list: false,
													type: 'hidden',
													defaultValue: store.record.store_id
													},
												uname: {
													create: false,
													edit: false,
													list: true
												}
											}
								},
									function (data1) { //opened handler
									data1.childTable.jtable('load');
								}
							);
						
					});
					return $img;
					}
				},
				store_name: {
					title: 'Store Name',
					width: '60%',
					edit: true,
					create: true,
					input: function (data) {
						if (data.record) {
							return '<input type="text" name="store_name" size="40" onKeyUp="javascript: this.value = this.value.toUpperCase();" value="' + data.record.store_name + '" />';
						} else {
							return '<input type="text" name="store_name" size="40" onKeyUp="javascript: this.value = this.value.toUpperCase();" value="" />';
						}
					}
				}
            },
				formCreated: function (event, data) {
                data.form.find('input[name="store_name"]').addClass('validate[required]');
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