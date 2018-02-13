var jid = 0;

window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({width: 600, height: 500});
	window.parent.$j.colorbox.settings.escKey = false;
	window.parent.$j.colorbox.settings.overlayClose = false;
	jid = $('jid-value').get('value');
	displayAssigned(jid);
});

function displayAssigned(jcard) {
	jt('#tableAssignedArtisans').jtable({
            title: 'ARTISAN(S) ASSIGN FOR JOBCARD# '+jcard,
			paging: false, // Enable paging
            pageSize: 15, // Set page size (default: 10)
            sorting: false, // Enable sorting
			selecting: true, // Enable selecting,
			dialogWidth: 300,
			loadingAnimationDelay: 0,
			jqueryuiTheme: true,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=104&action=listAssignedArtisans&jid='+jcard,
                createAction: 'index.php?option=com_jumi&fileid=104&action=createAssignedArtisans',
				deleteAction: 'index.php?option=com_jumi&fileid=104&action=deleteAssignedArtisans'
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
					defaultValue: jcard
				},
				artisan: {
					title: 'ARTISAN',
					list: false,
					create: true,
					edit: false,
					options: 'index.php?option=com_jumi&fileid=104&action=listArtisans&jid='+jcard
				},
				fullname: {
					title: 'ASSIGNED ARTISAN',
					width: '40%',
					list: true,
					create: false,
					edit: false
					
				},
				trade_description: {
					title: 'TRADE',
					width: '35%',
					create: false,
					edit: false
				},
				labour_cost: {
					title: 'LABOUR RATE',
					width: '25%',
					list: true,
					create: false,
					edit: false
				}
            },
            	rowUpdated: function (event, data) {
            		jt('#tableAssignedArtisans').jtable('reload');
            	}
            	/*rowInserted: function (event, data) {
            		jt('#tableAssignedArtisans').jtable('reload');
            	}*/
        });
		
		jt('#tableAssignedArtisans').jtable('load');
		
}