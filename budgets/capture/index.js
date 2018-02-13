var budgetCycle = 0;
var local_cc = '';
var local_acc = '';
var loc_desc = '';
var uid = '';
var staffNo = 0;
var cycleOpen = true;
var userType = 'academic';

window.addEvent('domready',function(){
	uid = $('uid').get('value');

	$('cost-codes').addEvent('change',function(){
		try	{
			jt('#tableData').jtable('destroy');
		}
		catch (err){
		}
		showBudget();
	});
	$('account-category').addEvent('change',function(){
		try	{
			jt('#tableData').jtable('destroy');
		}
		catch (err){
		}
		showBudget();
	});

	$('f-cost').addEvent('click',function(){
		$('f-cost').set('value','');
	});

	$('requestAccountCode').addEvent('click',function(){
		var userid = $('uid').get('value');
		var userno = $('user-id').get('value');
		var account_code = $('f-cost').get('value');
		var cc = $('cost-codes').getSelected().get('value');
		if (account_code.toString().length > 0){
			requestAccount(userno,userid,account_code,cc);
		} else {
			alert('Please specify an account code in the search field.');
		}
	});

	$('requestCostCode').addEvent('click',function(){
		var userid = $('uid').get('value');
		var userno = $('user-id').get('value');
		$('cost-code-request').set('value','');
		 jt( "#dialog-form" ).dialog({
			autoOpen: true,
			height: 200,
			width: 300,
			modal: true,
				buttons: {
					'Ok': function(){
						var req_cc = jt('#cost-code-request').val();
						jt(this).dialog('close');
							sendCostCodeEmail(userid,req_cc,userno);
					},
					'Cancel': function(){
						jt(this).dialog('close');
					}
			}
		 });
	});

	$('requestBudgetCostCode').addEvent('click',function(){
		var userid = $('uid').get('value');
		var userno = $('user-id').get('value');
		$('costcode-access-request').set('value','');
		$('access-motiv').set('value','');
		 jt( "#dialog-form-access" ).dialog({
			autoOpen: true,
			height: 290,
			width: 400,
			modal: true,
				buttons: {
					'Ok': function(){
						var costcode_access = jt('#costcode-access-request').val();
						var access_motivation = jt('#access-motiv').val();
						if (costcode_access.toString().length < 4){
							alert('Enter valid cost code...');
							return false;
						} 
						if (access_motivation.toString().length < 10){
							alert('Enter more descriptive motivation...');
							return false;
						}
						//Check if code is in list box...
						var fnd = false;
						jt('#cost-codes > option').each(function(){
							var ent = jt(this).val();
							var cc = ent.split(';');
							if (cc[1] == costcode_access){
								fnd = true;
							}
						});

						if (fnd == true){
							jt(this).dialog('close');
							sendCostCodeAccessEmail(userid,costcode_access,userno,access_motivation);
						} else {
							alert('Cost code not found in drop down list box.');
							$('costcode-access-request').set('html','');
							$('costcode-access-request').focus();
						}
					},
					'Cancel': function(){
						jt(this).dialog('close');
					}
			}
		 });
	});

	$('f-cost').set('value','');
	//showUserNotice();
	getUserType();
	//getUserType();
	//getAdminEmail();
	//popTitle();
	//displayDept();
	//popCostCodes(uid);
	//showBudget();

});


function showUserNotice(){
	jt('#user-notice').dialog({
    autoOpen: true,
    width: 350,
    height: 300,
    closeOnEscape: true,
    draggable: false,
    title: 'Budget 2017',
    buttons: {
        'OK': function () {
            jt('#user-notice').remove();  
			getUserType();
        }
    }
});
}


function getUserType(){
	jt('#user-type').dialog({
    autoOpen: true,
    width: 350,
    height: 150,
    closeOnEscape: true,
    draggable: true,
    title: 'User appointment type...',
    buttons: {
        'OK': function () {
			userType = jt('input:radio[name=utype]:checked').val();
			getAdminEmail();
			popTitle();
			displayDept();
			popCostCodes(uid);
			showBudget();
            jt('#user-type').remove();           
        },
        'Cancel': function () {
            jt('#user-type').dialog('close');
			window.location.href="https:/opa.cput.ac.za";
        }
        
    }
});
}

