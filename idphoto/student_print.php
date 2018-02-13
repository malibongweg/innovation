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
	@font-face
	{
		font-family: cardFont;
		src: url(TradeGothic.ttf);
	}
</style>

<input type="hidden" id="operator" value="<?php echo $_GET['op']; ?>" />

<!--Mag Encoding-->
<div  style="position: absolute; top: 0; left: 0; width: 204px; height: 320px;  margin: 0 auto; padding: 0">
		<div class="label-rotate" style="top: 132px; left: -126px; width: 316px; height: 62px" id="card-logo">
			<img src="/scripts/idphoto/img/student_new.jpg" style="width: 320px; height: 62px; border: 0 !important"  border="0" alt="">
		</div>

		<div class="label-rotate" style="top: 150px; left: -85px; width: 315px; height: auto; color: red; font-family: Arial ,Tahoma; font-weight: bold; font-size: 12px; color: #b90000;" id="card-type"></div>
		<div  class="label-rotate" style="top: 152px; left: -70px; width: 315px; height: auto; color: #000000; font-family: Arial ,Tahoma; font-weight: bold; font-size: 10px; color: #0080ff" id="card-campus"></div>
		<div  class="label-rotate" style="top: 150px; left: -55px; width: 315px; height: auto; color: #000000; font-family: Arial ,Tahoma; font-weight: bold; font-size: 12px; color: #1c0549" id="card-name"></div>
		<div  class="label-rotate" style="top: 150px; left: -40px; width: 315px; height: auto; color: #000000; font-family: Arial,Tahoma; font-weight: bold; font-size: 12px; color: #1c0549" id="card-no"></div>
		<div  class="label-rotate" style="top: 150px; left: -25px; width: 315px; height: auto; color: #000000; font-family: Arial,Tahoma; font-weight: bold; font-size: 12px; color: #1c0549" id="card-expire"></div>

		<!--div class="label-rotate" style="top: 5px; left: 70px; width: 101px; height: 105px">
			<div style="position: absolute; top: 1px; left: 1px; width: 99px; height: 103px; z-index: 2000">
			<img src="#" style="width: 100%; height: 100%" border="2" id="img-source">
			</div>
		</div-->

		
		<div class="label-rotate" style="top: 5px; left: 70px; width: 101px; height: 105px">
			<img src="#" style="position: absolute; top: 1px; left: 1px; width: 99px; height: 103px; border: 1px solid #000000" id="img-source">
		</div>

		<div  class="label-rotate" style="top: 215px; left: 80px; width: 180px; height: 38px" id="card-barcode">
			<canvas width="180" height="38" id="barcode-canvas"></canvas>
			<div id="hri" style="position: absolute; text-align: center; font-family: Arial,Tahoma; font-size: 12px; font-weight: bold; color: #000000; top: 20px; left:25px; width: 130px; height: 12px"></div>
		</div>

</div>

<!--Mag Encoding-->
<div>
	<span class="nodisplay" id="card-mag" style="font-size: 10px"></span>
</div>

<div class="noprint" style="position: relative; font-size: 10px; margin-top: 10px; padding: 3px; background-color: #ffffa4; border: 1px solid red; width: auto" id="card-busy">
	<img src="/images/auth.gif" width="60" height="10" border="0" alt="" style="vertical-align: middle">&nbsp;<span id="crd-msg">Busy...</span>
</div>




<script type="text/javascript">
window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({ 'height': 450, 'width': 250 });
	var userid = $('uid').get('value');
	getDetails(userid);
});

function getDetails(id){
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=126&action=search_id&ac=print&id='+id,
			method: 'get',
			noCache: true,
			onComplete: function(response){
			r = response.split(';');
			loadHeader('S')
		}
	}).send();
}

function loadHeader(type) {
	$j('#card-mag').text('~2;'+r[7]+'?');
	$('card-type').set('html','STUDENT IDENTIFICATION');
	$('card-campus').set('html',r[8]);
	$('card-name').set('html',r[1]);
	$('card-no').set('html',r[0]);
	$('card-expire').set('html',r[2]);
	$('img-source').set('src',r[6]);
	$('img-source').setStyle('border','1px solid #000000');
	//Barcode
		ctx=$('barcode-canvas').getContext("2d");
		ctx.font = 'bold 12px arial';
		var settings = {
		  output: 'canvas',
		  color: '#000000',
		  bgColor: '#FFFFFF',
		  showHRI: false,
		  moduleSize: 5,
		  barWidth: 1,
		  barHeight: 20,
		  posX: 0,
		  posY: 0,
		  fontSize: 12
		};
	 $j("#barcode-canvas").barcode(r[3], 'code39', settings);
	 //$j("#card-barcode").barcode(r[3], 'code39', settings);
	 $('hri').set('html',r[3]);

	 $('card-busy').setStyle('display','none');

	 setTimeout('prnWindow()',500);
	 setTimeout('closeCard()',10000);

}

function closeCard(){
	window.parent.$j.colorbox.close();
}

function prnWindow() {
	window.print();
}

</script>
