<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
$dbo->setQuery("select connect_string,user_name,password from cput_system_setup where system_name='its'");
$dbo->query();
if ($dbo->getNumRows() == 0) { echo "-1"; exit(); }
$row = $dbo->loadObject();

		$oci = oci_connect($row->user_name,$row->password,$row->connect_string);
		if (!$oci) { echo "-1"; exit(); }
		$sql = "select iadmagstrip from iadbio where iadstno=".$_GET['stno'];
		$res = oci_parse($oci,$sql);
		if (!$res) { echo "-1"; exit(); }
		oci_execute($res);
		$o = oci_fetch_object($res);
		if (strlen($o->IADMAGSTRIP) > 0) {
			$sql = sprintf("insert into barcode_upd (stdno,new_magno,status) values (%d,%d,%d)",$_GET['stno'],$o->IADMAGSTRIP,0);
			//echo $sql;
			$resx = oci_parse($oci,$sql);
			if (!$resx) { echo "-1"; exit(); }
			oci_execute($resx);
			echo "1";
		}

		

exit();

?>