window.addEvent('domready',function() {

	var h = window.getSize().y;
	$('table-header').setStyles({'height':parseInt(h) +'px'});
	$('data-div').setStyles({'height':parseInt(h-100)+'px'});
	$('student-no').set('value','');
	$('button-status').set('disabled',true);
	$('button-barcode').set('disabled',true);
	$('button-details').set('disabled',false);

	$('student-no').addEvent('click',function(){
		resetScreen();
	});

	$('button-details').addEvent('click',function(){
		var n = $('student-no').get('value');
		if (n.length < 5){
			alert('Please enter valid student/journal number.');
			$('student-no').set('value','');
			$('student-no').focus();
		} else getCredentials(n);
	});

	$('month-select').addEvent('change',function(){
		var n = $('student-no').get('value');
		displayStatement(n)
	});

	$('year-select').addEvent('change',function(){
		var n = $('student-no').get('value');
		displayStatement(n)
	});


	$('button-status').addEvent('click',function(){
		var n = $('student-no').get('value');
		blockUnblock(n);
	});

	$('button-barcode').addEvent('click',function(){
		var n = $('student-no').get('value');
		syncBarcode(n);
	});

	$('button-print').addEvent('click',function(){
		prnStatement();
	});

	$('button-journal').addEvent('click',function(){
		var j = $('student-no').get('value');
		impJournal(j);
	});
	
});

function prnStatement(){
	$('lnk-print').set('href','/scripts/system/meals/print_statement.php');
	$('lnk-print').click();
}

function blockUnblock(id){
	var t = $('button-status').get('value');
	var action = 'none';
	if (t == 'Block Account') {
		action = 'block_acc';
	} else {
		action = 'unblock_acc';
	}

	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=133&action=cblock&action2='+action+'&id='+id,
			method: 'get',
			noCache: true,
			onComplete: function(response){
			if (parseInt(response) == -1){
				alert('Unable to complete action...');
			} else {
				getCredentials(id);
			}
		}
	}).send();
}

function displayStatus(id){
	$('barcheck').setStyle('display','inline');
	$('button-status').set('disabled',false);
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=133&action=acc_status&id='+id,
			noCache: true,
			method: 'get',
			async: false,
			onComplete: function(response) {
				var r = parseInt(response);
				if (r >= 0)	{
					if (r == 0) {
						$('button-status').set('value','Block Account');
						$('button-status').setStyle('border','2px solid #008000');
					} else {
						$('button-status').set('value','Unblock Account');
						$('button-status').setStyle('border','2px solid #ff0000');
					}
				}
							var y = new Request({
								url: 'index.php?option=com_jumi&fileid=133&action=check_barcode&id='+id,
									noCache: true,
									method: 'get',
									onComplete: function(response){
										if (parseInt(response) > 0) {
											$('button-barcode').set('disabled',false);
											var bc = $('cardmag').get('value');
												if (parseInt(response) == parseInt(bc))	{
													$('button-barcode').setStyle('border','2px solid #008000');
													$('button-barcode').set('disabled',true);
												} else {
													$('button-barcode').setStyle('border','2px solid #ff0000');
													$('cardmag').set('value',response);
												}
										}
								}
							}).send();
				$('barcheck').setStyle('display','none');
		}
	}).send();
}

function syncBarcode(id) {
		var m = $('cardmag').get('value');
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=133&action=sync_barcode&id='+id+'&magno='+m,
				method: 'get',
				noCache: true,
				onComplete: function(response){
					if (parseInt(response) < 0){
						alert('Error updating barcode.');
					} else {
						getCredentials(id);
					}
			}
		}).send();
	}