function sendCostCodeEmail(uid,cc,userno){
	if (confirm('Are you sure?')){
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=136&action=costCodeRequest&uid='+uid+'&cc='+cc+'&id='+userno,
				method: 'get',
				noCache: true,
				async: false,
				onComplete: function(response){
				alert('Cost code request sent to the administrator. Please try again later.');
			}
		}).send();
	}
}

function sendCostCodeAccessEmail(uid,cc,userno,motive){
	if (confirm('Are you sure?')){
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=136&action=costCodeRequestAccess&uid='+uid+'&cc='+cc+'&id='+userno+'&motive='+motive,
				method: 'get',
				noCache: true,
				async: false,
				onComplete: function(response){
				alert('Access Granted...Please note, all transactions are recorded against your login name for audit purposes.');
				popCostCodes(uid);
			}
		}).send();
	}
}

function requestAccount(id,uid,acc,cc){
	$('requestAccountCode').set('disabled',true);
	$('budget-loader').setStyle('display','block');
	var bc = $('budget-cycle').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=136&action=verifyAccountCode&acc='+acc+'&yr='+bc+'&cc='+cc,
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response){
				$('requestAccountCode').set('disabled',false);
				$('budget-loader').setStyle('display','none');
				if (parseInt(response) == 0){
					alert('Invalid account code request.');
					return false;
				} else {
					if (confirm('Account request....Are you sure?')){
						var y = new Request({
							url: 'index.php?option=com_jumi&fileid=136&action=requestAccountEmail&acc='+acc+'&uid='+uid+'&id='+id+'&cc='+cc,
								method: 'get',
								async: false,
								noCache: true,
								onComplete: function(response){
									if (parseInt(response) ==-1){
										alert('System unable to locate your profile details. Please contact the finance department.');
									} else {
										alert('Account request sent to administrator. Please try again later.');
									}
							}
						}).send();
					}
				}
			}
	}).send();
}

function getAdminEmail(){
	$('budget-loader').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=136&action=getAdminEmail',
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response){
			$('admin-email').set('value',response);
		}
	}).send();
}

function displayDept() {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=136&action=getStaffNo&uid='+uid,
			method: 'get',
			noCache: true,
			onComplete: function(response){
			var stf = parseInt(response);
			var y = new Request({
				url: 'index.php?option=com_jumi&fileid=136&action=displayDept&id='+stf,
					method: 'get',
					noCache: true,
					onComplete: function(response){
					$('dept-name').set('html',response);
				}
			}).send();
		}
	}).send();
}

function popTitle() {
	$('budget-loader').setStyle('display','block');
	var x = new Request({
	url: 'index.php?option=com_jumi&fileid=136&action=getStaffNo&uid='+uid,
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response) {
			staffNo = parseInt(response);
		}
	}).send();
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=136&action=getConfig',
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response) {
			var r = response.split(';');
			//$('budget-title').set('html','Budget Capture - Cutoff Date: '+r[1]);//+' / Approval Date: '+r[2]);
			$('budget-cycle').set('value',r[4]);
			budgetCycle = parseInt(r[4]);
			$('cutoff-date').set('value',r[1]);
			$('super-users').set('value',r[3]);
				var currDate = new Date();
				var cutOff = new Date(r[1]);
					if (currDate > cutOff)	{
							//Check if super users is enabled...
						if (parseInt(r[3]) == 1){
							//Check if user is in list...
							var y = new Request({
								url: 'index.php?option=com_jumi&fileid=136&action=checkSuperUser&staffno='+staffNo,
								method: 'get',
								noCache: true,
								async: false,
								onComplete: function(response){
									if (parseInt(response) == 0){
										$('budget-loader').setStyle('display','none');
										alert('Budget cycle for year '+budgetCycle+' is closed. No amendments possible. Please contact finance department.');
										cycleOpen = false;
									}
								}
							}).send();
						} else {
							$('budget-loader').setStyle('display','none');
							alert('Budget cycle for year '+budgetCycle+' is closed. No amendments possible. Please contact finance department.');
							cycleOpen = false;
						}
					} else {
						cycleOpen = true;
					}

		}
	}).send();
}

