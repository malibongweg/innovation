<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
///jTable includes/////
$doc->addScript('scripts/jobcards/jtable/js/jquery-1.10.2.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery-ui-1.10.4.js');
$doc->addScript('scripts/jobcards/jtable/jquery.jtable.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery.validationEngine.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery.validationEngine-en.js');

$doc->addStyleSheet('scripts/jobcards/jtable/css/custom-theme/jquery-ui-1.10.4.custom.css');
$doc->addStyleSheet('scripts/jobcards/jtable/themes/metro/blue/jtable.css');
$doc->addStyleSheet('scripts/jobcards/jtable/css/validationEngine.jquery.css');

$doc->addScript("scripts/json.js");
?>
<script type="text/javascript">

window.addEvent('domready',function(){
	checkValidation();
});

function checkValidation(){
	var uname = $('uname').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=104&action=checkValidation&uname='+uname,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			if (parseInt(response) == 0){
				 jt("#validationMsg").dialog({
					 autoOpen: true,
					 height: 220,
					 width: 350,
					 modal: true,
					 buttons: {
						 close: function(){
							 jt(this).dialog("close");
					 }
				}
			 });
			} else {
				displayRequests(response);
			}
		}
	}).send();
}

function sendEmail(jid){
	var y = new Request({
	url: 'index.php?option=com_jumi&fileid=104&action=checkAck&jid='+jid,
	method: 'get',
	noCache: true,
	onComplete: function(response){
		if (parseInt(response) == 1) {
		/*Dont send anything*/
		} else {
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=104&action=sendAck&jid='+jid,
				method: 'get',
				noCache: true,
				onComplete: function(response){
					/*Keep yourself happy*/
				}
			}).send();
		}
	}
	}).send();


}

