var barcode = '';
window.addEvent('domready',function(){

	$('mobile-data').addEvent('submit',function(e){
		e.stop();
		var bc = $('current-barcode').get('value');
		var newcell = $('confirm-cell').get('value');
		if (bc.length < 11){
			alert('Barcode to short.');
			return false;
		}
		if (newcell.length < 10){
			alert('Mobile# to short.');
			return false;
		}
			var x = new Request({
			url: '/mcdet/ajax.php?action=confirmUser',
			method: 'post',
			data: this,
			onComplete: function(response){
				var d = json_parse(response);
				if (parseInt(d.Result) == 0){
					alert('An error occured. Please try again later.');
					return false;
				} else if (parseInt(d.Result) == 1){
					alert('Could not validate your account.\nPlease check all details before submitting.');
					return false;
				} else {
					alert('Information updated. Thank you.');
					window.location.url='http:/www.cput.ac.za';
					return false;
				}
			}
		}).send();
	});
	
	checkPrevious();

});

function checkPrevious(){
	var hash = $('hash').get('value');
	//Check and then continue//////
	if (hash.length > 0){
		var y = new Request({
			url: '/mcdet/ajax.php?action=loadData&hash='+hash,
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response){
				var data = json_parse(response);
				if (parseInt(data.Result) == 0){
					alert('Could not locate your data.');
					return false;
				} else {
					barcode = data.Record.BARCODE;
					$('student-no').set('value',data.Record.STDNO);
					$('stud-no').set('value',data.Record.STDNO);
					$('current-cell').set('value',data.Record.MOBILENO);
					$('current-barcode').focus();
				}
			}
		}).send();
	}
}