<?php
/*$fname = $_SERVER['DOCUMENT_ROOT'];
require_once($fname."/configuration.php");
$jconfig = new JConfig();
$con = mysql_connect($jconfig->host,$jconfig->user,$jconfig->password);
if (!$con) { echo "-1"; exit(); }
mysql_select_db($jconfig->db,$con); #ceffce*/
$dbo = &JFactory::getDBO();
?>
<img src="/images/cts_alerts.png" width="160" height="50" style="border: 1px solid #bcbcbc">
<div id="fmsg" style="width: auto; height: auto; min-height: 100px; max-height: 500px; position: relative; overflow: auto; font-size: 10px">

<?php
		$sql = "select a.id,a.failure_date,a.failure_message,c.color from cput_service_failure a 
		left outer join cput_service_failure_severity c on (a.severity_code = c.id)
		where year(a.failure_date) = year(now()) and a.current_state = 0 order by failure_date desc";

		//$sql = "select a.id,a.failure_date,a.failure_message,b.color from cput_service_failure a left outer join cput_service_failure_severity b
		//on (a.severity_code = b.id) where a.current_state = 0 order by failure_date desc";
		$dbo->setQuery($sql);
		$dbo->query();
		//$result = mysql_query($sql);
		echo '<div  style="width: auto; min-height: 122px; max-height: 500px; text-align: left; padding: 3px 3px; background-color: #f1f2ef; margin: 0 0 3px 0">';
		echo "<p style='font-size: 12px; text-align: center; color: #7d7d7d'>Click on link for details...</p>";
		if ($dbo->getNumRows() == 0) { 
			echo "<p style='font-weight: bold; font-size: 12px; text-align: center;'>No service failure alerts.</p>";
		} 
		else {
			$result = $dbo->loadObjectList();
			foreach($result as $row) {
				echo "<div style='padding: 3px; background-color: ".$row->color."; text-align: center; font-size: 9px; font-weight: normal; border: 1px solid #818181; margin-bottom: 3px'><a href='/scripts/system/failure_details.php?id=".$row->id."' class='modalizer'><b>".$row->failure_date."</b><br />".$row->failure_message."</i></a>";
				$sql = sprintf("select a.action_taken from cput_service_failure_resolve a where a.id = %d order by a.seq limit 1",$row->id);
				$dbo->setQuery($sql);
				$dbo->query();
				echo "<div style='position: relative; width: auto; height: auto; border: 1px solid #007b00; background-color: #c4ffc4'>";
				echo "<div style='position: relative; border-bottom: 1px solid #007b00; padding: 2px; width: auto; height: auto; background-color: #ffffff'>";
				echo "Current Status";
				echo "</div>";
						if ($dbo->getNumRows() == 0) {
							echo "Not available.";
						} else {
							$r = $dbo->loadObject();
							echo $r->action_taken;
						}
				echo "</div>";
				echo "</div>";
			}
			
		}
	
?>
</div>
</div>