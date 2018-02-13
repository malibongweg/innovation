<?php
echo getmypid()."\n";
$fname = dirname(__FILE__);
$fname .= "/main.proc";
	if (file_exists($fname)) {
		$ft = date('H:i:s',filemtime($fname));
		echo $ft."\n";
		$dt = date('H:i:s');
		echo $dt."\n";
		$diff = strtotime($dt) - strtotime($ft);
			if (intval($diff >= 600)) {
				$pidfile = dirname(__FILE__);
				$pidfile .= "/mainproc.pid";
				$fp = fopen($pidfile,"r");
				$pid = fgets($fp, 4096);
				fclose($fp);
				$r = system("kill -9 ".$pid);	
					if (!$r) {
						die();
					} else {
						unlink($fname);
					}
			}
	}

?>