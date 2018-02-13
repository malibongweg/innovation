var t = 0;
var w = 0;
var max = 0;

window.addEvent('domready',function() {

new DatePicker($('start-date'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler1',
    useFadeInOut: !Browser.ie
});

	new DatePicker($('end-date'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler2',
    useFadeInOut: !Browser.ie
});

	$$('.input_field').each(function(e) {
		e.addEvent('keypress',function(el) {
			if(el.key == 'enter') { 
				el.stop(); 
			}
		});
	});

	var h = window.getSize().y - 380;
	//$('table-header').setStyles({'height':parseInt(h) +'px'});
	//$('data-div').setStyles({'height':parseInt(h-100)+'px'});

/*var container = $(document.body);
  
  container.addEvent('mousemove',function(e) {
	  $('x-cord').set('value',e.page.x);
	  $('y-cord').set('value',e.page.y);
	  $('caseid').set('value',e.page.x);
  });	*/
	
	/*$$('.input_label').each(function(el){
		w = el.getStyle('width');
		alert(w);
			if (parseInt(w) >= parseInt(max))
			{
				max = w;
			}
	});
	$$('.input_label').each(function(e){
		e.setStyles({ width : max+'px' });
	});*/
	

	$('yr-select').addEvent('change',function() {
		$('data-div').set('html','');
		var yr = $('yr-select').getSelected().get('value');
		var mth = $('month-select').getSelected().get('value');
	});

	$('month-select').addEvent('change',function() {
		$('data-div').set('html','');
		var yr = $('yr-select').getSelected().get('value');
		var mth = $('month-select').getSelected().get('value');
		displayData(yr,mth);
	});

	$('staffno').addEvent('keyup',function() {
		clearTimeout(t)
		t = setTimeout('list_staff()',800);
	});

	$('staffno').addEvent('click',function() {
		$('staff-select').setStyle('display','none');
		$('staffno').set('value','');
	});

	$('staff-select-drop').addEvent('dblclick',function() {
		var s = $('staff-select-drop').getSelected().get('value');
		$('staffno').set('value',s);
		$('staff-select').setStyle('display','none');
		$('costcentre').focus();
	});

	$('costcentre').addEvent('keyup',function() {
		clearTimeout(t)
		t = setTimeout('list_centres()',800);
	});

	$('costcentre').addEvent('click',function() {
		$('centre-select').setStyle('display','none');
		$('costcentre').set('value','');
	});

//	$('validate-button').addEvent('click',function() {
//		validate_case($('caseid').get('value'));
//	});

	$('print-slip').addEvent('click',function() {
		var url = 'index.php?option=com_jumi&fileid=61&tmpl=component';
		var r = $('record-id').get('value');
			if (r > 0)
			{
				url = url +'&id='+r+'&dt='+new Date().getTime();
				$('slip-link').set('href',url);
				$('slip-link').click();
			} else {
				alert('Select an entry.');
			}
		
	});
	
	$('prn-monthly').addEvent('click',function() {
		
		var sdate = $('start-date').get('value');
		var edate = $('end-date').get('value');
		var y = $('yr-select').getSelected().get('value');
		var m = $('month-select').getSelected().get('value');
		var rt = $$('input[name=rtype]:checked').get('value');
		if (rt == 'admin')
		{
			var url = 'index.php?option=com_jumi&fileid=62&tmpl=component';
			url = url +'&yr='+y+'&mth='+m+'&sdate='+sdate+'&edate='+edate+'&dt='+new Date().getTime();
		}
		else {
			var url = 'index.php?option=com_jumi&fileid=87&tmpl=component';
			url = url +'&yr='+y+'&mth='+m+'&sdate='+sdate+'&edate='+edate+'&dt='+new Date().getTime();
		}
		$('monthly-link').set('href',url);
		$('monthly-link').click();
	});

	$('caseid').addEvent('click',function() {
		$('caseid').set('value','');
	});

	$('del-item').addEvent('click',function() {
		delItems();
	});

	$('centre-select-drop').addEvent('dblclick',function() {
		validateCentre();
	});

	$('new-item').addEvent('click',function() {
		$('stockbuttons').setStyle('display','none');
		$('items-form').setStyle('display','block');
		$('description').set('value','');
		$('price').set('value','');
		$('total').set('value','');
		$('qty').set('value','');
		$('stockcode').set('value','');
		$('stockcode').focus();
	});

	$('stockcode').addEvent('keyup',function() {
		this.value = this.value.toUpperCase();
		clearTimeout(t);
		t = setTimeout('show_stock()',800);
	});

	$('stockcode').addEvent('click',function() {
		$('lookup-stock').setStyle('display','none');
		$('stockcode').set('value','');
		$('description').set('value','');
		$('price').set('value','');
		$('total').set('value','');
		$('qty').set('value','');
	});

	$('stockitems').addEvent('dblclick',function() {
		var sc = $('stockitems').getSelected().get('value');
		$('stockcode').set('value',sc);
		setStockDetails(sc);
		$('lookup-stock').setStyle('display','none');
		//$('stockdetails').setStyle('display','block');
		$('qty').focus();
	});

	$('qty').addEvent('keyup',function() {
		var q = $('qty').get('value');
		var p = $('price').get('value');
		var t = q * p;
		$('total').set('value',t.toFixed(2));
	});

	$('add-item').addEvent('click',function() {
		addItem();
	});

	$('update-price').addEvent('click',function() {
		updatePrice();
	})


	$('cart-issue-form').addEvent('submit',function(e) {
		e.stop();
	var caseid = $('caseid').get('value');
	var staffno = $('staffno').get('value');
	var costcentre = $('costcentre').get('value');
	if (isNaN(caseid) || caseid.length == 0)
	{
		alert('Invalid case id.');
		$('caseid').focus();
		return false;
	}

	if (isNaN(staffno) || staffno.length == 0)
	{
		alert('Invalid staff number.');
		$('staffno').focus();
		return false;
	}

	if (costcentre.length < 4)
	{
		alert('Invalid cost centre.');
		return false;
	}

	var cnt = getItemCount();
	if (parseInt(cnt) == 0)
	{
		alert('No cartridges issued.');
		return false;
	}

	var stock_list = '';
	var st = $('items');
		for (i=0;i<st.options.length ;i++)
		{
			stock_list = stock_list + st.options[i].value+';';
			//alert(st.options[i].value);
		}
		
	$('booked-items').set('value',stock_list);
	
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=save_record&dt='+new Date().getTime(),
		method: 'post',
		data: this,
		noCache: true,
		onComplete: function(response) {
			if (parseInt(response) == -1)
			{
				alert('Error saving record.');
			} else {
				//alert('Record saved.');
			}
			$$('input[type=text]').each(function(e) {
				e.set('value','');
			});
			$('form-data').setStyle('display','none');
			$('table-header').setStyle('display','block');
			var yr = $('yr-select').getSelected().get('value');
			var mth = $('month-select').getSelected().get('value');
			deleteIllegals();
			displayData(yr,mth);

		}
	}).send();
	});

	$('cancel-record').addEvent('click',function() {
		cancelRecord();
	});

	var yr = $('yr-select').getSelected().get('value');
	var mth = $('month-select').getSelected().get('value');
	deleteIllegals();
	displayData(yr,mth);
});

