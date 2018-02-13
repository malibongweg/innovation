<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

if (isset($_GET)) {
	if ($_GET['action'] == 'showSchools'){
		$html = '<table width="100%" style="border: 1px solid #000000">';
		$sql = sprintf("select a.schoolcode,a.schoolname,a.telephone,a.faxnumber,a.principal
						from teaching_practice.SchoolTab a order by a.schoolname");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$html .= '<tr>';
			$html .= '<td width="5%" style="border: 1px solid gray"><input type="radio" name="recid" onclick="editSchool('.$row->schoolcode.');"     value="'.$row->schoolcode.'"></td>';
			$html .= '<td width="50%" style="border: 1px solid gray">'.$row->schoolname.'</td>';
			$html .= '<td width="10%" style="border: 1px solid gray">'.$row->telephone.'</td>';
			$html .= '<td width="10%" style="border: 1px solid gray">'.$row->faxnumber.'</td>';
			$html .= '<td width="25%" style="border: 1px solid gray">'.$row->principal.'</td>';
			$html .= '</tr>';
		}

		$html .= '</table>';
		echo $html;
	}
	else if ($_GET['action'] == 'editSchool') {
		$sql = sprintf("select schoolcode,schoolname from teaching_practice.SchoolTab where schoolcode = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObject();
		echo $result->schoolname;
	}

}
exit();
?>

