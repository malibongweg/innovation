<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
?>
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="username" value="<?php echo $user->username; ?>" />


<!--div id="internet" style="width: 100%; height: auto; float: left; padding: 3px 3px"><img style="vertical-align: middle" src="images/kit-ajax.gif" width="16" height="16" />
</div-->

<!--div id="printing" style="width: 100%; height: auto; float: left; padding: 3px 3px"><img style="vertical-align: middle" src="images/kit-ajax.gif" width="16" height="16" />
</div>

<div id="copying" style="width: 100%; height: auto; float: left; padding: 3px 3px"><img style="vertical-align: middle" src="images/kit-ajax.gif" width="16" height="16" />
</div-->


<div id="printing" style="width: 100%; height: auto; float: left; padding: 3px 3px">

<img style="vertical-align: middle" src="images/smiley2.png" width="16" height="16"  />

<a href="http://10.47.2.221/pwclient" target="_blank">Click Here For Print/Copy Balance</a>

</div>

<div id="proxy" style="width: 100%; height: auto; float: left; padding: 0 3px"><img style="vertical-align: middle" src="images/kit-ajax.gif" width="16" height="16" />
</div>



<script type="text/javascript" >


function getProxyBalance() {
	$('proxy').set('html','&nbsp;<img src="images/kit-ajax.gif" width="16" height="16" />');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=16&function=proxy&uid=<?php echo $user->username; ?>&dt='+new Date().getTime(),
		//	url: 'index.php?option=com_jumi&fileid=16&function=proxy&uid=alexanderm&dt='+new Date().getTime(),
		method: 'get',
		timeout: 8000,
		onTimeout: function () {
		x.cancel();
		$('proxy').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Internet not available.');
		},
		onComplete: function(response) {
							if (parseInt(response) == -1) {
								$('proxy').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Intenet not available.');
							} else  {
								$('proxy').set('html','<img style="vertical-align: middle" src="images/smiley2.png" width="16" height="16"  />&nbsp;Internet R '+response);
							}
			}
	}).send();
}

function getmBalance() {
	$('meals').set('html','&nbsp;<img src="images/kit-ajax.gif" width="16" height="16" />');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=16&function=mbal&uid=<?php echo $user->username; ?>&dt='+new Date().getTime(),
		method: 'get',
		timeout: 10000,
		onTimeout: function () {
		x.cancel();
		$('meals').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Meals not available.');
		},
		onComplete: function(response) {
							if (parseInt(response) == -1) {
								$('meals').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Meals not available.');
							} else if (parseFloat(response) <= 20) {
								$('meals').set('html','<img style="vertical-align: middle" src="images/smiley1.png" width="16" height="16"  />&nbsp;Meals R '+response);
							} else {
								$('meals').set('html','<img style="vertical-align: middle" src="images/smiley2.png" width="16" height="16"  />&nbsp;Meals R '+ response);
							}
			}
	}).send();
}

function getpBalance() {
	$('printing').set('html','&nbsp;<img src="images/kit-ajax.gif" width="16" height="16" />');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=16&function=pbal&uid=<?php echo $user->username; ?>&dt='+new Date().getTime(),
		method: 'get',
		timeout: 10000,
		onTimeout: function () {
		x.cancel();
		$('printing').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Printing not available.');
		},
		onComplete: function(response) {
							if (parseInt(response) == -1) {
								$('printing').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Printing not available.');
							} else if (parseFloat(response) <= 20) {
								$('printing').set('html','<img style="vertical-align: middle" src="images/smiley1.png" width="16" height="16"  />&nbsp;Printing R '+response);
							} else {
								$('printing').set('html','<img style="vertical-align: middle" src="images/smiley2.png" width="16" height="16"  />&nbsp;Printing R '+ response);
							}
			}
	}).send();
}

function getcBalance() {
	$('copying').set('html','&nbsp;<img src="images/kit-ajax.gif" width="16" height="16" />');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=16&function=cbal&uid=<?php echo $user->username; ?>&dt='+new Date().getTime(),
		method: 'get',
		timeout: 10000,
		onTimeout: function () {
		x.cancel();
		$('copying').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Copies not available.');
		},
		onComplete: function(response) {
							if (parseFloat(response) == 99999) {
								$('copying').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Copies not available.');
							} else if (parseFloat(response) == -1){
								$('copying').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;No copy account.');
							} else if (parseFloat(response) <= 20) {
								$('copying').set('html','<img style="vertical-align: middle" src="images/smiley1.png" width="16" height="16"  />&nbsp;Copies R '+response);
							} else if (parseFloat(response) > 20) {
								$('copying').set('html','<img style="vertical-align: middle" src="images/smiley2.png" width="16" height="16"  />&nbsp;Copies R '+response);
							} else if (parseFloat(response) == 5) {
								$('copying').set('html','<img style="vertical-align: middle" src="images/smiley2.png" width="16" height="16"  />&nbsp;Copies Account Blocked');
							} else {
								$('copying').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Copies not available.');
							}
			}
	}).send();
}

function getSMSBalance() {
	$('sms').set('html','&nbsp;<img src="images/kit-ajax.gif" width="16" height="16" />');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=16&function=smsbal&username='+$('username').get('value')+'&uid=<?php echo $user->id; ?>&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function () {
		x.cancel();
		$('sms').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Sms not available.');
		},
		onComplete: function(response) {
							if (parseFloat(response) == -1) {
								$('sms').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Sms not available.');
							} else if (parseFloat(response) <= 20) {
								$('sms').set('html','<img style="vertical-align: middle" src="images/smiley1.png" width="16" height="16"  />&nbsp;Sms R '+response);
							} else if (parseFloat(response) > 20) {
								$('sms').set('html','<img style="vertical-align: middle" src="images/smiley2.png" width="16" height="16"  />&nbsp;Sms R '+response);
							} else {
								$('sms').set('html','<img style="vertical-align: middle" src="images/account_error.png" width="16" height="16"  />&nbsp;Sms not available.');
							}
			}
	}).send();
}
//setTimeout('getpBalance()',1500);
//setTimeout('getcBalance()',3000);
setTimeout('getProxyBalance()',2000);
var rm = new RegExp('^[0-9]+$');
var r = rm.exec($('username').get('value'));
	if (r == null)
	{
		document.write('<div id="sms" style="width: 100%; height: auto; float: left; padding: 3px 3px"><img style="vertical-align: middle" src="images/kit-ajax.gif" width="16" height="16" /></div>');
		setTimeout('getSMSBalance()',2500);
	}// else {
	//	document.write('<div id="meals" style="width: 100%; height: auto; float: left; padding: 3px 3px"><img style="vertical-align: middle" src="images/kit-ajax.gif" width="16" height="16" /></div>');
	//	setTimeout('getmBalance()',3500);
	//}
</script>
