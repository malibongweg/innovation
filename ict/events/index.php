<?php
JHTML::_('behavior.mootools');
/*$fname = $_SERVER['DOCUMENT_ROOT'];
require_once($fname."/configuration.php");
$jconfig = new JConfig();
$con = mysql_connect($jconfig->host,$jconfig->user,$jconfig->password);
if (!$con) { echo "-1"; exit(); }
mysql_select_db($jconfig->db,$con);*/

$dbo = &JFactory::getDBO();
?>
<img src="/images/events_module.png" width="139" height="50" style="border: 1px solid #bebebe">
<div class="rnd-borders" style="width: auto; min-height: 50px; max-height: 350px; text-align: left; background-color: #f1f2ef; overflow: auto">
<?php
$sql = "select id, day(start_date) as d,date_format(start_date,'%M') as m,event_description from cput_events where date(now()) >= date(publish_date) and date(now()) <= date(end_date) order by start_date";
$dbo->setQuery($sql);
$dbo->query();
	if ($dbo->getNumRows() == 0) {
		echo '<div class="opacity-rnd-borders" style="width: auto; min-height: 50px; max-height: 150px; text-align: left; padding: 0; background-color: #f1f2ef; border: 1px solid #bcbcbc; margin: 0 0 3px 0">';
		echo "<p style='text-align: center; text-weight: bold'>Event calender empty.</p>";
		echo "</div>";
	} else {
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			echo '<div  style="position: relative; display: block; width: auto; min-height: 50px; max-height: 150px; text-align: left; padding: 1px; margin: 3px 3px 0 0; background-color: #d6e3f4; border: 1px solid #8ab1df">';
			echo "<div style='float: left; width: 33px; height: auto'>";
			echo "<img src='/images/cal.png' width='30' height='45' border='0' alt=''>";
			echo "<div style='position: absolute; font-size: 11px; top: 16px; left: 3px; width: 30px; text-align: center; height: 35px; color: #000000'>";
			echo "<span style='color: #000000; font-weight: bold'>".$row->d."</span></div>";
			echo "<div style='position: absolute; font-size: 11px; top: 26px; left: 2px; width: 30px; text-align: center; height: 35px; color: #000000'>";
			echo "<span style='color: #000000; font-weight: bold'>".substr($row->m,0,3)."</span></div></div>";
			echo "<div id='ev-info' style='float: left; width: 80px; height: auto'><a href='index.php?option=com_jumi&fileid=94&tmpl=component&id=".$row->id."' class='modalizer' id='text-link'>".$row->event_description."</a></div>";
			echo "<div style='clear: both'></div></div>";
		}
	}

?>
</div>