function popCostCodes(uid) {
	$('budget-loader').setStyle('display','block');
	$('cost-codes').empty();
	//Get user staff no
		var x = new Request({
		url: 'index.php?option=com_jumi&fileid=136&action=getStaffNo&uid='+uid,
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response) {
			staffNo = parseInt(response);
				if (staffNo == -1){
					alert('Could not locate staff number. Did you verify your network account on OPA?');
					return false;
				} else {
					var ae = $('admin-email').get('value');
					var ui = $('user-id').get('value');
					var y = new Request({
						url: 'index.php?option=com_jumi&fileid=136&action=getCostCodes&staffno='+staffNo+'&admin='+encodeURI(ae)+'&uid='+ui,
							method: 'get',
							noCache: true,
							async: false,
							onComplete: function(response) {
								var row = json_parse(response,function(data,text) {
									if (typeof text == 'string') {
										//var r = text.split(';');
										//var cc = r[0];
										//var cc_name = r[1];
										if (data == 'costcode'){
											local_cc = text;
										} else if (data == 'desc'){
											loc_desc = '['+local_cc+'] '+text;
										} else if (data == 'codetype'){
											if (text == 'n'){
												new Element('option',{ 'value':'n;'+local_cc,'text':loc_desc}).inject($('cost-codes'));
											} else if (text == 'e'){
												new Element('option',{ 'value':'e;'+local_cc,'text':loc_desc,'style':'background-color: #66ff00'}).inject($('cost-codes'));
											}
										}
									}
								});
								$('account-category').setStyle('display','inline');
								$('budget-loader').setStyle('display','none');
						    }
					}).send();
				}
			}
	}).send();
}

