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

	$('secondary-profile').addEvent('submit',function(e){
		e.stop();
		saveProfile(this);
	});

	//$('confirm-button').addEvent('click',function(){
	//	this.set('value','Busy...');
	//	this.set('disabled',true);
	//	saveProfileView();
	//	this.set('value','Confirm');
	//	this.set('disabled',false);
	//});

	$('staff-title').addEvent('click',function(){
		if ($('new-title').getStyle('display') == 'inline'){
			$('new-title').setStyle('display','none');
			//$('new-title').set('value','');
		} else {
			$('new-title').setStyle('display','inline');
			$('new-title').focus();
		}
	});

	$('staff-initials').addEvent('click',function(){
		if ($('new-initials').getStyle('display') == 'inline'){
			$('new-initials').setStyle('display','none');
			$('new-initials').set('value','');
		} else {
			$('new-initials').setStyle('display','inline');
			$('new-initials').focus();
		}
		
	});

	$('staff-aka').addEvent('click',function(){
		if ($('n-aka').getStyle('display') == 'inline'){
			$('n-aka').setStyle('display','none');
			$('n-aka').set('value','');
		} else {
			$('n-aka').setStyle('display','inline');
			$('n-aka').focus();
		}
		
	});

	$('staff-surname').addEvent('click',function(){
		if ($('new-surname').getStyle('display') == 'inline'){
			$('new-surname').setStyle('display','none');
			$('new-surname').set('value','');
		} else {
			$('new-surname').setStyle('display','inline');
			$('new-surname').focus();
		}
		
	});

	$('staff-fname').addEvent('click',function(){
		if ($('new-name').getStyle('display') == 'inline'){
			$('new-name').setStyle('display','none');
			$('new-name').set('value','');
		} else {
			$('new-name').setStyle('display','inline');
			$('new-name').focus();
		}
		
	});

	$('staff-faculty').addEvent('click',function(){
		if ($('new-faculty').getStyle('display') == 'inline'){
			$('new-faculty').setStyle('display','none');
			//$('new-faculty').set('value','');
		} else {
			$('new-faculty').setStyle('display','inline');
			$('new-faculty').focus();
		}
		
	});

	$('staff-department').addEvent('click',function(){
		if ($('new-department').getStyle('display') == 'inline'){
			$('new-department').setStyle('display','none');
		} else {
			$('new-department').setStyle('display','inline');
			$('new-department').focus();
		}
		
	});

	$('staff-jobtitle').addEvent('click',function(){
		if ($('new-jobtitle').getStyle('display') == 'inline'){
			$('new-jobtitle').setStyle('display','none');
			//$('new-jobtitle').set('value','');
		} else {
			$('new-jobtitle').setStyle('display','inline');
			$('new-jobtitle').focus();
		}
		
	});

	$('staff-linemanager').addEvent('click',function(){
		if ($('new-linemanager').getStyle('display') == 'inline'){
			$('new-linemanager').setStyle('display','none');
			//$('new-jobtitle').set('value','');
		} else {
			$('new-linemanager').setStyle('display','inline');
			$('new-linemanager').focus();
		}
		
	});

	$('staff-cellno').addEvent('click',function(){
		if ($('new-cellno').getStyle('display') == 'inline'){
			$('new-cellno').setStyle('display','none');
			//$('new-jobtitle').set('value','');
		} else {
			$('new-cellno').setStyle('display','inline');
			$('new-cellno').focus();
		}
		
	});



	$('fax-no').addEvent('click',function(){
		if ($('new-fax').getStyle('display') == 'inline'){
			$('new-fax').setStyle('display','none');
			$('new-fax').set('value','');
		} else {
			$('new-fax').setStyle('display','inline');
			$('new-fax').focus();
		}
		
	});



