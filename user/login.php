<?php
$dbo = &JFactory::getDBO();

if (isset($_GET['action'])) {
	
	if ($_GET['action'] == 'check_login') {
						
						//$lcon = @ldap_connect("10.19.0.11");
						$lcon = @ldap_connect("10.47.1.12");
                        if (!$lcon) { echo "-1"; exit(); }

						ldap_set_option($lcon, LDAP_OPT_PROTOCOL_VERSION, 3);
						ldap_set_option($lcon, LDAP_OPT_REFERRALS, 0);

						$lbind = @ldap_bind($lcon,"ads\ga-ldap-browse","CPUTadmin2008");
                        if (!$lbind) { echo "-2"; exit(); }
	
						$base_dn = "dc=ads,dc=cput,dc=ac,dc=za";
						$filter = "(&(objectClass=User)(sAMAccountName=".$_GET['user']."))";

						$result = @ldap_search($lcon,$base_dn,$filter);
						if (!$result) { echo "-3"; exit(); }

                        $en=ldap_get_entries($lcon,$result);
                        $dn = $en[0]['dn'];
						if (strlen($dn) <= 0) { echo "-3"; exit(); }

                        $rbind = @ldap_bind($lcon,$dn,urldecode($_GET['pass']));
                        if (!$rbind) { echo "-4"; exit(); }

						echo "1";
	}
}
exit();
