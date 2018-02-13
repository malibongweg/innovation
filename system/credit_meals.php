<?php
function update_meals_object($my,$row) {
//Get connection setup//
$sql = "select host,user_name,password,connect_string from cput_system_setup where system_name='meals'";
$result = mysql_query($sql,$my);
if (!$result) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Meals','Error getting Meals config.',now())");
	mysql_query($sql,$my);
	$sql = sprintf("update cput_pcounter aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}
if (mysql_num_rows($result) == 0) {
	$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Meals','No database connection information.',now())");
	mysql_query($sql,$my);
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}
$rowp = mysql_fetch_object($result);
$meals_db = $rowp->host.$rowp->connect_string;

//CONNECT TO INTERBASE
	$con = ibase_connect($meals_db,$row->user_name,$rowp->password);
	if (!$con)
	{ 
		$sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Meals','Cannot connect to server.',now())");
		mysql_query($sql,$my);
		$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
		mysql_query($sql,$my);
		return;
	 }

			$sql=sprintf("insert into pcounter (transno,stdno,transdate,rectype,amount,receipt_no) values (%d,%d,CURRENT_TIMESTAMP,%d,%0.2f,%d)",$row->trans_no,$row->account_no,$row->rec_type,$row->amount,$row->receipt_no);
				$res=ibase_query($con,$sql);
				if (!$res)
				{
					$sql = sprintf("call proc_pcounter_error('Meals','Error updating meals account.','%s','%s')",$row->account_no,$row->receipt_no);
					mysql_query($sql,$my);
					$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
					mysql_query($sql,$my);
				}
				else
				{
					$sql = sprintf("update cput_pcounter set status_flag = 1,processed_date = now() where  receipt_no = '%s'",$row->receipt_no);
					mysql_query($sql,$my);	
					$sql = sprintf("call proc_pcounter_error('Meals','Credit updated. [".$row->amount."]','%s','%s')",$row->account_no,$row->receipt_no);
					mysql_query($sql,$my);
				}

}
?>
