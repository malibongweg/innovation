var storeID = 0;




window.addEvent('domready',function(){

	$('orders-button').addEvent('click',function(){
		selectOrders();
	});

	$('items-button').addEvent('click',function(){
		selectItems();
	});

	jt("#eStore-report-params").submit(function(e){
		storeID = $('storeid').get('value');
		rep = parseInt($('rep-type-select').get('value'));
		e.preventDefault();
		if (rep == 1) {
			jt("#lnk-report").attr('href','index.php?option=com_jumi&fileid=159&tmpl=component&sdate='+jt("#start-date").val()+'&edate='+jt("#end-date").val()+'&cc='+jt("#cost-centre-rep").val()+'&si='+storeID);
		} else {
			jt("#lnk-report").attr('href','index.php?option=com_jumi&fileid=162&tmpl=component&sdate='+jt("#start-date").val()+'&edate='+jt("#end-date").val()+'&cc='+jt("#cost-centre-rep").val()+'&si='+storeID);
		}
		jt("#dialog-form-eStore").dialog('close');
		$('lnk-report').click();
	});
	
	
	jt("#dialog-form-eStore").dialog({
            autoOpen: false,
			title: 'Select date range',
            height: 400,
            width: 450,
            modal: true,
			buttons: {
				'Run Report': function() {
					jt("#eStore-report-params").submit();
				},
				Cancel: function() {
					jt( this ).dialog( "close" );
				}
			}
        });     

	/*jt("#claim-button").click(function(){
		storeID = $('storeid').get('value');
		$('rep-type-select').set('value','1');
		jt("select#cost-centre-rep").empty();
		jt.ajax({
			url:      'index.php?option=com_jumi&fileid=157&action=popCostCodes&storeid='+storeID,
			dataType: 'JSON',
			type:     'get',
			async: false,
			success:  function(data){
					var optionsRep = '<option value="0000">ALL</option>';
					for (var i = 0; i < data.length; i++) {
						optionsRep += '<option value="' + data[i].cost_centre + '">' + data[i].cost_centre + ' - ' +data[i].cc_name + '</option>';
					}
					jt("select#cost-centre-rep").append(optionsRep);
				}
			});	
		jt("#dialog-form-eStore").dialog('open');
	});*/

	jt("#summary-button").click(function(){
		$('rep-type-select').set('value','2');
		storeID = $('storeid').get('value');
		jt("select#cost-centre-rep").empty();
		jt.ajax({
			url:      'index.php?option=com_jumi&fileid=157&action=popCostCodes&storeid='+storeID,
			dataType: 'JSON',
			type:     'get',
			async: false,
			success:  function(data){
					var optionsRep = '<option value="0000">ALL</option>';
					for (var i = 0; i < data.length; i++) {
						optionsRep += '<option value="' + data[i].cost_centre + '">' + data[i].cost_centre + ' - ' +data[i].cc_name + '</option>';
					}
					jt("select#cost-centre-rep").append(optionsRep);
				}
			});	
		jt("#dialog-form-eStore").dialog('open');
	});

	jt("#start-date").datepicker({
		changemonth: true,
		changeday: true,
		dateFormat: 'yy-mm-dd'
	});

	jt("#end-date").datepicker({
		changemonth: true,
		changeday: true,
		dateFormat: 'yy-mm-dd'
	});
	
	checkStore();
	popOrders();
	
});

function selectOrders() {
	try
	{
		//jt('#tableItems').jtable('destroy');
		$('store-items').setStyle('display','none');
		$('store-orders').setStyle('display','block');
		popOrders();
	}
	catch (err)
	{
		//Nothing
		alert(err.message);
	}
}

function selectItems() {
	try
	{
		//jt('#tableOrders').jtable9'destroy');
		$('store-orders').setStyle('display','none');
		$('store-items').setStyle('display','block');
		popItems();
	}
	catch (err)
	{
		//Nothing
		alert(err.message);
	}
}

function checkStore() {
	var uid = $('uid').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=157&action=checkStore&uid='+uid,
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response) {
			var r = response.split(';');
			if (parseInt(r[0]) == 0){
				alert('No store allocation found. Contact administrator.');
				return false;
			} else {
				$('store-name').set('html',r[2]);
				$('storename').set('value',r[2]);
				$('uid').set('uid',r[1]);
				$('storeid').set('value',r[0]);
			}
		}
	}).send();
}

