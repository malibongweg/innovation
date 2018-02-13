var jid = 0;
window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({width: 700, height: 450});
	window.parent.$j.colorbox.settings.escKey = false;
	window.parent.$j.colorbox.settings.overlayClose = false;
	jid = $('jid').get('value');
	displayDelayed(jid);
});
function displayDelayed(jid) {
	jt('#tableDelayed').jtable({
            title: 'DELAYED ENTRIES FOR JOBCARD# '+jid,
			paging: false, // Enable paging
            pageSize: 15, // Set page size (default: 10)
            sorting: false, // Enable sorting
			selecting: true, // Enable selecting,
			dialogWidth: 300,
			loadingAnimationDelay: 0,
			jqueryuiTheme: true,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=104&action=listDelayed&jid='+jid,
                createAction: 'index.php?option=com_jumi&fileid=104&action=createDelayed',
				deleteAction: 'index.php?option=com_jumi&fileid=104&action=deleteDelayed'
            },
            toolbar: {
				items: [{
					icon: '/scripts/jobcards/images/cancel.png',
					text: 'Close Window',
					click: function () {
						window.parent.refreshTable();
						window.parent.$j.colorbox.close();
					}
			}]},
            fields: {
				id: {
					list: false,
					key: true
				},
				jobcard: {
					list: false,
					create: true,
					edit: false,
					type: 'hidden',
					defaultValue: jid
				},
				starting_time: {
					title: 'DELAY DATE',
					list: true,
					create: false,
					edit: false,
					width: '23%'
				},
				ending_time: {
					title: 'RECOMMENCE',
					width: '23%',
					list: true,
					create: false,
					edit: false
					
				},
				reason: {
					title: 'REASON',
					width: '54%',
					create: true,
					edit: false,
					type: 'textarea'
				}
            },
            	rowUpdated: function (event, data) {
            		jt('#tableDelayed').jtable('reload');
            	},
            	recordAdded: function (event, data) {
            		jt('#tableDelayed').jtable('reload');
            	}
        });
		
		jt('#tableDelayed').jtable('load');
}