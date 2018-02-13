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

 else if ($_GET['action'] == "displaygrades") {
     $data = array();
     $sql = sprintf("select schoolcode from teaching_practice.SchoolTab where schoolname = '%s'",$_GET['sname']);
     $dbo->setQuery($sql);
     $row = $dbo->loadObject();
     $scode = $row->schoolcode;

     $sql1 = sprintf("select SubCode from teaching_practice.Subjects where SubName = '%s'",$_GET['subname']);
     $dbo->setQuery($sql1);
     $row = $dbo->loadObject();
     $subcode = $row->SubCode;

     //echo $scode;
     $sql2 = sprintf("select GradeSeq from teaching_practice.SchoolGrades2 where schoolcode = %d and SubCode=%d", $scode,$subcode);
     $dbo->setQuery($sql2);
     $result = $dbo->loadObjectList();

     $i = 0;
     foreach($result as $row2){
         // echo  $row2->subcode;
         $sql3 = sprintf("select GradeSeqNo,GradeDesc from teaching_practice.Grades where GradeSeqNo = %d", $row2->GradeSeq);
         //echo $sql3;
         $dbo->setQuery($sql3);
         $row3 = $dbo->loadObject();

         $data[] = $row3->GradeSeqNo . ";" . $row3->GradeDesc;
         //echo $data;
         $i++;
     }

     echo json_encode($data);

 }

 else if ($_GET['action'] == "check_for_subj") {
     $stno = $_GET['stno'];
     $sql = sprintf("select schoolcode,schoolcode2 from teaching_practice.TeachStud where StudentNo='%s'",$_GET['stno']);
     $dbo->setQuery($sql);
     $dbo->query();
     if ($dbo->getNumRows() == 0){
         $cnt = 0;
     } else {
         $cnt = 1;
     }
     $row = $dbo->loadObject();
     $data = array();
     $data['Record']['sch1'] = $row->schoolcode;
     $data['Record']['sch2'] = $row->schoolcode2;
     $data['Record']['cnt'] = $cnt;
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
     $stud = $_POST['stud_nr'];
     $fullname = $_POST['f_name'];
     $rdate = $_POST['r_date'];
     $campus = $_POST['campus'];

     //gets the schoolid for the selected school
     $getschool = sprintf("select schoolcode from teaching_practice.SchoolTab where schoolname = '%s'",$_POST['select_school']);
     $dbo->setQuery($getschool);
     $row = $dbo->loadObject();
     $code = $row->schoolcode;

     //get system date
     $sql2 = sprintf("select SYSDATE() AS placed from DUAL");
     $dbo->setQuery($sql2);
     $row1 = $dbo->loadObject();

     $tns = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))';
     $conn = oci_connect('dirxml', 'dirxml', $tns) or die ('ERR');

     $sq = sprintf("select distinct stud.get_address(iadstno, 'CE',1,'I','S') cellphone
        from stud.iadbio , stud.iagenr
        where iadstno = iagstno
        and iagcyr = 2015
        and iadstno = %d
        group by iadstno", $_POST['stud_nr']);
     $result555 = oci_parse($conn,$sq);
     oci_execute($result555);
     $row = oci_fetch_object($result555);
     $cell = $row->CELLPHONE;

     //check if student applied for a a school already
     $checdup = sprintf("select StudSeqNo, StudentNo, schoolcode from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode=%d group by StudentNo, schoolcode",$_POST['stud_nr'],$code);
     $dbo->setQuery($checdup);
     $result = $dbo->query();

         $level = $_POST['s_level'];
         If (($level <= 2) or ($level >= 3)) {

             if ($dbo->getNumRows() == 0) {
                 $data = array();

                 $sql2 = sprintf("insert into teaching_practice.TeachStud(StudentNo, StudentName, DateReg, DatePlaced, schoolcode, cellphone, Campus)
                 values('%s','%s','%s','%s', %d, '%s','%s')", $stud, $fullname, $rdate, $row1->placed, $code, $cell,$campus);
                 $dbo->setQuery($sql2);
                 $result12 = $dbo->query();

                 //check the school the student applied for
                 $checdup2 = sprintf("select StudentNo, schoolcode from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode = %d group by StudentNo, schoolcode", $stud, $code);
                 $dbo->setQuery($checdup2);
                 $rowg = $dbo->loadObject();

                 //check to see if the schoolid in the database is equal to the one on the form
                 if ($rowg->schoolcode == $code) {

                     //update the number of students at a school if condition above is true
                     $sql4 = sprintf("update teaching_practice.SchoolTab set num_students = num_students - 1 where schoolcode = %d", $code);
                     $dbo->setQuery($sql4);
                     $result4 = $dbo->query();
                 }
             }
         }

         //$check = sprintf("select StudSeqNo, StudentNo, schoolcode from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode=%d GROUP BY StudentNo, schoolcode", $_POST['stud_nr'],$code);
         //$level = $_POST['level'];  
         //check the schoolcode and student combination so that to assign the right subjects
         $checkstud = sprintf("select StudSeqNo, StudentNo, schoolcode from teaching_practice.TeachStud where StudentNo = '%s' GROUP BY StudentNo", $_POST['stud_nr']);
         $dbo->setQuery($checkstud);
         $result = $dbo->loadObject();

         //check to see if the schoolid in the database is equal to the one on the form
         if (($result->schoolcode == $code) or ($level >= 3)) {

            /* if (($level >= 3) and ($result->schoolcode != $code)) {
                 $checkstud22 = sprintf("select StudentNo, schoolcode, count(StudentNo) as cnt from teaching_practice.TeachStud where StudentNo = '%s'", $_POST['stud_nr']);
                 $dbo->setQuery($checkstud22);
                 $resul2 = $dbo->loadObject();

                // if ($resul2->cnt != 1) {

                     $sql2 = sprintf("insert into teaching_practice.TeachStud(StudentNo, StudentName, DateReg, DatePlaced, schoolcode, cellphone)
                 values('%s','%s','%s','%s', %d, '%s')", $stud, $fullname, $rdate, $row1->placed, $code, $cell);
                     $dbo->setQuery($sql2);
                     $result12 = $dbo->query();

                     $sql4 = sprintf("update teaching_practice.SchoolTab set num_students = num_students - 1 where schoolcode = %d", $code);
                     $dbo->setQuery($sql4);
                     $result4 = $dbo->query();*/

                 //}

                 /*$checkstud22 = sprintf("select StudentNo, schoolcode,schoolcode2, count(schoolcode2) as cnt from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode2 = %d", $_POST['stud_nr'], $row->schoolcode);
                 $dbo->setQuery($checkstud22);
                 $resul2 = $dbo->loadObject();
                 if ($resul2->cnt == 0) {
                     $sql09 = sprintf("update teaching_practice.TeachStud  set schoolcode2 =  %d where StudentNo = '%s'", $row->schoolcode, $_POST['stud_nr']);
                     $dbo->setQuery($sql09);
                     $resultx = $dbo->query();

                     $checkstud2 = sprintf("select StudentNo, schoolcode,schoolcode2, count(schoolcode2) as cnt from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode2 = %d", $_POST['stud_nr'], $row->schoolcode);
                     $dbo->setQuery($checkstud2);
                     $resul = $dbo->loadObject();

                     $sch2_count = $resul->cnt;

                     //update the number of students at a school if condition above is true
                     $sql4 = sprintf("update teaching_practice.SchoolTab set num_students = num_students - 1 where schoolcode = %d", $row->schoolcode);
                     $dbo->setQuery($sql4);
                     $result4 = $dbo->query();
                 }*/
             //}
             //get schoolcode fot the school on the form
             $sql11 = sprintf("select a.schoolcode, a.schoolname from teaching_practice.SchoolTab a where a.schoolname = '%s'", $_POST['select_school']);
             $dbo->setQuery($sql11);
             $row = $dbo->loadObject();

             //get grade for the grade selected on the form
             $grade = sprintf("select a.GradeSeqNo, a.GradeDesc from teaching_practice.Grades a where a.GradeDesc = '%s'", $_POST['grade']);
             $dbo->setQuery($grade);
             $row4 = $dbo->loadObject();
             $res = $row4->GradeSeqNo;

             //get subject code for the subjects selected
             $subject = sprintf("select a.SubCode, a.SubName from teaching_practice.Subjects a where a.SubName ='%s'", $_POST['subs_offered']);
             $dbo->setQuery($subject);
             $row3 = $dbo->loadObject();
             $res1 = $row3->SubCode;


             //make sure user can only select one subject for 1 grade
             $selection = sprintf("select GradeSeq, SubCode,schoolcode from teaching_practice.SchoolSubs where GradeSeq = %d and SubCode = %d and schoolcode = %d and StudentNo = '%s'", $res, $res1, $code, $_POST['stud_nr']);
             $dbo->setQuery($selection);
             $result7 = $dbo->query();

             //if no entry is found in the database
             if ($dbo->getNumRows() == 0) {

                 //insert subjects selected on the form into the database
                 $sql9 = sprintf("insert into teaching_practice.SchoolSubs(schoolcode, GradeSeq, SubCode, Status, StudentNo, StudSeqNo)
                                        values(%d,%d,%d,'%s', '%s',%d)", $code, $res, $res1, 'Available', $_POST['stud_nr'], $result->StudSeqNo);
                 $dbo->setQuery($sql9);
                 $result = $dbo->query();

                 //getting an id to make it possible for a subject to be deleted once selected
                 $sql5 = sprintf("select seqno, schoolcode, GradeSeq, SubCode, StudentNo from teaching_practice.SchoolSubs where schoolcode =%d and GradeSeq=%d and SubCode = %d and StudentNo='%s'",
                     $code, $res, $res1, $_POST['stud_nr']);
                 $dbo->setQuery($sql5);
                 $row5 = $dbo->loadObject();
                 echo $row5->seqno;

             } else {
                 echo '2';
             }

         } else {
             $sql23 = sprintf("delete from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode = %d", $stud, $code);
             $dbo->setQuery($sql23);
             $result23 = $dbo->query();

             $sql4 = sprintf("update teaching_practice.SchoolTab set num_students = num_students + 1 where schoolcode = %d", $code);
             $dbo->setQuery($sql4);
             $result4 = $dbo->query();

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
     /*$student = sprintf("select StudSeqNo from teaching_practice.SchoolSubs where StudentNo='%s' and seqno=%d",$_GET['stno'],$_GET['sqno']);
     $dbo->setQuery($student);
     $rowst = $dbo->loadObject();
     $stud_seq = $row->StudSeqNo;*/

    //get school code
     $get_sch_code = sprintf("select schoolcode from teaching_practice.SchoolSubs where seqno = %d", $_GET['sqno']);
     $dbo->setQuery($get_sch_code);
     $row = $dbo->loadObject();
     $scode = $row->schoolcode;

     //delete subject where sequence number
     $sql = sprintf("delete from teaching_practice.SchoolSubs where seqno = %d",$_GET['sqno']);
     //echo $sql;
     $dbo->setQuery($sql);
     $result = $dbo->query();
     if(!$result)
     {
         echo '0';
     }
     else {

         // select sequence numbers of student for school deleted
         /*$sqnos = sprintf("select StudSeqNo, StudentNo, schoolcode from teaching_practice.SchoolSubs where StudentNo = '%s' and schoolcode=%d", $_GET['stno'], $scode);
         $dbo->setQuery($seqnos);
         $row_seqs = $dbo->loadObjectList();

         echo $sqnos;
         exit();*/

         // select remaining subjects
         $selection1 = sprintf("select GradeSeq, SubCode,schoolcode, StudentNo, count(*) as count from teaching_practice.SchoolSubs where StudentNo = '%s' and schoolcode=%d", $_GET['stno'], $scode);
         $dbo->setQuery($selection1);
         $row00 = $dbo->loadObject();

         //if nothing is left
         if ($row00->count == 0) {

             //delete student
             $sql23 = sprintf("delete from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode = %d", $_GET['stno'], $scode);
             $dbo->setQuery($sql23);
             $result23 = $dbo->query();
             //echo $sql23;
             //exit();
			$sql21 = sprintf("SELECT eseqno, evalseqno FROM teaching_practice.EvalStud WHERE StudentNo = '%s'",$_GET['stno']);
			$dbo->setQuery($sql21);
		    $result = $dbo->loadObjectList();
			foreach($result as $row)
			{
				$sql2344 = sprintf("delete from teaching_practice.EvalStud where eseqno = %d", $row->eseqno);
             	$dbo->setQuery($sql2344);
             	$result2344 = $dbo->query();
			 
				$sql544 = sprintf("update teaching_practice.Evaluator set visits = visits + 1 where evalseqno = %d", $row->evalseqno);
            	$dbo->setQuery($sql544);
             	$result544 = $dbo->query();
			}
             /*foreach($row_seqs as $row) {
                 $sql23 = sprintf("delete from teaching_practice.TeachStud where StudSeqNo = %d and schoolcode = %d", $row->StudSeqNo, $scode);
                 echo $sql23;
                 exit();
                 $dbo->setQuery($sql23);
                 $result23 = $dbo->query();

                 $i++;

             }*/

             //echo $sql23;
             //exit();

             //update school num students
             $sql5 = sprintf("update teaching_practice.SchoolTab set num_students = num_students + 1 where schoolcode = %d", $scode);
             $dbo->setQuery($sql5);
             $result5 = $dbo->query();

              echo '2';

         } else {

             /*$selection = sprintf("select GradeSeq, SubCode,schoolcode, StudentNo from teaching_practice.SchoolSubs where StudentNo = '%s'", $_GET['stno']);
             $dbo->setQuery($selection);
             $result7 = $dbo->query();

             if ($dbo->getNumRows() == 0) {

                 $sql22 = sprintf("delete from teaching_practice.TeachStud where StudentNo = '%s' and schoolcode = %d",$_GET['stno'],$scode);
                 $dbo->setQuery($sql22);
                 $result22 = $dbo->query();

                 $sql4 = sprintf("update teaching_practice.SchoolTab set num_students = num_students + 1 where schoolcode = %d", $scode);
                 $dbo->setQuery($sql4);
                 $result4 = $dbo->query();

                 echo '1';

             } else {
                 echo '1';
             }*/
             echo '1';

         }
     }
 }
     

 else if($_GET['action'] == 'delete_subject2')
 {
     $level = $_GET['level'];

     $get_sch_code = sprintf("select schoolcode from teaching_practice.SchoolSubs where seqno = %d", $_GET['sqno']);
     $dbo->setQuery($get_sch_code);
     $row = $dbo->loadObject();

     $scode = $row->schoolcode;


     $sql = sprintf("delete from teaching_practice.SchoolSubs where seqno = %d",$_GET['sqno']);
     //echo $sql;
     $dbo->setQuery($sql);
     $result = $dbo->query();

     if(!$result)
     {
         echo '0';
     }
     else{
         $selection = sprintf("select GradeSeq, SubCode,schoolcode, StudentNo from teaching_practice.SchoolSubs where StudentNo = '%s' and schoolcode=%d", $_GET['stno'], $scode);
         $dbo->setQuery($selection);
         $result7 = $dbo->query();
       
       if ($dbo->getNumRows() == 0) {
             //select schoolcodes of the schools the student applied to in order to update both school codes, schoolcode and schoolcode 2 to cater for 3rh-5th year students as well
             $sql55 = sprintf("select StudentNo, schoolcode, schoolcode2 from teaching_practice.TeachStud where StudentNo='%s'",$_GET['stno']);
             $dbo->setQuery($sql55);
             $row5 = $dbo->loadObject();

           if($level < 3) {
               $sql22 = sprintf("delete from teaching_practice.TeachStud where StudentNo = '%s' ", $_GET['stno']);
               $dbo->setQuery($sql22);
               $result22 = $dbo->query();

               $sql4 = sprintf("update teaching_practice.SchoolTab set num_students = num_students + 1 where schoolcode = %d", $row5->schoolcode);
               $dbo->setQuery($sql4);
               $result4 = $dbo->query();

               echo '1';
           }
           elseif($level >= 3)
           {
               if(($row5->schoolcode = $scode) and ($row5->schoolcode2==""))
               {

                   $sql6 = sprintf("delete from teaching_practice.TeachStud where StudentNo = '%s' ", $_GET['stno']);
                   $dbo->setQuery($sql6);
                   $result6 = $dbo->query();


                   $sql7 = sprintf("update teaching_practice.SchoolTab set num_students = num_students + 1 where schoolcode = %d", $row5->schoolcode);
                   $dbo->setQuery($sql7);
                   $result7 = $dbo->query();

                   echo '1';

               }

               elseif(($row5->schoolcode = $scode) and ($row5->schoolcode2!=""))
               {

                   $sql8 = sprintf("update teaching_practice.TeachStud set schoolcode = '' where StudentNo = '%s'", $_GET['stno']);
                   $dbo->setQuery($sql8);
                   $result8 = $dbo->query();


                /*   $sql9 = sprintf("update teaching_practice.SchoolTab set num_students = num_students + 1 where schoolcode = %d", $row5->schoolcode);
                   $dbo->setQuery($sql9);
                   $result9 = $dbo->query();*/

                   echo '1';

               }

               elseif(($row5->schoolcode2 = $scode) and ($row5->schoolcode!=""))
               {

                   $sql0 = sprintf("update teaching_practice.TeachStud set schoolcode2 = '' where StudentNo = '%s'", $_GET['stno']);
                   $dbo->setQuery($sql0);
                   $result0 = $dbo->query();


                   $sql11 = sprintf("update teaching_practice.SchoolTab set num_students = num_students + 1 where schoolcode = %d", $row5->schoolcode2);
                   $dbo->setQuery($sql11);
                   $result11 = $dbo->query();

                   echo '1';

               }

               elseif(($row5->schoolcode2 = $scode) and ($row5->schoolcode=""))
               {

                   $sql10 = sprintf("delete from teaching_practice.TeachStud where StudentNo = '%s' ", $_GET['stno']);
                   $dbo->setQuery($sql10);
                   $result10 = $dbo->query();


                   $sql11 = sprintf("update teaching_practice.SchoolTab set num_students = num_students + 1 where schoolcode = %d", $row5->schoolcode2);
                   $dbo->setQuery($sql11);
                   $result11 = $dbo->query();

                   echo '1';

               }
           }
       }
         else{
             echo '1';
         }

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

     $sql2 = sprintf("");

     $data[] = $row->email.';'.$row->fullname.';'.$row->qual_desc.';'.$row->reg_date;

     echo json_encode($data);

 }

    exit();

?>
		