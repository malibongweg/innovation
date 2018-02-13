<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");
$dbo->setQuery("call proc_pop_application('Application Tracker')");
$dbo->query();
?>
<a href="#" id="show-record" class="modalizer" /></a>
<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Application Tracker" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
</form>
<div class="art-block">
            <div class="art-block-tl"></div>

            <div class="art-block-tr"></div>
            <div class="art-block-bl"></div>
            <div class="art-block-br"></div>
            <div class="art-block-tc"></div>
            <div class="art-block-bc"></div>
            <div class="art-block-cl"></div>
            <div class="art-block-cr"></div>
            <div class="art-block-cc"></div>
            <div class="art-block-body">

        
                <div class="art-blockheader">
            <div class="l"></div>
            <div class="r"></div>
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Application Tracker</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">



<div id="search" class="main-div">
	<div class="main-header"><strong><span id="del-heading">Active Requests</span></strong></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>

	<div style="position: relative; height: auto; width: auto; margin: 0 16px 0 0">
		<table border="0" width="100%" style="table-layout: fixed">
		<tr>
			<td style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</td>
			<td style="width: 25%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>DATE</strong></td>
			<td style="width: 30%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>APPLICATION</strong></td>
			<td style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>TYPE</strong></td>
			<td style="width: 20%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px"><strong>REQUESTER</strong></td>
		</tr>
		</table>
	</div>

	<div id="view-data" style="position: relative; height: auto; width: auto; overflow: scroll">
	<table border="0" width="100%" style="table-layout: fixed">
	<?php
		$sql = sprintf("select a.id, a.entry_date,a.app,b.desc,a.email from #__bug_track a left outer join #__bug_track_types b on (a.request_type = b.id) where a.status = 1 order by a.entry_date");
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) {
			echo "<tr><td width='100%'>No active requests.</td></tr>";
		} else {
			$color1 = '#a5badc';
			$color2 = '#FFFFFF';
			$cnt = 1;
			$result = $dbo->loadObjectList();
			foreach($result as $row) {
				if (($cnt % 2) == 0 ) { $color = $color1; }  else { $color = $color2; }
				echo "<tr>";
				echo "<td style='overflow: hidden; width: 5%;   height: 11px; border: 1px solid ".$color."; background-color: ".$color."; color: #000000'><input type='radio' name='bug_id' id='bug-id' value=".$row->id." onclick='show_entry(this.value)'></td>";
				echo "<td style='overflow: hidden; width: 25%; height: 11px; border: 1px solid ".$color."; background-color: ".$color."; color: #000000'>".$row->entry_date."</td>";
				echo "<td style='overflow: hidden; width: 30%; height: 11px; border: 1px solid ".$color."; background-color: ".$color."; color: #000000'>".$row->app."</td>";
				echo "<td style='overflow: hidden; width: 20%; font-size: 10px; height: 11px; border: 1px solid ".$color."; background-color: ".$color."; color: #000000'>".$row->desc."</td>";
				echo "<td style='overflow: hidden; width: 20%; height: 11px; border: 1px solid ".$color."; background-color: ".$color."; color: #000000'>".$row->email."</td>";
				echo "</tr>";
				++$cnt;
			}
		}

	?>
	</table>
</div>

</div>

</div></div></div></div>
<script type="text/javascript">
function show_entry(id) {
	$('show-record').set('href','index.php?option=com_jumi&fileid=52&id='+id+'&tmpl=component&dt='+new Date().getTime());
	$('show-record').click();
}

window.addEvent('domready',function() {
	var h = window.innerHeight - 380;
	$('search').setStyle('height',h +'px');
	$('view-data').setStyle('height',h-50+'px');
});
</script>