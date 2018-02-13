<?php
$dbo =& JFactory::getDBO();

if (isset($_GET['action'])) {
	
	if ($_GET['action'] == 'scroll_msg') {
		echo '<table border="0" width="100%">';
		echo '<tr>';
		echo '<td width="10%" style="font-weight: bold">Msg ID#</td>';
		echo '<td width="70%" style="font-weight: bold">Message Text</td>';
		echo '<td width="10%" style="font-weight: bold">Color</td>';
		echo '<td width="10%" style="font-weight: bold">Active</td>';
		echo '</tr>';
		$dbo->setQuery("select id,msg,color,active from #__scroll_messages order by id");
		$rows = $dbo->LoadObjectList();
		foreach($rows as $items) {
		echo '<tr>'."\n";
		echo '<td width="10%"><input type="radio" name="id" id="id" value="'.$items->id.'">&nbsp;</td>'."\n";
		echo '<td width="70%">'.$items->msg.'</td>'."\n";
		echo '<td width="10%" style="background-color:'.$items->color.'">&nbsp</td>'."\n";
		switch ($items->active) {
			case 0: echo '<td width="10%"><input type="checkbox"></td>'; break;
			case 1: echo '<td width="10%"><input type="checkbox" checked></td>'; break;
		}
		echo '</tr>'."\n";
		}
		echo '</table>';	
		}
		else if ($_GET['action'] == 'save_msg') {
			if ($_GET['rec_action'] == 'new') {
				if (!isset($_GET['txt_active'])) $active = 0; else $active = 1;
				$sql = sprintf("insert into #__scroll_messages(msg,color,active) values('%s','%s',%d)",$_GET['txt_msg'],$_GET['txt_color'],$active);
				$dbo->setQuery($sql);
				$dbo->query();
			} else if ($_GET['rec_action'] == 'edit') {
				if (!isset($_GET['txt_active'])) $active = 0; else $active = 1;
				$sql = sprintf("update #__scroll_messages set msg='%s',color='%s',active=%d where id=%d",$_GET['txt_msg'],$_GET['txt_color'],$active,$_GET['rec_id']);
				$dbo->setQuery($sql);
				$dbo->query();
			}
		}
		else if ($_GET['action'] == 'get_message') {
			$sql = sprintf("select id,msg,color,active from #__scroll_messages where id=%d",$_GET['id']);
			$dbo->setQuery($sql);
			$row = $dbo->LoadObjectList();
			$data = array();
			foreach($row as $item) {
				$data['id'] = $item->id;
				$data['msg'] = $item->msg;
				$data['color'] = $item->color;
				$data['active'] = $item->active;
			}
			echo json_encode($data);
			}
exit;
}
?>