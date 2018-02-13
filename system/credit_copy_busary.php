<?php
function update_copy_busary_object($my,$row) {

//Get connection setup//
$sql = "select host,user_name,password,database_name from cput_system_setup where system_name='copies'";
$result = mysql_query($sql,$my);
if (!$result) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Photo Copy','%s',now())",mysql_error($my));
	mysql_query($sql);
	return;
}
if (mysql_num_rows($result) == 0) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Photo Copy','No database connector information found.',now())");
	mysql_query($sql,$my);
	return;
}
//Connect to copy system
$rowp = mysql_fetch_object($result);
$copy = pg_connect("host=".$rowp->host." dbname=".$rowp->database_name." user=".$rowp->user_name." password=".$rowp->password);
if (!$copy)
{
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Photo Copy','Error connecting to copy system.',now())");
	mysql_query($sql,$my);
	$sql = sprintf("update cput_pcounter set status_flag = 2, aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}

//////////////////////////////////////////////////////////////////////////////////
//GET RESULT SET

	$sql = sprintf("select count(*) as cnt from users where trim(reference) = '%s'",$row->account_no);
	$result = pg_query($copy,$sql);
	$xx = pg_fetch_object($result);
	if ($xx->cnt > 0)
	{
		if ($row->rec_type == 2192)
		{
			$amt = $row->amount;
			$amt = sprintf("%0.2f",$amt);
			$amt = explode(".",$amt);
			$val1 = sprintf("%02s",$amt[0]);
			$val2 = sprintf("%02s",$amt[1]);
			$amt = $val1.$val2;
			$sql = sprintf("select credituser('%s',1,%s,'2192 Transaction',7,1)",$row->account_no,$amt);
			$result = pg_query($copy,$sql);
			if (!$result) {
				$sql = sprintf("call proc_pcounter_error('Photo Copy','Error updating copy credit.','%s','%s')",$row->account_no,$row->receipt_no);
				mysql_query($sql,$my);
				$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
				mysql_query($sql,$my);
			} else {
				$sql = sprintf("update cput_pcounter set status_flag = 1,processed_date = now() where receipt_no = '%s'",$row->receipt_no);
				mysql_query($sql,$my);
				$sql = sprintf("call proc_pcounter_error('Photo Copy','Credit updated. [".$row->amount."]','%s','%s')",$row->account_no,$row->receipt_no);
				mysql_query($sql,$my);
			}
		}
		else if ($row->rec_type == 2191)
		{
			$amt = $row->amount;
			$amt = sprintf("%0.2f",$amt);
			$amt = explode(".",$amt);
			$val1 = sprintf("%02s",$amt[0]);
			$val2 = sprintf("%02s",$amt[1]);
			$amt = "-".$val1.$val2;
			$sql = sprintf("select credituser('%s',1,%s,'2191 Transaction',7,1)",$row->account_no,$amt);
			$result = pg_query($copy,$sql);
			if (!$result) {
				$sql = sprintf("call proc_pcounter_error('Photo Copy','Error updating copy credit.','%s','%s')",$row->account_no,$row->receipt_no);
				mysql_query($sql,$my);
				$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
				mysql_query($sql,$my);
			} else {
				$sql = sprintf("update cput_pcounter set status_flag = 1,processed_date = now() where receipt_no = '%s'",$row->receipt_no);
				mysql_query($sql,$my);
				$sql = sprintf("call proc_pcounter_error('Photo Copy','Credit updated. [".$row->amount."]','%s','%s')",$row->account_no,$row->receipt_no);
					mysql_query($sql,$my);
			}
		}
	} else {
		$sql = sprintf("call proc_pcounter_error('Photo Copy','No user object.','%s','%s')",$row->account_no,$row->receipt_no);
		mysql_query($sql,$my);
		$sql = sprintf("update cput_pcounter set status_flag = 2, aux3 = now() where receipt_no = '%s'",$row->receipt_no);
		mysql_query($sql,$my);
	}
}
?>
