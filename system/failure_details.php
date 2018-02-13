<?php
$fname = $_SERVER['DOCUMENT_ROOT'];
require_once($fname."/configuration.php");
$jconfig = new JConfig();
$con = mysql_connect($jconfig->host,$jconfig->user,$jconfig->password);
if (!$con) { echo "-1"; exit(); }
mysql_select_db($jconfig->db,$con);
?>
<style>
     html {margin:0;padding:0; height: 100%; background-color: #e4e4e4 }
	 body {font: 11px verdana,sans-serif,arial; color: #000000 }

.input_textarea {
	border: 1px solid #c8c6c7;
	font-size: 13px;
	font-weight: bold;
	color: #000000;
	filter: alpha(opacity=70);
	-moz-opacity:0.7;
	opacity: 0.7;
	text-align: left;
}
.input_label {
	width: 160px;
}

</style>
<?php
		$sql = "select a.id,a.system_id,b.description as failure_reason,c.description as severity,d.system_name,a.failure_message
		from cput_service_failure a 
		left outer join cput_service_failure_codes b on (a.failure_reason = b.id)
		left outer join cput_service_failure_severity c on (a.severity_code = c.id)
		left outer join change_control.cts_systems d on (a.system_id = d.system_no)
		where a.id = ".$_GET['id'];
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result)			
		?>
		
	
		<b>Description:</b><br />
		<?php echo trim($row->failure_message); ?></textarea>
		<br /><br />

		<b>Failure Reason:</b><br />
		<?php echo $row->failure_reason; ?>
		<br /><br />

		<b>Severity Code:</b><br />
		<?php echo $row->severity; ?>
		<br /><br />

		

<script type="text/javascript">
	window.parent.$j.colorbox.resize({ width: 500, height: 250 });
</script>