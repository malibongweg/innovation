window.addEvent('domready',function(){
checkDBMode();
	$('email-pin').addEvent('click',function(){
		$('email-pin').set('disabled',true);
		$('new-pin').set('disabled',true)
		$('search-uniflow').setStyle('display','none');
		$('sending-email').setStyle('display','block');
		sendPin();
	});

	$('new-pin').addEvent('click',function(){
		alert('Functionality disabled...Please try again later.');
		return false;
		var staffno = $('staff-no').get('value');
		$('email-pin').set('disabled',true);
		$('new-pin').set('disabled',true);
			var x = new Request({
			url: 'index.php?option=com_jumi&fileid=144&action=newPin&staffno='+staffno,
			method: 'post',
			noCache: true,
			timeout: 5000,
			onTimeout: function(){
			},
			data: $('user-details'),
			async: false,
			onComplete: function(){
				var y = new Request({
					url: 'index.php?option=com_jumi&fileid=144&action=getPin&staffno='+staffno,
					method: 'get',
					noCache: true,
					onComplete: function(response){
						if (parseInt(response) == -1){
							$('email-pin').set('disabled',false);
							$('new-pin').set('disabled',false)
							alert('Unable to contact ITS server.');
						} else if (parseInt(response) == 0){
							$('email-pin').set('disabled',false);
							//$('new-pin').set('disabled',false)
							$('new-pin').set('disabled',true);
							alert('We were unable to locate a pin number for you.');
						} else {
						var email = $('user-email').get('value');
						var pin = parseInt(response);
						$('staff-no').set('value',staffno);
						$('staff-pin').set('value',pin);
						$('email-pin').set('value','Email Pin to '+email);
						$('search-uniflow').setStyle('display','none');
						$('uniflow-data').setStyle('display','block');
						$('email-pin').set('disabled',false);
						//$('new-pin').set('disabled',false);
						$('new-pin').set('disabled',true);
					}
					sendPin();
				}
				}).send();
				
			}
		}).send();
		$('search-uniflow').setStyle('display','none');
		$('sending-email').setStyle('display','block');

	});

	
	$('new-pin').set('disabled',true);
	var uname = $('uname').get('value');
	checkAccount(uname);
	disableExport();

});

function checkDBMode(){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=144&function=checkDBStatus',
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response){
			var r = response.split(';');
			var ip = r[1].split('.');
			if (parseInt(r[2]) == 1){
				var l = 'Log Only';
			} else { var l = 'DB Interact'; }
			if (parseInt(r[0]) == 1){
				$('system-mode').set('value',r[0]);
				$('system-log').set('value',r[2]);
				$('main-header-title').setStyle('background-color','#ff0000');
				$('frame-title').set('html','System in Disaster Recovery Mode [Host: '+ip[3].toString()+ '] '+l);
			} else {
				$('system-mode').set('value',0);
				$('system-log').set('value',0);
			}
		}
	}).send();
}

function sendPin(){
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=144&action=sendMail',
			method: 'post',
			noCache: true,
			data: $('user-details'),
			onComplete: function(){
				$('sending-email').setStyle('display','none');
				$('email-pin').set('disabled',false);
				$('new-pin').set('disabled',false);
				//$('new-pin').set('disabled',true);
				alert('Pin Sent to Email Address...');
				//window.location.href='http://opa.cput.ac.za';
			}
		}).send();
}

function checkAccount(username){
	$('email-pin').set('disabled',true);
	$('new-pin').set('disabled',true);
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=144&action=checkAccount&login='+username.toLowerCase(),
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			if (parseInt(response) == -1){
				$('email-pin').set('disabled',false);
				$('new-pin').set('disabled',false);
				//$('new-pin').set('disabled',true);
				alert('Unable to contact ITS server.');
			} else if (parseInt(response) == 0){
				$('email-pin').set('disabled',false);
				$('new-pin').set('disabled',false);
				//$('new-pin').set('disabled',true);
				alert('We were unable to locate a pin number for you. Contact helpdesk.');
				window.location.href='http://opa.cput.ac.za';
			} else {
				var staffno = parseInt(response);
				var y = new Request({
					url: 'index.php?option=com_jumi&fileid=144&action=getPin&staffno='+staffno,
					method: 'get',
					noCache: true,
					onComplete: function(response){
						if (parseInt(response) == -1){
							$('email-pin').set('disabled',false);
							$('new-pin').set('disabled',false);
							alert('Unable to contact ITS server.');
						} else if (parseInt(response) == 0){
							$('email-pin').set('disabled',false);
							//$('new-pin').set('disabled',false);
							$('new-pin').set('disabled',true);
							alert('We were unable to locate a pin number for you. Contact helpdesk.');
							window.location.href='http://opa.cput.ac.za';
						} else {
						var email = $('user-email').get('value');
						var pin = parseInt(response);
						$('staff-no').set('value',staffno);
						$('staff-pin').set('value',pin);
						$('email-pin').set('value','Email Pin to '+email);
						$('search-uniflow').setStyle('display','none');
						$('uniflow-data').setStyle('display','block');
						$('email-pin').set('disabled',false);
						$('new-pin').set('disabled',false);
						disableExport();
					}
				}
				}).send();
			}
		}
	}).send();
}

function disableExport(){
	var sm = $('system-mode').get('value');
	var sl = $('system-log').get('value');
	if (parseInt(sm) == 1) {
		if (parseInt(sl) == 1){
			$('email-pin').set('value','Pin Request Function Disabled');
			$('email-pin').set('disabled',true);
			$('new-pin').set('value','Pin Request Function Disabled');
			$('new-pin').set('disabled',true);
		}
	}
}