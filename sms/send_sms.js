var tref;

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


	$('single-number').focus();

	new FormCheck('single-form');

	$('del-month').addEvent('change',function() {
		popDelivery();
	});

	$('pick-message').addEvent('click',function(){
		showDefined();
	});

	$('pick-message').addEvent('change',function(){
		showDefined();
	});

	$('new-msg-type').addEvent('click',function(){
		newStandard();
	});
	$('del-msg-type').addEvent('click',function(){
		delStandard();
	});
	$('save-msg-type').addEvent('click',function(){
		saveStandard();
	});
	
	$('std-message').addEvent('focus',function(){
		$('save-msg-type').setStyle('display','block');
		$('cancel-msg-type').setStyle('display','block');
	});

	$('cancel-msg-type').addEvent('click',function(){
		$('save-msg-type').setStyle('display','none');
		$('cancel-msg-type').setStyle('display','none');
		showStandard();
	});

	$('msg-type').addEvent('change',function(){
		showStandardMSG();
	});

	$('address-book-bulk').addEvent('change',function() {
		popBulkList();
	});

	$('single-form').addEvent('submit',function(e) {
		new Event(e).stop();
		if ($('single-number').value.length < 10)
		{
			alert('Not a valid cellular number.');
		}
	});

	$('single-message').addEvent('keyup',function(e) {
		var ct = parseInt($('single-message').get('value').length);
		var cl = (160 - ct);
		$('single-chars-left').set('html','Characters left: '+cl);
			if (cl <= 0)
			{
				$('single-message').set('value',$('single-message').get('value').substring(0,$('single-message').get('value').length-1));
				alert('Maximum amount of characters used.');
			}
	});

	$('bulk-message').addEvent('keyup',function(e) {
		var ct = parseInt($('bulk-message').get('value').length);
		var cl = (160 - ct);
		$('bulk-chars-left').set('html','Characters left: '+cl);
			if (cl <= 0)
			{
				$('bulk-message').set('value',$('bulk-message').get('value').substring(0,$('bulk-message').get('value').length-1));
				alert('Maximum amount of characters used.');
			}
	});

	$('std-message').addEvent('keyup',function(e) {
		var ct = parseInt($('std-message').get('value').length);
		var cl = (160 - ct);
		$('std-chars-left').set('html','Characters left: '+cl);
			if (cl <= 0)
			{
				$('std-message').set('value',$('std-message').get('value').substring(0,$('std-message').get('value').length-1));
				alert('Maximum amount of characters used.');
			}
	});

	if ($('sms-nav').getSelected().get('value') == '2')
	{
		$('sms-single').setStyle('display','none'); $('sms-bulk').setStyle('display','block'); $('sms-address').setStyle('display','none');
	}

	$('sms-nav').addEvent('change',function() {
		$('save-msg-type').setStyle('display','none');
		$('cancel-msg-type').setStyle('display','none');
		switch (this.value) 
		{
		case '1': $('sms-single').setStyle('display','block'); $('sms-bulk').setStyle('display','none'); $('sms-address').setStyle('display','none'); $('div-delivery').setStyle('display','none'); $('div-standard').setStyle('display','none');populateSingleAddressBook(); break; 
		case '2':  $('sms-single').setStyle('display','none'); $('sms-bulk').setStyle('display','block'); $('sms-address').setStyle('display','none'); $('div-delivery').setStyle('display','none'); $('div-standard').setStyle('display','none');popBulkList(); displayPickMessages(); break; 
		case '3':  $('sms-single').setStyle('display','none'); $('sms-bulk').setStyle('display','none'); $('sms-address').setStyle('display','block'); $('div-delivery').setStyle('display','none');$('div-standard').setStyle('display','none'); popAddressBook() ;break; 
		case '4': $('sms-single').setStyle('display','none'); $('sms-bulk').setStyle('display','none'); $('sms-address').setStyle('display','none'); $('div-delivery').setStyle('display','block'); $('div-standard').setStyle('display','none');break; 
		case '5': $('sms-single').setStyle('display','none'); $('sms-bulk').setStyle('display','none'); $('sms-address').setStyle('display','none'); $('div-delivery').setStyle('display','none');$('div-standard').setStyle('display','block'); showStandard(); break; 
		}
	});

	$('address-book').addEvent('change',function() {
		populateSingleAddressBook();
	});

	$('address-book-book').addEvent('change',function() {
		popAddressBook();
	});

	$('address-book-entries').addEvent('change',function() {
		$('single-number').set('value',$('address-book-entries').getSelected().get('value'));
	});

	
