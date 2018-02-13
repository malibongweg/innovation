<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
require_once("scripts/system/functions.php");

//check the user that logged in 
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

 else if ($_GET['action'] == "displaysubs") {
     $data = array();
     $sql = sprintf("select schoolcode from teaching_practice.SchoolTab where schoolname = '%s'",$_GET['sname']);
     $dbo->setQuery($sql);
     $row = $dbo->loadObject();
     $scode = $row->schoolcode;
      //echo $scode;
      $sql2 = sprintf("select subcode from teaching_practice.SchoolGrades2 where schoolcode = %d", $scode);
      $dbo->setQuery($sql2);
      $result = $dbo->loadObjectList();

     //   print $result[0]->subcode;
     //   $test = $result[0];
     //echo $test->subcode;

     /*$sql4 = sprintf("select GradeSeq from teaching_practice.SchoolGrades2 where schoolcode = %d", $scode);
     $dbo->setQuery($sql4);
     $result2 = $dbo->loadObjectList();*/

     $i = 0;
     foreach($result as $row2){
        // echo  $row2->subcode;
         $sql3 = sprintf("select subcode,subname from teaching_practice.Subjects where SubCode = %d", $row2->subcode);
         //echo $sql3;
         $dbo->setQuery($sql3);
         $row3 = $dbo->loadObject();

        /* $sql4 = sprintf("select GradeSeq from teaching_practice.SchoolGrades2 where SubCode = %d",$row2->subcode );
         $dbo->setQuery($sql4);
         $row4 = $dbo->loadObject();

         $sql5 = sprintf("select GradeSeqNo, GradeDesc from teaching_practice.Grades where GradeSeqNo = %d", $row4->GradeSeq);
         $dbo->setQuery($sql5);
         $row5 = $dbo->loadObject();*/

        // echo $row3->subcode;
         //echo $row3->subcode . ";" . $row3->subname;
             //$data[] = $row3->subcode . ";" . $row3->subname.  ";" .$row5->GradeSeqNo . ";" .$row5->GradeDesc;
         $data[] = $row3->subcode . ";" . $row3->subname;
            //echo $data;
         $i++;
     }

     /*$j = 0;
     foreach($result2 as $row4){
         $sql5 = sprintf("select GradeSeqNo,GradeDesc from teaching_practice.Grades where GradeSeqNo = %d", $row4->GradeSeq);
         //echo $sql3;
         $dbo->setQuery($sql5);
         $row5 = $dbo->loadObject();

         // echo $row3->subcode;
         //echo $row3->subcode . ";" . $row3->subname;
         $data[] = $row5->GradeSeqNo . ";" . $row5->GradeDesc;
         //echo $data;
         $j++;
     }*/

     echo json_encode($data);

 }

 else if ($_GET['action'] == "display_grades") {
    // $data = array();
     $sql = sprintf("select SubCode, SubName from teaching_practice.Subjects where SubName = '%s'",$_GET['subj']);
     $dbo->setQuery($sql);
     $row = $dbo->loadObject();
     $subj = $row->SubCode;
     //echo $scode;
     $sql2 = sprintf("select GradeSeq from teaching_practice.SchoolGrades2 where SubCode=%d", $subj);
     $dbo->setQuery($sql2);
     $result = $dbo->loadObjectList();
     //   print $result[0]->subcode;
     //   $test = $result[0];
     //echo $test->subcode;
     $i = 0;
     foreach($result as $row2){
         // echo  $row2->subcode;
         $sql3 = sprintf("select GradeSeqNo,GradeDesc from teaching_practice.Grades where GradeSeqNo = %d", $row2->GradeSeq);
         //echo $sql3;
         $dbo->setQuery($sql3);
         $row3 = $dbo->loadObject();
         // echo $row3->subcode;
         //echo $row3->subcode . ";" . $row3->subname;
         $data[] = $row3->GradeSeqNo . ";" . $row3->GradeDesc;
         //echo $data;
         $i++;
     }

     echo json_encode($data);

 }

