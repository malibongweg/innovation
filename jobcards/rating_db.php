<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);//realpath(dirname(__FILE__)));
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

$sql = sprintf("update jobcards.jobcards set job_rating=%d,rating_details='%s' where cde='%s'",$_POST['job_rating'],
		$_POST['rating_details'],$_POST['rating_cde']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) { echo $dbo->getErrorMsg(); } else {
		echo "1";}

?>
