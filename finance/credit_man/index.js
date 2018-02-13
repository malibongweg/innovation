window.addEvent('domready',function(){
	
	$('prn-button').set('disabled',true);

	$('idsearch').addEvent('click',function() {
	$$('[type=text]').each(function(e) {
			e.value = '';
		});
		try{
			jt('#tableData').jtable('destroy');
		}catch(exception){ 
		}
		//	jt('#tableData').jtable('destroy');
		
		
		$('prn-button').set('disabled',true);
		setupWindow();
		
	});

	$('search-button').addEvent('click',function(){
		var stno = $('idsearch').get('value');
		searchEntry(stno);
	});

	$('prn-button').addEvent('click',function(){
		runReport();
	});

	setupWindow();
	//popData();

});

function runReport(){
	var stno = $('idsearch').get('value');
	$('rep-clearance').set('href','index.php?option=com_jumi&fileid=153&tmpl=component&stno='+stno);
	$('rep-clearance').click();
}


function searchEntry(stdno){
	$('search-button').set('disabled',true);
	$('credit-ajax').setStyle('display','inline');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=152&action=getDetails&stno='+stdno,
		method: 'get',
		noCache: true,
		onComplete: function(response) {
			if (parseInt(response) == -1){
				alert('No records to display...');
				$('search-button').setStyle('disabled',false);
				$('credit-ajax').setStyle('display','none');
				setupWindow();
				return false;
			}
			else if (parseInt(response) == -2){
				alert('Duplicate record found...');
				$('search-button').setStyle('disabled',false);
				$('credit-ajax').setStyle('display','none');
				setupWindow();
				return false;
			} else {
				var r = response.split(';');
				$('student-surname').set('value',r[0]);
				$('student-initials').set('value',r[1]);
				$('student-idno').set('value',r[2]);
				$('student-qual').set('value',r[3]);
				$('student-year').set('value',r[4]);
				$('student-subject').set('value',r[5]);
				$('student-subject-passed').set('value',r[6]);
				$('student-percent').set('value',r[7]);
				$('student-balance').set('value',r[8]);
				$('student-mindue').set('value',r[9]);
				$('student-perdue').set('value',r[10]);
				$('student-payments').set('value',r[11]);
				$('search-button').setStyle('disabled',false);
				$('credit-ajax').setStyle('display','none');
				popData(stdno);
			}
		}
	}).send();
}

function setupWindow() {
	$('idsearch').set('value','');
	$('credit-ajax').setStyle('display','none');
	$('search-button').set('disabled',false);
	$('idsearch').focus();
}

function popData(id){
	$('prn-button').set('disabled',false);
	var usr = $('login-name-cm').get('value');
	var seq = 0;
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=152&action=getSeq',
		noCache: true,
		async: false,
		onComplete: function(response){
			seq = parseInt(response);
		}
	}).send();
	jt('#tableData').jtable({
            title: 'History of Comments...',
			paging: false, //Enable paging
            pageSize: 20, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'cmcseq DESC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 500,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=152&action=browseComments&stno='+id,
				createAction: 'index.php?option=com_jumi&fileid=152&action=createComments',
				deleteAction: 'index.php?option=com_jumi&fileid=152&action=deleteComments'
            },
            fields: {
				CMCSEQ: {
					key: true,
					type: 'hidden',
					create: true,
					list: false,
					sorting: false,
					defaultValue: seq
				},
				CMCSTNO: {
					create: true,
					list: false,
					type: 'hidden',
					defaultValue: id
				},
				LISTDATE: {
					title: 'Entry Date',
					create: false,
					width: '20%',
					list: true,
					sorting: true
				},
				CMCDATE: {
					create: true,
					list: false,
					type: 'hidden'
				},
				CMCCOMNT: {
					title: 'Comment',
					list: true,
					width: '60%',
					create: true,
					type: 'textarea'
				},
				DISPUSER: {
					title: 'Sys User',
					width: '20%',
					list: true,
					create: false
				},
				SYSUSER: {
					list: false,
					create: true,
					type: 'hidden',
					defaultValue: usr
				}
				
            }
				
        });
		
		jt('#tableData').jtable('load');
}