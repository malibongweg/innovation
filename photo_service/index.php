<?php
//if ($_SERVER['REMOTE_ADDR'] != '10.47.2.149') die ('Function not allowed.');
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);//realpath(dirname(__FILE__)));
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = JFactory::getDBO();
if (isset($_GET['uid'])) {
	//Get location of stored photo
	$sql = sprintf("select location from identity.photos where userid = %d",$_GET['uid']);
	$dbo->setQuery($sql);
	$dbo->query();
	$cnt = $dbo->getNumRows();
	if ($cnt == 0) {
		echo "null"; //If no record found
	} else {
		$url = $dbo->loadResult();
		echo "https://opa.cput.ac.za".$url;
	}
}

?>
