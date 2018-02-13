window.addEvent('domready',function(){
	
	popRemove();
	popData();
	popAccounts();

});


function popRemove(){
	jt('#tableDataRemove').jtable({
            title: 'Budget Cost Code Removals [Read Only]',
			paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'cost_code ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=136&action=displayRequestCostCodesRemove',
                createAction: 'index.php?option=com_jumi&fileid=136&action=createRequestsCostCodesRemove',
				deleteAction: 'index.php?option=com_jumi&fileid=136&action=deleteRequestsCostCodesRemove'
            },
            fields: {
				id: {
					list: false,
					key: true
                },
				uid: {
					title: 'User Name',
					list: false,
					create: true,
					options: 'index.php?option=com_jumi&fileid=136&action=listUsernames'
				},
				cost_code: {
					title: 'Cost Code',
					width: '20%',
					list: false,
					create: true,
					options: 'index.php?option=com_jumi&fileid=136&action=listCostCodes'
				},
				ccc: {
					title: 'Cost Code',
					width: '20%',
					list: true,
					create: false
				},
				cc_name: {
					title: 'Cost Code Description',
					width: '40%',
					list: true,
					create: false
				},
				uname: {
					title: 'Staff Name',
					width: '40%',
					list: true,
					create: false
				}
				
            },
			formCreated: function (event, data) {
                data.form.find('input[name="uid"]').addClass('validate[required]');
				data.form.find('input[name="cost_code"]').addClass('validate[required]');
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
		
		jt('#tableDataRemove').jtable('load');
}

function popData(){
	jt('#tableData').jtable({
            title: 'Budget Cost Code Requests [Read Only]',
			paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'cost_code ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=136&action=displayRequestCostCodes',
                createAction: 'index.php?option=com_jumi&fileid=136&action=createRequestsCostCodes',
				deleteAction: 'index.php?option=com_jumi&fileid=136&action=deleteRequestsCostCodes'
            },
            fields: {
				id: {
					list: false,
					key: true
                },
				uid: {
					title: 'User Name',
					list: false,
					create: true,
					options: 'index.php?option=com_jumi&fileid=136&action=listUsernames'
				},
				cost_code: {
					title: 'Cost Code',
					width: '20%',
					list: false,
					create: true,
					options: 'index.php?option=com_jumi&fileid=136&action=listCostCodes'
				},
				ccc: {
					title: 'Cost Code',
					width: '20%',
					list: true,
					create: false
				},
				cc_name: {
					title: 'Cost Code Description',
					width: '40%',
					list: true,
					create: false
				},
				uname: {
					title: 'Staff Name',
					width: '40%',
					list: true,
					create: false
				}
				
            },
			formCreated: function (event, data) {
                data.form.find('input[name="uid"]').addClass('validate[required]');
				data.form.find('input[name="cost_code"]').addClass('validate[required]');
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

function popAccounts(){
	jt('#tableDataAccounts').jtable({
            title: 'Budget Account Code Requests [Read Only]',
			paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'cost_code ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=136&action=displayRequestAccountCodes',
                createAction: 'index.php?option=com_jumi&fileid=136&action=createRequestsAccountCodes',
				deleteAction: 'index.php?option=com_jumi&fileid=136&action=deleteRequestsAccountCodes'
            },
            fields: {
				id: {
					list: false,
					key: true
                },
				cost_code: {
					title: 'Cost Code',
					list: false,
					create: true,
					options: 'index.php?option=com_jumi&fileid=136&action=listCostCodes',
				},
				ccc: {
					title: 'Cost Code',
					width: '15%',
					list: true,
					create: false
				},	
				cc_name: {
					title: 'Cost Code Description',
					width: '35%',
					list: true,
					create: false
				},
				account_code: {
					title: 'Account#',
					width: '15%',
					list: false,
					create: true,
					options: 'index.php?option=com_jumi&fileid=136&action=listAccountCodes',
				},
				aaa: {
					title: 'Account#',
					width: '15%',
					list: true,
					create: false
				},
				fcdname1: {
					title: 'Account Description',
					width: '35%',
					list: true,
					create: false
				}
            },
			formCreated: function (event, data) {
                data.form.find('input[name="cost_code"]').addClass('validate[required]');
				data.form.find('input[name="account_code"]').addClass('validate[required]');
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
		
		jt('#tableDataAccounts').jtable('load');
}