<?php
//ini_set('error_reporting',1);
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);//realpath(dirname(__FILE__)));
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );

$dbo = &JFactory::getDBO();

if (isset($_GET['action'])) {
	if ($_GET['action'] == "statusCampusEntries"){
		$sql = sprintf("select a.campus_code, a.campus_name from structure.campus a where a.campus_code in
		(select b.campus from jobcards.jobcards b)");
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == "statusReportData"){
		$sql = sprintf("select b.status_desc, count(a.id) as cnt from jobcards.jobcards a left outer join jobcards.jobcard_status b on (a.job_status=b.id)
		left outer join structure.campus c on (a.campus=c.campus_code) where year(a.capture_date) = year(now()) and a.campus = %d group by a.campus, a.job_status",$_GET['cmp']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['cols'][0]['id'] = "";
		$return['cols'][0]['label'] = "Status";
		$return['cols'][0]['type'] = "string";
		$return['cols'][1]['id'] = "";
		$return['cols'][1]['label'] = "Jobs";
		$return['cols'][1]['type'] = "number";

		$i = 0;
		foreach($result as $row){
			$return['rows'][$i]['c'][0]['v'] = $row->status_desc .' [' .$row->cnt .']';
			$return['rows'][$i]['c'][0]['f'] = null;
			$return['rows'][$i]['c'][1]['v'] = intval($row->cnt);
			$return['rows'][$i]['c'][1]['f'] = null;
			++$i;
		}

		echo json_encode($return);
	}
else if ($_GET['action'] == "statusReportRatings"){
		$sql = sprintf("select a.job_rating, count(a.id) as cnt from jobcards.jobcards a
				where year(a.capture_date) = year(now()) and a.campus = %d group by a.campus, a.job_rating",$_GET['cmp']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['cols'][0]['id'] = "";
		$return['cols'][0]['label'] = "Rating";
		$return['cols'][0]['type'] = "string";
		$return['cols'][1]['id'] = "";
		$return['cols'][1]['label'] = "";
		$return['cols'][1]['type'] = "number";

		$i = 0;
		foreach($result as $row){
			$desc = 'Not Rated';
			switch (intval($row->job_rating)){
				case 0: $desc = 'Not Rated';
						break;
				case 1: $desc = 'Poor';
						break;
				case 2: $desc = 'Fair';
						break;
				case 3: $desc = 'Good';
						break;
				case 4: $desc = 'Excelent';
						break;
			}
			$return['rows'][$i]['c'][0]['v'] = $row->job_rating .' [' .$row->cnt .']';
			$return['rows'][$i]['c'][0]['f'] = $desc;
			$return['rows'][$i]['c'][1]['v'] = intval($row->cnt);
			$return['rows'][$i]['c'][1]['f'] = null;
			++$i;
		}

		echo json_encode($return);
	}
	else if ($_GET['action'] == "repLabour"){
		$sql = sprintf("select a.artisan, b.id, b.assigned_date, b.completion_date, ifnull(a.hours,'Not Filed.') as hours,
		concat(d.staff_sname,', ',d.staff_title,' ',d.staff_fname) as fullname,
		ifnull((select timediff(c.end_time,c.start_time) from jobcards.jobcard_delays c where c.jobcard = a.jobcard),0) as delays
		from jobcards.jobcard_artisans a left join jobcards.jobcards b on (a.jobcard=b.id)
		left outer join staff.staff d on (a.artisan = d.staff_no)
		where a.artisan=%d and b.assigned_date >= '%s' and b.assigned_date <= '%s' and b.completion_date is not null",$_GET['artisan'],
		$_GET['sdate'],$_GET['edate']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			$return = array();
			$return['Records'] = null;
			echo json_encode($return);
		} else {
			$result = $dbo->loadObjectList();
			$return = array();
			$return['Records'] = $result;
			echo json_encode($return);
		}
	}
	else if ($_GET['action'] == "getCampusName"){
		$sql = sprintf("select a.campus_name from structure.campus a where a.campus_code=%d",$_GET['campus']);
		$dbo->setQuery($sql);
		$campus = $dbo->loadResult();
		echo $campus;
	}
	else if ($_GET['action'] == "getCampusBuildings"){
		$sql = sprintf("select a.build_code,a.build_name from structure.buildings a where a.campus_code=%d and a.build_code in
		(select b.building from jobcards.jobcards b) ",$_GET['campus']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == "getCosts"){
		$sql = sprintf("select a.id,a.capture_date,ifnull(a.material_cost,0) as material_cost,ifnull(a.labour_cost,0) as labour_cost from jobcards.jobcards a
		where a.capture_date >= '%s' and a.capture_date <= '%s' and a.campus = %d and a.building = %d",
		$_GET['sdate'],$_GET['edate'],$_GET['campus'],$_GET['build']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Records'] = $result;
		echo json_encode($return);
	}
	else if ($_GET['action'] == "getOpenJobcards"){
		$sql = "select a.id,a.capture_date,ifnull(a.assigned_date,'NOT ASSIGNED') as assigned_date,b.build_name,c.status_desc,timediff(now(),a.capture_date) as openhours
		from jobcards.jobcards a left outer join structure.buildings b on(a.building=b.build_code)
		left outer join jobcards.jobcard_status c on (a.job_status=c.id) where 1=1 ";
		switch (intval($_GET['jtype'])){
			case 1: $sql .= " and a.job_status in (1,2,4,5) ";
					break;
			case 2: $sql .= " and a.job_status in (3,6) ";
					break;
		}
		if (intval($_GET['urgent']) ==1){
			$sql .= " and a.urgent = 1 ";
		}
		$sql .= sprintf(" and a.campus = %d	and a.capture_date >= '%s' and a.capture_date <= '%s' order by a.capture_date",$_GET['campus'],$_GET['start-date'],$_GET['end-date']);
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$return = array();
		$return['Records'] = $result;
		echo json_encode($return);
	}

}

?>
