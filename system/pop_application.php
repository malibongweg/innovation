<?php
defined('_JEXEC') or die('Restricted access');
$doc = & JFactory::getDocument();
$dbo =& JFactory::getDBO();
?>
<div style='width: auto; height: auto; position: relative; text-align: center; background-color: #D8D9D3; border: 1px solid #CACACA; overflow: hidden; display: block'>
<?php
	$dbo->setQuery("select app_name,cnt from #__pop_application group by app_name order by cnt desc limit 3");
	$dbo->query();
	if ($dbo->getNumRows() == 0) {
		echo "<div style='width: auto; height: auto; position: relative; text-align: center;  background-color: #D8D9D3'>";
			echo "No data...";
			echo "</div>";
	} else {
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			echo '<div class="rnd-borders" style="width: auto; height: auto; text-align: center; padding: 3px 3px; background-color: #f6f6f6; border: 1px solid #bcbcbc">';
			echo $row->app_name;
			echo "</div>";
		}
	}
?>
</div>