$('staff-campus').addEvent('click',function(){
		if ($('new-campus').getStyle('display') == 'inline'){
			$('new-campus').setStyle('display','none');
			//$('new-campus').set('value','');
		} else {
			$('new-campus').setStyle('display','inline');
			$('new-campus').focus();
		}
		
	});

	$('staff-email').addEvent('click',function(){
		if ($('new-email').getStyle('display') == 'inline'){
			$('new-email').setStyle('display','none');
			$('new-email').set('value','');
		} else {
			$('new-email').setStyle('display','inline');
			$('new-email').focus();
		}
		
	});

	$('staff-building').addEvent('click',function(){
		if ($('new-building').getStyle('display') == 'inline'){
			$('new-building').setStyle('display','none');
			//$('new-building').set('value','');
		} else {
			$('new-building').setStyle('display','inline');
			$('new-building').focus();
		}
		
	});

$('staff-floor').addEvent('click',function(){
		if ($('new-floor').getStyle('display') == 'inline'){
			$('new-floor').setStyle('display','none');
			$('new-floor').set('value','');
		} else {
			$('new-floor').setStyle('display','inline');
			$('new-floor').focus();
		}
		
	});


$('staff-room').addEvent('click',function(){
		if ($('new-room').getStyle('display') == 'inline'){
			$('new-room').setStyle('display','none');
			$('new-room').set('value','');
		} else {
			$('new-room').setStyle('display','inline');
			$('new-room').focus();
		}
		
	});

	$('staff-ext').addEvent('click',function(){
		if ($('new-ext').getStyle('display') == 'inline'){
			$('new-ext').setStyle('display','none');
			$('new-ext').set('value','');
		} else {
			$('new-ext').setStyle('display','inline');
			$('new-ext').focus();
		}
		
	});

	$('staff-speeddial1').addEvent('click',function(){
		if ($('new-speeddial1').getStyle('display') == 'inline'){
			$('new-speeddial1').setStyle('display','none');
		} else {
			$('new-speeddial1').setStyle('display','inline');
			$('new-speeddial1').focus();
		}
		
	});
	
	$('staff-stfnumber').addEvent('click',function(){
		if ($('new-stfnumber').getStyle('display') == 'inline'){
			$('new-stfnumber').setStyle('display','none');
		} else {
			$('new-stfnumber').setStyle('display','inline');
			$('new-stfnumber').focus();
		}
		
	});


	$$('input[type=text]').each(function(el){
		if (el.name.substring(0,3) == 'new')
		{
			el.set('value','');
		}
	});

checkData();


});

function checkData(){
	var lg = $('login-name').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=check_data&lg='+lg,
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response){
			if (parseInt(response) == 0)
			{
				alert('User login name/staff number validation required a.s.a.p.'+'\n'+'Verify details under Links->Verify Staff# option in the top menu bar.');
				window.location.href='/index.php';
			} else {
				displayInfo(lg);
			}
		}
	}).send();
}

function emailButton(){
	var fnd = false;
	$$('input[type=text]').each(function(el){
		if (el.name.substring(0,3) == 'new')
		{
			if (el.getStyle('display') == 'inline')
			{
				fnd = true;
			}
		}
	});
	//if (fnd == true)
	//{
	//	$('send-changes').setStyle('display','inline');
	//} else {
	//	$('send-changes').setStyle('display','none');
	//}
}

