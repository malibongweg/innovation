window.addEvent('domready',function() {

var numbers = [8,48,49, 50, 51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105];
	$$('.numeric').each(function(item) {
		item.addEvent('keydown', function(key) {
			for (i = 0; i < numbers.length; i++) {
				if(numbers[i] == key.code) {
					return true;
				}
			}
			return false;
		});
	});
displayInfo();

});

function displayInfo() {
	$('ajax').setStyle('display','block');
	var y = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=display_stud_info&uid='+$('login-name').get('value'),
			noCahce: true,
			method: 'get',
			timeout: 15000,
			onTimeout: function() {
				$('ajax').setStyle('display','none');
				alert('Error retrieving details.'+'\n'+'Please try later.');
				y.cancel();
			},
			onComplete: function(response) {
				if (parseInt(response) == -1)
				{	
					$('ajax').setStyle('display','none');
					alert('Error retrieving details.'+'\n'+'Please try later.');
				} else {
					var r = response.split(';');
					$('student-number').set('value',r[0]);
					$('student-fullname').set('value',r[5]);
					$('student-regdate').set('value',r[1]);
					$('student-qual').set('value',r[4]);
					$('student-ot').set('value',r[3]);
					$('student-bc').set('value',r[2]);
					$('student-fac').set('value',r[6]);
					$('student-dept').set('value',r[7]);
					$('student-cellno').set('value',r[8]);
				}
				$('ajax').setStyle('display','none');
				if (r[8].length < 10)
				{
					$('cell-msg').setStyle('display','block');
				}

		}
	}).send();
}