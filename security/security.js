var tref;
var local_uid;

window.addEvent('domready',function() {
	
	$('srch').value = '';
	$('srch').focus();

	$('card-reprint').addEvent('click',function() {
		if ($('card-reprint').checked == false){
			if (confirm('Clear re-print flag?'))	{
				var x = new Request({
					url: 'index.php?option=com_jumi&fileid=4&func=reset_reprint&uid='+$('lname').get('value')+'&dt='+new Date().getTime(),
					method: 'get',
						onComplete: function() {
						$('card-reprint').set('checked',false);
						alert('Re-Print flag cleared.');
					}
				}).send();
			}
		} else {
			$('card-reprint').set('checked',false);
		}
	});
	
	$('fac-officer').addEvent('click',function() {
		if ($('fac-officer').get('checked') == false) {
			if(confirm('This action will disable administration for all faculties.'+'\n'+'Are you sure?')) {
					$('fac-loader').setStyle('display','block');
					var x = new Request({
						url: 'index.php?option=com_jumi&fileid=4&func=masremove&uid='+$('user_id').value+'&dt='+new Date().getTime(),
						method: 'get',
						onComplete: function() {
							$('fac-loader').setStyle('display','none');
							$('faculty').empty();
							$('fac-officer').set('checked',false);
							$('div-faculty').setStyle('display','none');
							displaySecurity($('user_id').value);
							alert('MAS Administration security level removed.');
						}
					}).send();
			} else { $('fac-officer').set('checked',true); }
		} else {
			$('div-faculty').setStyle('display','block');
			$('faculty').empty();
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=4&func=getfac&dt='+new Date().getTime(),
				method: 'get',
				onComplete: function(response) {
						var row = json_parse(response,function(data,text) {
							if (typeof text == 'string') {
								new Element('option',{ 'value':data,'text':text}).inject($('faculty'));
							}
						});
				}
			}).send();
		}
	});

	$('fleet-checkbox').addEvent('click',function() {
		var uid = $('user_id').value;
		assignRevokeFleet(this,uid);
	});

	$('save-fleet').addEvent('click',function(){
		var uid = $('user_id').value;
		saveFleet(uid);
	});

	$('save-claim-system').addEvent('click',function(){
		var uid = $('user_id').value;
		saveSystem(uid);
	});
		
	$('srch').addEvent('keydown',function() {
		$('list-users').setStyle('display','none');
		$('div-mas-admin').setStyle('display','none');
		$('div-fleet').setStyle('display','none');
		$('div-claims').setStyle('display','none');
		$$('input[type=checkbox]').set('checked',false);
		$('user-details').setStyle('display','none');
		clearTimeout(tref);
		tref = setTimeout('srchUser()',500);
	});
	
	$('getUser').addEvent('click',function() {
		var uid = parseInt($('userList').getSelected().get('value'));
		local_uid = uid;
				if (typeof uid == 'number' && uid > 0) {
					$('list-users').setStyle('display','none');
					$('srch').value = '';
					displayInfo(uid);
				} else {
					alert('Please select user object.');
					$('srch').value = '';
					$('srch').focus();		
				}
	});
	
	$('security_levels').addEvent('submit',function(e) {
		new Event(e).stop();
		var Req = new Request({
					url: this.get('action'),//+'&at='+new Date().getTime(),
					method: 'get',
					data: this,
					onComplete: function (response) {
						alert('Security levels updated.');
					}
		}).send();
	});
	
	$('fac-form').addEvent('submit',function(e) {
		e.stop();
		$('fuid').value = $('user_id').value;
		var Req = new Request({
					url: this.get('action'),//+'&at='+new Date().getTime(),
					method: 'get',
					data: this,
					onComplete: function (response) {
						displaySecurity($('fuid').value);
						$('fac-officer').set('checked',true);
						alert('User assigned to faculty.');
					}
		}).send();
	});
	
});


function masAdmin(uid) {
	var fac;
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=4&func=faculty&uid='+uid+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
				var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						if (parseInt(text) == 0) {
							$('fac-officer').set('checked',false); $('div-faculty').setStyle('display','none');
						} else { $('fac-officer').set('checked',true); $('div-faculty').setStyle('display','block'); 
											var x = new Request({
											url: 'index.php?option=com_jumi&fileid=4&func=getassignedfac&uid='+uid,
											method: 'get',
											noCache: true,
											onComplete: function(response) {
												$('faculty').empty();
												var data = json_parse(response);
												if (parseInt(data.cnt) == 0){
														fac = 0;
												} else {
														fac = data.Record.faculty;
												}
												
												
																var x = new Request({
																url: 'index.php?option=com_jumi&fileid=4&func=getfac&dt='+new Date().getTime(),
																method: 'get',
																onComplete: function(response) {
																	$('faculty').empty();
																		var row = json_parse(response,function(data,text) {
																			if (typeof text == 'string') {
																				if (parseInt(data) == parseInt(fac)) { 	
																					new Element('option',{ 'value':data,'text':'['+data+']'+text,'selected':true}).inject($('faculty'));
																				} else {
																					new Element('option',{ 'value':data,'text':'['+data+']'+text}).inject($('faculty'));
																				}
																						
																			}
																		});
																}
																}).send();
											}
											}).send();					
						}
					}
				});
		}
	}).send();
}