//this is action takes the userid for the person that logged in and do the sql statement retrieving the user details 
 else if ($_GET['action'] == "display_stud_info") {
    $data = array();
    $username = $_GET['uid'];
    $sql = sprintf("select a.stud_numb,a.reg_date,a.stud_block,e.off_name as ot,f.qual_desc,i.email,a.stud_perstudy as level,
                        concat(b.pers_fname,' ',b.pers_sname) as fullname,c.fac_desc,d.dept_desc, z.phase as phase, z.type as stype
                        from student.student2015 a
                        left outer join student.personal b on (a.stud_numb = b.stud_numb)
                        left outer join structure.faculty c on (a.stud_fact = c.fac_code)
                        left outer join structure.department d on (a.stud_dept = d.dept_code)
                        left outer join structure.offerings e on (a.stud_ot = e.off_code)
                        left outer join structure.qualification f on (a.stud_qual = f.qual_code)
                        left outer join portal.cput_users i on (a.stud_numb = i.username)
                        left outer join teaching_practice.Criteria z on ( a.stud_qual= z.Qual)
                        where a.stud_numb= %d",$username);

     //echo $sql;

    $dbo->setQuery($sql);
    $rows = $dbo->loadObject();
    //$data = array();


    //For multiple rows
    //$rows = $dbo->loadObjectList();
    //$data['Record'] = $rows;

    //error checking
    if (is_object($rows)) {
        $data['Record'][]= $rows;
        $data['Restult'] = 'OK';
//echo $row->stud_numb.";".$row->reg_date.";".$row->stud_block.";".$row->ot.";".$row->qual_desc.";".$row->email.";".$row->fullname.";".$row->fac_desc.";".$row->dept_desc.";".$row->cell;
    } else {
            //echo "-1";
            $data['Result'] = 'ERR';
    }
   //this result from the query above is send back to the js in a json format
    echo json_encode($data);
 }

//this action is based on the selection criteria section a request that is send through from the js checks all this and should sent back a response
 else if ($_GET['action'] == "save_criteria") {
     
     
    //do sql here.....
     //a user selects a schoolname on the selection criteria section that is then checked against the database to get a schoolcode
   $sql1 = sprintf("select a.schoolcode from teaching_practice.SchoolTab a where a.schoolname = '%s'",$_POST['select_school']);

   $dbo->setQuery($sql1);
   $row = $dbo->loadObject();
    
    

   $sql2 = sprintf("select SYSDATE() AS placed from DUAL");
   $dbo->setQuery($sql2);
   $row1 = $dbo->loadObject();
     
   
 
  
        
       $checdub = sprintf("select StudentNo, schoolcode from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode = %d",$_POST['stud_nr'], $row->schoolcode);
        $dbo->setQuery($checdub);
        $result = $dbo->query();
        
        if ($dbo->getNumRows() == 0) {
            
        
        $data = array();
        $stud = $_POST['stud_nr'];
        $fullname =$_POST['f_name'];
        $rdate =$_POST['r_date'];
        
         //no duplicates
       $sql2 = sprintf("insert into teaching_practice.TeachStud(StudentNo, StudentName, DateReg, DatePlaced, schoolcode)
       SELECT '$stud','$fullname', '$rdate', '$row1->placed',  $row->schoolcode FROM DUAL
               WHERE NOT EXISTS (select schoolcode, StudentNo from teaching_practice.TeachStud where schoolcode = %d and StudentNo = '%s'",$row->schoolcode,$_POST['stud_nr']);
       $sql2 = $sql2.')';
       $dbo->setQuery($sql2);
       $result = $dbo->query();

         
       $checdub2 = sprintf("select StudentNo, schoolcode from teaching_practice.TeachStud where StudentNo = '%s'", $stud);
        $dbo->setQuery($checdub2);
        $rowg = $dbo->loadObject(); 
        
        if($rowg->schoolcode == $row->schoolcode){
       
       $sql4 = sprintf("update teaching_practice.SchoolTab set num_students = num_students - 1 where schoolcode = %d", $row->schoolcode);
      $dbo->setQuery($sql4);
      $result4 = $dbo->query(); 
         }
        }
        $checkstud = sprintf("select StudentNo, schoolcode from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode = %d",$_POST['stud_nr'], $row->schoolcode);
        $dbo->setQuery($checkstud);
        $result = $dbo->loadObject();
    
       
               
        if($result->schoolcode == $row->schoolcode)
        {
        
       //echo $result->schoolcode .' ' .$row->schoolcode;
       $sql11 = sprintf("select a.schoolcode, a.schoolname from teaching_practice.SchoolTab a where a.schoolname = '%s'", $_POST['select_school']);

       $dbo->setQuery($sql11);
       $row = $dbo->loadObject();

       $grade = sprintf("select a.GradeSeqNo, a.GradeDesc from teaching_practice.Grades a where a.GradeDesc = '%s'", $_POST['grade']);
       $dbo->setQuery($grade);
       $row4 = $dbo->loadObject();
       $res = $row4->GradeSeqNo;

       $subject = sprintf("select a.SubCode, a.SubName from teaching_practice.Subjects a where a.SubName ='%s'", $_POST['subs_offered']);
       $dbo->setQuery($subject);
       $row3 = $dbo->loadObject();
       $res1 = $row3->SubCode;

//check with Matsoso whether we can have two students teaching the same subject and grade at the same school???
//make sure user can only select one subject for 1 grade
       
       $selection = sprintf("select GradeSeq, SubCode,schoolcode from teaching_practice.SchoolSubs where GradeSeq = %d and SubCode = %d and schoolcode = %d" , $res, $res1, $row->schoolcode);
       $dbo->setQuery($selection);        
       $result7 = $dbo->query();
       
       if ($dbo->getNumRows() == 0) {
       
         $sql9 = sprintf("insert into teaching_practice.SchoolSubs(schoolcode, GradeSeq, SubCode, Status, StudentNo)
         values(%d,%d,%d,'%s', '%s')", $row->schoolcode, $res, $res1, 'Available', $_POST['stud_nr']);
           $dbo->setQuery($sql9);                                    
           $result = $dbo->query();
          
               
      
           $sql5 = sprintf("select seqno, schoolcode, GradeSeq, SubCode, StudentNo from teaching_practice.SchoolSubs where schoolcode =%d and GradeSeq=%d and SubCode = %d and StudentNo='%s'",
           $row->schoolcode, $res, $res1, $_POST['stud_nr']);
           $dbo->setQuery($sql5);
           $row5 = $dbo->loadObject();
               
           echo $row5->seqno;
      
       } else
       {
           echo '2';
       }
 }
 else
 {
     echo '-7';
 }
      
   
   
 }
   
//   else {
//                  
//        $data = array();
//    $checkstud2 = sprintf("select StudentNo, schoolcode from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode = %d",$_POST['stud_nr'], $row->schoolcode);
//    $dbo->setQuery($checkstud2);
//    $row6 = $dbo->loadObject();
//     echo $checkstud2;
//     
//  
//         if($row6->schoolcode == $row->schoolcode){
//
//       $sql1 = sprintf("select a.schoolcode, a.schoolname from teaching_practice.SchoolTab a where a.schoolname = '%s'", $_POST['select_school']);
//
//       $dbo->setQuery($sql1);
//       $row = $dbo->loadObject();
//
//       $grade = sprintf("select a.GradeSeqNo, a.GradeDesc from teaching_practice.Grades a where a.GradeDesc = '%s'", $_POST['grade']);
//       $dbo->setQuery($grade);
//       $row4 = $dbo->loadObject();
//       $res = $row4->GradeSeqNo;
//
//       $subject = sprintf("select a.SubCode, a.SubName from teaching_practice.Subjects a where a.SubName ='%s'", $_POST['subs_offered']);
//       $dbo->setQuery($subject);
//       $row3 = $dbo->loadObject();
//       $res1 = $row3->SubCode;
//
////check with Matsoso whether we can have two students teaching the same subject and grade at the same school???
////make sure user can only select one subject for 1 grade
//       $sql = sprintf("select GradeSeq, SubCode,schoolcode, StudentNo from teaching_practice.SchoolSubs where GradeSeq = %d and SubCode = %d and schoolcode = %d and StudentNo = '%s'" , $res, $res1, $row->schoolcode, $_POST['']);
//       $dbo->setQuery($sql);
//        //echo $sql;
//       $result = $dbo->query();
//
//       if ($dbo->getNumRows() == 0) {
//         $sql4 = sprintf("insert into teaching_practice.SchoolSubs(schoolcode, GradeSeq, SubCode, Status, StudentNo)
//         values(%d,%d,%d,'%s', '%s')", $row->schoolcode, $res, $res1, 'Available', $_POST['stud_nr']);
//           $dbo->setQuery($sql4);
//
//           $result = $dbo->query();
//           if (!$result) {
//               echo "-2";
//               exit();
//           } else {
//               $sql5 = sprintf("select seqno, schoolcode, GradeSeq, SubCode, StudentNo from teaching_practice.SchoolSubs where schoolcode =%d and GradeSeq=%d and SubCode = %d and StudentNo='%s'",
//                   $row->schoolcode, $res, $res1, $_POST['stud_nr']);
//               $dbo->setQuery($sql5);
//               $row5 = $dbo->loadObject();
//               
//             //  echo $row5->seqno;
//
//               //echo json_encode($data);
//               //echo '1';
//           }
//       }else {
//           echo '2';
//       }
        // }
          
      
   
   
 else if($_GET['action'] == 'show_subjects')
 {
     $data = array();
     $sql = sprintf("select SubName, GradeDesc, seqno
from teaching_practice.SchoolSubs t, teaching_practice.Grades g, teaching_practice.Subjects s
        where t.SubCode = s.SubCode
        and t.GradeSeq = g.GradeSeqNo
        and t.StudentNo='%s'",$_GET['stno']);
     $dbo->setQuery($sql);
     $result = $dbo->loadObjectList();
   
     foreach($result as $row){
        $data[] = $row->SubName . ";" . $row->GradeDesc. ';' . $row->seqno;        
     }

     echo json_encode($data);   
 }
     

 else if($_GET['action'] == 'delete_subject')
 {
     //url: 'index.php?option=com_jumi&fileid=172&studentno='+studentno+'&school='+school+'&grade='+grade+'&action=delete_subject',

     
     
     $sql = sprintf("delete from teaching_practice.SchoolSubs where teaching_practice.SchoolSubs.seqno = %d ",$_GET['sqno']);
     //echo $sql;
     $dbo->setQuery($sql);
     $result = $dbo->query();
     if(!$result)
     {
         echo '0';
     }
     else{
         
         
                  
     $sql1 = sprintf("select schoolcode from teaching_practice.SchoolTab where schoolname='%s'",$_GET['school_name']);
     echo $sql1;
     $dbo->setQuery($sql1);
     $row = $dbo->loadObject();
     
      $selection = sprintf("select GradeSeq, SubCode,schoolcode, StudentNo from teaching_practice.SchoolSubs where StudentNo = '%s'", $_GET['stno']);
       $dbo->setQuery($selection);        
       $result7 = $dbo->query();
       
       if ($dbo->getNumRows() == 0) {
           
     $sql22 = sprintf("delete from teaching_practice.TeachStud where StudentNo = '%s' ",$_GET['stno']);     
     $dbo->setQuery($sql22);
     $result22 = $dbo->query();
     
      $sql4 = sprintf("update teaching_practice.SchoolTab set num_students = num_students + 1 where schoolcode = %d", $row->schoolcode);
      $dbo->setQuery($sql4);
      $result4 = $dbo->query();
         
         echo '1';
       }
         //echo $_GET['sid'].';'.$_GET['id'];

     }
 }

 else if($_GET['action'] = 'list_subs')
 {
     $data = array();
     $sql = sprintf("select a.SubName, b.GradeDesc, c.seqno from teaching_practice.Subjects a, teaching_practice.Grades b, teaching_practice.SchoolSubs c
where c.SubCode = a.SubCode
and c.GradeSeq = b.GradeSeqNo
and c.StudentNo ='%s'", $_GET['stno']);
     $dbo->setQuery($sql);
     $result = $dbo->loadObjectList();

     $i = 0;
     foreach($result as $row){

        $data[] = $row->SubName . ";" . $row->GradeDesc. ';' . $row->seqno;
         $i++;
     }

     echo json_encode($data);

 }

//this is action takes the userid for the person that logged in and do the sql statement retrieving the user details
 else if ($_GET['action'] == "display_stud_info2") {
     $data = array();
     $username = $_GET['uid'];
     $sql = sprintf("select a.stud_numb,a.reg_date,a.stud_block,e.off_name as ot,f.qual_desc,i.`email`,
                        concat(b.pers_fname,' ',b.pers_sname) as fullname,c.fac_desc,d.dept_desc
                        from student.student2015 a
                        left outer join student.personal b on (a.stud_numb = b.stud_numb)
                        left outer join structure.faculty c on (a.stud_fact = c.fac_code)
                        left outer join structure.department d on (a.stud_dept = d.dept_code)
                        left outer join structure.offerings e on (a.stud_ot = e.off_code)
                        left outer join structure.qualification f on (a.stud_qual = f.qual_code)
                        left outer join portal.cput_users i on (a.stud_numb = i.username)
                        where a.stud_numb = %d",$username);

     $dbo->setQuery($sql);
     $row = $dbo->loadObject();

     $data[] = $row->email.';'.$row->fullname.';'.$row->qual_desc.';'.$row->reg_date;

     echo json_encode($data);

 }

    exit();

?>
		