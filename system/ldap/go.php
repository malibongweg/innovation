<?
require_once("class.Email.php");
$d = getdate();
$lcon = @ldap_connect("10.47.20.1");
		if (!$lcon) {print "Error";Exit;}
        $lbind = @ldap_bind($lcon);
		$filter="(objectclass=inetOrgPerson)";
		$prop = array("cn","passwordexpirationtime","mail","logindisabled");
        $sr = @ldap_search($lcon,"ou=Staff,ou=Main,o=cput",$filter,$prop);
  			
		$en=ldap_get_entries($lcon,$sr);
		$fp = fopen("/var/www/html/novell_update/sessions","w");
		foreach ($en as $key=>$value)
		{
					$mail_array = array();
					if (isset($value['mail']))
					{
						$mail_array = $value['mail'];
						foreach ($mail_array as $keym => $valuem)
						{
							if (stripos($valuem,"@cput.ac.za") > 0)
							{
								$email = $valuem;
								break;
							}
						}
					}

					$dnm = $value['dn'];
					$dn = $value['dn'];
					$dn_array = explode("=",$dn);
					$dn = $dn_array[1];
					$dn_array = explode(",",$dn);
					$dn = $dn_array[0];
					$cn = strtolower($dn);
					//print $cn."\n";
			
					if (isset($value['passwordexpirationtime'][0])) {$expire = $value['passwordexpirationtime'][0];}

					$y = substr($expire,0,4);
					$m = substr($expire,4,2);
					$d = substr($expire,6,2);
					$sysdate = strtotime ('now');
					$obj = mktime(0,0,0,$m,$d,$y);
					$exp = intval((($obj-$sysdate)/86400));
						if (($exp >= 0) and $exp <= 5)
						{
							$crypt = $cn.$d['mday'].$d['mon'].$d['year'];
							$key = md5($crypt)."\n";
							fwrite($fp,$key);

							$email_details = "CTS NOTIFICATION\n\n";
							$email_details .= "NDS Object => {$dnm}\n";
							$email_details .= "Please note that your Novell user name and password\n";
							$email_details .= "will expire within the next {$exp} days.\n\n";
							$email_details .= "Please update your password at the following link:\n";
							$email_details .= "http://10.47.2.23/novell_update/index.php?key={$key}\n\n";
							$email_details .= "Please report any errors to the helpdesk @ (021)9596407\n";
							$Sender = "no-reply <apache@theseus.cput.ac.za>"; 
							//$Recipiant = "stevensm@cput.ac.za";
							$Recipiant = $email;
							$Subject = "Novell Password Expiration Notification";
							$CustomHeaders= "";
							$Bcc = ""; 
							$Cc = "";
							$message = new Email($Recipiant, $Sender, $Subject, $CustomHeaders);
							$message->Cc = $Cc; 
							$message->Bcc = $Bcc; 
							$message->SetTextContent($email_details);
							if (isset($email))
							{
							$message->Send();
							}
							unset($message);
		
						}
		}
		ldap_close($lcon);
		fclose($fp);
?>