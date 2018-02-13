window.addEvent('domready',function() {

	$('mth').addEvent('change',function() {
		displayLog();
	});

	$('log-year').addEvent('change',function() {
		displayLog();
	});

	$('status-flag').addEvent('change',function() {
		displayLog();
	});

	$('srch-button').addEvent('click',function() {
		displayLog();
	});

	$('filter-id').addEvent('click',function() {
		$('filter-id').set('value','');
	});

	$('bc-sync').addEvent('click',function() {
		syncBarcode();
	});

	$('hide-errors').addEvent('click',function() {
		displayLog();
	});

	$('hide-barcodes').addEvent('click',function() {
		displayLog();
	});

	//var h = window.getSize().y - 450;
	//$('log-details').setStyles({'height':parseInt(h) +'px'});
	//$('display-log').setStyles({'height':parseInt(h-80)+'px'});

	//Display the Log////////////////////////
	displayLog();
});

function clcEvent(comp) {
try { //in firefox
    comp.click();
    return;
} catch(ex) {}
try { // in chrome
    if(document.createEvent) {
        var e = document.createEvent('MouseEvents');
        e.initEvent( 'click', true, true );
        comp.dispatchEvent(e);
        return;
    }
} catch(ex) {}
try { // in IE
    if(document.createEventObject) {
         var evObj = document.createEventObject();
         comp.fireEvent("onclick", evObj);
         return;
    }
} catch(ex) {}
}


function displayLog() {
	$('balances-div').setStyle('display','none');
	$('display-log').set('html','');
	var mth = $('mth').getSelected().get('value');
	var stat = $('status-flag').getSelected().get('value');
	var flr = $('filter-id').get('value');
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var he = $('hide-errors').checked;
	var hbc = $('hide-barcodes').checked;
	var yr = $('log-year').get('value');

	var html = '<table border="0" width="100%" style="table-layout: fixed;margin: 0 auto">';
	var fnd = true;
	$('ajax-pcounter').setStyle('display','block');
	var mth = $('mth').get('value');
	var ft = $$('input[name=filter_type]:checked').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=63&mth='+mth+'&status='+stat+'&filter='+flr+'&he='+he+'&hbc='+hbc+'&action=show_log&search_on='+ft+'&yr='+yr,
		method: 'get',
		noCache: true,
		onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								$('display-log').set('html','No data to display.');
								fnd = false;
							} else {
								var d = text.split(';');
								var m = cnt % 2;
										if (m == 0)
										{
											var color = color1;
										} else {
											var color = color2;
										}
								html = html + '<tr>';
								if (parseInt(d[10]) == 1)
								{
									html = html + '<td style="overflow: hidden;font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="nop" disabled="true" /></td>'+'\n';
								} else {
									html = html + '<td style="overflow: hidden;font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="pid" onclick="getEntry(this.value)" value="'+d[0]+'" /></td>'+'\n';
								}
								html = html + '<td style="overflow: hidden;text-align: center; font-size: 10px; width: 4%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="unipid" onclick="getUniEntry(this.value)" value="'+d[0]+'" /></td>'+'\n';
								//tr date
								html = html + '<td style="overflow: hidden;text-align: center; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[1]+'</td>'+'\n';

								//prd date
								if (parseInt(d[10]) != 1 && parseInt(d[9]) < 100) {
									html = html + '<td style="overflow: hidden;font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: #378222; color: #000000"><strong>PENDING</strong></td>'+'\n'; 
								}
								else if (parseInt(d[10]) != 1 && parseInt(d[9]) >= 100 && parseInt(d[9]) < 500)
								{
									html = html + '<td style="overflow: hidden;font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: #ff8c8c; color: #000000"><strong>RESET REQ.</strong></td>'+'\n'; 
								}
								else if (parseInt(d[10]) != 1 && parseInt(d[9]) >= 500) {
									html = html + '<td style="overflow: hidden;font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: #000000; color: #ffffff">ACC ERROR</td>'+'\n';
								} else {
									html = html + '<td style="overflow: hidden;text-align: center; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[2]+'</td>'+'\n';
								}
								

								html = html + '<td style="font-size: 10px; width: 13%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[3]+'</td>'+'\n';
								html = html + '<td style="font-size: 10px; width: 13%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[4]+'</td>'+'\n';
								html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[5]+'</td>'+'\n';
								html = html + '<td style="overflow: hidden;text-align: right; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[6]+'</td>'+'\n';
								html = html + '<td style="overflow: hidden;text-align: right; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[8]+'</td>'+'\n';
								if (parseInt(d[9]) >= 5 && parseInt(d[9]) < 10)
								{
									html = html + '<td style="text-align: center; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: #ffff33; color: #000000">'+d[9]+'</td>'+'\n';
								}
								else if (parseInt(d[9]) >= 10 && parseInt(d[9]) <= 15)
								{
									html = html + '<td style="overflow: hidden;text-align: center; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: #ff9966; color: #000000">'+d[9]+'</td>';
								}
								else if (parseInt(d[9]) > 15 && parseInt(d[9]) <= 99)
								{
									html = html + '<td style="overflow: hidden;text-align: center; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: #ff8c8c; color: #000000">'+d[9]+'</td>';
								}
								else if (parseInt(d[9]) >= 100)
								{
									html = html + '<td style="overflow: hidden;text-align: center; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: #ff8c8c; color: #000000"><strong>'+d[9]+'</strong></td>';
								}
								else {
									html = html + '<td style="overflow: hidden;text-align: center; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">'+d[9]+'</td>';
								}
								html = html + '</tr>'+'\n';
								++cnt;
							}
						}
						
			});
			html = html + '</table>';
						if (fnd == true)
						{
							$('display-log').set('html',html);
						}
			$('ajax-pcounter').setStyle('display','none');
					
					//////////////Check for balance retrieval/////////
		if (flr.toString().length > 3)
		{
			var rm = new RegExp('^[0-9]+$');
			var r = rm.exec(flr);
				if (r != null)
				{
					$('balances-div').setStyle('display','block');
					getCopyBalance();
					getMealsBalance();
					getPrintingBalance();
				} 
				
		}
		//////////////////////////////////////////////////


		}
	}).send();

}

