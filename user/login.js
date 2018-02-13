window.addEvent('domready',function(){

window.parent.$j.colorbox.resize({ 'height': 300, 'width': 500 });

$('test-cred').addEvent('click',function(){
	testCred();
});

$('user-name').focus();

});


function testCred(){
	$('test-cred').set('disabled',true);
	var uname = $('user-name').get('value');
	var pass = $('user-password').get('value');
		if (uname.length < 1){
			alert('Invalid username.');
			return false;
		}
		if (pass.length < 1){
			alert('Invalid password.');
			return false;
		}
	$('check-busy').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=109&action=check_login&user='+uname+'&pass='+encodeURI(pass),
			method: 'get',
			noCache: true,
			onComplete: function(response){
			$('check-busy').setStyle('display','none');
			if (parseInt(response) == -1){
				alert('Error connecting to authorization server. Please try again later.');
			} else if (parseInt(response) == -2){
				alert('Error binding to authorization server. Please try again later.');
			} else if (parseInt(response) == -3){
				alert('Could not locate your user object. Please try again later.');
			} else if (parseInt(response) == -4){
				alert('Username and password combination invalid. Your password might have expired.');
			} else if (parseInt(response) == 1){
				alert('Username and password is valid.');
			}
			$('test-cred').set('disabled',false);
		}
	}).send();
}
