<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addScript("scripts/idphoto/jquery.js");
$doc->addScript("scripts/idphoto/jquery-barcode.js");
//$doc->addStyleSheet("scripts/idphoto/css.css");
$doc->addStyleSheet("scripts/idphoto/card.css","text/css");
?>
<style type="text/css">
	body { width: 204px; height: 320px; margin: 0; padding: 0; }
</style>


<?php echo '<input type="hidden" id="uid" value="'.$_GET['uid'].'"/>'; ?>

<input type="hidden" id="operator" value="<?php echo $_GET['op']; ?>" />
<input type="hidden" id="intern-card" value="<?php echo $_GET['intern']; ?>" />

<div  style="position: absolute; top: 0; left: 0; width: 204px; height: 320px;  margin: 0; padding: 0">

		<div style="position: absolute; top: 0; left: 0; width: 204px; height: 75px; " id="card-logo">
			<img src="#" style="width: 204px; height: 75px; border: 0 !important" id="card-header">
		</div>

		<div style="position: relative; margin-top: 80px; padding-left: 10px; width: 190px; height: auto; font-family: card-font,Arial,Tahoma; font-weight: bold; font-size: 12px; color: #b90000; text-align: left" id="card-type"></div>
		<div style="position: relative; margin-top: 3px; padding-left: 10px; width: 190px; height: auto; font-family: card-font,Arial,Tahoma; font-weight: bold; font-size: 10px; color: #0080ff; text-align: left" id="card-campus"></div>
		<div style="position: relative; margin-top: 3px; padding-left: 10px; width: 190px; height: auto; font-family: card-font,Arial,Tahoma; font-weight: bold; font-size: 12px; color: #1c0549; text-align: left" id="card-name"></div>
		<div style="position: relative; margin-top: 3px; padding-left: 10px; width: 190px; height: auto; font-family: card-font,Arial,Tahoma; font-weight: bold; font-size: 12px; color: #1c0549; text-align: left" id="card-no"></div>
		<div style="position: relative; margin-top: 3px; padding-left: 10px; width: 190px; height: auto; font-family: card-font,Arial,Tahoma; font-weight: bold; font-size: 12px; color: #1c0549; text-align: left" id="card-expire"></div>

<div>
	<span class="nodisplay"  id="card-mag-number" style="position: relative; margin-top: 3px; padding-left: 10px; width: 190px; height: auto; font-size: 10px">...</span>
</div>

		<div style="position: relative; margin-top: 5px; width: 90px; height: 100px; text-align: left">
			<img src="#" style="margin-left: 10px; width: 90px; height: 100px; border: 1px solid #000000" id="img-source">
		</div>
		
		<div style="position: relative; margin-top: 7px; width: 195px; height: auto" id="card-barcode">
	
		</div>
</div>

<!--Mag Encoding-->
<!--div>
	<span class="nodisplay" id="card-mag-number" style="font-size: 10px; margin-right: 50px; margin-top: 50px"></span>
</div-->

<div class="noprint" style="position: relative; font-size: 10px; margin-top: 5px; padding: 3px; background-color: #ffffa4; border: 1px solid red; width: auto" id="card-busy">
	<img src="/images/auth.gif" width="60" height="10" border="0" alt="" style="vertical-align: middle">&nbsp;<span id="crd-msg">Busy...</span>
</div>


<script type="text/javascript">
var ctx = null;
var posy = 0;
var r = '';
var cwidth = 0;


window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({ 'height': 450, 'width': 250 });

	var userid = $('uid').get('value');
	setTimeout('getDetails('+userid+')',1500);
});

function getDetails(id){
	$('crd-msg').setStyle('font-family','arial');
	$('crd-msg').setStyle('font-size','14px');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=126&action=search_id&ac=print&id='+id,
			method: 'get',
			noCache: true,
			async: false,
			onComplete: function(response){
			r = response.split(';');

			//$j('#card-mag-number').text('~2;'+r[7]+'?');
			
			if (r[4] == 'T'){
				loadHeader('T')
			} else if (r[4] == 'C'){
				loadHeader('C');
			} else if (r[4] == 'I'){
				loadHeader('I')
			}
				var ic = $('intern-card').get('value');
				if (parseInt(ic) == 1) { loadHeader('I'); }
		}
	}).send();
}

function loadHeader(type){
	$j('#card-mag-number').text('~2;'+r[7]+'?');
	
	if (type == 'C'){
		$('card-header').set('src','/scripts/idphoto/img/contractor2016.jpg');
		$('card-type').set('html','CONTRACTOR IDENTIFICATION CARD');
	} else if (type == 'I'){
		$('card-header').set('src','/scripts/idphoto/img/intern2016.jpg');
		$('card-type').set('html','INTERN IDENTIFICATION CARD');
	}  else if (type == 'T'){
		$('card-header').set('src','/scripts/idphoto/img/staff.jpg');
		$('card-type').set('html','STAFF IDENTIFICATION CARD');
	} 

	$('card-name').set('html',r[1]);

	$('card-no').set('html',r[0]);

	if (type == 'T'){
		$('card-expire').set('html','DATE ISSUED: '+ new Date().getFullYear());
	} else {
		$('card-expire').set('html','DATE EXPIRE: '+r[2]);
	}

	$('card-campus').set('html',r[8]);

	$('img-source').set('src',r[6]);
	$('img-source').setStyle('border','1px solid #000000');

	
		var settings = {
		  output: 'css',
		  color: '#000000',
		  bgColor: '#FFFFFF',
		  moduleSize: 5,
		  barWidth: 1,
		  barHeight: 20,
		  posX: 0,
		  posY: 0,
		  fontSize: 12
		};
	
	 $j("#card-barcode").barcode(r[3], 'code39', settings);

	 $('card-busy').setStyle('display','none');

	 setTimeout('prnCard()',100);
	 setTimeout('closeCard()',10000);
	
}

function prnCard() {
   window.print();
}

function closeCard(){
	window.parent.$j.colorbox.close();
}
</script>