$('cell_file').addEvent('change',function() {
	$('frm1Submit').setStyle('display','block');
});

	$('add-new-group').addEvent('click',function() {
		var grp = prompt('Address Book Name:','');
		if (grp !=null && grp!='')
		{
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=23&action=new_group&uid='+$('uid').get('value')+'&grp='+encodeURI(grp)+'&dt='+new Date().getTime(),
				method: 'get',
				onComplete: function (response) {
					if (parseInt(response) == -1)
					{
						alert('Error occured...could not insert new address book.');
					}
					$('address-book-book').empty();
					var y = new Request({
							url: 'index.php?option=com_jumi&fileid=23&action=list_books&uid='+$('uid').get('value')+'&dt='+new Date().getTime(),
							method: 'get',
							timeout: 5000,
							onTimeout: function() {
								new Element('option',{ 'value': '-1','text':'Error retrieving entires'}).inject($('address-book-book'));
								y.cancel();
							},
							onComplete: function(response) {
									var row = json_parse(response,function(data,text) {
										if (typeof text == 'string') {
										new Element('option',{ 'value':data,'text':text}).inject($('address-book-book'));
									}
								});
								popAddressBook();
							}
					}).send();
				}
			}).send();
		}
	});


	$('del-add-group').addEvent('click',function() {
		deleteAddressGroup();
	});


	$('delete-entry').addEvent('click',function() {
		deleteAddEntry();
	});

	$('new-entry').addEvent('click',function() {
		addAddEntry();
	});

	$('send-single').addEvent('click',function() {
		checkSystemStatus();
		if (parseInt($('sysstat').get('value') == 0))
		{
			return false;
		}
		showBalance();
		if (parseFloat($('balance').get('html')) <= 0)
		{
			alert('No balance available for sending SMS. Please re-charge.');
			return false;
		}
		$('send-single').setStyle('display','none');
		var no = $('single-number').get('value');
		if (no.length != 10)
		{
			alert('Invalid cellular number.');
			$('send-single').setStyle('display','block');
			return false;
		}
			$('show-sending').setStyle('display','block');
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=23&action=send_single&dt='+new Date().getTime(),
				metod: 'post',
				timeout: 10000,
				onTimeout: function() {
					$('single-message').set('value','Timeout exception.');
					$('send-single').setStyle('display','block');
					$('show-sending').setStyle('display','none');
					x.cancel();
				},
				data: $('single-form'),
				onComplete: function(response) {
					if (parseInt(response) > 0)
					{
						showBalance();
						$('send-single').setStyle('display','block');
						$('show-sending').setStyle('display','none');
						alert('SMS Sent. Check your delivery report for confirmation.');
					} else { 
						$('send-single').setStyle('display','block');
						$('show-sending').setStyle('display','none');
						alert('SMS Gateway is in maintenance mode. Please try again later.');
					}
					$('send-single').setStyle('display','block');
					$('show-sending').setStyle('display','none');
				}
			}).send();
	});

	$('send-bulk').addEvent('click',function() {
		checkSystemStatus();
		if (parseInt($('sysstat').get('value') == 0))
		{
			return false;
		}
		showBalance();
		if (parseFloat($('balance').get('html')) <= 0)
		{
			alert('No balance available for sending SMS. Please re-charge.');
			return false;
		}
		$('bulk-chars-left').setStyles({'display':'none'});
		$('sms-busy').setStyles({'display':'inline'});
		setTimeout('sendSMSes()',500);
		
		
	});



	populateSingleAddressBook();
	checkSystemStatus();

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
			}
		}
	}).send();
}