function srchUser() {
	$('ajax-loader').setStyle('display', 'block');
	var url = 'index.php?option=com_jumi&fileid=4&user='+$('srch').value+'&func=srch&dt='+new Date().getTime();
	var x = new Request({
		url: url,
		method: 'get',
		onComplete: function(response) {
				$('userList').empty();
				var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					var r = text.split(';');
					new Element('option',{ 'value':r[0],'text':r[1]+' ['+r[2]+']'}).inject($('userList'));
				}
			});
			$('ajax-loader').setStyle('display','none');
			$('list-users').setStyle('display','block');
		}
	}).send();
}

function displayInfo(uid) {

	var y = new Request({
		url: 'index.php?option=com_jumi&fileid=4&uid='+uid+'&func=card_reprint&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var d = response.split(';');
			if (parseInt(d[1]) == 0){
				$('card-reprint').set('checked',false);
			} else {
				if (parseInt(d[0]) == 1){
					$('card-reprint').set('checked',true);
				} else {
					$('card-reprint').set('checked',false);
				}
			}
		}
	}).send();

	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=4&uid='+uid+'&func=info&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
				$('userList').empty();
					var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						switch (data) {
							case 'id': $('uid').value = text; break;
							case 'username': $('lname').value = text; break;
							case 'name': $('uname').value = text; break;
							case 'email': $('email').value = text; break;
						}
					}
				});
				$('user-details').setStyle('display','block');
				var utype = parseInt($('lname').get('value'));
				if (utype > 0){
					$('crp').setStyle('display','block');
				} else {
					$('crp').setStyle('display','none');
				}

				displaySecurity(uid);
				masAdmin(uid);
				displayFleet(uid);
				displayClaims(uid);
		}
	}).send();
	
}

function displaySecurity(uid) {
	$('user_id').value = uid;
	$$('input[type=checkbox]').each(function(e) {
		if (e.get('id') != 'fac-form') { e.set('checked', false); }
	});
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=4&uid='+uid+'&func=security&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
				var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						$$('input[type=checkbox]').each(function(e) {
							if (e.get('id') == 'chk'+text) {
								e.set('checked',true);
							}
						});
					}
				});
				$('div-mas-admin').setStyle('display','block');
		}
	}).send();	
}

function displayFleet(uid) {
	$('div-fleet').setStyle('display','block');
	var r;
	var rr;
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=4&func=display_fleet&uid='+uid,
		noCache: true,
		method: 'get',
		onComplete: function(response){
				var r = json_parse(response);
				$('fleet-checkbox').set('checked',true);
				$('div-fleet-access').setStyle('display','block');
				$('fleet-campus').empty();
				var y = new Request({
					url: 'index.php?option=com_jumi&fileid=4&func=display_fleet_campus&uid='+uid,
					noCache: true,
						method: 'get',
					onComplete: function(response){
							var flag = false;
							var row = json_parse(response,function(data,text) {
								if (typeof text == 'string') {
									rr = text.split(';');
										for (var i=0;i<r.data.length ;i++ ){
											if (r.data[i].campus == parseInt(rr[0])){
												flag = true;
											}
										}

											if (flag == true)
											{
												new Element('option',{ 'value':rr[0],'text':rr[1],'selected':true}).inject($('fleet-campus'));
											} else {
												new Element('option',{ 'value':rr[0],'text':rr[1]}).inject($('fleet-campus'));
											}
										flag = false;
										
								}
							});
						
					}
				}).send();
			
		}
	}).send();
}

