<html>
<head>
  <script type="text/javascript" src="mootools-core-1.4.5.js"></script>
</head>
</body>
<?php

print("<b>CPUT Student iEnabler Pin Request</b>");


?>
<form id='pin-form'>
       Enter StudentNo:<br> <input name="student_no" id='stdno' type="text" /><br><br>
       Enter Last 8Digits of IDNo:<br> <input name="pass_no" id='digits' type="text" /><br><br>
       <input type="submit" name="SubMitBtn" value="Get my Pin Now" />
</form>


<script type='text/javascript'>
	window.addEvent('domready',function() {
		$('stdno').set('value','');
		$('digits').set('value','');
		$('stdno').focus();
				
		$('pin-form').addEvent('submit',function(e) {
			e.stop();
			var login = $('stdno').get('value');
			var pwd = $('digits').get('value');
			
			if (login.length != 9){
				alert('You need to Enter a student number of 9 digits');
				$('stdno').focus();
				return false;
			}
			
			if (pwd.length != 8) {
				$('digits').focus();
				alert('You need to Enter last 8 digits of ID/Passport');
				return false;
			}
			
			var x = new Request({
				url: 'db.php',
				data: this,
				method: 'post',
				onComplete: function(response){
					if (response == 'ERR') {
						alert('Your student number and Last 8 Digits of IDno Could not be verified.');
						$('stdno').set('value','');
						$('digits').set('value','');
						$('stdno').focus();
					} else {
						///////Get Pin
						var x = new Request({
							url: 'pin.php?stdno='+login,
							method: 'get',
							onComplete: function(response){
								if (response.length == 0) {
									alert('No pin retrieved. Please request one now.');
								} else {
									alert('Your pin number is '+response);
								}
							}
						}).send();
					}
				}
			}).send();
			});
	});
  
</script>

</body>
</html>

