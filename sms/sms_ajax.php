<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);

if (isset($_GET['func'])) {

	if ($_GET['func'] == 'stat') {
		$data = array();
		$dbo->setQuery("select `mode` from cput_system_setup_master where id = 1");
		$mode = $dbo->loadResult();

		$dbo->setQuery("select system_status,sms_charge,notify_email,cmd_location,hr_email from #__sms_settings where id = 1");
		$result = $dbo->loadObjectList();
		foreach($result as $item) {
			$data[0] = $item->system_status;
			$data[1] = $item->sms_charge;
			$data[2] = $item->notify_email;
			$data[3] = $item->cmd_location;
			$data[4] = $item->hr_email;
			$data[5] = $mode;
		}
		echo json_encode($data);
	}

	else if ($_GET['func'] == 'updstat') {
		if ($_GET['smode'] == 0) {
			$dbo->setQuery("update cput_system_setup_master set mode = 0 where id = 1");
		} else {
			$dbo->setQuery("update cput_system_setup_master set mode = 1 where id = 1");
		}
		$dbo->query();

		if ($_GET['stat'] == 'true' ) $stat = 1; else $stat = 0;
		$dbo->setQuery("update #__sms_settings set system_status = ".$stat.", sms_charge=".$_GET['amt'].", notify_email='".$_GET['notify']."', cmd_location='".urldecode($_GET['cmd'])."', hr_email = '".$_GET['hr_email']."' where id = 1");
		$result = $dbo->query();
		if (!$result) echo '0'; else echo '1';
	}

	else if ($_GET['func'] == 'smsCredit') {
		$sql = sprintf("insert into #__sms_credits (username,reference,amount,credit_type,credit_user,costcentre,account_code) values('%s','%s',%0.2f,%d,'%s','%s','%s')",
		$_GET['xusername'],$_GET['ref'],$_GET['credit_amount'],$_GET['credit_type'],$_GET['cuser'],$_GET['costcode'],$_GET['accountcode']);
		$dbo->setQuery($sql);
		$result = 0;
		$result = $dbo->query();
		if (!$result) echo '0'; else echo '1';
	}

}
exit();

?>