<?php
$dbo = &JFactory::getDBO();

if (isset($_GET)) {

	if ($_GET['action'] == "browseUsers") {
		$sql = sprintf("select id,uid,expire_date from cput_idcard_users order by expire_date desc");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		echo json_encode($return);		
	}
	else if ($_GET['action'] == 'listUsers') {
		$sql = sprintf("select id,concat(name,' [',username,']') as u_name from cput_users order by name");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return['Result'] = 'OK';
		$i = 0;
		foreach ($result as $row) {
			$return['Options'][$i]['DisplayText'] = $row->u_name;
			$return['Options'][$i]['Value'] = $row->id;
			++$i;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'usersDelete') {
		$sql = sprintf("delete from cput_idcard_users where uid = %d",$_POST['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		
		$sql = sprintf("delete from cput_user_usergroup_map where user_id = %d and group_id = 31",$_POST['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'usersNew') {
		$sql = sprintf("insert into cput_user_usergroup_map (user_id,group_id) values (%d,31)",$_POST['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
		
		$sql = sprintf("insert into cput_idcard_users (uid,expire_date) values (%d,'%s')",$_POST['uid'],$_POST['expire_date']);
		$dbo->setQuery($sql);
		$dbo->query();
		$lstid = $dbo->insertid();
		$sql = sprintf("select id,uid,expire_date from cput_idcard_users where id = %d",$lstid);
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}

}
exit();

?>