function getEntry() {
	var id = $$('input[name=pid]:checked').get('value');
	var y = new Request({
		url: 'index.php?option=com_jumi&fileid=63&action=check_invalid&id='+id,
		noCache: true,
		method: 'get',
		onComplete: function (response) {
				var rm = new RegExp('^[0-9]+$');
				var r = rm.exec(response);
							if (confirm('Change account no?')){
								var acc = prompt('Enter new account#','');
								if (acc!=null && acc!=''){
										var x = new Request({
										url: 'index.php?option=com_jumi&fileid=63&action=reset_acc&acc='+acc+'&id='+id,
										noCache: true,
										method: 'get',
										onComplete: function(response) {
											if (parseInt(response) == -1)
											{
												alert('Error changing transaction.');
											} else {
												alert('Transaction successful.');
												displayLog();
											}
										}

									}).send()	
								}
							} else if (confirm('Reset requested?'+'\n'+'Are you sure?')){
									var x = new Request({
										url: 'index.php?option=com_jumi&fileid=63&action=reset&id='+id,
										noCache: true,
										method: 'get',
										onComplete: function(response) {
											if (parseInt(response) == -1)
											{
												alert('Error resetting transaction.');
											} else {
												alert('Transaction reset successful.');
												displayLog();
											}
										}

									}).send();
								}
					
		}
	}).send();
	 
}

function getUniEntry() {
	$('ajax-pcounter').setStyle('display','block');
	var id = $$('input[name=unipid]:checked').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=63&action=getUniReceipt&rn='+id,
		method: 'get',
		async: false,
		onComplete: function(response) {
			$('ajax-pcounter').setStyle('display','none');
			if (parseInt(response) == -1) {
				alert('No receipt found on Uniflow.');
				return false;
			} else {
				var data = response;
				$('ajax-pcounter').setStyle('dispplay','block');
				var y = new Request({
					url: 'index.php?option=com_jumi&fileid=63&action=getUniData&rn='+data,
					method: 'get',
					onComplete: function(response) {
						$('ajax-pcounter').setStyle('display','none');
						var data = response;
						if (parseInt(data) == -1) {
							alert('No entry found in Uniflow database.');
						} else {
							var r = data.split(';');
							alert('UNIFLOW DATA'+'\n'+'Account# '+r[0]+'\n'+'Trans Date: '+r[1]+'\n'+'Proc Date:   '+r[2]+'\n'+'Amount: '+r[3]);
						}
					}
				}).send();
			}
		}
	}).send();
	 
}

function getCopyBalance() {
	$('cp-bal').set('html','&nbsp;<img src="/images/kit-ajax-pcounter.gif" width="16" height="16" />');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=16&function=cbal&uid='+$('filter-id').get('value'),
		noCache: true,
		method: 'get',
		timeout: 18000,
		onTimeout: function () {
		x.cancel();
		$('cp-bal').set('html','Not Available.');
		},
		onComplete: function(response) {
							if (parseFloat(response) == 99999) {
								$('cp-bal').set('html','Balance not available.');
							} else if (parseFloat(response) == -1){
								$('cp-bal').set('html','No copy account.');
							} else 
								$('cp-bal').set('html','R'+response);
							}
	}).send();
}

function getMealsBalance() {
	$('mls-bal').set('html','&nbsp;<img src="/images/kit-ajax-pcounter.gif" width="16" height="16" />');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=16&function=mbal&uid='+$('filter-id').get('value'),
		noCache: true,
		method: 'get',
		timeout: 8000,
		onTimeout: function () {
		x.cancel();
		$('mls-bal').set('html','Not Available.');
		},
		onComplete: function(response) {
							if (parseInt(response) == -1) {
								$('mls-bal').set('html','Meals not available.'); 
							} else {
								$('mls-bal').set('html','R '+response);
							}
			}
	}).send();
}

function getPrintingBalance() {
	$('prn-bal').set('html','&nbsp;<img src="/images/kit-ajax-pcounter.gif" width="16" height="16" />');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=16&function=pbal&uid='+$('filter-id').get('value'),
		noCache: true,
		method: 'get',
		timeout: 10000,
		onTimeout: function () {
		$('prn-bal').set('html','Not Available.');
		x.cancel();
		},
		onComplete: function(response) {
							if (parseInt(response) == -1) {
								$('prn-bal').set('html','Printing not available.'); 
							} else if (parseFloat(response) <= 20) {
								$('prn-bal').set('html','R '+response);
							} else {
								$('prn-bal').set('html','R '+ response);
							}
			}
	}).send();
}

function syncBarcode() {
	var fi = $('filter-id').get('value');
	if (fi.length < 9)
	{
		alert('Please enter a valid student number.');
	} else {
		var rm = new RegExp('^[0-9]+$');
			var r = rm.exec(fi);
				if (r != null)
				{
					$('validate_student').set('href','index.php?option=com_jumi&fileid=72&tmpl=component&stno='+fi);
					$('validate_student').click();
				} else {
					alert('Numeric numbers only');
				}
	}
}