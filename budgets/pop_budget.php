<?php
$recins=0;
#Script extracts students from ITS and populate into OPA

$script_start = date('l dS \of F Y h:i:s A');
$error_count = 0;
$sql_error_count = 0;
$error_message = "";
$proc_message = "";
$script_message = "";

#Opens config file for script
$proc_message = $proc_message . "READING SQL STATEMENT FILE ->";
$handle = fopen("./budgets.sql","r");
	if (!$handle)
	{
		++$error_count;
		$error_message = "Could not open SQL config file.\n";
		$proc_message = $proc_message . "FAILED\n";
	}
	else
	$proc_message = $proc_message . "OK\n";

#Reads config and populate parameters
$opa_rows = fgets($handle,4096);
$its_values = fgets($handle,4096);
$from = fgets($handle,4096);
$where = fgets($handle,4096);
$email = fgets($handle,4096);

	$opa_rows = explode(":",$opa_rows);
	$opa_rows = trim($opa_rows[1]);
	$its_values = explode(":",$its_values);
	$its_values = trim($its_values[1]);
	$from = explode(":",$from);
	$from = trim($from[1]);
	$where = explode(":",$where);
	$where = trim($where[1]);
	$email = explode(":",$email);
	$email = trim($email[1]);

#Close config file
fclose($handle);


#Reads database config file
$proc_message = $proc_message . "READING DATABASE CONFIGURATION FILE->";
$handle = fopen("./database.conf","r");
if (!$handle)
	{
		++$error_count;
		$error_message = $error_message . "Could not open student database config file.\n";
		$proc_message = $proc_message . "FAILED\n";
	}
	else $proc_message = $proc_message . "OK\n";

$its_user = fgets($handle,4096);
$its_pass = fgets($handle,4096);
$tns = fgets($handle,4096);
$opa_user = fgets($handle,4096);
$opa_pass = fgets($handle,4096);
$opa_host = fgets($handle,4096);
$opa_database = fgets($handle,4096);

$its_user = explode(":",$its_user);
$its_user = trim($its_user[1]);
$its_pass = explode(":",$its_pass);
$its_pass = trim($its_pass[1]);
$tns = explode(":",$tns);
$tns = trim($tns[1]);
$opa_user = explode(":",$opa_user);
$opa_user = trim($opa_user[1]);
$opa_pass = explode(":",$opa_pass);
$opa_pass = trim($opa_pass[1]);
$opa_host = explode(":",$opa_host);
$opa_host = trim($opa_host[1]);
$opa_database = explode(":",$opa_database);
$opa_database = trim($opa_database[1]);

#Close database config file
fclose($handle);

#Get table definition
$proc_message = $proc_message . "READING TABLE DEFINITION FILE->";
$filename = "./table_def.sql";
$handle = fopen($filename,"r");
if (!$handle)
	{
		++$error_count;
		$error_message = $error_message . "Could not open student table definition file.\n";
		$proc_message = $proc_message . "FAILED\n";
	}
	else $proc_message = $proc_message . "OK\n";

$table_header = "";
$table_def = $table_header ." ". fread($handle,filesize($filename));
fclose($handle);

$filename = "./table_def2.sql";
$handle = fopen($filename,"r");
if (!$handle)
	{
		++$error_count;
		$error_message = $error_message . "Could not open student table definition file.\n";
		$proc_message = $proc_message . "FAILED\n";
	}
	else $proc_message = $proc_message . "OK\n";

$table_header = "";
$table_def2 = $table_header ." ". fread($handle,filesize($filename));
fclose($handle);



#Open oracle and mysql database connections
$proc_message = $proc_message . "CONNECTING TO ORACLE->";
$ora_con = oci_connect($its_user,$its_pass,$tns);
	if (!$ora_con) 
	{
		++$error_count;
		$error_message = $error_message . "Error connecting to ORACLE database.\n";
		$proc_message = $proc_message . "FAILED\n";
	}
	else $proc_message = $proc_message . "OK\n";

$proc_message = $proc_message . "CONNECTION TO OPA->";
$opa_con = mysql_connect($opa_host,$opa_user,$opa_pass);
	if (!$opa_con)
	{
		++$error_count;
		$error_message = $error_message . "Error connecting to OPA database.\n";
		$proc_message = $proc_message . "FAILED\n";
	}
	else $proc_message = $proc_message . "OK\n";

#Do oracle (ITS) select
$proc_message = $proc_message . "SELECT DATA VALUES FROM ITS->";
$its_sql = "select distinct ".$its_values." ".$from." ".$where;
//$fp = fopen("./sql.txt","w");
//fwrite($fp,$its_sql);
//fclose($fp);
//print $its_sql;exit;


$res = oci_parse($ora_con,$its_sql);
	if (!$res)
	{
		++$error_count;
		$error_message = $error_message . "Could not parse ORACLE SQL.\n";
		$proc_message = $proc_message . "FAILED\n";
	}
	else
	{
		$proc_message = $proc_message . "OK\n";
		$resx = oci_execute($res);
		if (!$resx)
		{
			++$error_count;
			$e = oci_error($res);
			$error_message = $error_message . $e['message']."\n";
		}
	}


#Select opa database
mysql_select_db($opa_database,$opa_con);


#Drop student copy table
$proc_message = $proc_message . "DROPING OPA COPY TABLE->";
$yr = date("Y");
$drop = "drop table fgcsum_copy";
$res_opa = mysql_query($drop,$opa_con);
	if (!$res_opa)
	{
		++$error_count;
		$error_message = $error_message . "DROP TABLE FAILED->".mysql_error($opa_con)."\n";
		$proc_message = $proc_message . "NOT FOUND---THIS IS OK\n";
	}
		else $proc_message = $proc_message . "OK\n";

