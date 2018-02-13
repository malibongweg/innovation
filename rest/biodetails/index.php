<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//chdir('..');
include_once ('../src/Epi.php');
Epi::setPath('base', '../src');
Epi::init('config');
Epi::init('route','database');
Epi::setSetting('exceptions', true);
Epi::setPath('config', dirname(__FILE__));
getConfig()->load('configuration.ini');
$mysql_user = getConfig()->get('mysql_user');
$mysql_pass = getConfig()->get('mysql_pass');
$mysql_host = getConfig()->get('mysql_host');
EpiDatabase::employ('mysql','mobile',$mysql_host,$mysql_user,$mysql_pass); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]

getRoute()->post('/', 'bioDetails');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function bioDetails()
{
		$data = array();
        $rec = getDatabase()->one("SELECT stud_numb,CONCAT(pers_title,' ',pers_fname,' ',pers_sname) as student_name,pers_idno,pers_dob,pers_saddr1,pers_saddr2,pers_saddr3,pers_scode
									  FROM student.personal_curr WHERE stud_numb = :id", array(':id' => $_POST['uname']));
		$data['record'][0]['studentno'] = $rec['stud_numb'];
		$data['record'][0]['studentname'] = $rec['student_name'];
		$data['record'][0]['idno'] = $rec['pers_idno'];
		$data['record'][0]['addr1'] = $rec['pers_saddr1'];
		$data['record'][0]['addr2'] = $rec['pers_saddr2'];
		$data['record'][0]['addr3'] = $rec['pers_saddr3'];
		$data['record'][0]['postcode'] = $rec['pers_scode'];
		echo json_encode($data);
}
?>