function showBudget(){
	var staffNo = -1;
	var ae = '';
	var ui = 0;
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=136&action=getStaffNo&uid='+uid,
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response) {
			staffNo = parseInt(response);
				if (staffNo > 0){
					ae = $('admin-email').get('value');
					ui = $('user-id').get('value');
				}
			}
	}).send();
	var userid = $('uid').get('value');
	$('budget-loader').setStyle('display','block');
	$('filter-div').setStyle('display','block');						
	var loc_combined = $('cost-codes').getSelected().get('value');
	var r = loc_combined.toString().split(';');
	local_cc = r[1];
	local_acc = $('account-category').getSelected().get('value');
	if (r[0].toString() == 'n'){
		readOnlyBudget(local_cc,local_acc,ui,userType);
	} else if (cycleOpen == false){
		readOnlyBudget(local_cc,local_acc,ui,userType);
	}else if (cycleOpen == true){
			jt('#tableData').jtable({
            title: 'Financial Accounts for '+String(budgetCycle),
			paging: true, //Enable paging
            pageSize: 25, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'fcdacc ASC', //Set default sorting
			selecting: true, //Enable selecting,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=136&action=displayAccountCodes&uid='+ui+'&usertype='+userType
            },
            fields: {
				local_cc: {
				title: '',
				create: false,
				edit: false,
				width: '3%',
				sorting: false,
				display: function(accountCodes) {
									var $img = jt('<img src="/images/arrow1.png" title="" />');
									var $img2 = jt('<img src="/images/noentry.png" title="" />');
									loc_cc = local_cc;//$('cost-codes').getSelected().get('value');
									loc_acc = local_acc;//= $('account-category').getSelected().get('value').toString();
									var acc_sub = accountCodes.record.fcdacc.substring(0,1);
									$img.click(function () {
											if (acc_sub != '3' && acc_sub != 'S') {
														jt('#tableData').jtable('openChildTable',
														$img.closest('tr'), //Parent row
														{
														title: 'Budget Allocations for '+String(budgetCycle),
														sorting: false,
														dialogWidth: 500,
														selecting: true,
														recordUpdated: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
														recordDeleted: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
														recordAdded: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
														closeRequested: (function (event, data) { jt('#tableData').jtable('reload'); }),
														actions: {
															listAction: 'index.php?option=com_jumi&fileid=136&action=displayBudgetAlloc&acc='+accountCodes.record.fcdacc+'&cc='+loc_cc+'&yr='+budgetCycle,
															createAction: 'index.php?option=com_jumi&fileid=136&action=createBudgetAlloc&uid='+userid,
															deleteAction: 'index.php?option=com_jumi&fileid=136&action=deleteBudgetAlloc&uid='+userid,
															updateAction: 'index.php?option=com_jumi&fileid=136&action=updateBudgetAlloc&uid='+userid
														},
														fields: {
															bud_acc_num: {
																key: true,
																list: false
															},
															cost_code: {
																create: true,
																edit: false,
																list: false,
																type: 'hidden',
																defaultValue: loc_cc
															},
															account_code: {
																create: true,
																edit: false,
																list: false,
																type: 'hidden',
																defaultValue: accountCodes.record.fcdacc
																//options: '/scripts/budgets/capture/ajax.php?action=loadAccounts&usertype='+userType
															},
															for_year: {
																create: true,
																edit: false,
																list: false,
																type: 'hidden',
																defaultValue: budgetCycle
															},
															budget_desc: {
																title: 'Short Motivation',
																width: '70%',
																create: true,
																edit: true,
																list: true,
																display: function(data) {
																	return '<b>'+data.record.budget_desc+'</b>';
																},
																input: function(data){
																	if (data.record){
																		return '<input type="text" name="budget_desc" value="'+data.record.budget_desc+'" size="64" maxlength="64" />';
																	} else {
																		return '<input type="text" name="budget_desc" size="64" maxlength="64" />';
																	}
																	
																}
															},
															budget_amount: {
																title: 'Budget Amount for '+String(budgetCycle),
																width: '30%',
																create: true,
																edit: true,
																list: true,
																inputClass: 'validate[required,custom[number]]'
															},
															next_budget: {
																title: 'Budget Amount for '+String(budgetCycle+1),
																width: '30%',
																create: false,
																edit: false,
																list: false,
																inputClass: 'validate[custom[number]]'
															},
															next_next_budget: {
																title: 'Budget Amount for '+String(budgetCycle+2),
																width: '30%',
																create: false,
																edit: false,
																list: false,
																inputClass: 'validate[custom[number]]'
															}
														},
														
																formCreated: function (event, data) {
																	data.form.find('input[name="budget_desc"]').addClass('validate[required]');
																	data.form.validationEngine({promptPosition : "topLeft", scroll: false});
																},
																//Validate form when it is being submitted
																formSubmitting: function (event, data) {
																	return data.form.validationEngine('validate');
																},
																//Dispose validation logic when form is closed
																formClosed: function (event, data) {
																	data.form.validationEngine('hide');
																	data.form.validationEngine('detach');
																}
														},
														function (data) { //opened handler
															data.childTable.jtable('load');
														});
												} else if (acc_sub == '3') {
														jt('#tableData').jtable('openChildTable',
														$img.closest('tr'), //Parent row
														{
														title: 'Budget Allocations for '+String(budgetCycle),
														sorting: false,
														dialogWidth: 500,
														selecting: true,
														actions: {
															listAction: 'index.php?option=com_jumi&fileid=136&action=displaySalaryBudgetAlloc&cc='+loc_cc
														},
														fields: {
															cost_code: {
																title: 'Cost Code',
																width: '30%',
																key: true,
																list: true
															},
															budget_staff: {
																title: 'Staff Member',
																width: '40%',
																list: true
															},
															post: {
																title: 'Post',
																width: '30%',
																list: true
															}
														}
														},
															function (data) { //opened handler
																	data.childTable.jtable('load');
														});
											} else if (acc_sub == 'S') {
						
														jt('#tableData').jtable('openChildTable',
														$img.closest('tr'), //Parent row
														{
														title: 'Budget Allocations Staff Members ['+String(budgetCycle)+']',
														sorting: false,
														dialogWidth: 500,
														selecting: true,
														recordUpdated: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
														recordDeleted: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
														recordAdded: (function (event, data) { jt('#tableData').find('.jtable-child-table-container').jtable('reload'); }),
														closeRequested: (function (event, data) { jt('#tableData').jtable('reload'); }),
														actions: {
															listAction: 'index.php?option=com_jumi&fileid=136&action=displayStaffBudget&acc='+accountCodes.record.fcdacc+'&cc='+loc_cc+'&yr='+budgetCycle,
															createAction: 'index.php?option=com_jumi&fileid=136&action=createStaffBudget&uid='+userid,
															deleteAction: 'index.php?option=com_jumi&fileid=136&action=deleteStaffBudget&uid='+userid
														},
														fields: {
															id: {
																key: true,
																list: false
															},
															cost_code: {
																list: false,
																create: true,
																type: 'hidden',
																defaultValue: loc_cc
															},
															account_code: {
																list: false,
																create: true,
																type: 'hidden',
																defaultValue: accountCodes.record.fcdacc
															},
															staff_name: {
																title: 'Post',
																list: true,
																create: true,
																width: '30%',
																input: function(data){
																	if (data.record){
																		return '<input type="text" size="50" name="staff_name" value="'+data.record.staff_name+'" onkeyup="this.value=this.value.toUpperCase()" />';
																	} else {
																		return '<input type="text" size="50" name="staff_name" value="" onkeyup="this.value=this.value.toUpperCase()" />';
																	}
																}
															},
															job_title: {
																title: 'Job Title',
																list: true,
																create: true,
																width: '30%',
																input: function(data){
																	if (data.record){
																		return '<input type="text" size="50" name="job_title" value="'+data.record.job_title+'" onkeyup="this.value=this.value.toUpperCase()" />';
																	} else {
																		return '<input type="text" size="50" name="job_title" value="" onkeyup="this.value=this.value.toUpperCase()" />';
																	}
																}
															},
															grade: {
																title: 'Grade',
																width: '10%',
																list: true,
																create: true,
																input: function(data){
																	if (data.record){
																		return '<input type="text" size="2" name="grade" value="'+data.record.grade+'" />';
																	} else {
																		return '<input type="text" size="2" name="grade" value="" />';
																	}
																}
															},
															reason: {
																title: 'Reason',
																list: false,
																create: true,
																input: function(data){
																	if (data.record){
																		return '<textarea cols="50" rows="2" name="reason" value="'+data.record.reason+'" />';
																	} else {
																		return '<textarea cols="50" rows="2" name="reason" value="" />';
																	}
																}
															},
															staff_type: {
																title: 'Staff Type',
																width: '15%',
																list: true,
																create: true,
																options: { 'P': 'PERMANENT', 'T': 'TEMPORARY' }
															},
															bud_acc_num: {
																list: false,
																create: true,
																type: 'hidden'
															},
															budget_amount: {
																title: 'Budget',
																width: '15%',
																list: true,
																create: true
															},
															for_year: {
																list: false,
																create: true,
																type: 'hidden',
																defaultValue: budgetCycle
															}
															
														},
																formCreated: function (event, data) {
																data.form.find('input[name="staff_name"]').addClass('validate[required]');
																data.form.find('input[name="job_title"]').addClass('validate[required]');
																data.form.find('input[name="budget_amount"]').addClass('validate[required],custom[number]');
																data.form.validationEngine();
																},
																//Validate form when it is being submitted
																formSubmitting: function (event, data) {
																	return data.form.validationEngine('validate');
																},
																//Dispose validation logic when form is closed
																formClosed: function (event, data) {
																	data.form.validationEngine('hide');
																	data.form.validationEngine('detach');
																}
														},
															function (data) { //opened handler
																			data.childTable.jtable('load');
														});
											}//End else condition
										
								});//End of image click function
								if (accountCodes.record.edit_account > 0){
									return $img;
								} else {
									return $img2;
								}
									
							}//End of display function
                },
                fcdacc: {
					title: 'Acc',
					create: false,
					edit: false,
					width: '7%'
                },
				fcdname1: {
                    title: 'Account Name',
                    width: '30%',
					edit: false,
					create: false
                },
				actuals1: {
                    title: 'Act'+String(budgetCycle-2),
                    width: '15%',
					edit: false,
					create: false,
					sorting: false
                },
				actuals2: {
                    title: 'Act'+String(budgetCycle-1),
                    width: '15%',
					edit: false,
					create: false,
					sorting: false
                },	
				budget: {
                    title: 'Budget'+String(budgetCycle-1),
                    width: '15%',
					edit: false,
					create: false,
					sorting: false
                },	
				current_budget: {
                    title: 'Budget'+String(budgetCycle),
                    width: '15%',
					edit: false,
					create: false,
					sorting: false
                },
				filter_acc: {
						defaultValue: '1'
					}
            }
				
					
        });
						//jt('#LoadRecordsButton').click(function (e) {
						//	e.preventDefault();
						//	jt('#tableData').jtable('load', {  'cc': String(local_cc), 'acc':String(local_acc), 'cycle_now':budgetCycle, 'cycle_old': budgetCycle-1, 'f_cost' : jt('#f-cost').val() });
						//});
				 
						//Load all records when page is first shown
						//jt('#LoadRecordsButton').click();
		
		jt('#tableData').jtable('load',{ 'cc': String(local_cc), 'acc':String(local_acc), 'cycle_now':budgetCycle, 'cycle_old': budgetCycle-1 });
		$('budget-loader').setStyle('display','none');
		////////////////////////////Cycle closed/////////////////////////////////////
}
}


