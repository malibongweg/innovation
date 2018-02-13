<?php
echo getmypid()."\n";
$fname = dirname(__FILE__);
$fname .= "/pcounter.proc";
	if (file_exists($fname)) {
		$ft = date('H:i:s',filemtime($fname));
		$dt = date('H:i:s');
		echo $dt."\n";
		$diff = strtotime($dt) - strtotime($ft);
			if (intval(abs($diff)) >= 600) {
				$pidfile = dirname(__FILE__);
				$pidfile .= "/pcounter.pid";
				$fp = fopen($pidfile,"r");
				$pid = fgets($fp, 4096);
				fclose($fp);
				$r = system("kill -9 ".$pid);	
						unlink($fname);
						unlink($pidfile);
			}
	}

?>
