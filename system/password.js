var onceoff;
var ureg = false;
var showWarning1 = false;
window.addEvent('domready',function(){

window.parent.$j.colorbox.resize({ 'height': 280, 'width': 530 });

			/*new DatePicker($('sm0'), {
				pickerClass: 'datepicker',
				timePicker: false,
				format: '%Y-%m-%d',
				positionOffset: {x: 5, y: 0},
				toggleElements: '.date_toggler1',
				useFadeInOut: !Browser.ie
			});*/
				
				var numbers = [8,9,46,48,49,50,51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105,109,110,190];
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

				$('close-wnd').addEvent('click',function(){
					window.parent.$j.colorbox.close();
				});



				$('request').addEvent('click',function(){
					if (this.value == 'Send Token')
					{
							var lg = $('login-name').get('value');
							var cell = $('cellular').get('value');
							
							if (cell.length < 10)
							{
								alert('Enter valid cellular number.');
								return false;
							}
							if (onceoff == true)
									{
										var of = 1;
									} else { var of = 0; }
									var lg = $('lname').get('value');
								var x = new Request({
									url:'index.php?option=com_jumi&fileid=63&action=send_token&cell='+cell+'&lg='+lg+'&of='+of,
										noCache: true,
										method: 'get',
										onComplete: function(){
										window.parent.$j.colorbox.resize({ 'height': 280, 'width': 530 });
										$('token-div').setStyle('display','block');
										$('request').set('value','Change Password');
										$('request').set('disabled',false);
										document.getElementById('token').focus();
									}
								}).send();
					} else if (this.value == 'Submit') {
						window.parent.$j.colorbox.resize({ 'height': 280, 'width': 530 });
						validateInfo();
					} else if (this.value == 'Change Password')
					{
						var tk = $('token').get('value');
						var lg = $('lname').get('value');
						if (tk.length < 6)
						{
							alert('Invalid token');
							document.getElementById('token').focus();
						} else {
							var lg = $('lname').get('value');
							var uid = $('login-name').get('value');
							var x = new Request({
								url:'index.php?option=com_jumi&fileid=63&action=verify_token&lg='+lg+'&token='+tk,
									method: 'get',
									noCache: true,
									onComplete: function(response){
										if (parseInt(response) == -1)
										{
											alert('No valid access token found on system.');
										} else {
											changePassword(uid);
										}
								}
							}).send();
						}
					}
				});

			$('login-name').addEvent('blur',function(){
						var rm = new RegExp('^[0-9]+$');
						var r = rm.exec(this.value);
							if (r != null)
							{
								$('lname').set('value',this.value);
								checkForCell(this.value);
							} else {
									var q = new Request({
									url: 'index.php?option=com_jumi&fileid=63&action=check_valid_id&uid='+this.value,
										method: 'get',
										noCache: true,
										timeout: 10000,
										onTimeout: function(){
										alert('LDAP Timeout...Please report to CTS helpdesk.');
											window.parent.$j.colorbox.close();
									},
										onComplete: function(response){
											if (parseInt(response) == 0)
											{
												$('details').set('html','Could not validate your login name/staff number combination.<br />Please contact the CTS helpdesk to reset your password manually.<br />For future use, please verify your login name/staff number combination under the Utilities menu option once your are logged in.<br /><input type="button" value="Close" onclick="javascript: window.parent.$j.colorbox.close();" />');
											} else {
												$('lname').set('value',response);
												//checkForCell(response);
											}
									}
								}).send();
							}
				});


$('login-name').focus();
	

});
////////////////////////////////////////////////////////////////////////////////////


