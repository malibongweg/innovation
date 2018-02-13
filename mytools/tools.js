window.addEvent('domready',function() {
	
		copy_status();
 		setTimeout('meals_status()',1500);
  
});

function blockCopy() {
	var uid = $('uid').get('value');
	//uid = '30000030';
	$('c-button').setStyle('display','none');
	$('cajax').setStyle('display','block')
	$('cajax').set('html','<img src="/images/kit-ajax.gif" width="16" height="10" alt="" align="middle"/> Attempting to perform action...please wait.');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=18&func=bcopy&uid='+uid+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			$('cajax').set('html','Unable to get response from server. Timeout experienced.');
		x.cancel();
		copy_status();
		},
		onComplete: function(response) {
							if (parseInt(response) == 99999) {
									$('cajax').set('html','Unable to establish database connection...');
							} else if (parseInt(response) == 0) {
								alert('Action failed....Try later.');
							}
			copy_status(); 
		}
	}).send();	
}

function unBlockCopy() {
	var uid = $('uid').get('value');
	//uid = '30000030';
	$('c-button').setStyle('display','none');
	$('cajax').setStyle('display','block')
	$('cajax').set('html','<img src="/images/kit-ajax.gif" width="16" height="10" alt="" align="middle"/> Attempting to perform action...please wait.');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=18&func=ubcopy&uid='+uid+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			$('cajax').set('html','Unable to get response from server. Timeout experienced.');
		x.cancel();
		copy_status();
		},
		onComplete: function(response) {
							if (parseInt(response) == 99999) {
									$('cajax').set('html','Unable to establish database connection...');
							} else if (parseInt(response) == 0) {
								alert('Action failed....Try later.');
							}
			copy_status(); 
		}
	}).send();	
}


function copy_status() {

//////get current copy card status
	$('ajax').setStyle('display','block');
  var x = new Request({
    url: 'index.php?option=com_jumi&fileid=18&func=cstat&uid='+$('uname').value+'&dt='+new Date().getTime(),
    method: 'get',
    timeout: 8000,
    onTimeout: function () {
		$('ajax').setStyle('display','none');
		$('mcard').set('html','Meals account not available.');
      x.cancel();
    },
    onComplete: function(response) {
							if (parseInt(response) == -9) {
									$('ccard').set('html','<b>Photocopy not available.</b>');
							} else {
								switch (parseInt(response)) {
									case -1: { 
												$('ccard').set('html','<b>No photocopy account.</b>');
												break;
												}
									case 0: {	$('ccard').set('html','<b>Photocopy: <span style="color: #339900">Open</span>&nbsp;<a id="bucopy" href="1"><span style="color: #000000; font-weight: bold">&nbsp;[Block]</span></a>');
												 break;
												}
									case 1: {
												$('ccard').set('html','<b>Photocopy: <span style="color: #ff0000">Blocked</span>&nbsp;<a id="bucopy" href="0"><span style="color: #000000; font-weight: bold">&nbsp;[UnBlock]</span></a>');
												 break;
												}
								}
							}
						if ($('bucopy'))
						{
							$('bucopy').addEvent('click',function(e) {
							blockUnblockCopy(e);
							});
						}
						
						$('ajax').setStyle('display','none');
    }
  }).send();
}


function meals_status() {
$('ajax').setStyle('display','block');
  var x = new Request({
    url: 'index.php?option=com_jumi&fileid=18&func=mstat&uid='+$('uname').value+'&dt='+new Date().getTime(),
    method: 'get',
    timeout: 8000,
    onTimeout: function () {
		$('ajax').setStyle('display','none');
		$('ccard').set('html','Photocopy not available.');
      x.cancel();
    },
    onComplete: function(response) {
							if (parseInt(response) == -1) {
									$('ccard').set('html','<b>Meals not available.</b>');
							} else {
								switch (parseInt(response)) {
									case -1: { 
												$('mcard').set('html','<b>Meals not available.</b>');
												break;
												}
								    case -2: { 
												$('mcard').set('html','<b>No meals account.</b>');
												break;
												}
									case 0: {	$('mcard').set('html','<b>Meals: <span style="color: #339900">Open</span>&nbsp;<a id="bumeals" href="1"><span style="color: #000000; font-weight: bold">&nbsp;[Block]</span></a>');
												 break;
												}
									case 1: {
												$('mcard').set('html','<b>Meals: <span style="color: #ff0000">Blocked</span>&nbsp;<a id="bumeals" href="0"><span style="color: #000000; font-weight: bold">&nbsp;[UnBlock]</span></a>');
												 break;
												}
								}
							}
							if ($('bumeals'))
						{
							$('bumeals').addEvent('click',function(e) {
							blockUnblockMeals(e);
							});
						}
						$('ajax').setStyle('display','none');
    }
  }).send();
}

function blockUnblockCopy(e) {
	e.stop();
	var ac = $('bucopy').get('href');
	if (parseInt(ac) == 0)
	{
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=18&func=ubcopy&uid='+$('uname').value,
			noCache: true,
			method: 'get',
			timeout: 8000,
			onTimeout: function () {
				x.cancel();
				copy_status();
			},
			onComplete: function(response) {
				copy_status();
			}
		}).send();
	} else if (parseInt(ac) == 1)
	{
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=18&func=bcopy&uid='+$('uname').value,
			noCache: true,
			method: 'get',
			timeout: 8000,
			onTimeout: function () {
				x.cancel();
				copy_status();
			},
			onComplete: function(response) {
				copy_status();
			}
		}).send();
	}
}

function blockUnblockMeals(e) {
	e.stop();
	var ac = $('bumeals').get('href');
	if (parseInt(ac) == 0)
	{
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=18&func=ubmeals&uid='+$('uname').value,
			noCache: true,
			method: 'get',
			timeout: 8000,
			onTimeout: function () {
				x.cancel();
				meals_status();
			},
			onComplete: function(response) {
				meals_status();
			}
		}).send();
	} else if (parseInt(ac) == 1)
	{
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=18&func=bmeals&uid='+$('uname').value,
			noCache: true,
			method: 'get',
			timeout: 8000,
			onTimeout: function () {
				x.cancel();
				meals_status();
			},
			onComplete: function(response) {
				meals_status();
			}
		}).send();
	}
}