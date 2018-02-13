window.addEvent('domready',function() {

var numbers = [8,48,49,45,50,51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105,109];
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

	$('cls-button').addEvent('click',function(){
		window.parent.$j.colorbox.close();
	});

$('cls-button2').addEvent('click',function(){
		window.parent.$j.colorbox.close();
	});

	$('verify-button').addEvent('click',function(){
		doVerify();
	});

  window.parent.$j.colorbox.resize({ 'height': 250, 'width': 600 });

	$('staff-no').focus();

	checkVerify();
	
});

function doVerify(){
	var stf = $('staff-no').get('value');
	var id = $('id-no').get('value');
	if (parseInt(id.length) < 10) {
	   alert('Please enter your identity number. Only foreign users enter DOB.');
	   return false;
	}
	var lg = $('login-name').get('value');
	$('verify-button').setStyle('display','none');
	$('cls-button').setStyle('enabled','false');
	var x = new Request({
		url: '/scripts/user/staffdata.php?action=verify_staffno&stf='+stf+'&id='+id,
		noCache: true,
		method: 'get',
		onComplete: function(response){
				if (parseInt(response) == 0)
				{
					$('stf-details').set('html','Could not verify staff number. Please try again.');
				} else {
					var y = new Request({
						url: '/scripts/user/staffdata.php?action=verify_save&stf='+stf+'&lg='+lg,
							method: 'get',
							noCache: true,
							async: false,
							onComplete: function(response){
						}
					}).send();
					$('stf-details').set('html','Staff number verified. Thank you.');
				}

				
		}
	}).send();
}

function checkVerify(){
	var lg = $('login-name').get('value');
	var x = new Request({
		url: '/scripts/user/staffdata.php?action=fs&lg='+lg,
			method: 'get',
			noCache: true,
			onComplete: function(response){
			if (parseInt(response) > 0)
			{
				$('stf-details').set('html','Our records show that you have verified your details. Thank you.');
				$('stf-btn').setStyle('display','none');
				$('stf-btn2').setStyle('display','block');
			}
		}
	}).send();
}
