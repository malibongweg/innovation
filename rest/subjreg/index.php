<?php
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

getRoute()->post('/', 'subjReg');
getRoute()->run();


/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
function subjReg()
{

        $record = getDatabase()->all("select distinct concat('[',a.subj_code,'] ',b.subj_desc) as subject_desc,concat('BLOCK=',a.subj_block,' OT=',a.subj_ot,' CG=',a.subj_classgrp) as subj_details,a.subj_cancel from							student.subject2013 a left outer join structure.subject b on (a.subj_code=b.subj_code) where a.stud_numb=:id", array(':id' => $_POST['uname']));
		$data = array();

		foreach ($record as $key => $value) {
			$data['record'][$key] = $value;
		}
		echo json_encode($data);
		
}
?>

