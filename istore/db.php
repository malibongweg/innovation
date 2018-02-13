<?php
$dbo =& JFactory::getDBO();
require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");

if (isset($_GET)) {

	if ($_GET['action'] == 'browseAdmin') {
		$dbo->setQuery("select distinct count(store_id) as cnt from istore.istores");
		$nr = $dbo->loadResult();
		$sql = sprintf("select istore.istores.store_id,istore.istores.store_name from istore.istores order by %s limit %d,%d",$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $nr;
		echo json_encode($return);

	}
	else if ($_GET['action'] == 'displayControllers') {
		$sql = sprintf("select istore_controllers.id,istore_controllers.uid,istore_controllers.store_id,portal.cput_users.name as uname
					from istore.istore_controllers left outer join portal.cput_users on (istore_controllers.uid = portal.cput_users.id)
					where istore_controllers.store_id=%d",$_GET['storeid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		//$return['TotalRecordCount'] = 0;
		echo json_encode($return);

	}
	else if ($_GET['action'] == 'listUsers') {
		$return = array();
		$data = array();
		$dbo->setQuery("select id,upper(name) as fullname from portal.cput_users where substring(portal.cput_users.username,1,1) regexp '[A-Za-z]+' order by name");
		//$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname) as fullname from staff.staff a where a.staff_resign > current_date order by a.staff_sname");
		$result = $dbo->loadObjectList();
		$i = 0;
		foreach($result as $row){
				$return['Options'][$i]['DisplayText'] = $row->fullname;
				$return['Options'][$i]['Value'] = $row->id;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listUsersStaff') {
		$return = array();
		$data = array();
		//$dbo->setQuery("select id,upper(name) as fullname from portal.cput_users where substring(portal.cput_users.username,1,1) regexp '[A-Za-z]+' order by name");
		$dbo->setQuery("select a.staff_no,concat(a.staff_sname,', ',a.staff_fname) as fullname from staff.staff a where a.staff_resign > current_date order by a.staff_sname");
		$result = $dbo->loadObjectList();
		$i = 0;
		foreach($result as $row){
				$return['Options'][$i]['DisplayText'] = $row->fullname;
				$return['Options'][$i]['Value'] = $row->staff_no;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listControllers') {
		$return = array();
		$dbo->setQuery("select id,name from portal.cput_users where substring(portal.cput_users.username,1,1) regexp '[A-Z..a-z]+' order by name");
		$result = $dbo->loadObjectList();
		$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->name;
				$return['Options'][$i]['Value'] = $row->id;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listCostCentres') {
		$return = array();
		$dbo->setQuery("select distinct cc_code,cc_name from budgets.cost_codes_single order by cc_code");
		$result = $dbo->loadObjectList();
		$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->cc_code.' - '.$row->cc_name;
				$return['Options'][$i]['Value'] = $row->cc_code;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'popCostCodes') {
		$data = array();
		$sql = sprintf("select distinct istore.istore_orders.cost_centre,budgets.cost_codes_single.cc_name from istore.istore_orders
				left outer join budgets.cost_codes_single on (istore.istore_orders.cost_centre = budgets.cost_codes_single.cc_code)
			where istore.istore_orders.store_id = %d",$_GET['storeid']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		echo json_encode($result);
	}
	else if ($_GET['action'] == 'listAccountCodes') {
		$return = array();
		$dbo->setQuery("select distinct fcdacc,fcdname1 from budgets.account_codes where for_year = year(now())
						and fcdacc between 40000 and 49999 order by fcdacc");
		$result = $dbo->loadObjectList();
		$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->fcdacc.' - '.$row->fcdname1;
				$return['Options'][$i]['Value'] = $row->fcdacc;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listStockItems') {
		$return = array();
		$dbo->setQuery("select istore.istore_items.item_id,istore.istore_items.item_desc,istore.istore_items.unit_price,istore.istore_items.item_id
					from istore.istore_items where istore.istore_items.istores_store_id = ".$_GET['storeid']);
		$result = $dbo->loadObjectList();
		$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->item_desc;
				$return['Options'][$i]['Value'] = $row->item_id;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'listStatus') {
		$return = array();
		if (isset($_GET['status'])) {
			$dbo->setQuery("select istore.istore_order_status.id,istore.istore_order_status.`status`
		  from istore.istore_order_status where istore.istore_order_status.id = ".$_GET['status']);
		} else {
		$dbo->setQuery("select istore.istore_order_status.id,istore.istore_order_status.`status`
		  from istore.istore_order_status order by istore.istore_order_status.id");
		}
		$result = $dbo->loadObjectList();
		$i = 0;
			foreach ($result as $row) {
				$return['Options'][$i]['DisplayText'] = $row->status;
				$return['Options'][$i]['Value'] = $row->id;
				++$i;
			}
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createStore') {
		$sql = sprintf("insert into istore.istores (store_name,finance_controller_id) values ('%s',%d)",$_POST['store_name'],$_POST['finance_controller_id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$id = $dbo->insertid();
		$sql = sprintf("select istore.istores.store_id,istore.istores.store_name,istore.istores.finance_controller_id,portal.cput_users.name
		from istore.istores left outer join portal.cput_users on (istore.istores.finance_controller_id = portal.cput_users.id) where istore.istores.store_id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateStore') {
		$sql = sprintf("update istore.istores set store_name = '%s' where store_id = %d",$_POST['store_name'],$_POST['store_id']);
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteStore') {
		$sql = sprintf("delete from istore.istores where istore.istores.store_id = %d",$_POST['store_id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createController') {
		$sql = sprintf("insert into istore.istore_controllers (uid,store_id) values (%d,%d)",$_POST['uid'],$_POST['store_id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$sql = sprintf("select istore_controllers.id,istore_controllers.uid,istore_controllers.store_id,portal.cput_users.name as uname
					from istore.istore_controllers left outer join portal.cput_users on (istore_controllers.uid = portal.cput_users.id)
					where istore_controllers.id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteController') {
		$sql = sprintf("delete from istore.istore_controllers where istore.istore_controllers.id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'displayOwners') {
		$sql = sprintf("select istore.istore_owners.id,istore.istore_owners.istores_store_id,istore.istore_owners.uid,portal.cput_users.name as fullname
				from istore.istore_owners
				left outer join portal.cput_users on (istore.istore_owners.uid = portal.cput_users.id)
				where istore.istore_owners.istores_store_id = %d",$_GET['storeid']);
		$dbo->setQuery($sql);
		$dbo->query();
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createOwner') {
		$sql = sprintf("insert into istore.istore_owners (uid,istores_store_id) values (%d,%d)",$_POST['uid'],$_POST['istores_store_id']);
		$dbo->setQuery($sql);
		$dbo->query();
		//$id = $dbo->insertid();
		$sql = sprintf("select istore.istore_owners.id,istore.istore_owners.istores_store_id,istore.istore_owners.uid,portal.cput_users.name
				from istore.istore_owners
				left outer join portal.cput_users on (istore.istore_owners.uid = portal.cput_users.id)
				where istore.istore_owners.id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteOwner') {
		$sql = sprintf("delete from istore.istore_owners where istore.istore_owners.id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'checkStore') {
		$sql = sprintf("select istore.istore_owners.istores_store_id,istore.istore_owners.uid,istore.istores.store_name
		from istore.istore_owners
		left outer join istore.istores on (istore.istore_owners.istores_store_id = istore.istores.store_id) where istore.istore_owners.uid = %d",$_GET['uid']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$store = $row->istores_store_id;
		if (is_null($store) || strlen($store) == 0) { $store = 0; }
		echo $store.";".$row->uid.";".$row->store_name;
	}
	else if ($_GET['action'] == 'browseItems') {
		$dbo->setQuery("select count(istore.istore_items.item_id) as cnt from istore.istore_items where istore.istore_items.istores_store_id = %d",$_GET['storeid']);
		$nr = $dbo->loadResult();
		$sql = sprintf("select istore.istore_items.item_id,istore.istore_items.istores_store_id,istore.istore_items.item_desc,
			istore.istore_items.unit_price,istore.istore_items.onhand,istore.istore_items.account_code
			from istore.istore_items where istore.istore_items.istores_store_id = %d order by istore.istore_items.item_desc",$_GET['storeid']);
		$dbo->setQuery($sql);
		$dbo->query();
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $nr;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createItem') {
		$sql = sprintf("select concat(substring(replace(istore.istores.store_name,' ',''),1,4),'-') as cde
		from istore.istores where istore.istores.store_id = %d",$_POST['istores_store_id']);
		$dbo->setQuery($sql);
		$cde = $dbo->loadResult();

		$sql = sprintf("insert into istore.istore_items (item_id,istores_store_id,item_desc,onhand,unit_price,account_code) values ('%s',%d,'%s',%0.2f,%0.2f,%d)",
		$cde.$_POST['item_id'],$_POST['istores_store_id'],$_POST['item_desc'],$_POST['onhand'],$_POST['unit_price'],$_POST['account_code']);
		$dbo->setQuery($sql);
		$dbo->query();
		//$id = $dbo->insertid();
		$sql = sprintf("select istore.istore_items.item_id,istore.istore_items.istores_store_id,istore.istore_items.item_desc,
			istore.istore_items.unit_price,istore.istore_items.onhand
			from istore.istore_items where istore.istore_items.item_id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateItem') {
		$sql = sprintf("update istore.istore_items set item_desc = '%s', onhand = %0.2f, unit_price = %0.2f, account_code = %d where item_id = '%s'",
		$_POST['item_desc'],$_POST['onhand'],$_POST['unit_price'],$_POST['account_code'],$_POST['item_id']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteItem') {
		$sql = sprintf("delete from istore.istore_items where istore.istore_items.item_id = '%s'",$_POST['item_id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'browseOrders') {
		$sql = sprintf("select count(istore.istore_orders.order_no) from istore.istore_orders where istore.istore_orders.store_id = %d",$_GET['storeid']);
		$dbo->setQuery($sql);
		$nr = $dbo->loadResult();
		$sql = sprintf("select distinct istore.istore_orders.order_no as recid, istore.istore_orders.store_id,istore.istore_orders.order_no,istore.istore_orders.order_date,istore.istore_orders.service_date,istore.istore_orders.cost_centre,
		istore.istore_orders.istore_order_status_id,istore.istore_orders.client_uid,istore.istore_orders.creator_uid,istore.istore_orders.comments,
		concat(staff.staff.staff_title,' ',staff.staff.staff_init,' ',staff.staff.staff_sname) as clientname,
		concat (budgets.cost_codes_single.cc_code,'-',budgets.cost_codes_single.cc_name) as costcode
		from istore.istore_orders left outer join staff.staff on (istore.istore_orders.client_uid=staff.staff.staff_no) 
		left outer join budgets.cost_codes_single on (istore.istore_orders.cost_centre = budgets.cost_codes_single.cc_code) where istore.istore_orders.store_id = %d order by %s limit %d,%d",$_GET['storeid'],$_GET['jtSorting'],$_GET['jtStartIndex'],$_GET['jtPageSize']);
		$dbo->setQuery($sql);
		$dbo->query();
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $nr;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'createOrder') {
		$order_no = substr($_GET['sn'],0,3);
		$dbo->setQuery("select max(substr(order_no,4)) from istore.istore_orders where store_id = ".$_POST['store_id']);
		$result = $dbo->loadResult();
		$cnt = intval($result)+1;
		$order_no = $order_no . (string)sprintf('%04d',$cnt);

		$sql = sprintf("insert into istore.istore_approvals (order_no,cde) values ('%s',md5('%s'))",$order_no,$order_no);
		$dbo->setQuery($sql);
		$dbo->query();

		$sql = sprintf("insert into istore.istore_orders (store_id,order_no,order_date,service_date,cost_centre,client_uid,creator_uid,istore_order_status_id,comments) values (%d,'%s','%s','%s','%s',%d,%d,%d,'%s')",$_POST['store_id'],$order_no,$_POST['order_date'],$_POST['service_date'],$_POST['cost_centre'],
			$_POST['client_uid'],$_GET['uid'],$_POST['istore_order_status_id'],$_POST['comments']);
		//echo $sql;
		$dbo->setQuery($sql);
		$dbo->query();
		//$id = $dbo->insertid();
		//$sql = sprintf("select distinct istore.istore_orders.store_id,istore.istore_orders.order_no,istore.istore_orders.order_date,istore.istore_orders.service_date,istore.istore_orders.cost_centre,
		//istore.istore_orders.istore_order_status_id,istore.istore_orders.client_uid,istore.istore_orders.creator_uid,istore.istore_orders.comments
		//from istore.istore_orders where istore.istore_orders.order_no = '%s'",$order_no);
		$sql = sprintf("select distinct istore.istore_orders.order_no as recid, istore.istore_orders.store_id,istore.istore_orders.order_no,istore.istore_orders.order_date,istore.istore_orders.service_date,istore.istore_orders.cost_centre,
		istore.istore_orders.istore_order_status_id,istore.istore_orders.client_uid,istore.istore_orders.creator_uid,istore.istore_orders.comments,
		concat(staff.staff.staff_title,' ',staff.staff.staff_init,' ',staff.staff.staff_sname) as clientname,
		concat (budgets.cost_codes_single.cc_code,'-',budgets.cost_codes_single.cc_name) as costcode
		from istore.istore_orders left outer join staff.staff on (istore.istore_orders.client_uid=staff.staff.staff_no) 
		left outer join budgets.cost_codes_single on (istore.istore_orders.cost_centre = budgets.cost_codes_single.cc_code) where istore.istore_orders.order_no = '%s'",$order_no);
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateOrder') {
		$sql = sprintf("update istore.istore_orders set client_uid = %d, cost_centre='%s',comments='%s' where istore.istore_orders.order_no = '%s'",
		$_POST['client_uid'],$_POST['cost_centre'],$_POST['comments'],$_POST['order_no']);
		$dbo->setQuery($sql);
		$dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'browseOrderItems') {
		$sql = sprintf("select count(id) from istore.istore_order_items where istore.istore_order_items.istore_orders_order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$nr = $dbo->loadResult();
		$sql = sprintf("select istore.istore_order_items.id,istore.istore_order_items.istore_orders_order_no,istore.istore_order_items.istore_items_item_id,
				istore.istore_order_items.price,istore.istore_order_items.qty,istore.istore_order_items.total_price,istore.istore_order_items.cc,istore.istore_order_items.acc
				from istore.istore_order_items where istore.istore_order_items.istore_orders_order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$dbo->query();
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Result'] = 'OK';
		$return['Records'] = $result;
		$return['TotalRecordCount'] = $nr;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'getItem') {
		$sql = sprintf("select istore.istore_items.item_id,istore.istore_items.item_desc,istore.istore_items.unit_price
			from istore.istore_items where istore.istore_items.item_id = '%s'",$_GET['id']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		echo $row->item_id.";".$row->item_desc.";".$row->unit_price;
	}
	else if ($_GET['action'] == 'createOrderItem') {
		$sql = sprintf("select istore.istore_items.account_code from istore.istore_items where istore.istore_items.item_id='%s'",$_POST['istore_items_item_id']);
		$dbo->setQuery($sql);
		$acc = $dbo->loadResult();
		$sql = sprintf("insert into istore.istore_order_items (istore_orders_order_no,istore_items_item_id,price,qty,total_price,cc,acc,note)
		values ('%s','%s',%0.2f,%d,%0.2f,'%s',%d,'%s')",$_POST['istore_orders_order_no'],$_POST['istore_items_item_id'],$_POST['price'],$_POST['qty'],$_POST['total_price'],
			$_POST['cc'],$acc,$_POST['note']);
		$dbo->setQuery($sql);
		$dbo->query();
		//$id = $dbo->insertid();
		$sql = sprintf("select istore.istore_order_items.id,istore.istore_order_items.istore_orders_order_no,istore.istore_order_items.istore_items_item_id,
				istore.istore_order_items.price,istore.istore_order_items.qty,istore.istore_order_items.total_price,istore.istore_order_items.cc,istore.istore_order_items.acc
				from istore.istore_order_items where istore.istore_order_items.id = LAST_INSERT_ID()");
		$dbo->setQuery($sql);
		$row = $dbo->loadAssoc();
		$return = array();
		$return['Result'] = 'OK';
		$return['Record'] = $row;
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'deleteOrderItem') {
		$sql = sprintf("delete from istore.istore_order_items where id = %d",$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$return = array();
		$return['Result'] = 'OK';
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'setStatus') {
		$sql = sprintf("update istore.istore_orders set istore_order_status_id = %d where order_no='%s'",$_GET['status'],$_GET['orderno']);
		$dbo->setQuery($sql);
		$dbo->query();
	}
	else if ($_GET['action'] == 'getStatus') {
		$sql = sprintf("select istore_order_status_id from istore.istore_orders where order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$result = $dbo->loadResult();
		echo $result;
	}
	else if ($_GET['action'] == 'sendCompleted') {
		$sql = sprintf("update istore.istore_orders set istore_order_status_id = %d where order_no='%s'",$_GET['status'],$_GET['orderno']);
		$dbo->setQuery($sql);
		$dbo->query();
		$sql = sprintf("select id,email from portal.cput_users where id = %d",$_GET['op']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObject();
		$operator = $result->email;
		$op_id = $result->id;

		$sql = sprintf("insert into istore.istore_audit (orderno,userid,action) values ('%s',%d,'MARK COMPLETED')",$_GET['orderno'],$op_id);
		$dbo->setQuery($sql);
		$dbo->query();

	}
	else if ($_GET['action'] == 'sendMailApproval') {

		$sql = sprintf("select store_id,client_uid,creator_uid,order_no,order_date,cost_centre,comments from istore.istore_orders where order_no='%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$creator_uid = $row->creator_uid;
		$client_uid = $row->client_uid;
		$orderno=$row->order_no;
		$cost_centre = $row->cost_centre;
		$comments = $row->comments;
		$order_date = $row->order_date;
		$storeid = $row->store_id;

		$sql = sprintf("select a.email from portal.cput_users a join istore.istore_controllers b on (a.id = b.uid) where b.store_id = %d",$storeid);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$sendTo = array();
		foreach ($result as $control) {
			$sendTo[] = $control->email;
		}

		$sql = sprintf("select name,email from portal.cput_users where id = %d",$creator_uid);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$requestor = $row->name;
		$req_email = $row->email;

		$sql = sprintf("select name,email from portal.cput_users where id = %d",$client_uid);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$client = $row->name;
		$client_email = $row->email;

		$sql = sprintf("select sum(total_price) from istore.istore_order_items where istore_orders_order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$amount = $dbo->loadResult();

		$sql = sprintf("select cde from istore.istore_approvals where order_no='%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$email_code = $dbo->loadResult();

		$addresses = serialize($sendTo);
		$details = "Cape Peninsula University of Technology.<br />";
		$details .= "<hr>";
		$details .= "Date: ".date('Y-m-d')."<br />";
		$details .= "Requestor: ".$requestor."&nbsp;(".$req_email.")<br />";
		$details .= "Client: ".$client."&nbsp;(".$client_email.")<br />";
		$details .= "<hr>";
		$details .= "Order# ".$orderno."<br />";
		$details .= "Order Date: ".$order_date."<br />";
		$details .= "Cost Code: ".$cost_centre."<br />";
		$details .= "Account Code: ".$account_code."<br />";
		$details .= "Comments: ".$comments."<br />";
		$details .= "The amount of R".$amount." is requested for approval.<br /><br />";
		$details .= "To APPROVE order, click <a href='http://".$_SERVER['SERVER_NAME']."/scripts/istore/email/approve.php?cde=".$email_code."'> === HERE ===</a><br /><br />";
		$details .= "To REJECT order, click <a href='http://".$_SERVER['SERVER_NAME']."/scripts/istore/email/reject.php?cde=".$email_code."'> === HERE === </a><br /><br />";
		$details .= "The status of the order will be sent to the requestor and client pending the approval or rejection of the order.<br />";
		$details .= "Thank you.<br />";
		$details .= "CTS Department";
		sendMail($addresses,'Request for Order Approval',$details);
	}
	else if ($_GET['action'] == 'sendOrderApproval') {
		$sql = sprintf("select store_id,client_uid,creator_uid,order_no,order_date,cost_centre,comments from istore.istore_orders where order_no='%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$creator_uid = $row->creator_uid;
		$client_uid = $row->client_uid;
		$orderno=$row->order_no;
		$cost_centre = $row->cost_centre;
		$comments = $row->comments;
		$order_date = $row->order_date;
		$storeid = $row->store_id;

		$sql = sprintf("select sum(total_price) from istore.istore_order_items where istore_orders_order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$amount = $dbo->loadResult();

		$sql = sprintf("select email from portal.cput_users where id = %d",$creator_uid);
		$dbo->setQuery($sql);
		$requestor = $dbo->loadResult();

		$sql = sprintf("select email from portal.cput_users where id = %d",$client_uid);
		$dbo->setQuery($sql);
		$client = $dbo->loadResult();

		$sql = sprintf("update istore.istore_approvals set istore.istore_approvals.processed=1 where istore.istore_approvals.order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$dbo->query();

		$sendTo = array();
		$sendTo[] = $requestor;
		$sendTo[] = $client;
		$addresses = serialize($sendTo);
		$details = "Cape Peninsula University of Technology.<br />";
		$details .= "<hr>";
		$details .= "Date: ".date('Y-m-d')."<br />";
		$details .= "Requestor: ".$requestor."&nbsp;(".$req_email.")<br />";
		$details .= "Client: ".$client."&nbsp;(".$client_email.")<br />";
		$details .= "<hr>";
		$details .= "Order# ".$orderno."<br />";
		$details .= "Order Date: ".$order_date."<br />";
		$details .= "Cost Code: ".$cost_centre."<br />";
		$details .= "Account Code: ".$account_code."<br />";
		$details .= "Comments: ".$comments."<br />";
		$details .= "The amount of R".$amount." was APPROVED by the finance department.<br /><br />";
		$details .= "Thank you.<br />";
		$details .= "CTS Department";
		sendMail($addresses,'Order Approval',$details);
	}
	else if ($_GET['action'] == 'sendReject') {
		$sql = sprintf("select store_id,client_uid,creator_uid,order_no,order_date,cost_centre,comments from istore.istore_orders where order_no='%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$creator_uid = $row->creator_uid;
		$client_uid = $row->client_uid;
		$orderno=$row->order_no;
		$cost_centre = $row->cost_centre;
		$comments = $row->comments;
		$order_date = $row->order_date;
		$storeid = $row->store_id;

		$sql = sprintf("select sum(total_price) from istore.istore_order_items where istore_orders_order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$amount = $dbo->loadResult();

		$sql = sprintf("select email from portal.cput_users where id = %d",$creator_uid);
		$dbo->setQuery($sql);
		$requestor = $dbo->loadResult();

		$sql = sprintf("select email from portal.cput_users where id = %d",$client_uid);
		$dbo->setQuery($sql);
		$client = $dbo->loadResult();

		$sql = sprintf("update istore.istore_approvals set istore.istore_approvals.processed=1 where istore.istore_approvals.order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$dbo->query();

		$sendTo = array();
		$sendTo[] = $requestor;
		$sendTo[] = $client;
		$addresses = serialize($sendTo);
		$details = "Cape Peninsula University of Technology.<br />";
		$details .= "<hr>";
		$details .= "Date: ".date('Y-m-d')."<br />";
		$details .= "Requestor: ".$requestor."&nbsp;(".$req_email.")<br />";
		$details .= "Client: ".$client."&nbsp;(".$client_email.")<br />";
		$details .= "<hr>";
		$details .= "Order# ".$orderno."<br />";
		$details .= "Order Date: ".$order_date."<br />";
		$details .= "Cost Code: ".$cost_centre."<br />";
		$details .= "Account Code: ".$account_code."<br />";
		$details .= "Comments: ".$comments."<br />";
		$details .= "The amount of R".$amount." was REJECTED by the finance department.<br />";
		$details .= "please contact finance controller for more information</br></br>";
		$details .= "Thank you.<br />";
		$details .= "CTS Department";
		sendMail($addresses,'Order Rejection',$details);
	}
	else if ($_GET['action'] == 'sendInprogress') {
		$sql = sprintf("select id,email from portal.cput_users where id = %d",$_GET['op']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObject();
		$operator = $result->email;
		$op_id = $result->id;

		$sql = sprintf("insert into istore.istore_audit (orderno,userid,action) values ('%s',%d,'MARK INPROGRESS')",$_GET['orderno'],$op_id);
		$dbo->setQuery($sql);
		$dbo->query();

		$sql = sprintf("update istore.istore_orders set istore_order_status_id = %d where order_no='%s'",$_GET['status'],$_GET['orderno']);
		$dbo->setQuery($sql);
		$dbo->query();

		$sql = sprintf("select store_id,client_uid,creator_uid,order_no,order_date,service_date,cost_centre,comments from istore.istore_orders where order_no='%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$creator_uid = $row->creator_uid;
		$client_uid = $row->client_uid;
		$orderno=$row->order_no;
		$cost_centre = $row->cost_centre;
		$comments = $row->comments;
		$order_date = $row->order_date;
		$service_date = $row->service_date;
		$storeid = $row->store_id;

		$sql = sprintf("select sum(total_price) from istore.istore_order_items where istore_orders_order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$amount = $dbo->loadResult();

		$sql = sprintf("select email from portal.cput_users where id = %d",$creator_uid);
		$dbo->setQuery($sql);
		$requestor = $dbo->loadResult();

		$sql = sprintf("select email from portal.cput_users where id = %d",$client_uid);
		$dbo->setQuery($sql);
		$client = $dbo->loadResult();

		$sql = sprintf("update istore.istore_approvals set istore.istore_approvals.processed=1 where istore.istore_approvals.order_no = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$dbo->query();

		$sendTo = array();
		$sendTo[] = $operator;
		$sendTo[] = $client;
		$addresses = serialize($sendTo);
		$details = "Cape Peninsula University of Technology.<br />";
		$details .= "<hr>";
		$details .= "Date: ".date('Y-m-d')."<br />";
		$details .= "Requestor: ".$requestor."&nbsp;(".$req_email.")<br />";
		$details .= "Client: ".$client."&nbsp;(".$client_email.")<br />";
		$details .= "<hr>";
		$details .= "Order# ".$orderno."<br />";
		$details .= "Order Date: ".$order_date."<br />";
		$details .= "<b>Service Date: ".$order_date."</b><br />";
		$details .= "Comments: ".$comments."<br />";
		$details .= "The order for the requested job was approved and is currently marked as 'In-progress'.<br />";
		$details .= "Please notify your supplier by fowarding this email to them.<br /><br />";
		$details .= "Thank you.<br />";
		$details .= "CTS Department";
		sendMail($addresses,'Order Approval',$details);
	}
	else if ($_GET['action'] == 'showToolTip') {
		$data = array();
		$sql = sprintf("select a.action_date, b.name, a.`action` from istore.istore_audit a
				left outer join portal.cput_users b on (a.userid=b.id)
				where a.orderno = '%s'",$_GET['orderno']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		foreach ($result as $row) {
			$data[] = $row->action_date.";".$row->name.";".$row->action;
		}
		echo json_encode($data);
	}

}

exit();



?>