///////////////////////////////////

function validateCentre() {
	var s = $('centre-select-drop').getSelected().get('value');
	var sn = $('staffno').get('value');
		$('costcentre').set('value',s);
		$('centre-select').setStyle('display','none');
		var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=validate_centre&sn='+sn+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
				if (parseInt(response) == -2)
				{
					alert('Could not verify user cost centre...');
				} else {
					if (s != response)
					{
						alert('User is currently allocated to cost centre '+response+'\n'+'Please confirm cost centre.');
					}
				}
		}
	}).send();
}

function deleteIllegals() {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=delete_illegals&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function() {
		}
	}).send();
}

function updatePrice() {
	var p = $('price').get('value');
	var sc = $('stockcode').get('value');
		if (parseFloat(p) <= 0)
		{
			alert('Please enter a valid price.');
		} else {
			if (confirm('Are you sure?'))
			{
				var x = new Request({
				url: 'index.php?option=com_jumi&fileid=58&action=update_price&id='+sc+'&price='+p+'&dt='+new Date().getTime(),
				method: 'get',
				onComplete: function(response) {
					alert('Price updated.');
				}
			}).send();
				
			}
		}
}

function addItem() {
	var sc = $('stockcode').get('value');
	var desc = $('description').get('value');
	var qty = $('qty').get('value');
	var tot = $('total').get('value');
	if (isNaN(qty) || qty.length < 1)
	{
		alert('No quantity specified.');
		return false;
	}

	new Element('option',{ 'value':sc+':'+qty,'text': qty+' x ['+sc+'] '+desc+' = R'+tot}).inject($('items'));
	$('items-form').setStyle('display','none');
	$('stockbuttons').setStyle('display','block');
}

function delItems() {
	var s = $('items').getSelected().get('value');
	if (s.length <= 0)
	{
		alert('Select an item.');
	} else {
		if (confirm('Are you sure?'))
		{
			$('items').getSelected().dispose();
		}
	}
}

