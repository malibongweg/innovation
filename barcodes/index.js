window.addEvent('domready',function(){

	clearStdno();
	srchEvent();
	copyButton();
	mealsButton();

});

function copyButton(){

$('copy-button-fix').addEvent('click',function(){

	var locStno = $('std-no').get('value');
	var locCardno = $('cardno').get('value');

	$('copy-button-fix').setStyle('display','none');
	$('ajax-barcode').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=227&action=updateCopier&stno='+locStno+'&cardno='+locCardno,
		method: 'get',
		async: true,
		onComplete: function(response){
			var data = json_parse(response);
			if (parseInt(data.Error) == 0){
				reloadScreen();
			} else {
				alert('An error occured suring the update...');
			}
			$('ajax-barcode').setStyle('display','none');
		}
	}).send();

});

}

function mealsButton(){
$('meals-mail-button').addEvent('click',function(){

	var locStno = $('std-no').get('value');
	$('ajax-barcode').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=227&action=mailMeals&stno='+locStno,
		method: 'get',
		async: true,
		onComplete: function(response){
			var data = json_parse(response);
			$('ajax-barcode').setStyle('display','none');
			if (parseInt(data.Error) < 0){
				alert('An error occurred...Please contact CTS department.');
			} else {
				alert('Meals PIN was sent to user.');
			}
		}
	}).send();

});

$('meals-button-fix').addEvent('click',function(){

	var locStno = $('std-no').get('value');
	var locCardno = $('cardno').get('value');

	$('meals-button-fix').setStyle('display','none');
	$('ajax-barcode').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=227&action=updateMeals&stno='+locStno+'&cardno='+locCardno,
		method: 'get',
		async: true,
		onComplete: function(response){
			var data = json_parse(response);
			if (parseInt(data.Error) == 0){
				reloadScreen();
			} else {
				alert('An error occured suring the update...');
			}
			$('ajax-barcode').setStyle('display','none');
		}
	}).send();

});

}

function clearStdno(){
	$('std-no').addEvent('click',function(){
		$('std-no').set('value','');
		$('crd-name').set('html',"");
		$('crd-barcode').set('html',"");
		$('student-pic').setAttribute('src',"");
		$('barcodes-details').setStyle('display','none');
		$('ajax-barcode').setStyle('display','none');
	});
}

function srchEvent(){
	$('std-search').addEvent('click',function(){
	var locStno = $('std-no').get('value');
	if (locStno.length < 9){
		alert('Please enter a valid student number!');
		return;
	}


		$('ajax-barcode').setStyle('display','block');
		$('meals-button-fix').setStyle('display','none');
		$('copy-button-fix').setStyle('display','none');
	
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=227&action=srchStudent&stno='+locStno,
		method: 'get',
		async: false,
		onComplete: function(response){
			$('barcodes-details').setStyle('display','block');
			var data = json_parse(response);
			if (parseInt(data.itsError) == 0){
				$('crd-name').set('html',data.fullName+"<br />"+data.stdNo);
				$('crd-barcode').set('html',data.itsMag);
				$('student-pic').setAttribute('src',data.imgLocation);
				$('cardno').set('value',data.itsMag);
			}

			if (parseInt(data.copyError) == 0){
				if (parseInt(data.itsMag) == parseInt(data.copyMag)){
					$('copy-barcode').set('html',"Copier: "+data.copyMag);
					$('copy-button-fix').setStyle('display','none');
					$('copy-barcode').setStyle('color','#006600');
				} else {
					$('copy-barcode').set('html',"Copier: "+data.copyMag);
					$('copy-button-fix').setStyle('display','block');
					$('copy-barcode').setStyle('color','#ff0000');
				}
				
			} else {
				$('copy-barcode').set('html',"Copier: No data found...");
				$('copy-button-fix').setStyle('display','block');
			}

			if (parseInt(data.mealsError) == 0){
				if (parseInt(data.itsMag) == parseInt(data.mealsMag)){
					$('meals-barcode').set('html',"Meals: "+data.mealsMag);
					$('meals-button-fix').setStyle('display','none');
					$('meals-barcode').setStyle('color','#006600');
				} else {
					if (data.mealsMag == null){
						$('meals-barcode').set('html',"Meals: No data found...");
						$('meals-button-fix').setStyle('display','block');
						$('meals-mail-button').setStyle('display','none');
						$('meals-barcode').setStyle('color','#ff0000');
					} else {
						$('meals-barcode').set('html',"Meals: "+data.mealsMag);
						$('meals-button-fix').setStyle('display','block');
						$('meals-barcode').setStyle('color','#ff0000');
					}
				}
				
			} else {
				$('meals-barcode').set('html',"No data found");
				$('meals-button-fix').setStyle('display','block');
			}

			$('ajax-barcode').setStyle('display','none');
		}
	}).send();
	});
}

function reloadScreen(){

		$('ajax-barcode').setStyle('display','block');
		$('meals-button-fix').setStyle('display','none');
		$('copy-button-fix').setStyle('display','none');
	var locStno = $('std-no').get('value');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=227&action=srchStudent&stno='+locStno,
		method: 'get',
		async: false,
		onComplete: function(response){
			$('barcodes-details').setStyle('display','block');
			var data = json_parse(response);
			if (parseInt(data.itsError) == 0){
				$('crd-name').set('html',data.fullName+"<br />"+data.stdNo);
				$('crd-barcode').set('html',data.itsMag);
				$('student-pic').setAttribute('src',data.imgLocation);
				$('cardno').set('value',data.itsMag);
			}

			if (parseInt(data.copyError) == 0){
				if (parseInt(data.itsMag) == parseInt(data.copyMag)){
					$('copy-barcode').set('html',"Copier: "+data.copyMag);
					$('copy-button-fix').setStyle('display','none');
					$('copy-barcode').setStyle('color','#006600');
				} else {
					$('copy-barcode').set('html',"Copier: "+data.copyMag);
					$('copy-button-fix').setStyle('display','block');
					$('copy-barcode').setStyle('color','#ff0000');
				}
				
			} else {
				$('copy-barcode').set('html',"No data found");
				$('copy-button-fix').setStyle('display','none');
			}

			if (parseInt(data.mealsError) == 0){
				if (parseInt(data.itsMag) == parseInt(data.mealsMag)){
					$('meals-barcode').set('html',"Meals: "+data.mealsMag);
					$('meals-button-fix').setStyle('display','none');
					$('meals-mail-button').setStyle('display','block');
					$('meals-barcode').setStyle('color','#006600');
				} else {
					$('meals-barcode').set('html',"Meals: "+data.mealsMag);
					$('meals-button-fix').setStyle('display','block');
					$('meals-barcode').setStyle('color','#ff0000');
				}
				
			} else {
				$('meals-barcode').set('html',"No data found");
				$('meals-button-fix').setStyle('display','none');
			}

			$('ajax-barcode').setStyle('display','none');
		}
	}).send();

}