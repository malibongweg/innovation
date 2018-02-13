<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

if (isset($_GET)) {
	if ($_GET['action'] == 'loadPage'){
		$pg = intval($_GET['page']);
		$return = array();
		$sql = sprintf("select a.item_code from auction.items a where a.login = '%s'",$_GET['login']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() > 0){
			$item = $dbo->loadResult();
			$return['item'] = $item;
		} else {
			$return['item'] = '';
		}
		$sql = sprintf("select a.id,a.item_code,a.description,a.reserved,a.bid_amount from auction.items a limit %d,12",$pg);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() < 12){
			$return['Foward'] = "disabled";
		} else {
			$return['Foward'] = "enabled";
		}
		if ($pg == 0){
			$return['Back'] = "disabled";
		} else {
			$return['Back'] = "enabled";
		}
		$result = $dbo->loadObjectList();
		$return['Records'] = $result;
		echo json_encode($return);
	}
	if ($_GET['action'] == 'checkBid'){
	$return = array();
		$sql = sprintf("select a.item_code from auction.items a where a.login = '%s'",$_GET['login']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() > 0){
			$item = $dbo->loadResult();
			$return['item'] = $item;
		} else {
			$return['item'] = '';
		}
		echo json_encode($return);
	}
	if ($_GET['action'] == 'saveBid'){
		$return = array();
		//Check if you are the last bid user///
		$uname = explode('[',$_POST['username']);
		$uname = trim($uname[0]);
		$sql = sprintf("select item_code,login from auction.items where login = '%s'",$_POST['uname']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() > 0){
			$row = $dbo->loadObject();
			$last_bidder = $row->login;
			if ($uname == trim($last_bidder)){
				$return['high'] = 1;
				$return['item'] = $row->item_code;
				echo json_encode($return);
				exit();
			}
		}
		$sql = sprintf("select bid_amount,reserved from auction.items where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$amount = $row->bid_amount;
		$reserve = $row->reserved;
		if (floatval($amount) == 0) {
			$amount = $reserve;
		}
		$amount = $amount + 50;
		$sql = sprintf("update auction.items set bid_amount = %0.2f,userid='%s',username='%s' where id = %d",$amount,$_POST['uid'],$_POST['username'],$_POST['id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$sql = sprintf("select a.id,a.item_code,a.description,a.reserved,a.bid_amount from auction.items a where a.id = %d",$_POST['id']);
		$return['id'] = $_POST['id'];
		$return['amount'] = "R".number_format($amount,2,".",",");
		$return['high'] = 0;
		echo json_encode($return);
	}
	if ($_GET['action'] == 'bidTime'){
		$return = array();
		$sql = sprintf("select a.bidding_start,a.bidding_stop,a.status from auction.config a where a.id = 1");
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$return['start'] = $row->bidding_start;
		$return['stop'] = $row->bidding_stop;
		$return['status'] = $row->status;
		echo json_encode($return);

	}
exit();
}