function setStockDetails(id) {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=get_stock_details&id='+id+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					var rec = text.split(';')
					$('description').set('value',rec[0]);
					$('price').set('value',rec[1]);
				}
			});
		}
	}).send();
}

function displayData(yr,mth) {
	$('ajax-cart').setStyle('display','block');
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table style="width: 100%; position: relative; height: auto; border: 0px" id="data-table">';
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=display_data&yr='+yr+'&mth='+mth+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 8000,
		onTimeout: function() {
			$('data-div').set('html','Time-out error.');
			x.cancel();
		},
		onComplete: function(response) {
				var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						if (parseInt(text) == -1)
						{
							$('data-div').set('html','No data found.');
							fnd = false;
						} else {
							var rec = text.split(';');
							var m = cnt % 2;
							if (m == 1)
							{
								var color = color1;
							} else {
								var color = color2;
							}
							html = html + '<tr>';
							html = html + '<td style="width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + '<input type="radio" name="id" value="'+rec[1]+'" onclick="getRecord(this.value)" /></td>';
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[0] + '</td>';
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[2] + '</td>';
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[3] + '</td>';
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[4] + '</td>';
							html = html + '<td style="width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[5] + '</td>';
							html = html + '</tr>';
							++cnt;
						}
					}
				});
		$('ajax-cart').setStyle('display','none');	
		html = html + '</table>';
		if (fnd == true)
				{
					$('data-div').set('html',html);
				}
		}
	}).send();
}

function getRecord(id) {
	$('record-id').set('value',id);
}

function editRecordPre() {
	var id = $('record-id').get('value');
	if (parseInt(id) <= 0)
	{
		alert('Select an entry.');
	} else {
		editRecord(id);
	}
}

function prnSlip() {
	var id = $('record-id').get('value');
	if (parseInt(id) <= 0)
	{
		alert('Select an entry.');
	} else {
		
	}
	$('record-id').set('value',0);
}

function editRecord(id) {
	$('table-header').setStyle('display','none');
	$('form-data').setStyle('display','block');
	$('form-action').set('value','edit');
	$$('input[type=text]').each(function(e) {
		e.set('value','');
	});
	//$('new-item').setStyle('display','none');
	//$('del-item').setStyle('display','none');
		var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=edit_record&id='+id+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			rec = response.split(';');
			$('captured').set('value',rec[4]);
			$('transno').set('value',rec[0]);
			$('costcentre').set('value',rec[5]);
			$('staffno').set('value',rec[3]);
			$('casedetails').set('value',rec[2]);
			$('caseid').set('value',rec[1]);
			$('info').set('html','Date: '+rec[4]+' / Transaction# '+rec[0]);
				var s = $('status');
				for (i=0;i<s.options.length ;i++ )
				{
					if (s.options[i].value == rec[6])
					{
						s.options.selectedIndex = i; break;
					}
				}
						var y = new Request({
						url: 'index.php?option=com_jumi&fileid=58&action=pop_stock&id='+id+'&dt='+new Date().getTime(),
						method: 'get',
						onComplete: function(response) {
									$('items').empty();
									var row = json_parse(response,function(data,text) {
										if (typeof text == 'string') {
											var rec = text.split(';');
											var r = rec[1]+ ' x ['+rec[0]+'] '+rec[3]+' = R'+rec[2];
											new Element('option',{ 'value':rec[0]+':'+rec[1],'text':r}).inject($('items'));
										}
									});
						}
						}).send();
						
			$('caseid').focus();
			$('record-id').set('value',0);
		}
	}).send();

}

function newRecord() {
	$('table-header').setStyle('display','none');
	$('form-data').setStyle('display','block');
	$('new-item').setStyle('display','block');
	$('del-item').setStyle('display','block');
	$('items').empty();
	$$('input[type=text]').each(function(e) {
		e.set('value','');
	});
	$('casedetails').set('value','');
	$('form-action').set('value','new');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=get_transno&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			rec = response.split(';');
			$('captured').set('value',rec[1]);
			$('transno').set('value',rec[0]);
			$('info').set('html','Date: '+rec[1]+' / Transaction# '+rec[0]);
			$('caseid').focus();
			$('record-id').set('value',0);
		}
	}).send();
}

function list_staff() {
	$('staff-select-drop').empty();
	var sn = $('staffno').get('value');
	$('ajax1').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=list_staff&staffno='+sn+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					if (parseInt(text) == -1)
					{
						new Element('option',{ 'value':'-1','text':'No matches found.'}).inject($('staff-select-drop'));
					} else {
						new Element('option',{ 'value':data,'text':text}).inject($('staff-select-drop'));
					}
					
				}
			});
			$('staff-select').setStyle('display','block');
			$('ajax1').setStyle('display','none');
		}
	}).send();
}

