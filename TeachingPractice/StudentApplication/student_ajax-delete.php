<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
require_once("scripts/system/functions.php");

if ($_GET['action'] == "display_info") {
		$pt = $_SERVER['DOCUMENT_ROOT']."/scripts/crons/config.cfg";
		$fp = file($pt);
		$ldap = $fp[4];
		$cnt = preg_match('/^[0-9]+$/',$_GET['uid']);
		if ($cnt >= 1) {
			$username = $_GET['uid']; 
		} else {
			$sql = sprintf("select userid from cput_users_cellular where login='%s'",$_GET['uid']);
			$dbo->setQuery($sql);
			$row = $dbo->loadObject();
			$username = $row->userid;
                  
		
}
}
        else if ($_GET['action'] == "display_stud_info") {
		$data = array();
		$username = $_GET['uid']; 
		$sql = sprintf("select a.stud_numb,a.reg_date,a.stud_block,e.off_name as ot,f.qual_desc,i.`email`,
							concat(b.pers_fname,' ',b.pers_sname) as fullname,c.fac_desc,d.dept_desc
							from student.student2013 a
							left outer join student.personal b on (a.stud_numb = b.stud_numb)
							left outer join structure.faculty c on (a.stud_fact = c.fac_code)
							left outer join structure.department d on (a.stud_dept = d.dept_code)
							left outer join structure.offerings e on (a.stud_ot = e.off_code)
							left outer join structure.qualification f on (a.stud_qual = f.qual_code)							
							left outer join portal.cput_users i on (a.stud_numb = i.username)
							where a.stud_numb = %d",$username);
							
		$dbo->setQuery($sql);
		$rows = $dbo->loadObject();
                //$data = array();
               
                
                ////////////For multiple rows/////////
                  //$rows = $dbo->loadObjectList();
                  //$data['Record'] = $rows;
                /////////////////////////////////////
               
                
		if (is_object($rows)) {
                $data['Record'][]= $rows; 
                $data['Restult'] = 'OK';
	//echo $row->stud_numb.";".$row->reg_date.";".$row->stud_block.";".$row->ot.";".$row->qual_desc.";".$row->email.";".$row->fullname.";".$row->fac_desc.";".$row->dept_desc.";".$row->cell;
		} else {
				//echo "-1";
                    $data['Result'] = 'ERR';
		}
 echo json_encode($data);
       }
	
   else if ($_GET['action'] == "save_criteria") {
    //do sql here.....
             
 $sql1 = sprintf("select a.schoolcode from teaching_practice.SchoolTab a where a.schoolname = '%s'",$_POST['select_school']);
              
$dbo->setQuery($sql1);
$row = $dbo->loadObject();
 
    
$sql2 = sprintf("select SYSDATE() AS placed from DUAL");     
$dbo->setQuery($sql2);
$row1 = $dbo->loadObject();   
    
    $checkstud = sprintf("select StudentNo from teaching_practice.TeachStud where StudentNo = %d ",$_POST['stud_nr']);
    $dbo->setQuery($checkstud);
   // $row5 = $dbo->loadObject();
    $result = $dbo->query();
    if($dbo->getNumRows()== 0) 
    {
      
        $sql2 = sprintf("insert into teaching_practice.TeachStud(StudentNo, StudentName, DateReg, DatePlaced, schoolcode)
        values('%s','%s','%s','%s',%d)", $_POST['stud_nr'],$_POST['f_name'], $_POST['r_date'],$row1->placed,  $row->schoolcode);
        $dbo->setQuery($sql2);
        $result = $dbo->query();
    }        
    
       if (!$result) 
           { echo "-1";
           exit(); 
         
           }		
		 else {        
            //  echo $_POST['subs_offered'].';'.$_POST['new_grade'].';'.$_POST['lang'];
   
    
         $sql1 = sprintf("select a.schoolcode from teaching_practice.SchoolTab a where a.schoolname = '%s'",$_POST['select_school']);
   
         $dbo->setQuery($sql1);
         $row = $dbo->loadObject();
 
       
        $grade = sprintf("select a.GradeSeqNo from teaching_practice.Grades a where a.GradeDesc = '%s'", $_POST['new_grade']);
         $dbo->setQuery($grade);
         $row4 = $dbo->loadObject();

    
 $subject = sprintf("select a.SubCode from teaching_practice.Subjects a where a.SubName ='%s'", $_POST['subs_offered']);
 $dbo->setQuery($subject);
 $row3 = $dbo->loadObject();
   
       
 $sql3 = sprintf("insert into teaching_practice.SchoolSubs(schoolcode, GradeSeq, SubCode, Status, StudentNo)
  values(%d,%d,%d,'%s', '%s')", $row->schoolcode, $row4->GradeSeqNo, $row3->SubCode,'Available', $_POST['stud_nr']);        
 $dbo->setQuery($sql3);
 $rows = $dbo->loadObject();
   //$$dbo->setQuery($sql3);
 //$rows = $dbo->loadObject();
 
//$sql9 = sprintf("select schoolcode, GradeSeqNo, SubCode, StudentNo from teaching_practice.SchoolSubs where StudentNo = '%s'",$_POST['stud_nr']);		   
//$dbo->setQuery($sql9);
// $rows1 = $dbo->loadObject();
  $data =  array();  
 
//$result = $dbo->query();
// if (is_object($rows)) {
//                $data['Record'][]= $rows; 
//                $data['Restult'] = 'OK';
//                } else {
//                  $data['Result'] = 'ERR';  
//                    //echo $row3->SubCode.';'.$row4->GradeSeqNo.';'.$row->schoolcode.';'.$_POST['stud_nr'];
// //$data['Result'] = 'OK';
//  }
// echo json_encode($data);
//   
//    
//  
  }
   }else
   {
       $sql3 = sprintf("insert into teaching_practice.SchoolSubs(schoolcode, GradeSeq, SubCode, Status, StudentNo)
           values(%d,%d,%d,'%s', '%s')", $row->schoolcode, $row4->GradeSeqNo, $row3->SubCode,'Available', $_POST['stud_nr']);        
    $dbo->setQuery($sql3);
     $data = array();  
		   
 $result = $dbo->query();
 if (!$result){
 $data['Result'] = 'ERR';
 } else {
 $data['Result'] = 'OK';
 }
echo json_encode($data);
   
   }
//  if ($_GET['action'] == "delete_subj"){
//      
//       $sql = sprintf("DELETE FROM teaching_practice.SchoolSubs
//               WHERE SubCode = %d
//               and schoolcode = %d
//               and GradeSeq = %d
//               and StudentNo = %d",$_GET['code'],$_GET['grade'],$_GET['scode'],$_GET['snum']);
//       $dbo->setQuery($sql);
//       $result = $dbo->query();
//       
//       echo $sql;
//   }
//  
   
   
   
   
    exit();
   
           
         
?>
		