function displayInfo(lg) {
	$('ajax-sp').setStyle('display','block');
	var y = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=display_info&uid='+lg,
			noCahce: true,
			method: 'get',
			timeout: 10000,
			onTimeout: function() {
				$('ajax-sp').setStyle('display','none');
				alert('Error retrieving details.'+'\n'+'Please try later.');
				y.cancel();
			},
			onComplete: function(response) {
				var r = response.split(';');
				if (parseInt(response) < 0)
				{	
					$('ajax-sp').setStyle('display','none');
					alert('Error retrieving primary data.'+'\n'+'Please try later.');
				} else {
					$('staff-stfnumber').set('value',r[0]);
					$('hidden-staffno').set('value',r[0]);
					$('sec-staff').set('value',r[0]);
					$('aka-staff').set('value',r[0]);
					$('staff-title').set('value',r[1]);
					$('staff-initials').set('value',r[2]);
					$('staff-surname').set('value',r[3]);
					$('staff-fname').set('value',r[4]);
					$('staff-faculty').set('value',r[5]);
					$('staff-department').set('value',r[6]);
					$('staff-cellno').set('value',r[7]);
					$('staff-jobtitle').set('value',r[8]);
					$('staff-linemanager').set('value',r[9]);
					$('staff-campus').set('value',r[10]);
					$('staff-building').set('value',r[11]);
					$('staff-floor').set('value',r[12]);
					$('staff-room').set('value',r[13]);
					$('staff-ext').set('value',r[14]);
					$('staff-speeddial1').set('value',r[15]);
					$('staff-email').set('value',r[16]);
					displayLastViewed();
					displaySubmitted(r[0]);
				}
				$('ajax-sp').setStyle('display','none');
					
							var z = new Request({
								url: 'index.php?option=com_jumi&fileid=79&action=display_secondary&stfno='+r[0],
								noCahce: true,
								method: 'get',
								timeout: 10000,
								onTimeout: function() {
									$('ajax-sp').setStyle('display','none');
									alert('Error retrieving secondary details.'+'\n'+'Please try later.');
									z.cancel();
								},
								onComplete: function(response) {
									if (parseInt(response) == -1)
									{
										alert('Error retrieving secondary data.');
									} else {
										var rr = response.split(';');
										$('fax-no').set('value',rr[0]);
										$('speed-dial').set('value',rr[1]);
										$('sec-ext').set('value',rr[4]);
										$('sec-fax').set('value',rr[5]);
										$('sec-email').set('value',rr[6]);
										$('other-floor').set('value',rr[9]);
										$('other-room').set('value',rr[10]);
										$('other-ext').set('value',rr[11]);
										$('other-fax').set('value',rr[12]);
										$('staff-aka').set('value',rr[13]);
										$('sec-ext2').set('value',rr[16]);
										$('sec-fax2').set('value',rr[17]);
										$('sec-email2').set('value',rr[18]);
							
										var op = $('secretary-person2').getElements('option');
										op.each(function(el){
											if (el.value == rr[15])
											{
												el.selected = true;
											}
										});
										var op = $('staff-hod2').getElements('option');
										op.each(function(el){
											if (el.value == rr[14])
											{
												el.selected = true;
											}
										});
										//var op = $('line-manager').getElements('option');
										//op.each(function(el){
										//	if (el.value == rr[2])
									//		{
									//			el.selected = true;
									//		}
									//	});
										var op = $('secretary-person').getElements('option');
										op.each(function(el){
											if (el.value == rr[3])
											{
												el.selected = true;
											}
										});
										var op = $('other-campus').getElements('option');
										op.each(function(el){
											if (el.value == rr[7])
											{
												el.selected = true;
											}
										});
										var op = $('other-building').getElements('option');
										op.each(function(el){
											if (el.value == rr[8])
											{
												el.selected = true;
											}
										});
									}

								}
							}).send();

		}
	}).send();
}


function displaySubmitted(stfno) {
	var x = new Request({
	url: 'index.php?option=com_jumi&fileid=79&action=display_submitted&uid='+stfno,
		noCache: true,
		method: 'get',
		onComplete: function(response) {
		var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						$('key').setStyle('display','block');
						var r = text.split(';');
						if (parseInt(r[1]) == 1) {
							$(data.substring(4)+'-yellow').setStyle('display','inline');
							$(data.substring(4)+'-yellow').set('value',r[0]);
						} else if (parseInt(r[1]) == 2) {
							$(data.substring(4)+'-green').setStyle('display','inline');
							$(data.substring(4)+'-green').set('value',r[0]);
						}
						  else if (parseInt(r[1]) == 3) {
							$(data.substring(4)+'-red').setStyle('display','inline');
							$(data.substring(4)+'-red').set('value',r[0]);
						}
					}
					});
		}
			
	}).send();
}