function sendSMSes(){
		var displayError = false;
		var nos = $('bulk-numbers').get('value').split(/\n/);
		//var xval = tval.replace(/[\n\r]/g, ';');
		//var nos = xval.split(';');
		for (i=0;i < nos.length ;++i ){
			nos[i] = nos[i].replace(/\r/,'');////////////////////////What a pain to make it compatibale with IE...uuuuurrrggghhhh
		}
	//alert(nos[0]+'  ---  '+nos[1]+' --- '+nos[2]); exit;
				if ($('bulk-numbers').get('value').length <= 0)
				{
					alert('No cellular numbers selected.');
					$('send-bulk').setStyles({'display':'block'});
					return false;
				} else if ($('bulk-numbers').get('value').length <= 20)
				{
					alert('Single cellular number detected. Use `Send Single SMS` option in navigation.');
					$('send-bulk').setStyles({'display':'block'});
					return false;
				}
		var cnt = 0;
		for (i=0;i < nos.length ;++i )
		{
				showBalance();
				if (parseFloat($('balance').get('html')) <= 0)
				{
					alert('No balance available for sending SMS. Please re-charge.');
					$('send-bulk').setStyles({'display':'block'});
					return false;
				}
			if (nos[i].length != 10)
			{
				continue;
			}	
			
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=23&action=send_bulk&cell='+nos[i]+'&dt='+new Date().getTime(),
				metod: 'post',
				data: $('bulk-form'),
				async: false,
				timeout: 5000,
				onTimeout: function() {
					$('bulk-chars-left').set('html','Timeout exception.');
					x.cancel();
				},
				onComplete: function(response) {
					//$('bulk-chars-left').set('html','<img src="/images/kit-ajax.gif" width="32" height="16" alt="" style="vertical-align: middle" /> Sending to '+nos[i]);
					if (parseInt(response) > 0)
					{
						showBalance();
						++cnt;
						
					} else { 
								if (displayError == true)
								{
									$('send-bulk').setStyles({'display':'block'});
									x.cancel();
									return false;
								}
								alert('SMS Gateway is in maintenance mode. Please try again later.');
								displayError = true;
								$('send-bulk').setStyles({'display':'block'});
								x.cancel();
					}
					var d = 0; while (d < 10000){
						++d;
					}
				}
			}).send();
		}
		$('bulk-chars-left').set('html','Message (160 characters max)');
		$('bulk-chars-left').setStyles({'display':'block'});
		$('send-bulk').setStyles({'display':'block'});
		$('sms-busy').setStyles({'display':'none'});
		alert('Sent '+cnt+' messages.');
}

function popAddressBook() {
	$('add-entries').empty();
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=23&action=get_entries&bookid='+$('address-book-book').getSelected().get('value')+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			new Element('option',{ 'value': '-1','text':'Error retrieving entires'}).inject($('add-entries'));
			x.cancel();
		},
		onComplete: function (response) {
				var row = json_parse(response,function(data,text) {
							if (typeof text == 'string') {
								if (parseInt(text) == -1)
								{
									new Element('option',{ 'value':'-1','text':'Address book empty.'}).inject($('add-entries'));
								} else {
									new Element('option',{ 'value':data,'text':text}).inject($('add-entries'));
								}
							}
				});
		}
	}).send();
}

function populateSingleAddressBook()  {
	var fnd = 0;
	$('address-book-entries').empty();
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=23&action=get_entries&bookid='+$('address-book').getSelected().get('value')+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			new Element('option',{ 'value': '-1','text':'Error retrieving entires'}).inject($('address-book-entries'));
			x.cancel();
		},
		onComplete: function (response) {
				var row = json_parse(response,function(data,text) {
							if (typeof text == 'string') {
								if (parseInt(text) == -1)
								{		
									$('single-number').set('value','');
									$('address-book-entries').empty();
									new Element('option',{ 'value': '-1','text':'Empty'}).inject($('address-book-entries'));
								} else
								{
									new Element('option',{ 'value':data,'text':text}).inject($('address-book-entries'));
									fnd = 1;
								}
							}
				});
				if (fnd > 0)
				{
					$('single-number').set('value',$('address-book-entries').getSelected().get('value'));
				}
				
			}
	}).send();
}


