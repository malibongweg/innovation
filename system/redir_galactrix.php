<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$user = & JFactory::getUser();
$date 		= date("Y-m-d");
$time 		= date("G:i:s");
$userid 	= $user->username;
$ipaddress 	= "10.12.1.4";
//$ipaddress 	= "155.238.33.42";
$url = "http://10.12.1.4/galactrix/ssogateway.aspx";
//$url = "http://10.12.1.4/galactrix/ssogateway.aspx?token=";
$info = $date.",".$time.",".$userid.",".$ipaddress;
$encodedinfo = base64_encode($info);
$randomvalue = md5(rand());
//echo $encodedinfo."<br />".$randomvalue;

?>
<form action="<?php echo $url; ?>" method="GET" name="ImIn" id='ImIn' target="_blank">	   
	<input type="hidden" name="token" value="<?php echo $encodedinfo; ?>" >
	<input type="hidden" name="jqnk" value="<?php echo $randomvalue; ?>" >
</form>

<div id="galax" style="position: relative; display: block">
<img src="images/kit-ajax.gif" width="32" height="11" border="0" alt=""><br />
<span style="font-family: sans-serif arial; font-weight: bold; font-size: 14px">Loading...</span>
</div>

<script type="text/javascript">

window.addEvent('domready',function(){
	setTimeout('busy()',500);
});
function busy(){
	$('ImIn').submit();
	$('galax').setStyle('display','none');
}
</script>