function popItems(){
	storeID = $('storeid').get('value');
	jt('#tableItems').jtable({
            title: 'Store Items',
			paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'item_desc ASC', //Set default sorting
			selecting: true, //Enable selecting,
			dialogWidth: 500,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=157&action=browseItems&storeid='+storeID,
                createAction: 'index.php?option=com_jumi&fileid=157&action=createItem',
				updateAction: 'index.php?option=com_jumi&fileid=157&action=updateItem',
				deleteAction: 'index.php?option=com_jumi&fileid=157&action=deleteItem'
            },
            fields: {
				item_id: {
					width: '15%',
					key: true,
					list: true,
					create: true,
					edit: false,
					sorting: false,
					title: 'Item Code',
					input: function (data) {
						if (data.record) {
							return '<input type="text" name="item_id" size="5" maxlength="5" onKeyUp="javascript: this.value = this.value.toUpperCase();" value="' + data.record.item_id + '" readonly />';
						} else {
							return '<input type="text" name="item_id" size="5" maxlength="5" onKeyUp="javascript: this.value = this.value.toUpperCase();" maxlength="10" value="" />';
						}
					}
				},
				istores_store_id: {
					list: false,
					edit: false,
					create: true,
					type: 'hidden',
					sorting: false,
					defaultValue: storeID,
					sorting: false
				},
				item_desc: {
					title: 'Description',
					width: '30%',
					edit: true,
					create: true,
					sorting: true,
					input: function (data) {
						if (data.record) {
							return '<input type="text" name="item_desc" size="40" onKeyUp="javascript: this.value = this.value.toUpperCase();" value="' + data.record.item_desc + '" />';
						} else {
							return '<input type="text" name="item_desc" size="40" onKeyUp="javascript: this.value = this.value.toUpperCase();" value="" />';
						}
					}
				},
				account_code: {
					title: 'Account Code',
					width: '30%',
					edit: true,
					create: true,
					list: true,
					sorting: false,
					options: 'index.php?option=com_jumi&fileid=157&action=listAccountCodes'
				},
				onhand: {
					title: 'Onhand',
					edit: true,
					create: true,
					list: false,
					sorting: false,
					input: function (data) {
						if (data.record) {
							return '<input type="text" name="onhand" size="5"  value="' + data.record.onhand + '" />';
						} else {
							return '<input type="text" name="onhand" size="5"  value="0" />';
						}
					}
				},
				unit_price: {
					title: 'Unit Price',
					width: '15%',
					edit: true,
					create: true,
					list: true,
					sorting: false,
					input: function (data) {
						if (data.record) {
							return '<input type="text" name="unit_price" size="7"  value="' + data.record.unit_price + '" />';
						} else {
							return '<input type="text" name="unit_price" size="7"  value="0.00" />';
						}
					}
				}
				
            },
				formCreated: function (event, data) {
                data.form.find('input[name="item_desc"]').addClass('validate[required]');
				data.form.find('input[name="onhand"]').addClass('validate[custom[number]]');
				data.form.find('input[name="unit_price"]').addClass('validate[custom[number]]');
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
        });
		
		jt('#tableItems').jtable('load');
}

