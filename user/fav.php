<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$user = & JFactory::getUser();
?>

<div id="fav-links" style="width: 100%; height: auto; padding: 5px 5px 5px 5px; display: none">
<img src="images/ajax-loader.gif" width="16" height="16"  />
</div>

<script type="text/javascript" >
function getFavs() {
	$('fav-links').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=4&func=fav&uid=<?php echo $user->id; ?>&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
					if ($('fav-links')) {
						$('fav-links').set('html',response);
					}
			}
	}).send();
}

setTimeout('getFavs()',1000);
</script>