function resetScreen(){
	$('student-no').set('value','');
	$('data-div').set('html','');
	$('student-no').focus();
	$('ajax-meals').setStyle('display','none');
	$('barcheck').setStyle('display','none');
	$('student-details').setStyle('display','none');
	$('button-status').set('disabled',true);
	$('button-barcode').set('disabled',true);
	$('button-details').set('disabled',false);
	$('params').setStyle('display','none');
	$('button-status').setStyle('border','0');
	$('button-barcode').setStyle('border','0');
	$('button-status').set('value','Account Status');
	$('button-print').set('disabled',true);
	$('button-journal').set('disabled',false);
	$('barcheck').setStyle('display','none');
	$('barmag').set('html','Checking Status/Barcode...');
}

function getCredentials(id) {
	$('barcheck').setStyle('display','block');
	$('button-journal').set('disabled',true);
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=133&action=get_credentials&id='+id,
		method: 'get',
		noCache: true,
		timeout: 10000,
		onTimeout: function() {
			$('data-div').set('html','No data available...Please try later.');
			$('ajax-meals').setStyle('display','none');
			x.cancel();
		},
		onComplete: function(response) {
						if (parseInt(response) <= 0)
						{
							$('data-div').set('html','No data available...Please try later.');
							$('ajax-meals').setStyle('display','none');
						} else {
							var rec = response.split(';');
							$('mtitle').set('html','['+id+'] '+rec[0]+ ' Balance: R'+rec[1]);
							$('cardmag').set('value',rec[2]);
							$('student-details').setStyle('display','block');
						}
		$('ajax-meals').setStyle('display','none');
		$('user-balance').setStyle('display','block');
		$('params').setStyle('display','inline');
		$('button-print').set('disabled',false);
		displayStatus(id);
		$('barcheck').setStyle('display','none');
		displayStatement(id);
		}
	}).send();
}

function displayStatement(id) {
	$('ajax-meals').setStyle('display','block');
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var mth = $('month-select').getSelected().get('value');
	var yr = $('year-select').getSelected().get('value');
	var html = '<table style="width: 100%; position: relative; height: auto; border: 0px; table-layout: fixed" id="data-table">';
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=133&action=display_data&mth='+mth+'&yr='+yr+'&id='+id,
		method: 'get',
		timeout: 15000,
		noCache: true,
		onTimeout: function() {
			$('data-div').set('html','No data available...Try again later.');
			x.cancel();
		},
		onComplete: function(response) {
				var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						if (parseInt(text) == -1)
						{
							$('data-div').set('html','No data available.');
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
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[0] + '</td>';
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[1] + '</td>';
							html = html + '<td style="width: 40%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
							html = html + rec[3] + '</td>';
							html = html + '<td style="width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: right">';
							html = html + rec[2] + '</td>';
							html = html + '</tr>';
							++cnt;
						}
					}
				});
		$('ajax-meals').setStyle('display','none');	
		html = html + '</table>';
		if (fnd == true)
				{
					$('data-div').set('html',html);
				}
		}
	}).send();
}

function impJournal(jno){
	$('barmag').set('html','Checking for processed journal...Please wait.');
	$('barcheck').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=133&action=check_journal&journal='+jno,
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response){
			if (parseInt(response) == -1){
				$('barcheck').setStyle('display','block');
				$('barmag').set('html','Checking Status/Barcode...');
				resetScreen();
				alert('Error confirming journal...Please contact CTS department.');
			} else {
				if (parseInt(response) == 0){
					$('barmag').set('html','Journal verified...Getting data from ITS.');
					$('journal-link').set('href','index.php?option=com_jumi&fileid=134&tmpl=component&journal='+jno);
					$('journal-link').click();
					resetScreen();
				} else if (parseInt(response) == -9){
					$('barcheck').setStyle('display','block');
					$('barmag').set('html','Checking Status/Barcode...');
					resetScreen();
					alert('Database error...');
				} else {
					$('barcheck').setStyle('display','block');
					$('barmag').set('html','Checking Status/Barcode...');
					resetScreen();
					alert('Our records show that journal '+jno+' has been processed...Cannot proceed.');
				}
			}
		}
	}).send();
}