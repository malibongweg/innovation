<?php

else if ($_GET['action'] == "save_criteria") {
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
?>