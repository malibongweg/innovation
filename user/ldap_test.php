<?php

						$lcon = ldap_connect("10.19.0.11");
                        if (!$lcon) { echo ldap_err2str(ldap_errno($lcon)); exit(); }

						ldap_set_option($lcon, LDAP_OPT_PROTOCOL_VERSION, 3);
						ldap_set_option($lcon, LDAP_OPT_REFERRALS, 0);

						$lbind = ldap_bind($lcon,"ads\ga-ldap-browse","CPUTadmin2008");
                        if (!$lbind) { echo ldap_err2str(ldap_errno($lcon)); exit(); }
	
						$base_dn = "dc=ads,dc=cput,dc=ac,dc=za";
						$filter = "(&(objectClass=User)(sAMAccountName=stevensm1))";

						$result = ldap_search($lcon,$base_dn,$filter);
						if (!$result) { echo ldap_err2str(ldap_errno($lcon)); exit(); }

                        $en=ldap_get_entries($lcon,$result);
                        $dn = $en[0]['dn'];
						if (strlen($dn) <= 0) { echo "-3"; exit(); }

                        $rbind = @ldap_bind($lcon,$dn,'Mms2604202@11');
                        if (!$rbind) { echo ldap_err2str(ldap_errno($lcon)); exit(); }
	
						ldap_close($lcon);
						echo "1";
?>
