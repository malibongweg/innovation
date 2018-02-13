var jq = jQuery.noConflict();

window.addEvent('domready',function() {
    jq("#tabs").tabs();                
	//$('navMain').setStyle('display','block'); ///Mootools
	jq("#navMain").css('display','block'); ///jQuery
	
	$('schoolForm1edits').addEvent('submit',function(e){
		e.stop();
	});






	displaySchoolList();

}); 

function displaySchoolList() {
	var x = new Request({
		url: '/scripts/TeachingPractice/SchoolMaintanance/data.php?action=showSchools',
		method: 'get',
		noCache: true,
		timeout: 5000,
		onTimeout: function() {
		},
		onComplete: function(response) {
			$('schoolListDiv').set('html',response);
		}
	}).send();
}

function editSchool(cde) {
	$('schoolListDiv').setStyle('display','none');
	$('displayForm1').setStyle('display','block');
	var x = new Request({
		url: '/scripts/TeachingPractice/SchoolMaintanance/data.php?action=editSchool&id='+cde,
		method: 'get',
		noCache: true,
		timeout: 5000,
		onTimeout: function() {
		},
		onComplete: function(response) {
			$('school-name').set('value',response);
			$('school-name').focus();
		}
	}).send();
}
