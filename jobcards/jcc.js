window.addEvent('domready',function(){

$('srch-job').addEvent('click',function(){
		var j = $('job-no').get('value');
		searchJob(j);
});

$('complete-jobcard').addEvent('click',function(){
	var j = $('job-no').get('value');
	finishJob(j);
});

clearFields();
$('job-no').focus();

});

function finishJob(jno){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=finish_job&id='+jno,
			noCache: true,
			method: 'get',
			onComplete: function(response){
			if (parseInt(response) == -1)	{
				alert('Error updating jobcard.');
			} else {
				alert('Jobcard updated');
				clearFields();
				$('job-no').focus();
			}
		}
	}).send();
}

function clearFields(){
	$('job-no').set('value','');
	$('applicant').set('value','');
	$('building').set('value','');
	$('roomno').set('value','');
	$('job-details').set('value','');
	$('complete-jobcard').set('disabled',true);
}


function searchJob(jno){
	$('j-busy').setStyle('display','inline');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=search_job&id='+jno,
			noCache: true,
			method: 'get',
			onComplete: function(response){
			var res = response;
				$('j-busy').setStyle('display','none');
				if (parseInt(res) == -1)	{
					clearFields();
					$('job-details').set('value','Jobcard not found...');
				} else {
					var r = res.split(';');
					if (parseInt(r[4]) == 3 || parseInt(r[4]) ==4)	{
						alert('Jobcard already completed or cancelled.');
						$('job-no').set('value','');
						$('job-no').focus();
					} else {
					$('applicant').set('value',r[0]);
					$('building').set('value',r[1]);
					$('roomno').set('value',r[2]);
					$('job-details').set('value',r[3]);
					$('complete-jobcard').set('disabled',false);
					}
				}
		}
	}).send();
}