function saveProfile(form){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=save_profile',
			noCache: true,
			method: 'post',
			data: form,
			onComplete: function(response){
	
				var y = new Request({
					url: 'index.php?option=com_jumi&fileid=79&action=save_profile_sec',
					noCache: true,
					method: 'post',
					data: $('sec-pri'),
						onComplete: function(response){
									var z = new Request({
									url: 'index.php?option=com_jumi&fileid=79&action=save_profile_aka',
									noCache: true,
									method: 'post',
									data: $('aka-form'),
										onComplete: function(response){
										if (parseInt(response) == -1)
										{
											alert('Error updating profile information.');
										} else {
											alert('Information successfully updated.');
										}
											var stc = $('new-cellno').get('value');
											if (stc.length > 7){
												var uid = $('staff-stfnumber').get('value');
												var lg = $('staff-lname').get('value');
												lg = lg.toLowerCase();
												var c = new Request({
													url: 'index.php?option=com_jumi&fileid=79&action=save_profile_cell&uid='+uid+'&cell='+stc+'&lg='+lg,
													noCache: true,
													medthod: 'get',
													async: false,
													onComplete: function(response){

													}
												}).send();
											}
									}		
								}).send();
					}
				}).send();
		}
	}).send();
	sendToHR();
}

function Test(){
	$$('select').each(function(el){
		
				alert(el.name);
	});
}

function sendToHR() {
	var chg = '';
	$$('input[type=text]').each(function(el,idx){
		if (el.name.substring(0,3) == 'new'){
			if (el.getStyle('display') == 'inline'){
					///Send updates to database...
					 var x = new Request({
			         url: 'index.php?option=com_jumi&fileid=79&action=saveChangesDB&stno='+$('staff-stfnumber').get('value')+'&field_name='+$$('input[type=text]')[idx].name+'&old_value='+$$('input[type=text]')[idx-1].value+'&new_value='+$$('input[type=text]')[idx].value,
						method: 'get',
						noCache: true,
						async: false,
						onComplete: function(response) {
					 }
					}).send();
		}
	}
	});


	$$('select').each(function(el,idx){
		if (el.name.substring(0,3) == 'new'){
			if (el.getStyle('display') == 'inline'){
				
				var opt = el.getElements("option");
					opt.each(function(ex){
						if (ex.selected == true)
						{
							///Send updates to database...
							 var oldValue = $('staff-'+el.name.substring(4)).get('value');
							 var x = new Request({
							 url: 'index.php?option=com_jumi&fileid=79&action=saveChangesDB&stno='+$('staff-stfnumber').get('value')+'&field_name='+el.name+'&old_value='+encodeURIComponent(oldValue)+'&new_value='+encodeURIComponent(ex.text+' ['+ex.value+']'),
								method: 'get',
								noCache: true,
								async: false,
								onComplete: function(response) {
							 }
							}).send();
						}
					});
			}
		}
	});
/*
	$('ajax-title').set('html','Updating data..please wait.');
	$('ajax-sp').setStyle('display','block');
	
	if (cnt == true){
		var dept = $('staff-department').get('value');
		var fac = $('staff-faculty').get('value');
	    var x = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=mail_hr&uid='+$('uid').get('value')+'&stfno='+$('staff-number').get('value')+'&dept='+encodeURIComponent(dept)+'&fac='+encodeURIComponent(fac)+'&info='+encodeURIComponent(chg),
			method: 'get',
			noCache: true,
			onComplete: function(response){
			if (parseInt(response) != 1){
				alert('Error sending changes to HR department. Please contact CTS helpdesk.');
			} else {
				alert('HR updates sent successfully.');
			}
			$('send-changes').set('value','Send Changes');
			$('send-changes').set('disabled',false);
		}
		}).send();
	}
	*/
	$('ajax-sp').setStyle('display','none');
	$$('input[type=text]').each(function(el){
		if (el.id.substring(0,3) == 'new')
		{
			el.setStyle('display','none');
			el.set('value','');
		}
	});
	$$('select').each(function(el,idx){
		el.setStyle('display','none');
	});
	checkData();
}

function saveProfileView(){
	saveProfile($('secondary-profile'));
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=save_profile_view&id='+$('staff-stfnumber').get('value'),
			method: 'get',
			noCache: true,
			onComplete: function(){
			//alert('Profile review date saved.');
			displayLastViewed();
		}
	}).send();
}

function displayLastViewed(){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=79&action=display_last_view&id='+$('staff-stfnumber').get('value'),
			method: 'get',
			noCache: true,
			onComplete: function(response){
			$('mtitle').set('html','Human Resource Details [Last Confirmed: '+response+']');
		}
	}).send();
}