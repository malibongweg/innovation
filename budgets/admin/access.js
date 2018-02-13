window.addEvent('domready',function(){
	
	
	popData();

});



function popData(){
	jt('#tableData').jtable({
            title: 'Budget Cost Code Access Requests...',
			paging: true, //Enable paging
            pageSize: 20, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'staff_sname ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=136&action=displayAccessCostCodes',
                createAction: 'index.php?option=com_jumi&fileid=136&action=createAccessCostCodes',
				deleteAction: 'index.php?option=com_jumi&fileid=136&action=deleteAccessCostCodes'
            },
            fields: {
				id: {
					list: true,
					key: true,
					create: false,
					edit: false,
					width: '3%',
					sorting: false,
					display: function(accessUsers) {
						var $img = jt('<img src="/images/arrow1.png" title="" />');
						$img.click(function () {
								jt('#tableData').jtable('openChildTable',
											$img.closest('tr'), //Parent row
											{
														title: 'Cost Code Access...',
														sorting: false,
														paging: false,
														dialogWidth: 500,
														selecting: true,
														recordDeleted: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
														recordAdded: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
														actions: {
															listAction: 'index.php?option=com_jumi&fileid=136&action=listCostCodeAccess&id='+accessUsers.record.id,
															createAction: 'index.php?option=com_jumi&fileid=136&action=assignCostCodes',
															deleteAction: 'index.php?option=com_jumi&fileid=136&action=deleteAssignCostCodes'
														},
															fields: {
																id: {
																	list: false,
																	create: true,
																	defaultValue: accessUsers.record.id,
																	type: 'hidden'
																},
																cc_name: {
																	title: 'Cost Code',
																	width: '80%',
																	create: false,
																	edit: false
																},
																cost_code: {
																	title: 'Cost Code#',
																	width: '50%',
																	create: true,
																	list: false,
																	options: 'index.php?option=com_jumi&fileid=136&action=listCostCodes'
															},
															cc: {
																	title: 'Cost Code#',
																	width: '20%',
																	create: false,
																	edit: false
															},
															idref: {
																	create: false,
																	key: true,
																	list: false
															}
														}
											},
											function (data) { //opened handler
												data.childTable.jtable('load');
											}
								)
						});
						return $img;
					}
                },
				staff_sname: {
					list: false,
					create: false
				},
				budget_user: {
					title: 'User Name',
					width: '77%',
					list: true,
					create: false,
					sorting: false
				},
				staffno: {
					title: 'Staff#',
					width: '50%',
					list: false,
					create: true,
					sorting: false,
					options: 'index.php?option=com_jumi&fileid=136&action=listUsernames'
				},
				stfno: {
					title: 'Staff#',
					width: '20%',
					list: true,
					create: false,
					sorting: false
				}
				
            },
        });
		
		jt('#tableData').jtable('load');
}

