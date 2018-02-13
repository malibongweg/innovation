<?php
$dbo =& JFactory::getDBO();
//require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");

if (isset($_GET)) {

	if ($_GET['action'] == 'displayMenus') {
		$dbo->setQuery('select id,id as id_title,url_link,disaster_link from cput_its_alt_link order by id');
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		//$return['TotalRecordCount'] = $nr;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createMenus') {
		$sql = sprintf("insert into cput_its_alt_link (id,url_link,disaster_link) values (%d,'%s','%s')",$_POST['id'],$_POST['url_link'],$_POST['disaster_link']);
		$dbo->setQuery($sql);
		$res = $dbo->query();
		
		$sql = sprintf("select id,id as id_title,url_link,disaster_link from cput_its_alt_link where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateMenus') {
		$sql = sprintf("update cput_its_alt_link set url_link='%s',disaster_link = '%s' where id = %d",$_POST['url_link'],$_POST['disaster_link'],$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteMenus') {
		$sql = sprintf("delete from cput_its_alt_link where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listMenuTitles') {
		$return = array();
		$dbo->setQuery('select id,title from cput_menu where published = 1');
		$result = $dbo->loadObjectList();
		$return['Result'] = 'OK';
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->title;
			$return['Options'][$i]['Value'] = $row->id;
			++$i;
		}
		echo json_encode($return);
	}
}

exit();



?>