function popOrders(){
	var storeID = $('storeid').get('value');
	var uid = $('uid').get('value');
	var storeName = $('storename').get('value');
	jt('#tableOrders').jtable({
            title: 'Orders',
			paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'order_date desc', //Set default sorting
			multiSelect: true,
			selecting: true, //Enable selecting,
			dialogWidth: 500,
			selectionChanged: (function () {
				var $selectedRows = jt('#tableOrders').jtable('selectedRows');
				var tip = '';
                jt('#SelectedRowList').empty();
                if ($selectedRows.length > 0) {
                    //Show selected rows
                    $selectedRows.each(function () {
                        var record = jt(this).data('record');
						if (record.istore_order_status_id == 5 || record.istore_order_status_id == 6) {

							jt.ajax({
								url:      'index.php?option=com_jumi&fileid=157&action=showToolTip&orderno='+record.order_no,
								dataType: 'text',
								type:     'GET',
								async: false,
								success:  function(response){
									var row = json_parse(response,function(data,text) {
										if (typeof text == 'string') {
											var r = text.split(';');
											tip = tip + r[0]+' '+r[1]+' '+r[2]+'</br>';
										}
									});
									jt("#audit-div").html(tip);
								}
								});
						}
					});
				}

			}),
			rowInserted: (function (event, data) {
				if (parseInt(data.record.istore_order_status_id) != 1) {
					data.row.find('.jtable-edit-command-button').hide(); 
                    //data.row.find('.jtable-delete-command-button').hide();
				}

				switch (parseInt(data.record.istore_order_status_id))	{
				case 1: 
					jt('#tableOrders').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#0066cc");
					break;
				case 2: 
					jt('#tableOrders').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#ffff99");
					break;
				case 3:
					jt('#tableOrders').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#66ff66");
					break;
				case 4:
					jt('#tableOrders').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#ff0000");
					break;
				case 5:
					jt('#tableOrders').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#65ca00");
					break;
			    case 6:
					jt('#tableOrders').find('div.jtable-main-container .jtable tbody tr td:last').css("background", "#003300");
					break;
				default:
					break;
				}
			}),
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=157&action=browseOrders&storeid='+storeID,
                createAction: 'index.php?option=com_jumi&fileid=157&action=createOrder&uid='+uid+'&sn='+storeName,
				updateAction: 'index.php?option=com_jumi&fileid=157&action=updateOrder'
            },
				toolbar: {
					items: [{
						icon: '/images/mail.png',
						text: 'Send Approval Request',
						click: function () {
								var $selectedRows = jt('#tableOrders').jtable('selectedRows');
								if ($selectedRows.length > 0) {
									$selectedRows.each(function () {
										var record = jt(this).data('record');
										if (parseInt(record.istore_order_status_id) != 1) {
											alert('Request for approval already sent.');
										} else {
													////Do email notification and change status
												  if (confirm('Are you sure?')) {
													$('estore-busy').setStyle('display','block');
														////Send emails
														jt.ajax({
														url:      'index.php?option=com_jumi&fileid=157&action=sendMailApproval&orderno='+record.order_no,
														dataType: 'text',
														type:     'GET',
														async: false,
														success:  function(data){
																//Update status of order
																jt.ajax({
																url:      'index.php?option=com_jumi&fileid=157&action=setStatus&status=2&orderno='+record.order_no,
																dataType: 'text',
																type:     'GET',
																async: false,
																success:  function(data){
																	jt('#tableOrders').jtable('reload');  
																}
															});	
														}
													});
													alert('Request for approval sent.');	
													$('estore-busy').setStyle('display','none');
												  }
										   }
									});
								} else {
									alert('Please select record.');
								}
						}
				},
				{
					icon: '/images/progress.png',
						text: 'Mark As In-Progress',
						click: function () {
							var op = $('uid').get('value');
							var $selectedRows = jt('#tableOrders').jtable('selectedRows');
								if ($selectedRows.length > 0) {
									$selectedRows.each(function () {
										var record = jt(this).data('record');
										if (parseInt(record.istore_order_status_id) != 3) {
											alert('Only Approved orders can be changed to Inprogress.');
										} else {
												if (confirm('Are you aure?')) {
													$('estore-busy').setStyle('display','block');
													jt.ajax({
													url:      'index.php?option=com_jumi&fileid=157&action=sendInprogress&op='+op+'&status=6&orderno='+record.order_no,
													dataType: 'text',
													type:     'GET',
													async: false,
													success:  function(data){
														$('estore-busy').setStyle('display','none');
														jt('#tableOrders').jtable('reload');  
														alert('Order marked as In-Progress.');
													}
													});	
												}
										}

									});
								} else {
									alert('Please select record.');
								}
					}
				},
				{
					icon: '/images/complete.png',
						text: 'Mark As Complete',
						click: function () {
							var op = $('uid').get('value');
							var $selectedRows = jt('#tableOrders').jtable('selectedRows');
								if ($selectedRows.length > 0) {
									$selectedRows.each(function () {
										var record = jt(this).data('record');
										if (parseInt(record.istore_order_status_id) != 6) {
											alert('Only Inprogress orders can be Completed.');
										} else {
												if (confirm('Are you aure?')) {
													jt.ajax({
													url:      'index.php?option=com_jumi&fileid=157&action=sendCompleted&status=5&orderno='+record.order_no+'&op='+op,
													dataType: 'text',
													type:     'GET',
													async: false,
													success:  function(data){
														jt('#tableOrders').jtable('reload');  
														alert('Order marked as complete.');
													}
													});	
												}
										}

									});
								} else {
									alert('Please select record.');
								}
					}
				}
				
				]},
            fields: {
				rec_id: {
					key: false,
					list: true,
					width: '3%',
					edit: false,
					create: false,
					sorting: false,
					title: '',
					display: function(orders) {
						var $img = jt('<img src="/images/arrow1.png" title="" />');
						$img.click(function () {
									jt('#tableOrders').jtable('openChildTable',
									$img.closest('tr'),{ //Parent row
									title: 'Items',
									sorting: false,
									dialogWidth: 500,
									selecting: true,
									deleteConfirmation: function(data) {
										var localOrderNo = data.record.istore_orders_order_no;
										var localStatus = 0;
										jt.ajax({
												url:      'index.php?option=com_jumi&fileid=157&action=getStatus&orderno='+localOrderNo,
												dataType: 'text',
												type:     'GET',
												async: false,
												success:  function(status){
											     localStatus = status;
												}
											});
										if (parseInt(localStatus) != 1) {
											data.cancel = true;
											data.cancelMessage = 'You can not delete items when order is pending, accepted or rejected!';
										}
									},
									recordUpdated: (function (event, data) { jt('#tableOrders').find('.jtable-child-table-container').jtable('reload'); }),
									recordDeleted: (function (event, data) { jt('#tableOrders').find('.jtable-child-table-container').jtable('reload'); }),
									recordAdded: (function (event, data) { jt('#tableOrders').find('.jtable-child-table-container').jtable('reload'); }),
									closeRequested: (function (event, data) { jt('#tableOrders').jtable('reload'); }),
									formCreated: (function (event,data) {
										//Select 1st product
										var val = data.form.find('[name=istore_items_item_id] option:selected').val();
											var r = new Array();
											jt.ajax({
												url:      'index.php?option=com_jumi&fileid=157&action=getItem&id='+val,
												dataType: 'text',
												type:     'GET',
												async: false,
												success:  function(data){
											      r = data.split(';');
												}
											});
			  									  data.form.find('[name=istore_items_item_id]').val(r[0]);
												  data.form.find('[name=price]').val(r[2]);
										//Change product onchange
										data.form.find('[name=istore_items_item_id]').change(function() {
											var val = data.form.find('[name=istore_items_item_id] option:selected').val();
											var r = new Array();
											jt.ajax({
												url:      'index.php?option=com_jumi&fileid=157&action=getItem&id='+val,
												dataType: 'text',
												type:     'GET',
												async: false,
												success:  function(data){
											      r = data.split(';');
												}
											});
			  									  data.form.find('[name=istore_items_item_id]').val(r[0]);
												  data.form.find('[name=price]').val(r[2]);
										});
										//Calc total price
										data.form.find('[name=qty]').blur(function() {
											var price = data.form.find('[name=price]').val();
											var localQty = data.form.find('[name=qty]').val();
											var grandTotal = (price * localQty);
											data.form.find('[name=total_price]').val(grandTotal);
										});
										//Calc on price change
										data.form.find('[name=price]').blur(function() {
											var price = data.form.find('[name=price]').val();
											var localQty = data.form.find('[name=qty]').val();
											var grandTotal = (price * localQty);
											data.form.find('[name=total_price]').val(grandTotal);
										});
									}),
										actions: {
											listAction: 'index.php?option=com_jumi&fileid=157&action=browseOrderItems&orderno='+orders.record.order_no,
											createAction: 'index.php?option=com_jumi&fileid=157&action=createOrderItem',
											deleteAction: 'index.php?option=com_jumi&fileid=157&action=deleteOrderItem',
										},
										fields: {
												id: {
													key: true,
													list: false,
												},
												cc: {
													create: true,
													edit: false,
													list: false,
													type: 'hidden',
													defaultValue: orders.record.cost_centre
												},
												istore_orders_order_no: {
													create: true,
													edit: false,
													list: false,
													type: 'hidden',
													defaultValue: orders.record.order_no
													},
												istore_items_item_id: {
													title: 'Item',
													create: true,
													edit: false,
													list: true,
													options: 'index.php?option=com_jumi&fileid=157&action=listStockItems&storeid='+storeID
												},
												qty: {
													title: 'Qty',
													edit: true,
													create: true,
													sorting: false,
													input: function (data) {
														if (data.record) {
															return '<input type="text" name="qty" size="8" style="text-align: right" value="' + data.record.qty + '" />';
														} else {
															return '<input type="text" name="qty" size="8" style="text-align: right" value="0" />';
														}
													}
												},
												price: {
													title: 'Price',
													edit: true,
													create: true,
													input: function (data) {
														if (data.record) {
															return '<input type="text" name="price" size="8" style="text-align: right" value="' + data.record.price + '" />';
														} else {
															return '<input type="text" name="price" size="8" style="text-align: right" value="0" />';
														}
													}
												},
												total_price: {
													title: 'Total Price',
													edit: false,
													create: true,
													input: function (data) {
														if (data.record) {
															return '<input type="text" name="total_price" readonly size="8" style="text-align: right" value="' + data.record.total_price + '" />';
														} else {
															return '<input type="text" name="total_price" readonly size="8" style="text-align: right" value="0" />';
														}
													}
												},
											note: {
												title: 'Notes',
												edit: true,
												create: true,
												list: false,
												type: 'textarea'
											}
											}
								},
									function (data) { //opened handler
									data.childTable.jtable('load');
								}
							);
						
					});
					return $img;
					}
				},
				order_no: {
					key: true,
					width: '10%',
					list: true,
					edit: false,
					sorting: true,
					title: 'Order#'
				},
				store_id: {
					key: false,
					list: false,
					edit: false,
					sorting: false,
					type: 'hidden',
					defaultValue: storeID
				},
				order_date: {
					title: 'Order Date',
					width: '15%',
					list: true,
					edit: false,
					create: true,
					type: 'date',
					displayFormat: 'yy-mm-dd',
					sorting: true
				},
				service_date: {
					title: 'Service Date',
					width: '15%',
					list: true,
					edit: false,
					create: true,
					type: 'date',
					displayFormat: 'yy-mm-dd',
					sorting: true
				},
				clientname: {
					title: 'Client',
					width: '20%',
					edit: false,
					create: false,
					sorting: false,
					list: true,
				},
				client_uid: {
					title: 'Client',
					width: '20%',
					edit: true,
					create: true,
					sorting: false,
					list: false,
					options: 'index.php?option=com_jumi&fileid=157&action=listUsersStaff'
				},
				costcode: {
					title: 'Cost Centre',
					width: '30%',
					edit: false,
					create: false,
					list: true,
					sorting: false,
					},
				cost_centre: {
					title: 'Cost Centre',
					width: '30%',
					edit: true,
					create: true,
					list: false,
					sorting: false,
					options: 'index.php?option=com_jumi&fileid=157&action=listCostCentres'
					},
				istore_order_status_id: {
					title: 'Status',
					width: '30%',
					edit: false,
					create: true,
					list: true,
					sorting: false,
					options: function (data) {
						if (data.record) {
							return 'index.php?option=com_jumi&fileid=157&action=listStatus';
						} else {
							return 'index.php?option=com_jumi&fileid=157&action=listStatus&status=1';
						}
					}
				},
				comments: {
					title: 'Comments',
					edit: true,
					create: true,
					list: false,
					sorting: false,
					type: 'textarea'
				}
				
            },
				formCreated: function (event, data) {
                data.form.find('input[name="order_date"]').addClass('validate[required]');
				data.form.find('input[name="comments"]').addClass('validate[required]');
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
        });
		
		jt('#tableOrders').jtable('load');
}