function changePassword(uid){
	var lg = $('lname').get('value');
	var password = '';
	$('login-name').set('disabled',true);
	$('barcode-number').set('disabled',true);
	$('id-number').set('disabled',true);
	$('sm0').set('disabled',true);
	$('cellular').set('disabled',true);
	$('request').set('disabled',true);
	var cell = $('cellular').get('value');

	if (ureg = false){
	var y = new Request({
			url:'index.php?option=com_jumi&fileid=63&action=save_cell_profile&lg='+lg+'&cell='+cell+'&uid='+uid,
			noCache: true,
			method: 'get',
			onComplete: function(response){
			if (parseInt(response) == -1)
				{
					alert('Error saving user/celluar profile.');											
				}
			}
	}).send();
	}

	var x = new Request({
		url:'index.php?option=com_jumi&fileid=63&action=change_password&lg='+uid,
			noCache: true,
			method: 'get',
			timeout: 10000,
			onTimeout: function(){
			alert('LDAP Time-out...Please try again later.');
			x.cancel();
			},
			onComplete: function(response){
				if (parseInt(response) < 0)
				{
					alert('An error occured during your password reset.'+'\n'+ 'Please contact the helpdesk.');
					window.parent.$j.colorbox.close();
				} else {
					password = response;
					alert('Your temporary password will be sent to your cellular device.'+'\n'+'Password is valid for 48 hours.Please change your password'+'\n'+'as soon as possible.'+'\n'+'Click OK to send password.');
							var p = new Request({
								url:'index.php?option=com_jumi&fileid=63&action=send_password&cell='+cell+'&password='+password,
									method: 'get',
									noCache: true,
									async: false,
									onComplete: function(){
									window.parent.$j.colorbox.close();
								}
							}).send();
				}
			}
	}).send();
}

function checkForCell(value){
	var x = new Request({
		url:'index.php?option=com_jumi&fileid=63&action=check_cell&lg='+value,
			method:'get',
			noCache: true,
			timeout: 5000,
			onTimeout: function(){
			x.cancel();
			$('cellular').set('disabled',false);	
		},
			onComplete: function(response){
				if (parseInt(response) < 0)
				{
					$('cellular').set('disabled',false);	
				} else if (response.length == 0)
				{
					$('cellular').set('disabled',false);	
				}	
				else {
					ureg = true;
					$('cellular').set('value',response);
					$('cellular').setStyle('background-color','#dadada');
					$('cellular').set('disabled',true);
				}
		}
	}).send();
}

function testForStudent(exp){
	var rm = new RegExp('^[0-9]+$');
			var r = rm.exec(exp);
				if (r != null)
				{
					return true;
				} else {
					return false;
				}
}

function msg(){
	if (confirm('This facility is for students only...Continue?'))
{
	document.getElementById('login-name').focus();
} else {
	window.parent.$j.colorbox.close();
}
}

function validateInfo(){
	var lg = $('login-name').get('value');
	var bc = $('barcode-number').get('value');
	var id = $('id-number').get('value');
	var cell = $('cellular').get('value');
	var tk = $('token').get('value');
	//if (lg.length < 9)
	//{
	//	alert('Invalid student number.');
	//	document.getElementById('login-name').focus();
	//	return false;
	//}

	if (bc.length < 7)
	{
		alert('Invalid barcode number.');
		document.getElementById('barcode-number').focus();
		return false;
	}


	if (cell.length < 10)
	{
		alert('Invalid cellular number.');
		document.getElementById('cellular').focus();
		return false;
	}

	$('login-name').set('disabled',true);
	$('barcode-number').set('disabled',true);
	$('id-number').set('disabled',true);
	$('sm0').set('disabled',true);
	$('cellular').set('disabled',true);
	$('ajax-password').setStyle('display','block');
	var safe = true;
		$('request').set('disabled',true);
		var lg = $('lname').get('value');
		var bc = $('barcode-number').get('value');
		var id  = $('id-number').get('value');
		var tk = $('token').get('value');
		var dob = $('sm0').get('value');
		var uid = $('login-name').get('value');
		var x = new Request({
			url:'index.php?option=com_jumi&fileid=63&action=verify_credentials&uid='+uid+'&lg='+lg+'&idno='+id+'&dob='+dob+'&ms='+bc,
				method: 'get',
				noCache: true,
				onComplete: function(response){
					if (parseInt(response) < 0)
					{
						safe = false;
					} else {
						safe = true;
					}
							if (safe == false)
							{
								alert('We were unable to verify your credentials. Please contact the helpdesk.');
							} else {
								$('request').set('value','Send Token');
								$('request').set('disabled',false);
								alert('User credentials verified. Click on the Send Token button for access token.');
							}
					}
		}).send();
}

function resizeCal(){
	window.parent.$j.colorbox.resize({ 'height': 420, 'width': 530 });
}
function resizeOrig(){
	window.parent.$j.colorbox.resize({ 'height': 280, 'width': 530 });
}
