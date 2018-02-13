<?php
function update_printing_object($my,$ldap_server,$ldap_user,$ldap_pass,$row) {
if ($row->account_no == 'PRINTING') {
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}

if ($row->account_no == 'PRINT') {
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	return;
}
$lcon = ldap_connect($ldap_server);
if (!$lcon)
{
   $sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Printing','Error connecting to LDAP server.',now())");
	mysql_query($sql,$my);
	$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
	mysql_query($sql,$my);
	echo "Error connecting to LDAP\n";
	return;
}
        //BIND WITH MODIFY ACCOUNT
        $lbind = ldap_bind($lcon,"cn=".$ldap_user.",o=cput",$ldap_pass);
		
        if (!$lbind)
        { $sql = sprintf("insert into cput_cron_log (system_name,msg,update_date) values ('Printing','Cannot bind to LDAP server.',now())");
			mysql_query($sql,$my);
			$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
			mysql_query($sql,$my);
			return;
		}

	$sr = ldap_search($lcon,"o=cput","cn=".$row->account_no);
	if (!$sr) { $sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
			mysql_query($sql,$my);
			return;
	}
	$en=ldap_get_entries($lcon,$sr);
	if (!$en) { $sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
			mysql_query($sql,$my);
			return;
	}

		
			$dn = $en[0]['dn'];
			$entry = ldap_first_entry($lcon,$sr);
				if (!$entry) { 
					$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
					mysql_query($sql,$my);
					return;
				}
				
				$value = ldap_get_values($lcon,$entry,"accountbalance");
				if (!$value)
				{
					$sql = sprintf("call proc_pcounter_error('Printing','Could not get account balance value from server.','%s','%s')",$row->account_no,$row->receipt_no);
					$r = mysql_query($sql,$my);
					$value[0] = 0;
					$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
					mysql_query($sql,$my);
				}
					$amt = sprintf("%0.2f",$row->amount);
					$amt = str_replace(".","",$amt);
					$entry = array();
					$entry['accountbalance'] = $value[0] + $amt;
					$oldval = number_format(($value[0]/100),2);

						if (!ldap_modify($lcon,$dn,$entry))
						{
							$sql = sprintf("call proc_pcounter_error('Printing','Could not modify balance.','%s','%s')",$row->account_no,$row->receipt_no);
							mysql_query($sql,$my);
							$sql = sprintf("update cput_pcounter set aux3 = now() where receipt_no = '%s'",$row->receipt_no);
							mysql_query($sql,$my);
						} else {
							$sql = sprintf("update cput_pcounter set status_flag=1,processed_date = now() where receipt_no = '%s'",$row->receipt_no);
							mysql_query($sql,$my);
							$sql = sprintf("call proc_pcounter_error('Printing','Credit updated. [".$row->amount."]','%s','%s')",$row->account_no,$row->receipt_no);
							mysql_query($sql,$my);
							$sr = ldap_search($lcon,"o=cput","cn=".$row->account_no);
							$entry = ldap_first_entry($lcon,$sr);
							$value = ldap_get_values($lcon,$entry,"accountbalance");
	    					$sql = sprintf("update cput_pcounter set old_value='%s',new_value='%s' where receipt_no = '%s'",$oldval,number_format(($value[0]/100),2),$row->receipt_no);
							mysql_query($sql,$my);
						}
				
		
ldap_close($lcon);
}
?>
