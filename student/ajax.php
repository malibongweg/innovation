<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);

if (isset($_GET['action'])) {


	if ($_GET['action'] == 'displayyear') {
		$data = array();
		$sql = sprintf("select A.subj_code, A.subj_ot, subj_sapse, subj_credit, subj_desc, A.subj_year, A.subj_cancel, if(subj_comment=' ', '.', subj_comment) as subj_comment, subj_block, subj_yearmark, subj_exammark, subj_suppress, subj_finalmark, subj_qualif, A.subj_ot from student.subject" .$_GET['yr']." A, structure.subject B where stud_numb = '%s' and A.subj_code = B.subj_code and A.subj_year = B.subj_year and A.subj_ot = B.subj_ot  order by subj_year desc, subj_block desc, A.subj_code ",$_GET['stno']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$data[] = $row->subj_year.";".$row->subj_block.";".$row->subj_qualif.";".$row->subj_sapse.";".$row->subj_ot.";".$row->subj_code.";".$row->subj_desc.";".$row->subj_yearmark.";".$row->subj_exammark.";".$row->subj_finalmark.";".$row->subj_comment.";".$row->subj_credit.";".$row->subj_cancel.";".$row->subj_suppress;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'displayall') {
		for ($i = $_GET['byr']; $i >= $_GET['eyr']; $i --) {
			$sql = sprintf("select A.subj_code, A.subj_ot, subj_sapse, subj_credit, subj_desc, A.subj_year, A.subj_cancel, if(subj_comment=' ', '.', subj_comment) as subj_comment, subj_block, subj_yearmark, subj_exammark, subj_suppress, subj_finalmark, subj_qualif, A.subj_ot from student.subject" .$i." A, structure.subject B where stud_numb = '%s' and A.subj_code = B.subj_code and A.subj_year = B.subj_year and A.subj_ot = B.subj_ot  order by subj_year desc, subj_block desc, A.subj_code ",$_GET['stno']);
			$dbo->setQuery($sql);
			$dbo->query();
			if ($dbo->getNumRows() == 0) continue;
			$result = $dbo->loadObjectList();
			foreach($result as $row){
				$data[] = $row->subj_year.";".$row->subj_block.";".$row->subj_qualif.";".$row->subj_sapse.";".$row->subj_ot.";".$row->subj_code.";".$row->subj_desc.";".$row->subj_yearmark.";".$row->subj_exammark.";".$row->subj_finalmark.";".$row->subj_comment.";".$row->subj_credit.";".$row->subj_cancel.";".$row->subj_suppress;
			}
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'whoami') {
		$sql = sprintf("select user_id,group_id from #__user_usergroup_map where user_id = %d and group_id = 9",$_GET['uid']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
			if ($dbo->getNumRows() == 0) { echo "0"; } else { echo "1"; }
	}
	else if ($_GET['action'] ==	getStudentDetails) {
		$sql = sprintf('select concat(a.pers_title," ",a.pers_fname," ",a.pers_sname) as sname from student.personal a where a.stud_numb = %d',$_GET['stno']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObject();
		echo $result->sname;
	}

}
exit();

?>