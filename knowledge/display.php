<?php
defined('_JEXEC') or die('Restricted access');
$dbo = &JFactory::getDBO();
$doc = & JFactory::getDocument();
$doc->addScript("templates/opa/css/global.css");
$doc->addScript("templates/system/css/system.css");
$qid = substr($_GET['qid'],0,-1);
$ids = split(";",$qid);
foreach($ids as $value){
	$sql = sprintf("select id,question from #__knowledge_base where id = %d",$value);
	$dbo->setQuery($sql);
	$result = $dbo->loadObjectList();
	echo "<div style='width: auto; height: 100%; font-size: 12px; font-family: sans-serif verdana arial'>";
	foreach($result as $row){
		echo "<ul>";
		echo "<li style='font-weight: bold; color: red'>".$row->question."</li>";
				$sql = sprintf("select answer from #__knowledge_answers where q_id = %d",$row->id);
				$dbo->setQuery($sql);
				$res = $dbo->loadObjectList();
				echo "<ul>";
				foreach($res as $row2){
					echo "<li style='color: navy'>";
					echo $row2->answer;
					echo "</li>";
				}
				echo "</ul>";
		echo "</ul>";
	}
}
?>
</div>