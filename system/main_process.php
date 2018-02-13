<?php

$scr = "";
$fname = dirname(__FILE__);
$proc = $fname . "/main.proc";
if (file_exists($proc)) {
echo "Process is running...";
exit();
} else {
$fp = fopen($proc,"w");
fwrite($fp,"Running");
fclose($fp);
}

//create pid file
$pidfile = dirname(__FILE__);
$pidfile .= "/mainproc.pid";
$fp = fopen($pidfile,"w");
fwrite($fp,getmypid());
fclose($fp);


//MAKE DB CONNECTION
$scr = "";
$fname = dirname(__FILE__);
$fname = explode("/",$fname);
$cnt = count($fname)-1;
for ($i=0;$i<=$cnt-1;$i++){
	$scr .= $fname[$i]."/";
}
$scr = $scr."config.cfg";
$cfg = trim(file_get_contents($scr));
$cfg = explode("\r\n",$cfg);
$my = mysql_connect($cfg[0],$cfg[1],$cfg[2]);
if (!$my) die();
mysql_select_db($cfg[3]);
$ldap_server = $cfg[4];
$ldap_user = $cfg[5];
$ldap_pass = $cfg[6];

///Include credit printing
$scr = "";
$fname = dirname(__FILE__);
$fname = explode("/",$fname);
$cnt = count($fname);
for ($i=0;$i<=$cnt-1;$i++){
	$scr .= $fname[$i]."/";
}
$scr = $scr."credit_printing.php";
require_once($scr);

//Include credit copy
$scr = "";
$fname = dirname(__FILE__);
$fname = explode("/",$fname);
$cnt = count($fname);
for ($i=0;$i<=$cnt-1;$i++){
	$scr .= $fname[$i]."/";
}
$scr = $scr."credit_copy.php";
require_once($scr);

//Include credit copy busary
$scr = "";
$fname = dirname(__FILE__);
$fname = explode("/",$fname);
$cnt = count($fname);
for ($i=0;$i<=$cnt-1;$i++){
	$scr .= $fname[$i]."/";
}
$scr = $scr."credit_copy_busary.php";
require_once($scr);

//Include credit copy busary
$scr = "";
$fname = dirname(__FILE__);
$fname = explode("/",$fname);
$cnt = count($fname);
for ($i=0;$i<=$cnt-1;$i++){
	$scr .= $fname[$i]."/";
}
$scr = $scr."credit_meals.php";
require_once($scr);


$scr = "";
$fname = dirname(__FILE__);
$fname = explode("/",$fname);
$cnt = count($fname);
for ($i=0;$i<=$cnt-1;$i++){
	$scr .= $fname[$i]."/";
}
$scr = $scr."update_copier_barcode.php";
require_once($scr);

$scr = "";
$fname = dirname(__FILE__);
$fname = explode("/",$fname);
$cnt = count($fname);
for ($i=0;$i<=$cnt-1;$i++){
	$scr .= $fname[$i]."/";
}
$scr = $scr."update_meals_barcode.php";
require_once($scr);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////Connected? Continue!!!
$sql = "select account_no,trans_no,amount,old_value,new_value,aux1,aux2,rec_type,receipt_no from cput_pcounter where status_flag <> 1 and account_no REGEXP '^[0-9]+$' = 1  and retry_seq <= 100 order by retry_seq,trans_date desc";
$result = mysql_query($sql,$my);

if (!$result) { unlink($proc); die(); }

while ($row = mysql_fetch_object($result)) {
	if ($row->rec_type == 224) {
			update_copy_object($my,$row);
	}
	if ($row->rec_type == 21) {
			update_copy_object($my,$row);
	}
	if ($row->rec_type == 212) {
			update_printing_object($my,$ldap_server,$ldap_user,$ldap_pass,$row);
	}
	if ($row->rec_type == 121) {
			update_printing_object($my,$ldap_server,$ldap_user,$ldap_pass,$row);
	}
	if ($row->rec_type == 33) {
			update_printing_object($my,$ldap_server,$ldap_user,$ldap_pass,$row);
	}
	if ($row->rec_type == 2192) {
			update_copy_object($my,$row);
	}
	if ($row->rec_type == 2191) {
			update_copy_object($my,$row);
	}
	if ($row->rec_type == 272) {
			update_meals_object($my,$row);
	}
	if ($row->rec_type == 37) {
			update_meals_object($my,$row);
	}
	if ($row->rec_type == 2222) {
			update_printing_object($my,$ldap_server,$ldap_user,$ldap_pass,$row);
	}
	if ($row->rec_type == 11111) {
			update_copy_barcode($my,$row);
	}
	if ($row->rec_type == 11112) {
			update_meals_barcode($my,$row);
	}
}

unlink($pidfile);
unlink($proc);
?>