function list_centres() {
	$('centre-select-drop').empty();
	var sn = $('costcentre').get('value');
	$('ajax2').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=list_centres&id='+sn+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					if (parseInt(text) == -1)
					{
						new Element('option',{ 'value':'-1','text':'No matches found.'}).inject($('centre-select-drop'));
					} else {
						new Element('option',{ 'value':data,'text':text}).inject($('centre-select-drop'));
					}
					
				}
			});
			$('centre-select').setStyle('display','block');
			$('ajax2').setStyle('display','none');
		}
	}).send();
}

function validate_case(id) {
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=validate_case&id='+id+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 10000,
		onTimeout: function() {
			$('caseid').set('value','DB Error');
			x.cancel();
		},
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					if (parseInt(text) == -1)
					{
						$('caseid').set('value','DB Error');
					} else if (parseInt(text) == -2)
					{
						$('caseid').set('value','Invalid Case#');
					} else {
						var rec = text.split(';');
						$('caseid').set('value',rec[0]);
						$('casedetails').set('value','[CALLER ID: '+rec[2]+'] '+rec[1]);
					}
					
				}
			});
			$('staffno').focus();
			
		}
	}).send();
}

function show_stock() {
$('stockitems').empty();
	var sn = $('stockcode').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=list_stock&id='+sn+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
				if (typeof text == 'string') {
					if (parseInt(text) == -1)
					{
						new Element('option',{ 'value':'-1','text':'Error occured.'}).inject($('stockitems'));
					} else if (parseInt(text) == -2)
					{
						new Element('option',{ 'value':'-2','text':'No match found.'}).inject($('stockitems'));
					}
					else {
						new Element('option',{ 'value':data,'text':text}).inject($('stockitems'));
					}
					
				}
			});
			$('lookup-stock').setStyle('display','block');
		}
	}).send();
}

function saveRecord(frm) {
	var caseid = $('caseid').get('value');
	var staffno = $('staffno').get('value');
	var costcentre = $('costcentre').get('value');
	if (isNaN(caseid) || caseid.length == 0)
	{
		alert('Invalid case id.');
		$('caseid').focus();
		return false;
	}

	if (isNaN(staffno) || staffno.length == 0)
	{
		alert('Invalid staff number.');
		$('staffno').focus();
		return false;
	}

	if (costcentre.length < 4)
	{
		alert('Invalid cost centre.');
		return false;
	}

	var cnt = getItemCount();
	if (parseInt(cnt) == 0)
	{
		alert('No cartridges issued.');
		return false;
	}

	var stock_list = '';
	var st = $('items');
		for (i=0;i<st.options.length ;i++)
		{
			stock_list = stock_list + st.options[i].value+';';
			//alert(st.options[i].value);
		}
		
	$('booked-items').set('value',stock_list);
	//alert($('booked-items').get('value'));
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=save_record&dt='+new Date().getTime(),
		method: 'post',
		data: frm,
		noCache: true,
		onComplete: function(response) {
			if (parseInt(response) == -1)
			{
				alert('Error saving record.');
			} else {
				//alert('Record saved.');
			}
			$$('input[type=text]').each(function(e) {
				e.set('value','');
			});
			$('form-data').setStyle('display','none');
			$('table-header').setStyle('display','block');
			var yr = $('yr-select').getSelected().get('value');
			var mth = $('month-select').getSelected().get('value');
			deleteIllegals();
			displayData(yr,mth);

		}
	}).send();
}

function getItemCount() {
	var st = $('items');
	return st.options.length;
}

function cancelRecord() {
var a = $('form-action').get('value');
if (a == 'new')
{

	if (confirm('Are you sure?'))
	{
		var id = $('transno').get('value');
		var x = new Request({
		url: 'index.php?option=com_jumi&fileid=58&action=cancel_record&id='+id+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function() {
			alert('Record Cancelled.');
			$('form-data').setStyle('display','none');
			$('table-header').setStyle('display','block');
			var yr = $('yr-select').getSelected().get('value');
			var mth = $('month-select').getSelected().get('value');
			deleteIllegals();
			displayData(yr,mth);

		}
	}).send();
	}
} else {
	$('form-data').setStyle('display','none');
			$('table-header').setStyle('display','block');
			var yr = $('yr-select').getSelected().get('value');
			var mth = $('month-select').getSelected().get('value');
			deleteIllegals();
			displayData(yr,mth);
}
}