function readOnlyBudget(loc_cc,loc_acc,uid,utype){
	//var loc_combined = $('cost-codes').getSelected().get('value');
	//var r = loc_combined.toString().split(';');
	//loc_cc = r[1];
	//loc_acc = $('account-category').getSelected().get('value');
	$('budget-loader').setStyle('display','block');
	jt('#tableData').jtable({
            title: 'Financial Accounts for '+String(budgetCycle),
			paging: true, //Enable paging
            pageSize: 20, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'fcdacc ASC', //Set default sorting
			selecting: true, //Enable selecting,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=136&action=displayAccountCodes&uid='+uid+'&usertype='+userType
            },
            fields: {
				loc_cc: {
				title: '',
				create: false,
				edit: false,
				width: '3%',
				sorting: false,
				display: function(accountCodes) {
									var $img = jt('<img src="/images/arrow1.png" title="" />');
									var cc = jt('#cost-codes option:selected').val();//getSelected().get('value');
									var cc_split = cc.toString().split(';');
									loc_cc = cc_split[1];
									var acc_sub = accountCodes.record.fcdacc.substring(0,1);
									$img.click(function () {
											if (acc_sub != '3') {
														jt('#tableData').jtable('openChildTable',
														$img.closest('tr'), //Parent row
														{
														title: 'Budget Allocations for '+String(budgetCycle),
														sorting: false,
														dialogWidth: 500,
														selecting: true,
														actions: {
															listAction: 'index.php?option=com_jumi&fileid=136&action=displayBudgetAlloc&acc='+accountCodes.record.fcdacc+'&cc='+loc_cc+'&yr='+budgetCycle
														},
														fields: {
															bud_acc_num: {
																key: true,
																list: false
															},
															cost_code: {
																create: false,
																edit: false,
																list: false,
																type: 'hidden',
																defaultValue: loc_cc
															},
															account_code: {
																create: false,
																edit: false,
																list: false,
																type: 'hidden',
																defaultValue: accountCodes.record.fcdacc
															},
															for_year: {
																create: false,
																edit: false,
																list: false,
																type: 'hidden',
																defaultValue: budgetCycle
															},
															budget_desc: {
																title: 'Short Motivation',
																width: '70%',
																create: false,
																edit: false,
																list: true,
																display: function(data) {
																	return '<b>'+data.record.budget_desc+'</b>';
																},
																input: function(data){
																	if (data.record){
																		return '<input type="text" name="budget_desc" value="'+data.record.budget_desc+'" size="64" maxlength="64" />';
																	} else {
																		return '<input type="text" name="budget_desc" size="64" maxlength="64" />';
																	}
																	
																}
															},
															budget_amount: {
																title: 'Budget Amount for '+String(budgetCycle),
																width: '30%',
																create: false,
																edit: false,
																list: true,
																inputClass: 'validate[required,custom[number]]'
															},
															next_budget: {
																title: 'Budget Amount for '+String(budgetCycle+1),
																width: '30%',
																create: false,
																edit: false,
																list: false,
																inputClass: 'validate[custom[number]]'
															},
															next_next_budget: {
																title: 'Budget Amount for '+String(budgetCycle+2),
																width: '30%',
																create: false,
																edit: false,
																list: false,
																inputClass: 'validate[custom[number]]'
															}
														},
														
																formCreated: function (event, data) {
																	data.form.find('input[name="budget_desc"]').addClass('validate[required]');
																	data.form.validationEngine({promptPosition : "topLeft", scroll: false});
																},
																//Validate form when it is being submitted
																formSubmitting: function (event, data) {
																	return data.form.validationEngine('validate');
																},
																//Dispose validation logic when form is closed
																formClosed: function (event, data) {
																	data.form.validationEngine('hide');
																	data.form.validationEngine('detach');
																}

																},
																function (data) { //opened handler
																	data.childTable.jtable('load');
														});//End if jTable function
											} else {//End of condition function
														jt('#tableData').jtable('openChildTable',
														$img.closest('tr'), //Parent row
														{
														title: 'Budget Allocations for '+String(budgetCycle),
														sorting: false,
														dialogWidth: 500,
														selecting: true,
														actions: {
															listAction: 'index.php?option=com_jumi&fileid=136&action=displaySalaryBudgetAlloc&cc='+loc_cc
														},
														fields: {
															cost_code: {
																title: 'Cost Code',
																width: '30%',
																key: true,
																list: true
															},
															budget_staff: {
																title: 'Staff Member',
																width: '40%',
																list: true
															},
															post: {
																title: 'Post',
																width: '30%',
																list: true
															}
														}
														},
															function (data) { //opened handler
																	data.childTable.jtable('load');
														});
											}//end of Else
										
								});//End of image click function
								//return $img;
								
							}//End of display function
                },
                fcdacc: {
					title: 'Acc',
					create: false,
					edit: false,
					width: '7%'
                },
				fcdname1: {
                    title: 'Account Name',
                    width: '30%',
					edit: false,
					create: false
                },
				actuals1: {
                    title: 'Act'+String(budgetCycle-2),
                    width: '15%',
					edit: false,
					create: false,
					sorting: false
                },
				actuals2: {
                    title: 'Act'+String(budgetCycle-1),
                    width: '15%',
					edit: false,
					create: false,
					sorting: false
                },	
				budget: {
                    title: 'Budget'+String(budgetCycle-1),
                    width: '15%',
					edit: false,
					create: false,
					sorting: false
                },	
				current_budget: {
                    title: 'Budget'+String(budgetCycle),
                    width: '15%',
					edit: false,
					create: false,
					sorting: false
                },
				filter_acc: {
						defaultValue: '1'
					}
            }
				
					
        });
						jt('#LoadRecordsButton').click(function(e) {
							e.preventDefault();
							jt('#tableData').jtable('load', {  'cc': String(local_cc), 'acc':String(local_acc), 'cycle_now':budgetCycle, 'cycle_old': budgetCycle-1, 'f_cost' : jt('#f-cost').val() });
						});
		
						jt('#tableData').jtable('load',{ 'cc': String(local_cc), 'acc':String(local_acc), 'cycle_now':budgetCycle, 'cycle_old': budgetCycle-1 });
						$('budget-loader').setStyle('display','none');
}

