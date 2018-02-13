var dept_codes = '';

window.addEvent('domready',function(){
	
	getStaffNo();

});

function getStaffNo() {
	var uid = $('login-name').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=getStaffNo&login='+uid,
			method: 'get',
			onComplete: function(response) {
				if (parseInt(response) == 0) {
					alert('Could not locate staff#.');
				} else {
					$('staff-no').set('value',response);
					getDept(response);
				}
		}
	}).send();
}

function getDept(stf) {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=getHRdept&uid='+stf,
			method: 'get',
			onComplete: function(response) {
			if (parseInt(response) == 0) {
					alert('Could not locate department allocation.');
				} else {
					dept_codes = '(';
					var flag = 0;
					var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
								flag = flag + 1;
								if (parseInt(flag) > 1) {
									dept_codes = dept_codes +','
								}
								dept_codes = dept_codes + text;
						}
					});
					dept_codes = dept_codes +')';
				}
				popData();
			
		}
	}).send();
}

function popData(){
	var dept = $('staff-dept').get('value');
	jt('#tableData').jtable({
            title: 'Submitted Entires',
			paging: true, //Enable paging
            pageSize: 20, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'staff_sname ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=79&action=displayAmendmentsList&dept='+dept_codes
            },
            fields: {
				staffindex: {
					list: true,
					width: '3%',
					title: '',
					sorting: false,
					display: function(staffCodes) {
							var $img = jt('<img src="/images/arrow1.png" title="" />');
							$img.click(function () {
									jt('#tableData').jtable('openChildTable',
									$img.closest('tr'), //Parent row
									{
										title: 'Field change requests',
										sorting: false,
										paging: false,
										dialogWidth: 500,
										selecting: true,
										recordUpdated: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
										recordDeleted: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
										recordAdded: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
										closeRequested: (function (event, data) { jt('#tableData').jtable('reload'); }),
										actions: {
												listAction: 'index.php?option=com_jumi&fileid=79&action=displayAmendments&uid='+staffCodes.record.staffno,
												updateAction: 'index.php?option=com_jumi&fileid=79&action=updateAmendments'
												},
												fields: {
															id: {
																list: false,
																title: '',
																key: true,
																sorting: false
															},
															field_name: {
																title: 'Field Name',
																edit: false,
																sorting: false
															},
															old_field_value: {
																title: 'Old Value',
																edit: false
															},
															new_field_value: {
																title: 'Requested Value',
																edit: false,
																sorting: false
															},
															status: {
																title: 'Update Status',
																edit: true,
																list: true,
																options: 'index.php?option=com_jumi&fileid=79&action=listStatus'
															}
												},
												
									},
										function (data) { //opened handler
											data.childTable.jtable('load');
										}
								);
							});
							return $img;
					}
				},
				staff_member: {
					title: 'Staff Member',
					edit: false,
					create: false
				},
				staffno: {
					title: 'Staff#',
					edit: false,
					soeting: false
				}
				
            }
				
        });
		
		jt('#tableData').jtable('load');
}