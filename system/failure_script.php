<?php
$fname = $_SERVER['DOCUMENT_ROOT'];
require_once($fname."/configuration.php");
$jconfig = new JConfig();
$con = mysql_connect($jconfig->host,$jconfig->user,$jconfig->password);
if (!$con) { echo "-1"; exit(); }
mysql_select_db($jconfig->db,$con);
		$data = array();
		$sql = "select a.id,a.system_id,b.description as failure_reason,c.description as severity,d.system_name,a.failure_message,a.resolve_message
		from cput_service_failure a 
		left outer join cput_service_failure_codes b on (a.failure_reason = b.id)
		left outer join cput_service_failure_severity c on (a.severity_code = c.id)
		left outer join change_control.cts_systems d on (a.system_id = d.system_no)
		where date(a.end_date) <= date(now())";
		$result = mysql_query($sql,$con);
		if (!$result) { die(mysql_error($result)); }
		
			if (mysql_num_rows($result) == 0) {
				echo "-1"; exit(); 
			}
		while ($row = mysql_fetch_object($result))
		{
			$data[] = "[".$row->system_id."] ".$row->system_name.";".$row->failure_message.";".$row->resolve_message.";".$row->failure_reason.";".$row->severity;
		}
		echo json_encode($data);
?>