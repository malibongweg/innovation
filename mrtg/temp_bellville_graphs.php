<?php
create_graph("temp_bellville-hour.png",  "Hourly Temperatures");
create_graph("temp_bellville-day.png", "Daily Temperatures");
create_graph("temp_bellville-week.png",  "Weekly Temperatures");
create_graph("temp_bellville-month.png",  "Monthly Temperatures");
create_graph("temp_bellville-year.png", "Yearly Temperatures");

function create_graph($output, $title) {
  $options = array(
    "--slope-mode",
    "--start=-86400",
    "--end=-300",
    "--title=$title",
    "--vertical-label=Celcius/Percent",
    "--height=200",
    "--width=500",
    "--base=1000",
    "--alt-autoscale-max",
    "--lower-limit='10'",
    "DEF:temp=/var/www/html/scripts/mrtg/temp_bellville.rrd:temp:AVERAGE",
    "DEF:humi=/var/www/html/scripts/mrtg/temp_bellville.rrd:humi:AVERAGE",
    "AREA:humi#0000FF:Humidity",
    "AREA:temp#FF0000:Temperature",
    "GPRINT:humi:AVERAGE:Humidity %0.2lf",
    "GPRINT:temp:AVERAGE:Temperature %0.2lf",
  );

  $ret = rrd_graph($output, $options, count($options));
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
  }
}

?>
