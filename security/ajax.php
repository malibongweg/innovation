<?php
$dbo =& JFactory::getDBO();

if (isset($_GET)) {
	
	if ($_GET['func'] == 'srch') {
		$sql = "select id,name,username,email from #__users where lower(username) like '".strtolower($_GET['user'])."%'";
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$data = array();
		foreach($result as $row){
			$data[] = $row->id.";".$row->username.";".$row->name;
		}
		echo json_encode($data);
	}
	else if ($_GET['func'] == 'reset_reprint') {
		$sql = sprintf("insert into identity.photo_count(uid,cnt,blocked) values (%d,1,0) on duplicate key update blocked = 0",$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
		echo "1";
	}
	else if ($_GET['func'] == 'card_reprint') {
		$sql = sprintf("select blocked,count(*) as cnt from identity.photo_count where uid = %d",$_GET['uid']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		if (is_null($row->blocked)) { $b = 0; } else { $b = $row->blocked; }
		echo $b,";",$row->cnt;
	}
	else if ($_GET['func'] == 'info') {
		$sql = sprintf("select id,name,username,email from #__users where id = %d",$_GET['uid']);
		$dbo->setQuery($sql);
		$row = $dbo->loadRow();
		$data = array();
		$data['id'] = $row[0];
		$data['name'] = $row[1];
		$data['username'] = $row[2];
		$data['email'] = $row[3];
		echo json_encode($data);
	}
	else if($_GET['func'] == 'security') {
		$sql = sprintf("select group_id from #__user_usergroup_map where user_id = %d",$_GET['uid']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObjectList();
		$data = array();
		foreach($row as $item) {
			$data[] = $item->group_id;
		}
		echo json_encode($data);
	}
	else if($_GET['func'] == 'updateSecurity') {
		$sql = sprintf("delete from #__user_usergroup_map where user_id = %d",$_GET['user_id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		foreach($_GET as $key=>$value) {
			if (substr($key,0,3) == 'chk') {
				$sql = sprintf("insert into #__user_usergroup_map (user_id, group_id) values (%d,%d)",$_GET['user_id'],substr($key,3));
				$dbo->setQuery($sql);
				$result = $dbo->query();
			}
		}
	}
	else if($_GET['func'] == 'faculty') {
		$sql = sprintf("SELECT mas.func_get_assigned_faculty(%d)",$_GET['uid']);
		$dbo->setQuery($sql);
		$row = $dbo->loadResult();
		$data = array();
			$data[] = $row;
		echo json_encode($data);
	}
	else if($_GET['func'] == 'masremove') {
		$sql = sprintf("delete from mas.faculty_admin where uid=%d",$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
		$sql = sprintf("CALL proc_remove_mas_admin(%d)",$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if($_GET['func'] == 'getfac') {
		$sql = sprintf("select fac_code,fac_desc from structure.faculty where fac_active='Y' order by fac_desc");
		$dbo->setQuery($sql);
		$row = $dbo->loadObjectList();
		$data = array();
		foreach($row as $item) {
			$data[$item->fac_code] = $item->fac_desc;
		}
		echo json_encode($data);
	}
	else if($_GET['func'] == 'setfac') {
		$sql = sprintf("CALL mas.proc_set_fac_admin(%d,'%s')",$_GET['fuid'],$_GET['faculty']);
		echo $sql;
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if($_GET['func'] == 'getassignedfac') {
		$data = array();
		$sql = sprintf("select faculty from mas.faculty_admin where uid = %d",$_GET['uid']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) {
			$data['cnt'] = 0;
		} else {
			$row = $dbo->loadObject();
			$data['cnt'] = 1;
			$data['Record'] = $row;
		}
		echo json_encode($data);
	}
	else if($_GET['func'] == 'fav') {
		$html = '';
		$dbo->setQuery("select app_name,url from #__user_default_app where uid = ".$_GET['uid']);
		$dbo->query();
		if ($dbo->getNumRows() > 0) {
				$row = $dbo->LoadObjectList();
				foreach($row as $item) {
						$html .= '<a href="'.$item->url.'"><img src="/images/default.png" width="16" height="16" border="0" /> '.$item->app_name.'</a><br />'; 
				}	
		} else {
			$html .= 'Default Application: None<br />';
		}	
		
		$dbo->setQuery("select fav_app_url,app_name from #__user_fav_apps where uid = ".$_GET['uid']." order by dt_added desc");
		$dbo->query();
		if ($dbo->getNumRows() > 0) {	
				$row = $dbo->LoadObjectList();
				foreach($row as $item) {
						$html .= '<a href="'.$item->fav_app_url.'"><img src="/images/fav2.png" width="16" height="16" border="0"/> '.$item->app_name.'</a><br />'; 
				}			
		} else {
			$html .= 'Favorites: None<br />';
		}
		echo $html;
	}
	else if($_GET['func'] == 'add_fav') {
		$sql = sprintf("CALL proc_save_favs(%d,'%s','%s')",$_GET['uid'],$_GET['appname'],$_GET['url']);
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if($_GET['func'] == 'add_default') {
		$sql = sprintf("CALL proc_set_default_app(%d,'%s','%s')",$_GET['uid'],$_GET['appname'],$_GET['url']);
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if($_GET['func'] == 'display_fleet') {
		$dbo->setQuery("select id,uid,campus,grp_id from #__fleet_access where uid = ".$_GET['uid']);
		$dbo->query();
			if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
		$row = $dbo->loadObjectList();
		$Record = array();
		$record['data'] = $row;
		echo json_encode($record);
		//echo $row->id.";".$row->uid.";".$row->campus.";".$row->grp_id;
	}
	else if($_GET['func'] == 'display_fleet_campus') {
		$data = array();
		$dbo->setQuery("select a.campus_code,a.campus_name from structure.campus a order by a.campus_name");
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->campus_code.";".$row->campus_name;
		}
		echo json_encode($data);
	}
	else if($_GET['func'] == 'remove_fleet') {
		$dbo->setQuery("delete from #__fleet_access where uid = ".$_GET['uid']);
		$result = $dbo->query();
			if (!$result) { echo "-1"; exit(); }
		$dbo->setQuery("delete from #__user_usergroup_map where #__user_usergroup_map.user_id = ".$_GET['uid']." and #__user_usergroup_map.group_id = 28");
		$result = $dbo->query();
			if (!$result) { echo "-2"; exit(); }
		echo "1";
	}
	else if($_GET['func'] == 'save_fleet_pre') {
		$dbo->setQuery("delete from #__fleet_access where uid = ".$_GET['uid']);
		$result = $dbo->query();
			if (!$result) { echo "-1"; exit(); }
		$dbo->setQuery("delete from #__user_usergroup_map where #__user_usergroup_map.user_id = ".$_GET['uid']." and #__user_usergroup_map.group_id = 28");
		$result = $dbo->query();
			if (!$result) { echo "-2"; exit(); }
	}
	else if($_GET['func'] == 'save_fleet') {
		$sql = sprintf("insert into cput_fleet_access(uid,campus,grp_id) values (%d,%d,28)",$_GET['uid'],$_GET['cmp']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) { echo "-3"; exit(); }
	}
	else if($_GET['func'] == 'save_fleet_post') {
		$sql = sprintf("insert into cput_user_usergroup_map (user_id,group_id) values (%d,28)",$_GET['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) { echo "-4"; exit(); }
		echo "1";
	}
	else if($_GET['func'] == 'display_claims') {
		$dbo->setQuery("select a.id,a.system_name from itsclaim.systems a left join itsclaim.opa_users b on (a.id = b.system_id)
						where b.uid = ".$_GET['uid']);
		$dbo->query();
		$return = array();
		if ($dbo->getNumRows() == 0) {
			$return['code'] = -1;
			$return['system_id'] = 0;
		} else {
			$row = $dbo->loadObject();
			$return['code'] = 1;
			$return['system_id'] = $row->id;
		}
		echo json_encode($return);
	}
	else if($_GET['func'] == 'listSystems') {
		$dbo->setQuery("select a.id,a.system_name from itsclaim.systems a order by a.system_name");
		$dbo->query();
		$return = array();
		if ($dbo->getNumRows() == 0) {
			$return['code'] = -1;
			$return['system_id'] = 0;
		} else {
			$row = $dbo->loadObjectList();
			$return['code'] = 1;
			$return['system_id'] = $row;
		}
		echo json_encode($return);
	}
	else if($_GET['func'] == 'save_system') {
		$dbo->setQuery("delete from itsclaim.opa_users where uid = ".$_GET['uid']);
		$result = $dbo->query();
			if (!$result) { echo "-1"; exit(); }

		$sql = sprintf("insert into itsclaim.opa_users (uid,system_id) values (%d,%d)",$_GET['uid'],$_GET['sys']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) { echo "-3"; exit(); }
		echo "1";
	}
}
exit();
?>
