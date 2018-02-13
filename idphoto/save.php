<?php

$filename = date('YmdHis') . '.jpg';
$str = file_get_contents('php://input');
$result = file_put_contents( '/scripts/idphoto/' . $filename,pack("H*",$str);
if (!$result) {
    print "ERROR: Failed to write data to $filename, check permissions\n";
    exit();
}

$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' 
     . $filename;
print "$url\n";
?>