function assignRevokeFleet(e,uid){
	var r;
	if (e.checked == false){
		if (confirm('This will revoke fleet management access rights. Are you sure?'))
		{
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=4&func=remove_fleet&uid='+uid,
				method: 'get',
				noCahce: true,
				onComplete: function(response) {
					if (parseInt(response) < 0)
					{
						alert('Error revoking user rights.');
					} else {
						masAdmin(uid);
						displaySecurity(uid);
						displayFleet(uid);
					}
				}
			}).send();
		} else {
			e.checked = true;
		}
	} else {
		var y = new Request({
					url: 'index.php?option=com_jumi&fileid=4&func=display_fleet_campus&uid='+uid,
					noCache: true,
						method: 'get',
					onComplete: function(response){
							var row = json_parse(response,function(data,text) {
								if (typeof text == 'string') {
									r = text.split(';');
										new Element('option',{ 'value':r[0],'text':r[1]}).inject($('fleet-campus'));
										
								}
							});
							$('div-fleet-access').setStyle('display','block');
					}
				}).send();
	}
	
}

function saveFleet(uid){
	var y = new Request({
					url: 'index.php?option=com_jumi&fileid=4&func=save_fleet_pre&uid='+uid+'&cmp='+cmp,
					noCache: true,
						async: false,
						method: 'get',
					onComplete: function(response){
							if (parseInt(response) < 0)
							{
								alert('Error assigning fleet access rights.');
							} 
					}
				}).send();
		
	var cmp = $('fleet-campus');//.getSelected().get('value');
	var campus = '(';
	for (var i =0;i<cmp.options.length ;i++ ){
		if (cmp.options[i].selected == true){
		
	var y = new Request({
					url: 'index.php?option=com_jumi&fileid=4&func=save_fleet&uid='+uid+'&cmp='+cmp.options[i].value,
					noCache: true,
						method: 'get',
						async: false,
					onComplete: function(response){
							if (parseInt(response) < 0)
							{
								alert('Error assigning fleet access rights.');
							} 
					}
				}).send();
		}
	}
	var y = new Request({
					url: 'index.php?option=com_jumi&fileid=4&func=save_fleet_post&uid='+uid+'&cmp='+cmp,
					noCache: true,
						async: false,
						method: 'get',
					onComplete: function(response){
							if (parseInt(response) < 0)
							{
								alert('Error assigning fleet access rights.');
							} 
					}
				}).send();
				masAdmin(uid);
								displaySecurity(uid);
								displayFleet(uid);
}

function displayClaims(uid) {
	$('div-claims').setStyle('display','block');
	var r;
	var rr;
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=4&func=display_claims&uid='+uid,
		noCache: true,
		method: 'get',
		async: false,
		onComplete: function(response){
				var data = json_parse(response);
				$('claim-system-name').empty();
					if (parseInt(data.code) > 0){
						var y = new Request({
							url: 'index.php?option=com_jumi&fileid=4&func=listSystems',
							noCache: true,
							method: 'get',
							onComplete: function(response){
										var row = json_parse(response);
										new Element('option',{ 'value':0,'text':''}).inject($('claim-system-name'));
										if (row.code > 0){
											for (var i = 0;i<row.system_id.length ;++i ){
												if (parseInt(row.system_id[i].id) == parseInt(data.system_id)) { 	
													new Element('option',{ 'value':row.system_id[i].id,'text':row.system_id[i].system_name,'selected':true}).inject($('claim-system-name'));
												} else {
													new Element('option',{ 'value':row.system_id[i].id,'text':row.system_id[i].system_name}).inject($('claim-system-name'));
												}
											}
										} else {
											new Element('option',{ 'value':0,'text':'NO SYSTEMS DEFINED'}).inject($('claim-system-name'));
										}
							}
						}).send();
					} else {
						var y = new Request({
							url: 'index.php?option=com_jumi&fileid=4&func=listSystems',
							noCache: true,
							method: 'get',
							onComplete: function(response){
										var row = json_parse(response);
										new Element('option',{ 'value':0,'text':''}).inject($('claim-system-name'));
										if (row.code > 0){
											for (var i = 0;i<row.system_id.length ;++i ){
													new Element('option',{ 'value':row.system_id[i].id,'text':row.system_id[i].system_name}).inject($('claim-system-name'));
												}
										} else {
											new Element('option',{ 'value':0,'text':'NO SYSTEMS DEFINED'}).inject($('claim-system-name'));
										}
							}
						}).send();
					}
		}
	}).send();

}

function saveSystem(uid){
	var sys = $('claim-system-name').getSelected().get('value');
	var y = new Request({
					url: 'index.php?option=com_jumi&fileid=4&func=save_system&uid='+uid+'&sys='+sys,
					noCache: true,
						method: 'get',
					onComplete: function(response){
							if (parseInt(response) < 0)
							{
								alert('Error assigning its claim system access rights.');
							} else {
								alert('Access granted.');
								masAdmin(uid);
								displaySecurity(uid);
								displayFleet(uid);
								displayClaims(uid)
							}
					}
				}).send();
}



