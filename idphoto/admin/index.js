window.addEvent('domready',function(){
	
	popData();

});


function popData(){
	jt('#tableData').jtable({
            title: 'Temp user allocations...',
			paging: false, //Enable paging
            pageSize: 20, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'expire_date DESC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 700,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=150&action=browseUsers',
				createAction: 'index.php?option=com_jumi&fileid=150&action=usersNew',
				deleteAction: 'index.php?option=com_jumi&fileid=150&action=usersDelete'
            },
            fields: {
				uid: {
					title: 'User#',
					key: true,
					create: true,
					list: true,
					sorting: false,
					options: 'index.php?option=com_jumi&fileid=150&action=listUsers'
				},
				expire_date: {
					title: 'Expire Date',
					type: 'date',
					displayFormat: 'yy-mm-dd',
					create: true,
					list: true,
					sorting: true
				}
				
            }
				
        });
		
		jt('#tableData').jtable('load');
}