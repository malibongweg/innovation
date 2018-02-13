<?php
function update_copy_object($my,$row) {

if ($row->account_no == 'COPY') {
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}

if ($row->account_no == 'PHOTOCOPY') {
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}
//Get connection setup//
$sql = "select host,user_name,password,database_name from cput_system_setup where system_name='copies'";
$result = mysql_query($sql,$my);
if (!$result) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Photo Copy Config Error.','%s',now())",mysql_error($my));
	mysql_query($sql);
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}
if (mysql_num_rows($result) == 0) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Photo Copy','No database connection information.',now())");
	mysql_query($sql,$my);
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}
//Connect to copy system
$rowp = mysql_fetch_object($result);
$copy = pg_connect("host=".$rowp->host." dbname=".$rowp->database_name." user=".$rowp->user_name." password=".$rowp->password);
if (!$copy)
{
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Photo Copy','Error connecting to copy system.',now())");
	mysql_query($sql,$my);
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}

///Get point ids
$sql = "select unitid,svalue from notebox where functionid=104";
$presult = pg_query($copy,$sql);
if (!$presult) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('PCounter Population-Notereader','Error getting point id.',now())");
	mysql_query($sql,$my);
	return;
} 

$p = array();
while ($prow = pg_fetch_object($presult)) {
	$p[$prow->unitid] = $prow->svalue;
}

////////Connected? Continue!!!
	$sql = sprintf("select count(*) as cnt from users where trim(reference) = '%s'",$row->account_no);
	$res = pg_query($copy,$sql);
		if (!$res) {
			$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
			mysql_query($sql,$my);
			return;
		}
	$copnum = pg_fetch_object($res);
	if (!is_object($copnum)) {
		$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
			mysql_query($sql,$my);
			return;
	}
	if ($copnum->cnt > 0) {

			$sql = sprintf("select userid from users where trim(reference) = '%s'",$row->account_no);
			$result = pg_query($copy,$sql);
				if (!$result) {
					$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
					mysql_query($sql,$my);
					return;
				}
			$rowx = pg_fetch_object($result);
				if (!is_object($rowx)) {
					$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
					mysql_query($sql,$my);
					return;
				}
			$uid = $rowx->userid;


			$sql = sprintf("select amount from credits where userid = %d and creditno=1",$uid);
			$result = pg_query($copy,$sql);
					if (!$result) {
					$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
					mysql_query($sql,$my);
					return;
				}
			$rowx = pg_fetch_object($result);
			if (!is_object($rowx)) { $pbalance = 0; } else
			{  $pbalance = $rowx->amount; }


			$amt = $row->amount;
			$amt = sprintf("%0.2f",$amt);
			$amt = explode(".",$amt);
			$val1 = sprintf("%02s",$amt[0]);
			$val2 = sprintf("%02s",$amt[1]);
			$amt = $val1.$val2;

			$racc = 1;
			if (trim(substr($row->aux1,0,3)) == "RCL") {
				$idx = intval(substr($row->aux2,4,3));
				$racc = $p[$idx];

			}

			if ($row->rec_type == 224) {
				$copy_credit = sprintf("select credituser('%s',1,%s,'Transaction 224 Reader %d',%d,1)",$row->account_no,$amt,$idx,$racc);
			} else if ($row->rec_type == 21) {
				$copy_credit = sprintf("select credituser('%s',1,%s,'Transaction 21',1,1)",$row->account_no,$amt,$idx,$racc);
			} else if ($row->rec_type == 2191) {
				$copy_credit = sprintf("select credituser('%s',1,%s,'Transaction 2191 [Bursary]',1,1)",$row->account_no,$amt,$idx,$racc);
			} else if ($row->rec_type == 2192) {
				$copy_credit = sprintf("select credituser('%s',1,%s,'Transaction 2192 [Bursary]',1,1)",$row->account_no,$amt,$idx,$racc);
			}
			$resc = pg_query($copy,$copy_credit);
			if (!$resc)
				{
					$sql = sprintf("call proc_pcounter_error('Photo Copy','Error updating copy credit.','%s','%s')",$row->account_no,$row->receipt_no);
					mysql_query($sql,$my);
					$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
					mysql_query($sql,$my);
				}
				else
				{
					$sql = sprintf("update cput_pcounter set status_flag = 1,processed_date = now(), old_value='%s',new_value='%s', aux3 = '%s' where receipt_no = '%s'",number_format(($pbalance/100),2),number_format((($pbalance+$amt)/100),2),"Acc# ".$racc,$row->receipt_no);
					mysql_query($sql,$my);
					$sql = sprintf("call proc_pcounter_error('Photo Copy','Credit updated. [".$row->amount."]','%s','%s')",$row->account_no,$row->receipt_no);
					mysql_query($sql,$my);
				}

	} else {
		$sql = sprintf("call proc_pcounter_error('Photo Copy','No user account.','%s','%s')",$row->account_no,$row->receipt_no);
		mysql_query($sql,$my);
		$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
		mysql_query($sql,$my);
	}
}
?>
