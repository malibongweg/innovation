<?php
/*$fname = $_SERVER['DOCUMENT_ROOT'];
require_once($fname."/configuration.php");
$jconfig = new JConfig();
$option = array(); //prevent problems
$option['driver']   = $jconfig->dbtype;            // Database driver name
$option['host']     = $jconfig->host;   // Database host name
$option['user']     = $jconfig->user;       // User for database authentication
$option['password'] = $jconfig->password;   // Password for database authentication
$option['database'] = $jconfig->db;      // Database name
$option['prefix']   = $jconfig->dbprefix;    
$dbo = & JDatabase::getInstance( $option );
*/
$dbo = &JFactory::getDBO();
if (isset($_GET['action'])) {


	if ($_GET['action'] == 'search_knowledge') {
		$dbo->setQuery("call proc_pop_application('Knowledge Base')");
		$dbo->query();
		$keywords = strtolower($_GET['keywords']);

		if (strlen($keywords) <= 1) {
			$sql = "select distinct a.id,a.question,a.keywords,
			(select count(b.q_id) from #__knowledge_answers b where a.id = b.q_id) as cnt 
			from #__knowledge_base a order by id desc limit 50";
		} else {
		$sql = "select distinct a.id,a.question,a.keywords,
		(select count(b.q_id) from #__knowledge_answers b where a.id = b.q_id) as cnt 
		 from #__knowledge_base a where match(a.keywords) against('";
		if (intval($_GET['stype']) == 0) {
				$sql .= urldecode($keywords)."' in boolean mode)";
			} else {
				$kw = explode(" ",urldecode($keywords));
				$match = "";
				foreach($kw as $word) {
					$match .= "+".$word." ";
				}
				$match = substr($match,0,-1);
				$sql .= $match."' in boolean mode)";
			}
		}
		//echo $sql;exit();
		$dbo->setQuery($sql);
		$data = array();
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			//$data[$row->id] = $row->question.";1;".$row->keywords;
			$data[$row->id] = $row->question.";".$row->cnt.";".$row->keywords;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'excempt') {
		$data = array();
		$dbo->setQuery("select keyword from #__knowledge_keywords_excempt order by keyword");
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadRowList();
		foreach($result as $row) {
			$data[] = $row;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'get_q') {
		$data = array();
		$dbo->setQuery("select id,question,keywords from #__knowledge_base where id=".$_GET['id']);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$data[] = $row->id.";".$row->question.";".$row->keywords;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'get_possibles') {
		$dbo->setQuery("select id,q_id,answer from #__knowledge_answers where q_id=".$_GET['id']);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row) {
			$data[$row->id] = $row->answer;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'display_q') {
		$dbo->setQuery("select id,q_id,answer from #__knowledge_answers where id=".$_GET['id']);
		$dbo->query();
		if ($dbo->getNumRows() == 0) { $data[] = "-1"; echo json_encode($data); exit(); }
		$result = $dbo->loadObjectList();
		foreach($result as $row){
			$data[$row->id] = $row->answer;
		}
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'update_q') {
		$sql = sprintf("update #__knowledge_base set keywords='%s' where id=%d",urldecode($_POST['k']),$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) echo "-1";
		else echo "1";
	}
	else if ($_GET['action'] == 'update_a') {
		$dbo->setQuery("select proc_check_knowledge_base(".$_POST['qid'].") as cnt");
		$cnt = $dbo->loadResult();
		if ($cnt == 0) {
			$sql = sprintf("insert into #__knowledge_answers (q_id,answer) values (%d,'%s')",$_POST['recid'],urldecode(addslashes($_POST['txt'])));
		} else {
			$sql = sprintf("update #__knowledge_answers set answer='%s' where id=%d",urldecode(addslashes($_POST['txt'])),$_POST['qid']);
		}
		$dbo->setQuery($sql);
		$result = $dbo->query();
		if (!$result) echo "-1";
		else echo "1";
	}
	else if ($_GET['action'] == 'save_new') {
		$sql = sprintf("insert into #__knowledge_base(question,keywords) values ('%s','%s')",addslashes(urldecode($_GET['q'])),addslashes(urldecode($_GET['q'])));
		$dbo->setQuery($sql);
		//echo $sql; exit();
		$result = $dbo->query();
		if (!$result)
			$data[] = "-1";
		else $data[] = "1";
		echo json_encode($data);
	}
	else if ($_GET['action'] == 'del_answer') {
		$dbo->setQuery("delete from #__knowledge_answers where id = ".$_GET['id']);
		$dbo->query();
	}

}
exit();

?>