function loadCellFromFile() {
	$('bulk-numbers').set('value','');
	$('send-bulk-buttons').setStyle('display','none');
	$('loadBulkButton').setStyle('display','block');
}

function popBulkList() {
	$('bulk-numbers').set('value','');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=23&action=get_entries&bookid='+$('address-book-bulk').getSelected().get('value')+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			$('address-book-bulk').empty(); 
			new Element('option',{ 'value': '-1','text':'Error retrieving entires'}).inject($('address-book-bulk'));
			x.cancel();
		},
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
							if (typeof text == 'string') {
								if (parseInt(text) == -1)
								{
									$('bulk-numbers').set('value','Address book empty.');
								} else {
									var fc = $('bulk-numbers').get('value');
									if (fc.length < 1)  { var txt = fc + data } else { var txt =  fc + "\n" + data; }
									$('bulk-numbers').set('value',txt);
								}
							}
			});
		}
	}).send();
}

function displayPickMessages() {
	var uid = $('username').get('value');
	$('pick-message').empty();
	var y = new Request({ 
		url: 'index.php?option=com_jumi&fileid=23&action=msg_types&uid='+uid+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			new Element('option',{ 'value':"-1",'text':"Timeout exception."}).inject($('pick-message'));
			y.cancel();
		},
		onComplete: function(response) {
								var row = json_parse(response,function(data,text) {
									 if (typeof text == 'string') {
										if (text == '-1')
										{
											new Element('option',{ 'value':data,'text':'No defined message types'}).inject($('pick-message'));
										}
										else {
											new Element('option',{ 'value':data,'text':text}).inject($('pick-message'));
										}
									}
								});
								showDefined();
		}
	}).send();	
}
function deleteAddressGroup() {
	var sb = $('address-book-book').getSelected().get('value');
	if (sb >= 0)
	{
		if (confirm('Are you sure?'))
		{
			var x = new Request({ 
				url: 'index.php?option=com_jumi&fileid=23&action=del_grp&bookid='+sb+'&dt='+new Date().getTime(),
				method: 'get',
				timeout: 5000,
				onTimeout: function() {
					alert('Error occured...cannot delete address book.');
					x.cancel();
				},
				onComplete: function(response) {
					if (parseInt(response) == -1)
					{
						alert('Error occured...cannot delete address book.');
					} else
					{
						popAddressBookBook();
					}
				}
			}).send();
		}
	} else { alert('Nothing to select.'); }
}

function popAddressBookBook() {
	$('address-book-book').empty();
	var y = new Request({
							url: 'index.php?option=com_jumi&fileid=23&action=list_books&uid='+$('uid').get('value')+'&dt='+new Date().getTime(),
							method: 'get',
							timeout: 5000,
							onTimeout: function() {
								new Element('option',{ 'value': '-1','text':'Error retrieving entires'}).inject($('address-book-book'));
								y.cancel();
							},
							onComplete: function(response) {
									var row = json_parse(response,function(data,text) {
										if (typeof text == 'string') {
										new Element('option',{ 'value':data,'text':text}).inject($('address-book-book'));
									}
								});
								popAddressBook();
							}
					}).send();
}

function deleteAddEntry() {
	if (confirm('Are you sure?'))
	{
		var ae = $('add-entries').getSelected().get('value');
		if (ae >= 0)
		{
			var x = new Request({ 
				url: 'index.php?option=com_jumi&fileid=23&action=del_entry&cell='+ae+'&dt='+new Date().getTime(),
				method: 'get',
				timeout: 5000,
				onTimeout: function () {
					alert('Error occured...cannot delete entry.');
					x.cancel();
				},
				onComplete: function(response) {
					if (parseInt(response) == -1)
					{
						alert('Error occured...cannot delete entry.');
					} else { popAddressBook(); }
				}
			}).send();
		} else { alert('Nothing selected.');
	}
}
}

