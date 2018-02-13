<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);

if (isset($_GET['action'])) {


	if ($_GET['action'] == 'display_list') {
		$data = array();
		$dbo->setQuery("select a.date_requested,a.id,concat(d.staff_title,' ',d.staff_init,' ',d.staff_sname) as applicant,
		b.build_name,a.roomno,e.description as job_status
		from cput_jobcards a left outer join structure.buildings b on (a.building = b.build_code)
		left outer join structure.department c on (a.department = c.dept_code)
		left outer join staff.staff d on (a.applicant = d.staff_no)
		left outer join cput_jobcard_status e on (a.job_status = e.id)
		where a.campus = ".$_GET['campus']." order by a.date_requested,a.id desc limit 1000");
		$dbo->query();
		if ($dbo->getNumRows() ==  0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[] = $row->date_requested.";".$row->id.";".$row->applicant.";".$row->build_name.";".$row->roomno.";".$row->job_status
		}
		echo json_encode($data);
	}
	
}
exit();