function displayRequests(uid){
	var email = $('uemail').get('value');
	jt('#userMaintenance').jtable({
        title: 'CURRENT MAINTENANCE REQUESTS',
		paging: false, // Enable paging
        pageSize: 10, // Set page size (default: 10)
        sorting: false, // Enable sorting
		selecting: true, // Enable selecting,
		dialogWidth: 300,
		loadingAnimationDelay: 0,
		jqueryuiTheme: true,
		rowInserted: (function (event, data) {
			switch (parseInt(data.record.job_status))	{
			case 1:
				jt('#userMaintenance').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#66ff66");
				break;
			case 2:
				jt('#userMaintenance').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#ffff99");
				break;
			case 3:
				jt('#userMaintenance').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#ffffff");
				break;
			case 4:
				jt('#userMaintenance').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#5983ff");
				break;
			case 5:
				jt('#userMaintenance').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#ff8888");
				break;
			case 6:
				jt('#userMaintenance').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#000000");
				break;
			default:
				break;
			}
		}),
        actions: {
            listAction: 'index.php?option=com_jumi&fileid=104&action=listUserMaintenance&uid='+uid,
            createAction: 'index.php?option=com_jumi&fileid=104&action=createUserMaintenance'
        },
        fields: {
			id: {
				key: true,
				list: true,
				title: 'REF#',
				edit: false,
				width: '7%',
				sorting: false
			},
			job_status: {
				list: false,
				create: false
			},
			creator: {
				list: false,
				create: true,
				edit: false,
				type: 'hidden',
				defaultValue: uid
			},
			capture_date: {
				title: 'REQUEST DATE',
				width: '20%',
				list: true,
				create: false,
				edit: false
			},
			applicant: {
				list: false,
				create: true,
				type: 'hidden',
				defaultValue: uid
			},
			contact_no: {
				title: 'CONTACT#',
				list: false,
				create: true,
				edit: true,
				input: function (data) {
			        if (data.record) {
			            	return '<input type="text" name="contact_no" id="contact-no" size="15" value="' + data.record.contact_no + '" />';
			        	} else {
			        		return '<input type="text" name="contact_no" size="15" id="contact-no" value="" />';
			        	}
			    	}
			},
			contact_time: {
				title: 'CONTACT TIME',
				list: false,
				create: true,
				edit: true,
				input: function (data) {
			        if (data.record) {
			            	return '<input type="text" name="contact_time" id="contact-time" size="30" maxlenght="30" value="' + data.record.contact_time + '" onkeyup="this.value=this.value.toUpperCase();" />';
			        	} else {
			        		return '<input type="text" name="contact_time" size="30" maxlenght="30" id="contact-time" value="" onkeyup="this.value=this.value.toUpperCase();" />';
			        	}
			    	}
			},
			email: {
				title: 'EMAIL ADDRESS',
				list: false,
				edit: true,
				create: true,
				defaultValue: email,
				input: function (data) {
			            	return '<input type="text" name="email" readonly id="app-email" size="50" value="' + email + '" />';
			    	}
			},

			campus: {
				title: 'CAMPUS',
				list: true,
				create: true,
				edit: true,
				sorting: false,
				options: 'index.php?option=com_jumi&fileid=104&action=listCampus',
				width: '25%'
			},
			building: {
				title: 'BUILDING',
				width: '30%',
				list: true,
				create: true,
				edit: true,
				dependsOn: 'campus',
				sorting: false,
				options: function (data) {
                    if (data.source == 'list') {
                        return 'index.php?option=com_jumi&fileid=104&action=listBuildings';
                    }
                     return 'index.php?option=com_jumi&fileid=104&action=listBuildings&cid=' + data.dependedValues.campus;
                }
			},
			status_desc: {
				title: 'STATUS',
				list: true,
				create: false
			},
			roomno: {
				title: 'ROOM#',
				list: false,
				create: true,
				edit: true,
				input: function (data) {
			        if (data.record) {
			            return '<input type="text" name="roomno" size="10" onkeyup="this.value=this.value.toUpperCase();" value="' + data.record.roomno + '" />';
			        } else {
			            return '<input type="text" name="roomno" size="10" onkeyup="this.value=this.value.toUpperCase();" value="" />';
			        }
			    }
			},
			job_details: {
				title: 'JOB DETAILS',
				type: 'textarea',
				create: true,
				edit: true,
				list: false
			}
        },

        recordsLoaded: function(event, data) {
        	jt('#userMaintenance').find('.jtable-toolbar-item-text').html('Log New Request');
        	jt('.jtable-data-row').dblclick(function() {
                var row_id = jt(this).attr('data-record-key');
                jt('#jobcard-details-link').attr('href','index.php?option=com_jumi&fileid=164&tmpl=component&jid='+row_id+'&prn=0');
                jt('#jobcard-details-link')[0].click();
            });
        },
        recordAdded: function(event, data) {
        	sendEmail(data.record.id);
        },

    	formCreated: function (event, data) {
			data.form.find('input[name="email"]').addClass('validate[required]');
			data.form.find('input[name="job_details"]').addClass('validate[required]');
			data.form.find('input[name="contact_no"]').addClass('validate[required]');
            data.form.validationEngine({promptPosition : 'topLeft', scroll: false});
			},
			// Validate form when it is being submitted
		formSubmitting: function (event, data) {
				return data.form.validationEngine('validate');
			},
			// Dispose validation logic when form is closed
		formClosed: function (event, data) {
				data.form.validationEngine('hide');
				data.form.validationEngine('detach');
		}
    });
	jt('#userMaintenance').jtable('load');
}

</script>

<input type="hidden" id="uname" value="<?php echo $user->username; ?>" />
<input type="hidden" id="uemail" value="<?php echo $user->email; ?>" />
<a href="#" id="jobcard-details-link" class="modal" /></a>
<div id="userMaintenance"></div>

<div id="validationMsg" style="display: none">
<p style="font-size: 14px">User validation required. Please click <a class="modal" href="http://opa.localhost/index.php?option=com_jumi&fileid=92&tmpl=component">HERE</a> for validation.</p>
</div>