function addAddEntry() {
	var cell = prompt('Enter cellular number:','');
	if (cell!=null && cell!='')
	{
		if (cell.length != 10)
		{
			 alert('Please enter a valid number.');
		} else {
			var nme = prompt('Name/Surname of person:','');
			var x = new Request({ 
					url: 'index.php?option=com_jumi&fileid=23&action=new_entry&cell='+encodeURI(cell)+'&nme='+encodeURI(nme)+'&bookid='+$('address-book-book').getSelected().get('value')+'&dt='+new Date().getTime(),
					method: 'get',
					timeout: 5000,
					onTimeout: function() {
						alert('Error occured...cannot add entry.');
						x.cancel();
					},
					onComplete: function(response) {
						if (parseInt(response) == -1)
						{
							alert('Error occured...cannot add entry.');
						} else {
							 popAddressBook();
						}
					}
			}).send();
		}
	} else { alert('Please enter a valid number.'); }
}

function showBalance() {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=23&action=get_balance&uid='+$('username').get('value')+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function () {
			$('balance').set('html','Not available');
			x.cancel();
		},
		onComplete: function(response) {
			if (parseFloat(response) < 0)
			{
				$('balance').set('html','Not available');
			} else $('balance').set('html',response);
		}
	}).send();
}

function checkSystemStatus() {
	var rcode = 0;
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=23&action=system_status&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			x.cancel();
		},
		onComplete: function(response) {
			rcode = parseInt(response);
			if (rcode == 0)
			{
				var z = new Request({
				url: 'index.php?option=com_jumi&fileid=23&action=log_error&dt='+new Date().getTime(),
				method: 'get',
				timeout: 5000,
				OnTimeout: function() {
					z.cancel();
				},
				onComplete: function() {
				}
				}).send();

				var y = new Request({
				url: 'index.php?option=com_jumi&fileid=23&action=mail_error&dt='+new Date().getTime(),
				method: 'get',
				timeout: 5000,
				OnTimeout: function() {
					y.cancel();
				},
				onComplete: function() {
					alert('SMS error status was sent to Administrator'+'\n'+'Please try again later.');
				}
				}).send();
				$('sms-nav').setStyle('display','none');
				$('sms-single').setStyle('display','none');
				$('sms-bulk').setStyle('display','none');
				$('err-msg').set('html','SMS Gateway is currently in maintenance mode. Please try again later');
				$('sms-status').setStyle('display','block');
				$('sms-address').setStyle('display','block');
				$('sysstat').set('value','0');
			} else { $('sysstat').set('value','1'); }
		}
	}).send();
}

function popDelivery() {
	var mth = $('del-month').getSelected().get('value');
	var uid = $('delivery-user').get('value');
	$('delivery-entries').empty();
	$('del-heading').set('html','<img src="/images/kit-ajax.gif" width="16" height="16" alt="" style="vertical-align: middle" /> Retrieving report..Please wait.');
	var x = new Request({ 
		url: 'index.php?option=com_jumi&fileid=23&action=del_reports&mth='+mth+'&uid='+uid+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 10000,
		onTimeout: function() {
			new Element('option',{ 'value':"-1",'text':"Error retrieving report."}).inject($('delivery-entries'));
			x.cancel();
		},
		onComplete: function(response) {
								var row = json_parse(response,function(data,text) {
									 if (typeof text == 'string') {
										if (text == '-1')
										{
											new Element('option',{ 'value':data,'text':'No Data'}).inject($('delivery-entries'));
										}
										else {
											new Element('option',{ 'value':data,'text':text}).inject($('delivery-entries'));
										}
									}
								});
				$('del-heading').set('html','SMS Delivery Reports (Current Year only.)');
		}
	}).send();
}

function showStandard() {
	var uid = $('username').get('value');
	$('msg-type').empty();
	var x = new Request({ 
		url: 'index.php?option=com_jumi&fileid=23&action=msg_types&uid='+uid+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			new Element('option',{ 'value':"-1",'text':"Timeout exception."}).inject($('msg-type'));
			x.cancel();
		},
		onComplete: function(response) {
								var row = json_parse(response,function(data,text) {
									 if (typeof text == 'string') {
										if (text == '-1')
										{
											new Element('option',{ 'value':data,'text':'No message types'}).inject($('msg-type'));
											$('std-message').set('value','');
										}
										else {
											new Element('option',{ 'value':data,'text':text}).inject($('msg-type'));
										}
									}
								});
					if (parseInt($('msg-type').getSelected().get('value')) > 0)
					{
						getStandardMessage($('msg-type').getSelected().get('value'));
					}
		}
	}).send();	
}

