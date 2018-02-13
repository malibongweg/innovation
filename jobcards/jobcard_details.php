<?php
error_reporting(1);
// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("/scripts/json.js");
?>
<style>
.btn {
  background: #CACCC7;
  background-image: -webkit-linear-gradient(top, #CACCC7, #CACCC7);
  background-image: -moz-linear-gradient(top, #CACCC7, #CACCC7);
  background-image: -ms-linear-gradient(top, #CACCC7, #CACCC7);
  background-image: -o-linear-gradient(top, #CACCC7, #CACCC7);
  background-image: linear-gradient(to bottom, #CACCC7, #CACCC7);
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0px;
  font-family: Arial;
  color: #000000;
  font-size: 12px;
  padding: 8px 10px 8px 10px;
  text-decoration: none;
}

.btn:hover {
  background: #8e9599;
  background-image: -webkit-linear-gradient(top, #8e9599, #8e9599);
  background-image: -moz-linear-gradient(top, #8e9599, #8e9599);
  background-image: -ms-linear-gradient(top, #8e9599, #8e9599);
  background-image: -o-linear-gradient(top, #8e9599, #8e9599);
  background-image: linear-gradient(to bottom, #8e9599, #8e9599);
  text-decoration: none;
}

</style>
<script type="text/javascript">
window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({width: 800, height: 620});
	$('send-email').addEvent('click',function(){
		sendEmail();
	});

	$('close-window').addEvent('click',function(){
		window.parent.$j.colorbox.close();
	});

	var p = $('can-print').get('value');
	if (parseInt(p) == 0) {
		$('send-email').setStyle('display','none');
	}
	displayJobcard(<?php echo $_GET['jid']; ?>);
	displayArtisans(<?php echo $_GET['jid']; ?>);
	displayDelays(<?php echo $_GET['jid']; ?>);
});

function displayJobcard(jid){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=jobcardDetails&jid='+jid,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			var row = json_parse(response,function(key,text) {
				switch (key) {
					case 'id':
						$('jobcardno').set('html',text);
						break;
					case 'capture_date':
						$('date-captured').set('html',text);
						break;
					case 'assigned_date':
						var d = '';
						if (text == null) { d = 'NOT ASSIGNED'; } else { d = text; }
						$('date-assigned').set('html',d);
						break;
					case 'applicant':
						$('applicant').set('html',text);
						break;
					case 'contact_no':
						$('contactno').set('html',text);
						break;
					case 'contact_time':
						$('contact-time').set('html',text);
						break;
					case 'email':
						$('email-address').set('html',text);
						break;
					case 'campus_name':
						$('campus').set('html',text);
						break;
					case 'build_name':
						$('building').set('html',text);
						break;
					case 'roomno':
						$('roomno').set('html',text);
						break;
					case 'vandalism':
						var v = '';
						if (parseInt(text) == 0) { v = 'NO'; } else { v = 'YES'; }
						$('vandalism').set('html',v);
						break;
					case 'status_desc':
						$('status').set('html',text);
						break;
					case 'job_details':
						$('details').set('html',text);
						break;
				}

			});
		}
	}).send();
}

function displayArtisans(jid){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=listAssignedArtisans&jid='+jid,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			var data = json_parse(response);
			var text = '';
			if (data.Records.length == 0) {
				text = 'NONE ASSIGNED';
			} else {
				for (var i = 0; i<data.Records.length; ++i){
					text = text + data.Records[i].fullname+' ['+data.Records[i].trade_description+']<br />';
				}
			}
			$('artisans').set('html',text);
		}
	}).send();
}

function displayDelays(jid){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=showDelays&jid='+jid,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			var data = json_parse(response);
			var text = '';
			if (data.Records.length == 0) {
				text = 'NO DELAYS';
			} else {
				for (var i = 0; i<data.Records.length; ++i){
					text = text + data.Records[i].start_time+' -> '+data.Records[i].reason+'<br />';
				}
			}
			$('delays').set('html',text);
		}
	}).send();
}

function sendEmail(){
	var email = $('email-address').get('value');
	var y = new Request({
	url: 'index.php?option=com_jumi&fileid=104&action=checkAck&jid=<?php echo $_GET['jid']; ?>',
	method: 'get',
	noCache: true,
	onComplete: function(response){
		if (parseInt(response) == 1) {
			alert('Acknowledgement email was already sent to user.');
		} else {
			$('details-ajax').setStyle('display','block');
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=104&action=sendAck&jid=<?php echo $_GET['jid']; ?>',
				method: 'get',
				noCache: true,
				onComplete: function(response){
					$('details-ajax').setStyle('display','none');
					alert('Acknowledgement email sent to user.');
				}
			}).send();
		}
	}
	}).send();


}
</script>
<input type="hidden" id="can-print" value="<?php echo $_GET['prn']; ?>" />
<div id="details-ajax" style="top: 10px; left: 10px; z-order: 1000; border: 1px solid #c8c8c8; background-color: #d8d8d8;padding: 3px; display: none">
	<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Sending email...Please wait.
</div>

<div style="display: inline-block; width: 97%; height: 100%; padding: 5px; border: 2px solid #c8c8c8; background-color: #e2e2e2; text-align: center; font-size: 24px; font-weight: bold">
<p>JOBCARD DETAILS</p>
<input type="button" value="Send Acknowledgement Email" class="btn" id="send-email" />&nbsp;
<input type="button" value="Close Window" class="btn" id="close-window" />
</div>

<div>

<div style="width: 47%; float: left; height: auto; margin: 5px 0 0 0; padding: 3px; border: 2px solid #c8c8c8; background-color: #e2e2e2" >
<p>JOBCARD#: <span id="jobcardno" style="font-weight: bold"></span></p>
<p>DATE CAPTURED: <span id="date-captured" style="font-weight: bold"></span></p>
<p>DATE ASSIGNED: <span id="date-assigned" style="font-weight: bold"></span></p>
<p>APPLICANT: <span id="applicant" style="font-weight: bold"></span></p>
<p>CONTACT# <span id="contactno" style="font-weight: bold"></span></p>
<p>CONTACT TIME: <span id="contact-time" style="font-weight: bold"></span></p>
<p>EMAIL: <span id="email-address" style="font-weight: bold"></span></p>
<p>CAMPUS: <span id="campus" style="font-weight: bold"></span></p>
<p>BUILDING: <span id="building" style="font-weight: bold"></span></p>
<p>ROOMNO# <span id="roomno" style="font-weight: bold"></span></p>
<p>VANDALISM? <span id="vandalism" style="font-weight: bold"></span></p>
<p>STATUS: <span id="status" style="font-weight: bold"></span></p>
<p>JOB DETAILS: <span id="details" style="font-weight: bold"></span></p>
</div>

<div style="width: 47%; float: right; height: auto; margin: 5px 10px 0 0; padding: 3px; border: 2px solid #c8c8c8; background-color: #e2e2e2" >
<div style="margin: 3px; padding: 3px; width: auto; background-color: #c8c8c8; display: block">ARTISAN(S) ASSIGNED</div>
<p id="artisans" style="margin: 3px; padding: 3px"></p>
</div>

<div style="width: 47%; float: right; height: auto; margin: 5px 10px 0 0; padding: 3px; border: 2px solid #c8c8c8; background-color: #e2e2e2" >
<div style="margin: 3px; padding: 3px; width: auto; background-color: #c8c8c8; display: block">POSSIBLE DELAYS
<p id="delays" style="margin: 3px; padding: 3px"></p>
</div>
</div>

</div>