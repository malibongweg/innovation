<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addScript("scripts/idphoto/jquery.js");
$doc->addScript("scripts/idphoto/jquery-barcode.js");
$doc->addStyleSheet("scripts/idphoto/card.css","text/css");
echo '<input type="hidden" id="uid" value="'.$_GET['uid'].'"/>';
?>
<style type="text/css">
	body { width: 204px; height: 320px; margin: 0; }
</style>

<input type="hidden" id="operator" value="<?php echo $_GET['op']; ?>" />
<div style="position: relative">
<span id='card-mag' class="nodisplay"></span>
</div>

<div style="position: absolute; top: 0; left: 0; width: 204px; height: 50px; text-align: center" id="card-type">
<span id="card-type-name" style="font-weight: bold; font-family: Arial, Tahoma; color: #ffffff; font-size: 12px; line-height: 45px">..........</span>
</div>

<div style="position: absolute; top: 50px; left: 50px; width: 5px; height: 270px; text-align: center" id="card-line"></div>

<div class="label-rotate" style="position: absolute; top: 160px; left: -100px; width: 270px; height: 50px; font-family: Arial,Tahoma; font-weight: bold; font-size: 17px">CAPE PENINSULA<br>UNIVERSITY OF TECHNOLOGY</div>

<div class="label-rotate" style="position: absolute;  top: 155px; left: -50px; width: 270px; height: 50px; font-family: Arial,Tahoma; font-weight: bold; font-size: 12px" id="card-name"></div>

<div class="label-rotate" style="position: absolute; top: 155px; left: -30px; width: 270px; height: 50px; font-family: Arial,Tahoma; font-weight: bold; font-size: 12px" id="card-desc"></div>

<div class="label-rotate" style="position: absolute; top: 155px; left: -10px; width: 270px; height: 50px; font-family: Arial,Tahoma; font-weight: bold; font-size: 12px" id="card-expire"></div>

<div class="label-rotate" style="position: absolute; top: 155px; left: 10px; width: 270px; height: 50px; font-family: Arial,Tahoma; font-weight: bold; font-size: 12px" id="card-number"></div>

<div class="label-rotate" style=" position: absolute; top: 60px; left: 70px; width: 110px; height: 115px;">
<img src="#" style="width: 110px; height: 115px" id="img-source">
</div>

<script type="text/javascript">
var type = '';

window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({ 'height': 450, 'width': 250 });
	
	var userid = $('uid').get('value');
	setTimeout('getDetails('+userid+')',1500);
});

function getDetails(id){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=126&action=search_id&id='+id,
			method: 'get',
			noCache: true,
			onComplete: function(response){
			r = response.split(';');
			if (r[4] == 'W'){
				type = 'W';
			} else if (r[4] == 'P'){
				type = 'P';
			} else if (r[4] == 'V'){
				type = 'V';
			} else if (r[4] == 'X'){
				type = 'X';
			} 
			prnCard();
		}
		
	}).send();
}

function prnCard(){
		var fillColor = '';
		if (type == 'W'){
			$('card-type').setStyle('background-color','#ff7f00');
			$('card-line').setStyle('background-color','#ff7f00');
			//fillColor = '#ff0000';
		} else if (type == 'P'){
			$('card-type').setStyle('background-color','#ff8000');
			$('card-line').setStyle('background-color','#FF8000');
			//fillColor = '#FF8000';
		}  else if (type == 'V'){
			$('card-type').setStyle('background-color','#006600');
			$('card-line').setStyle('background-color','#006600');
			//fillColor = '#ffff00';
		} else if (type == 'X'){
			$('card-type').setStyle('background-color','#00ff00');
			$('card-line').setStyle('background-color','#00ff00');
			//fillColor = '#A7DA4E';
		}
		
	
		//ctx.fillStyle = fillColor;
		//ctx.fillRect(0,0,203,50);

		if (type == 'W'){
			//$('cc-type').setStyle('font','bold 11px arial');
			$('card-type-name').setStyle('color','#000000');
			$('card-type-name').set('html','WALKTHROUGH');
		} else if (type == 'P'){
			//$('cc-type').setStyle('font','bold 11px arial');
			$('card-type-name').setStyle('color','#000000');
			$('card-type-name').set('html','PENSIONER IDENTIFICATION');
		}  else if (type == 'V'){
			//$('cc-type').setStyle('font','bold 11px arial');
			$('card-type-name').setStyle('color','#000000');
			$('card-type-name').set('html','VISITOR IDENTIFICATION');
		} else if (type == 'X'){
			//$('cc-type').setStyle('font','bold 11px arial');
			$('card-type-name').setStyle('color','#000000');
			$('card-type-name').set('html','CLEANER IDENTIFICATION');
		}

		
		//$('c-name').setStyle('font','bold 14px arial');
		$('card-name').set('html',r[1]);

		if (type != 'P'){
			$('card-number').setStyle('font','bold 12px arial');
			$('card-number').set('html',r[0]);
		}


		//$('c-dest').setStyle('font','bold 12px arial');
		$('card-desc').set('html',r[5]);

		if (type != 'P'){
			$('card-expire').set('html',r[2]);
		}

		$('img-source').set('src',r[6]);
		$('img-source').setStyle('border','1px solid #000000');

		

					
					  window.print();
					  var op = $('operator').get('value');
					  var x = new Request({
						  url: 'index.php?option=com_jumi&fileid=126&action=prn_history&uid='+r[0]+'&op='+op,
							  method: 'get',
							  noCache: true,
							  onComplete: function(){
						  }
					  }).send();
					  setTimeout('closeCard()',10000);
}

function closeCard(){
	window.parent.$j.colorbox.close();
}
</script>