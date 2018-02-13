window.addEvent('domready',function(){
	
	popData();

});


function popData(){
	var dept = $('staff-dept').get('value');
	jt('#tableData').jtable({
            title: 'HR Department Allocations',
			paging: false, //Enable paging
            pageSize: 20, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'staff_name ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=79&action=hrAlloc',
				createAction: 'index.php?option=com_jumi&fileid=79&action=hrNew',
				deleteAction: 'index.php?option=com_jumi&fileid=79&action=hrDel'
            },
            fields: {
				id: {
					key: true,
					list: false
				},
				uid: {
					title: 'Staff#',
					create: true,
					list: false,
					options: 'index.php?option=com_jumi&fileid=79&action=listStaffMembers'
				},
				staff_name: {
					title: 'Staff Member',
					edit: false,
					create: false,
					list: true
				},
				dept_desc: {
					title: 'Department',
					create: false,
					list: true,
					sorting: false
				},
				dept_code: {
					title: 'Department',
					create: true,
					list: false,
					options: 'index.php?option=com_jumi&fileid=79&action=listDepartments'
				}
				
            }
				
        });
		
		jt('#tableData').jtable('load');
}