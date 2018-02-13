<?php
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::setPath('config', dirname(__FILE__));
Epi::init('config');
Epi::init('route','database');
getConfig()->load('configuration.ini');
$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','mobile',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->post('/','registerDevice');
getRoute()->run();


function registerDevice() {

			$lcon = @ldap_connect("10.19.0.11");
            if (!$lcon) { $data['record'][0]['response'] = '1'; echo json_encode($data); exit(); }

			ldap_set_option($lcon, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($lcon, LDAP_OPT_REFERRALS, 0);

            $lbind = @ldap_bind($lcon,"ads\ga-ldap-browse","CPUTadmin2008");
            if (!$lbind) {  $data['record'][0]['response'] = '2'; echo json_encode($data); exit(); }

			$base_dn = "dc=ads,dc=cput,dc=ac,dc=za";
			$filter = "(&(objectClass=User)(sAMAccountName=".$_POST['uname']."))";
            $sr = @ldap_search($lcon,$base_dn,$filter);
			if (!$sr)// { echo ldap_err2str( ldap_errno($lcon) ); }
				{  $data['record'][0]['response'] = '3'; echo json_encode($data); exit(); }
                        
            $en=ldap_get_entries($lcon,$sr);
            $dn = $en[0]['dn'];
			if (strlen($dn) <= 0) {  $data['record'][0]['response'] = '4'; echo json_encode($data); exit(); }

            $rbind = @ldap_bind($lcon,$dn,$_POST['upass']);
            if (!$rbind) //{ echo ldap_err2str( ldap_errno($lcon) ); exit; }
				{  $data['record'][0]['response'] = '5'; echo json_encode($data); exit(); }

			getDatabase()->execute('DELETE FROM registered_devices where login = :login', array(':login' => $_POST['uname']));
			getDatabase()->execute('INSERT INTO registered_devices (login, device_id, os_name, uuid) values (:login,:device_id,:os_name,:uuid)'
			,array(':login' => $_POST['uname'],':device_id' => $_POST['device_type'],':os_name' => $_POST['osname'],':uuid' => $_POST['uuid']));
			$data['record'][0]['response'] = '6';
			echo json_encode($data);
		
	}
?>