<?php

$str = file_get_contents("php://input");
$id = $_GET['sysid'];
file_put_contents($_SERVER["DOCUMENT_ROOT"]."/scripts/idphoto/images/".$id.".jpg", pack("H*", $str));

?>