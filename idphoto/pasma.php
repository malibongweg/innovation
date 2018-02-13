<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$doc = & JFactory::getDocument();
$doc->addStyleSheet("scripts/idphoto/card.css","text/css");
$doc->addScript("scripts/json.js");
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
	.bg {
		background-image:url(/scripts/idphoto/images/pasma-final.jpg);
		-moz-background-size:100% 100%;
		-webkit-background-size:100% 100%;
		background-size:100% 100%;
	}
	
</style>

<!--Mag Encoding-->
<div  class="bg" style="position: absolute; top: 0; left: 0; width: 208px; height: 328px;  margin: 0 auto; padding: 0">
		
		<div class="label-rotate" style="top: 150px; left: -45px; width: 315px; height: auto; color: red; font-family: Arial ,Tahoma; font-weight: bold; font-size: 14px; color: #000000;" id="card-type">Membership Card</div>
		<div  class="label-rotate" style="top: 150px; left: -30px; width: 315px; height: auto; color: #000000; font-family: Arial ,Tahoma; font-weight: bold; font-size: 12px; color: #000000" id="card-branch">CPUT Bellville Branch</div>
		<div  class="label-rotate" style="top: 150px; left: -18px; width: 315px; height: auto; color: #000000; font-family: Arial ,Tahoma; font-weight: bold; font-size: 12px; color: #000000" id="card-name">Mr. John Jones</div>
		<div  class="label-rotate" style="top: 150px; left: -5px; width: 315px; height: auto; color: #000000; font-family: Arial,Tahoma; font-weight: bold; font-size: 12px; color: #000000" id="card-no">216000000</div>
		<div  class="label-rotate" style="top: 150px; left: 8px; width: 315px; height: auto; color: #000000; font-family: Arial,Tahoma; font-weight: bold; font-size: 12px; color: #000000" id="card-expire">2016</div>
		
		<div class="label-rotate" style="top: 20px; left: 110px; width: 80px; height: 120px">
			<img src="#" style="position: absolute; top: 1px; left: 1px; width: 99px; height: 103px; border: 1px solid #000000" id="img-source">
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
		url: '/scripts/idphoto/members.php?action=prn_search&uid='+id,
			method: 'get',
			noCache: true,
			onComplete: function(response){
			var pic = "";
			var data = json_parse(response);
			if (parseInt(data.pic) == -1) {
				pic = "/scripts/idphoto/img/blank.jpg?" + new Date().getTime();
			} else {
				pic = data.source + "?" + new Date().getTime();
			}
			$("card-branch").set("html",data.Record.branch);
			$("card-name").set("html",data.Record.mem_name);
			$("card-no").set("html",data.Record.stdno);
			$("card-expire").set("html",data.Record.expire);
			$("img-source").set("src",pic);
			
			$('card-busy').setStyle('display','none');
			setTimeout('prnWindow()',500);
			setTimeout('closeCard()',10000);
		}
	}).send();
}


function closeCard(){
	window.parent.$j.colorbox.close();
}

function prnWindow() {
	window.print();
}

</script>
