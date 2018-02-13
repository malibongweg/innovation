<?php

$options = array(
"--step", "300",
"DS:temp:GAUGE:600:0:U",
"DS:humi:GAUGE:600:0:U",
"RRA:AVERAGE:0.5:1:600",
"RRA:AVERAGE:0.5:6:700",
"RRA:AVERAGE:0.5:24:775",
"RRA:AVERAGE:0.5:288:797",
"RRA:MAX:0.5:1:600",
"RRA:MAX:0.5:6:700",
"RRA:MAX:0.5:24:775",
"RRA:MAX:0.5:288:797",
 );

$ret = rrd_create("/var/www/html/scripts/mrtg/temp_bellville.rrd", $options, count($options));
if (! $ret) {
 echo "<b>Creation error: </b>".rrd_error()."\n";
}

?>