function showStandardMSG() {
	var uid = $('username').get('value');
	var x = new Request({ 
		url: 'index.php?option=com_jumi&fileid=23&action=msg_types_sel&uid='+uid+'&id='+$('msg-type').getSelected().get('value')+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			new Element('option',{ 'value':"-1",'text':"Timeout exception."}).inject($('msg-type'));
			x.cancel();
		},
		onComplete: function(response) {
								var row = json_parse(response,function(data,text) {
									 if (typeof text == 'string') {
										if (text == '-1')
										{
											new Element('option',{ 'value':data,'text':'No message types'}).inject($('msg-type'));
											$('std-message').set('value','');
										}
										else {
											getStandardMessage(data);
										}
									}
								});
					//if (parseInt($('msg-type').getSelected().get('value')) > 0)
					//{
					//	getStandardMessage($('msg-type').getSelected().get('value'));
					//}
		}
	}).send();	
}

function getStandardMessage(id) {
	var x = new Request({ 
		url: 'index.php?option=com_jumi&fileid=23&action=msg_std&id='+id+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function() {
			$('std-message').set('value','Timeout exception.');
			x.cancel();
		},
		onComplete: function(response) {
								var row = json_parse(response,function(data,text) {
									 if (typeof text == 'string') {
										if (text == '-1')
										{
											$('std-message').set('value','No message defined.');
										}
										else {
											$('std-message').set('value',text);
										}
									}
								});
		}
	}).send();	
}

function newStandard(){
	var uid = $('username').get('value');
	$('new-msg-type').setStyle('display','none');
	$('del-msg-type').setStyle('display','none');
	var atype = prompt('Message description:','');
		if (atype !=null && atype!='')
		{
			$('msg-type').empty();
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=23&action=save_msg_type&uid='+uid+'&desc='+encodeURI(atype)+'&dt='+new Date().getTime(),
				method: 'get',
				onComplete: function(response){
					var row = json_parse(response,function(data,text) {
									 if (typeof text == 'string') {
										new Element('option',{ 'value':data,'text':text}).inject($('msg-type'));
									}
								});
						$('new-msg-type').setStyle('display','block');
						$('del-msg-type').setStyle('display','block');
				}
			}).send();
		} else {
			$('new-msg-type').setStyle('display','block');
			$('del-msg-type').setStyle('display','block');
		}
}

function saveStandard(){
	var id = $('msg-type').getSelected().get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=23&action=save_std_msg&id='+id+'&msg='+encodeURI($('std-message').get('value'))+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			$('save-msg-type').setStyle('display','none');
			$('cancel-msg-type').setStyle('display','none');
			showStandard();
		}
	}).send();
}

function delStandard() {
	var msg = $('msg-type').getSelected().get('value');
	if (parseInt(msg) > 0)
	{
		$('new-msg-type').setStyle('display','none');
		$('del-msg-type').setStyle('display','none');
		if (confirm('Are you sure?'))
		{
				var x = new Request({
				url: 'index.php?option=com_jumi&fileid=23&action=del_std_msg&id='+msg+'&dt='+new Date().getTime(),
				method: 'get',
				onComplete: function(response) {
					showStandard();
					$('new-msg-type').setStyle('display','block');
					$('del-msg-type').setStyle('display','block');
				}
			}).send();
		} else {
			$('new-msg-type').setStyle('display','block');
			$('del-msg-type').setStyle('display','block');
		}
	}
}

function showDefined() {
	var msg = $('pick-message').getSelected().get('value');
	if (parseInt(msg) > 0)
	{
				var x = new Request({
				url: 'index.php?option=com_jumi&fileid=23&action=msg_std&id='+msg+'&dt='+new Date().getTime(),
				method: 'get',
				onComplete: function(response) {
					var row = json_parse(response,function(data,text) {
									 if (typeof text == 'string') {
										$('bulk-message').set('value',text);
									}
								});
				}
			}).send();
	}
}