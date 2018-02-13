<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

if (isset($_GET)) {
	if ($_GET['action'] == 'loadProducts'){
		$data = array();
		$dbo->setQuery("select a.id,a.`desc`,a.price,a.amount_left from assets.auction_stock a order by a.`desc`");
		$result = $dbo->loadObjectList();
		$data['Records'] = $result;
		echo json_encode($data);

	} else if ($_GET['action'] == 'checkUser'){
		$data = array();
		$uid = $_GET['uid'];
		$sql = sprintf("select count(1) as cnt from portal.cput_users a where a.id = %d and a.id not in (select uid from assets.auction_bids b)",$uid);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$data['Record'] = $result;
		echo json_encode($data);

	} else if ($_GET['action'] == 'saveBid'){
		$data = array();
		$uid = $_GET['uid'];
		$bidItem = $_GET['biditem'];
		$ip = $_SERVER['REMOTE_ADDR'];

		$sql = sprintf("select a.id,a.name,a.username,b.userid as staffno from portal.cput_users a left outer join portal.cput_users_cellular b on (lower(a.username) = lower(b.login))
			where a.id = %d",$uid);
		$dbo->setQuery($sql);
		$person = $dbo->loadObject();

		$sql = sprintf("insert into assets.auction_bids (staffno,bid_item,remote_ip,username,login_name,uid) values (%d,%d,'%s','%s','%s',%d)",$person->staffno,$bidItem,$ip,$person->name,$person->username,$uid);
		$dbo->setQuery($sql);

		$result = $dbo->query($sql);
		if (!$result) {
			$data['Result']['code'] = 0;
		} else {
			$sql = sprintf("select amount_left from assets.auction_stock where id = %d",$bidItem);
			$dbo->setQuery($sql);
			$cnt = $dbo->loadResult();
			if (intval($cnt) > 0){
				$sql = sprintf("update assets.auction_stock a set a.amount_left = a.amount_left -1 where a.id = %d",$bidItem);
				$dbo->setQuery($sql);
				$dbo->query();
				$data['Result']['code'] = 1;
			} else {
				$data['Result']['code'] = 1;
			}
		}
		echo json_encode($data);
	} else if ($_GET['action'] == 'displayPreviousBid'){
		$data = array();
		$uid = $_GET['uid'];
		$sql = sprintf("select a.bid_time,a.username,b.`desc`,b.price from assets.auction_bids a left outer join assets.auction_stock b on (a.bid_item=b.id)
			where a.uid = %d",$uid);
		$dbo->setQuery($sql);
		$item = $dbo->loadObject();
		$data['Result'] = $item;
		echo json_encode($data);
	}
exit();
}
