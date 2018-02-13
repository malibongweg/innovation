<?php

$options = array(
"--step", "300",
"DS:temp:GAUGE:900:0:U",
"DS:humi:GAUGE:900:0:U",
"RRA:AVERAGE:0.5:1:900",
 );

$ret = rrd_create("/var/www/html/scripts/mrtg/temp_bellville.rrd", $options, count($options));
if (! $ret) {
 echo "<b>Creation error: </b>".rrd_error()."\n";
}

?>
