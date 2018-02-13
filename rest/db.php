<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();


$sql = "select a.failure_date,trim(a.failure_message) as msg from portal.cput_service_failure a 
		left outer join portal.cput_service_failure_severity c on (a.severity_code = c.id)
		where year(a.failure_date) = year(now()) and a.current_state = 0 order by failure_date desc";
$dbo->setQuery($sql);
$result = $dbo->loadObjectList();
$return = array();
$return['Records'] = $result;

echo json_encode($return);