$proc_message = $proc_message . "DROPING OPA COPY TABLE->";
$yr = date("Y");
$drop = "drop table budget_summary_copy";
$res_opa = mysql_query($drop,$opa_con);
	if (!$res_opa)
	{
		++$error_count;
		$error_message = $error_message . "DROP TABLE FAILED->".mysql_error($opa_con)."\n";
		$proc_message = $proc_message . "NOT FOUND---THIS IS OK\n";
	}
	else $proc_message = $proc_message . "OK\n";



	

$proc_message = $proc_message . "CREATING NEW OPA COPY TABLE->";
$res_opa = mysql_query($table_def,$opa_con);
if (!$res_opa)
	{
		++$error_count;
		$error_message = $error_message . "CREATE TABLE FAILED->".mysql_error($opa_con)."\n";
		$proc_message = $proc_message . "FAILED\n";
	}
	else $proc_message = $proc_message . "OK\n";
	
$proc_message = $proc_message . "CREATING NEW OPA COPY TABLE->";
$res_opa = mysql_query($table_def2,$opa_con);
if (!$res_opa)
	{
		++$error_count;
		$error_message = $error_message . "CREATE TABLE FAILED->".mysql_error($opa_con)."\n";
		$proc_message = $proc_message . "FAILED\n";
	}
	else $proc_message = $proc_message . "OK\n";




//Populate OPA table
$proc_message = $proc_message . "STARTING OPA DATABASE POPULATION\n";
while ($row = oci_fetch_object($res))
{
	$ncols = oci_num_fields($res);
	$local_value = "";
	for ($i = 1; $i <= $ncols; $i++)
	{
		$column_name  = oci_field_name($res, $i);
        $column_value = oci_result($res, $i);
		$column_type = oci_field_type($res, $i);
			if (($column_type == "VARCHAR2") or ($column_type == "VARCHAR"))
			{
				$column_value = "'".$column_value."'";
			}
			else if (is_null($column_value))
			{
				$column_value = "NULL";
			}
		$local_value = $local_value . $column_value . ",";
	}
	$local_value = substr($local_value,0,strlen($local_value)-1);
	$opa_sql = "insert into fgcsum_copy ({$opa_rows}) values({$local_value})";
	//print $opa_sql."\n";
	$ins_opa = mysql_query($opa_sql,$opa_con);
	if (!$ins_opa)
	{
		++$sql_error_count;
		//$error_message = $error_message . mysql_error($opa_con)."\n";
	} else ++$recins;
}

$opa_sql = "insert into budget_summary_copy select fgcglcc, fgcglacc, fgcfiny, sum(budcr), sum(buddr), sum(actcr), sum(actdr), sum(fgccom) from budgets.fgcsum_copy, budgets.account_codes where fgcfiny = for_year and fgcglacc = fcdacc and for_year in (2010,2011,2012,2013,2014) and (fcdacat NOT IN ('999', '800', '960') or fgcglacc = '83602') group by fgcglcc, fgcglacc, fgcfiny order by fgcglcc,fgcglacc, fgcfiny";
$ins_opa = mysql_query($opa_sql,$opa_con);
	if (!$ins_opa)
	{
		++$sql_error_count;
		//$error_message = $error_message . mysql_error($opa_con)."\n";
	}



$proc_message = $proc_message . "RENAMING TABLE(s)\n";
$opa_ren = sprintf("DROP TABLE fgcsum");
$opa_res = mysql_query($opa_ren,$opa_con);
$proc_message = $proc_message . "RENAMING TABLE(s)\n";
$opa_ren = sprintf("DROP TABLE budget_summary");
$opa_res = mysql_query($opa_ren,$opa_con);

$opa_ren = sprintf("ALTER TABLE budget_summary_copy RENAME budget_summary");
$opa_res = mysql_query($opa_ren,$opa_con);
$opa_ren = sprintf("ALTER TABLE fgcsum_copy RENAME fgcsum");
$opa_res = mysql_query($opa_ren,$opa_con);






$proc_message = $proc_message . "END OF OPA DATABASE POPULATION\n\n";

$script_end = date('l dS \of F Y h:i:s A');


$proc_message = $proc_message . "END OF OPA DATABASE POPULATION\n\n";

$script_end = date('l dS \of F Y h:i:s A');

$script_message = "OPA BUDGET DATA\n";
$script_message = $script_message . "=========================\n";
$script_message = $script_message . "SCRIPT START: {$script_start}\nSCRIPT END: {$script_end}\n\n";
$script_message = $script_message  . "PROCEDURE MESSAGES:\n";
$script_message = $script_message . $proc_message;

$script_message = $script_message . "GENERAL ERRORS: {$error_count}\nSQL ERROR COUNT: {$sql_error_count}\n";
$script_message = $script_message . "SCRIPT ERROR MESSAGES: {$error_message}\n";
$script_message = $script_message . "TOTAL RECORDS INSERTED: {$recins}\n";
$script_message = $script_message . "==========================\n\n";

$Sender = "no-reply <apache@theseus.cput.ac.za>"; 
				//$Recipiant = $email;
				$Recipiant = "stevensm@cput.ac.za";
				$Subject = "OPA BUDGET DATA";
				$CustomHeaders= "";
				$Bcc = ""; 
				$Cc = "";
				//$message = new Email($Recipiant, $Sender, $Subject, $CustomHeaders);
				//$message->Cc = $Cc; 
				//$message->Bcc = $Bcc; 
				//$message->SetTextContent($script_message);
				//$message->Send();
				//unset($message);
				
//$fp = fopen("/opt/opa_sync/opa_sync.log","a+");
//fwrite($fp,$script_message);
//fclose($fp);
?>
