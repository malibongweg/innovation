<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='photos'");
$row = $dbo->loadObject();
$oci_host = $row->host;
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;
$oci_host_dr = $row->disaster_host;
$oci_connect_dr = $row->disaster_connect_string;
$oci_user_dr = $row->disaster_user_name;
$oci_pass_dr = $row->disaster_password;
$oci_system_mode = intval($row->system_mode);
$oci_log = intval($row->log_only);

if (isset($_GET)) {

	if ($_GET['action'] == "search_id") {
		if ($oci_system_mode == 0){
			$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass;
		} else {
			$cs = $oci_connect_dr; $uname = $oci_user_dr; $pass = $oci_pass_dr;
		}
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "-1";
			exit();
		}
		$crd_type = '';
		$sql = sprintf("select card_num,card_name,expire_date,barcode,card_type,aux_field,magstrip,campus from dirxml.idphoto_view where card_num = %d",$_GET['id']);
		$res = oci_parse($con,$sql);
		oci_execute($res);
		$row = oci_fetch_object($res);
			if (is_object($row)){
				$ret = $row->CARD_NUM.";".$row->CARD_NAME.";".$row->EXPIRE_DATE.";".$row->BARCODE.";".$row->CARD_TYPE.";".$row->AUX_FIELD;
				$mag = $row->MAGSTRIP; $campus = $row->CAMPUS;
				$crd_type = $row->CARD_TYPE;
			} else {
				echo "-2"; exit();
			}

			     if (!isset($_GET['ac'])){
					if (trim($crd_type) == 'S') {
						$sql = sprintf("select blocked,count(*) as rec_count from identity.photo_count where uid=%d",$_GET['id']);
						$dbo->setQuery($sql);
						$r = $dbo->loadObject();
						if (intval($r->rec_count > 0)){
							if (intval($r->blocked) == 1){
								echo "-9"; exit();
							}
						} 
					}
				 }

			$sql = sprintf("select location from identity.photos where userid = %d",$_GET['id']);
			$dbo->setQuery($sql);
			$dbo->query();
			if ($dbo->getNumRows() == 0) $ret = $ret . ";";
			else { $row = $dbo->loadObject(); $ret = $ret . ";".$row->location.";".$mag.";".$campus; }
			echo $ret;

	}
    else if($_GET['function'] == 'checkDBStatus') {
        $dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='its'");
        $row = $dbo->loadObject();
        $oci_host_dr = $row->disaster_host;
        $oci_system_mode = intval($row->system_mode);
        $oci_log = intval($row->log_only);
        echo $oci_system_mode.";".$oci_host_dr.";".$oci_log;
    }
	else if ($_GET['action'] == "new_card") {
		if ($oci_system_mode == 0){
			$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass;
		} else {
			$cs = $oci_connect_dr; $uname = $oci_user_dr; $pass = $oci_pass_dr;
		}
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "-1";
			exit();
		}
		$sql = sprintf("select stud.oth_seq.nextval as cardno from dual");
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$row = oci_fetch_object($result);
		$cno = $row->CARDNO;

		$sql = sprintf("select stud.othbargen as barcode from dual");
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$row = oci_fetch_object($result);
		echo $cno.";".$row->BARCODE;
	}
	else if ($_GET['action'] == "save_oth") {
		if ($oci_system_mode == 0){
			$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass;
		} else {
			$cs = $oci_connect_dr; $uname = $oci_user_dr; $pass = $oci_pass_dr;
		}
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "-1";
			exit();
		}
		$sql = sprintf("insert into stud.oth_foto (card_id,surname,full_name,aux_field,expire_date,barcode,magstrip,card_type) values (%d,'%s','%s','%s','%s','%s',%d,'%s')",
		$_POST['card_num'],addslashes($_POST['oth_surname']),$_POST['oth_initials'],addslashes($_POST['oth_aux']),$_POST['expire_month']." ".date("Y"),
		$_POST['oth_barcode'],substr($_POST['oth_barcode'],4,7),$_POST['card_type']);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		if (!$result) echo "-1"; else echo "1";
	}
	else if ($_GET['action'] == "save_photo") {
		$sql = sprintf("call identity.proc_savephoto(%d,'%s')",$_GET['id'],urldecode($_GET['loc']));
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if ($result) echo "1"; else echo "-1";
	}
	else if ($_GET['action'] == "save_new_photo") {
		$sql = sprintf("call identity.proc_savenewphoto(%d,'%s')",$_GET['id'],urldecode($_GET['loc']));
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if ($result) echo "1"; else echo "-1";
	}
	else if ($_GET['action'] == "gen_studbar") {
		if ($oci_system_mode == 0){
			$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass;
		} else {
			$cs = $oci_connect_dr; $uname = $oci_user_dr; $pass = $oci_pass_dr;
		}
		$con = oci_connect($uname,$pass,$cs);
		if (!$con) {
			echo "-1";
			exit();
		}
		$id = $_GET['id'];
		$sql = "begin stud.studbargen(:stno); end;";
		$result = oci_parse($con,$sql);
		oci_bind_by_name($result,":stno",$id,32,SQLT_INT);
		oci_execute($result);
		echo "1";
	}
	else if ($_GET['action'] == "gen_staffbar") {
		if ($oci_system_mode == 0){
			$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass;
		} else {
			$cs = $oci_connect_dr; $uname = $oci_user_dr; $pass = $oci_pass_dr;
		}
		$con = oci_connect("dirxml","dirxml",$cs);
		if (!$con) {
			echo "-1";
			exit();
		}
		$id = $_GET['id'];
		$sql = "begin person.staffbargen(:stno); end;";
		$result = oci_parse($con,$sql);
		oci_bind_by_name($result,":stno",$id,32,SQLT_INT);
		oci_execute($result);
		echo "1";
	}
	else if ($_GET['action'] == "get_history") {
		$sql = sprintf("select ifnull(identity.proc_get_photo_taken(%d),0) as cnt",$_GET['uid']);
		$dbo->setQuery($sql);
		$row = $dbo->loadResult();
		echo $row;
	}
	else if ($_GET['action'] == "prn_history") {
		$sql = sprintf("insert into identity.photo_taken(userid,operator) values (%d,%d)",$_GET['uid'],$_GET['op']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
	}
	else if ($_GET['action'] == "security") {
		$sql = sprintf("select a.user_id as cnt from cput_user_usergroup_map a where a.user_id = %d and a.group_id = %d",$_GET['operator'],$_GET['grp']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if ($dbo->getNumRows() == 0)  echo "0"; else echo "1";
		exit();
	}
	else if ($_GET['action'] == "barcode_rules") {
		$sql = sprintf("select student_default,staff_default from identity.barcode_rules where id=1");
		$dbo->setQuery($sql);
		$result = $dbo->loadObject();
		echo $result->student_default.";".$result->staff_default;
	}
	else if ($_GET['action'] == "check_reset_card") {
		$sql = sprintf("select a.uid,a.cnt,a.blocked,count(*) as rec_count,concat (b.pers_title,' ',b.pers_fname,' ',b.pers_sname) as full_name
						from identity.photo_count a left outer join student.personal_curr b on (a.uid=b.stud_numb)
						where a.uid=%d",$_GET['uid']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		if (intval($row->rec_count) == 0){
			echo "0";
		} else {
			echo $row->uid.";".$row->cnt.";".$row->blocked.";".$row->full_name;
		}
	}
	else if ($_GET['action'] == "reset_card") {
		$sql = sprintf("update identity.photo_count set blocked = 0 where uid=%d",$_GET['uid']);
		//echo $sql;
		$dbo->setQuery($sql);
		$dbo->query();
		echo "1";
	}
exit();
}
