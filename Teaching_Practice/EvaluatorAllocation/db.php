<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'getId') {

        $con=mysql_connect("10.18.4.31","root","hp9000s");
        mysql_select_db("Post750");
        $username = $_GET['idd'];
        $result = mysql_query("select pn_uid from Post750.nuke_users where  pn_uname='$username'");
        $row = mysql_fetch_object($result);
        echo $row->pn_uid;
    }

    if ($_GET['action'] == "display_evaluators") {
        $data = array();
        $sql = sprintf("select evalseqno,evalname,evalsurname,idnumber,cellphone from teaching_practice.Evaluator");
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

        if (!$result) {
            $data[] = "-1"; echo json_encode($data); exit();
        }

        foreach($result as $row) {
            $data[] = $row->evalseqno.';'.$row->evalname.';'.$row->evalsurname.';'.$row->idnumber.';'.$row->cellphone;
        }
        echo json_encode($data);
    }

    if ($_GET['action'] == 'srch') {

        $sql = "select evalseqno,evalname,evalsurname,idnumber,address1,address2,address3,address4,postalcode,telephone,cellphone,faxnumber,email,category,visits from teaching_practice.Evaluator where evalname like'".$_GET['evaluator']."%'";

        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        $data = array();

        foreach($result as $row){
            $data[] = $row->evalseqno.';'.$row->evalname.';'.$row->evalsurname;
        }
        echo json_encode($data);

    }

    else if ($_GET['action'] == 'info') {

        $data = array();
        $sql = sprintf("select evalseqno,evalname,evalsurname,email,region,category,visits from teaching_practice.Evaluator where evalseqno=%d", $_GET['evalid']);
        $dbo->setQuery($sql);
        $row = $dbo->loadObject();
        $data[] = $row->evalname.';'.$row->evalsurname.';'.$row->email.';'.$row->category.';'.$row->visits.';'.$_GET['evalid'].';'.$row->region;
        echo json_encode($data);
    }

    else if ($_GET['action'] == 'evalinfo') {
        $data = array();
        $sql = sprintf("select evalseqno,evalname,evalsurname,idnumber,address1,address2,address3,address4,postalcode,telephone,cellphone,faxnumber,email,category,visits from teaching_practice.Evaluator where evalseqno=%d", $_GET['evalid']);

        $dbo->setQuery($sql);
        $row = $dbo->loadObject();

        $data[] = $row->evalseqno.';'.$row->evalname.';'.evalsurname.';'.$row->idnumber.';'.$row->address1.';'.$row->address2.';'.$row->address3.';'.$row->address4.';'.$row->postalcode.';'.$row->telephone.';'.$row->cellphone.';'.$row->faxnumber.';'.$row->email.';'.$row->category.';'.$row->visits;
        echo json_encode($data);
    }



    if ($_GET['action'] == "display_schools") {

        $data = array();

        $sql2 = sprintf("select schoolcode, schoolname, telephone, language, schooltype from teaching_practice.SchoolTab where language='%s'",$_GET['lang']);
        $dbo->setQuery($sql2);
        $result = $dbo->loadObjectList();

        if (!$result) {
            $data[] = "-1"; echo json_encode($data); exit();
        }

        foreach($result as $row) {

            $data[] = $row->schoolcode.";".$row->schoolname.";".$row->telephone.";".$row->language.";".$row->schooltype;
        }
        echo json_encode($data);
    }



    if ($_GET['action'] == "display_schools_subs") {

        $data = array();

        $subject = $_GET['subj_id'];

        $sql2 = sprintf("select schoolcode from teaching_practice.SchoolGrades2 where SubCode=%d",$_GET['subj_id']);
        $dbo->setQuery($sql2);
        $result = $dbo->loadObjectList();

        if (!$result) {
            $data[] = "-1"; echo json_encode($data); exit();
        }

        foreach($result as $row) {
            $sql2 = sprintf("select schoolcode, schoolname, telephone, language, schooltype from teaching_practice.SchoolTab where schoolcode=%d and language = '%s'",$row->schoolcode,$_GET['lang']);
            $dbo->setQuery($sql2);
            $row2 = $dbo->loadObject();
            $data[] = $row2->schoolcode.";".$row2->schoolname.";".$row2->telephone.";".$row2->language.";".$row2->schooltype.';'.$subject;
        }
        echo json_encode($data);
    }


    if ($_GET['action'] == "display_students") {
$array = $_GET['myArray'];
        $arr_vals = "(";
        
        for($i = 0; $i < count($array); $i++) {
            $arr_vals .= $array[$i].',';
        }
        
        $x = rtrim($arr_vals, ",");
        
        $y.=$x.")";
        
        $sql3 = sprintf("select seqno, schoolcode, GradeDesc, SubCode, StudentNo from teaching_practice.SchoolSubs t,  teaching_practice.Grades m where schoolcode  in $y and SubCode=%d and t.GradeSeq = m.GradeSeqNo ", $_GET['subject_id'] );

         $dbo->setQuery($sql3);
          $result = $dbo->loadObjectList();
        
        
       foreach($result as $row) {
           $sql2 = sprintf("select schoolname from teaching_practice.SchoolTab where schoolcode=%d", $row->schoolcode);
          $dbo->setQuery($sql2);
          $row2 = $dbo->loadObject();
        
            $sql5 = sprintf("select StudentName from teaching_practice.TeachStud where StudentNo = '%s'",$row->StudentNo);
            $dbo->setQuery($sql5);
            $row3 = $dbo->loadObject();
            $data[] = $row->StudentNo.";".$row2->schoolname.";".$row->SubCode.';'.$row3->StudentName. ";" .$row->GradeDesc;

        }
        
        echo json_encode($data);

    }

    if($_GET['action'] == 'allocate_student')
    {
        $array = $_GET['myArray'];
        $sql1 = sprintf("select evalseqno, visits from teaching_practice.Evaluator where evalname = '%s' and evalsurname='%s'",$_GET['eval_name'],$_GET['eval_sname']);
        $dbo->setQuery($sql1);
        $row1 = $dbo->loadObject();

        if($row1->visits < count($array))
        {
            echo '7';
            exit();
        }

        for($i = 0; $i < count($array); $i++) {

            $sql = sprintf("select StudentNo, evalseqno from teaching_practice.EvalStud where evalseqno=%d and StudentNo='%s'",$row1->evalseqno,$_GET['stnum']);
            $dbo->setQuery($sql);
            $result1 = $dbo->query();
            if ($dbo->getNumRows() == 0) {
                $sql = sprintf("insert into teaching_practice.EvalStud(StudentNo, evalseqno) values('%s',%d)", $array[$i], $row1->evalseqno);
                $dbo->setQuery($sql);
                $result = $dbo->query();

            }
            else{
                echo '-1';
                exit();
            }

        }


        if(!$result)
        {
            echo '0';
        }
        else{

            for($i = 0; $i < count($array); $i++) {
                if(strlen($array[$i]) > 5) {
                    $sql4 = sprintf("update teaching_practice.Evaluator set visits = visits - 1 where evalseqno = %d", $row1->evalseqno);
                    $dbo->setQuery($sql4);
                    $result4 = $dbo->query();

                }
            }



            echo '1';
        }

    }

    if($_GET['action'] == 'delete_student')
    {
        $array = $_GET['myArray'];

        $sql1 = sprintf("select evalseqno from teaching_practice.Evaluator where evalname = '%s' and evalsurname='%s'",$_GET['eval_name'],$_GET['eval_sname']);
        $dbo->setQuery($sql1);
        $row1 = $dbo->loadObject();

        for($i = 0; $i < count($array); $i++) {

            $sql = sprintf("delete from teaching_practice.EvalStud where StudentNo = '%s' and evalseqno = %d",$array[$i],$row1->evalseqno);
            $dbo->setQuery($sql);
            $result1 = $dbo->query();

            $sql4 = sprintf("update teaching_practice.Evaluator set visits = visits + 1 where evalseqno = %d", $row1->evalseqno);
            $dbo->setQuery($sql4);
            $result4 = $dbo->query();

        }
        if(!$result1)
        {
            echo '0';
        }
        else{
            echo $row1->evalseqno;
        }

    }


    if ($_GET['action'] == "display_selected_student") {

        $sql2 = sprintf("select schoolname from teaching_practice.SchoolTab where schoolcode=%d", $_GET['school']);
        $dbo->setQuery($sql2);
        $row2 = $dbo->loadObject();

        $data = array();
        $sql = sprintf("select seqno, schoolcode, GradeSeq, SubCode, StudentNo from teaching_practice.SchoolSubs where schoolcode=%d and SubCode=%d and StudentNo='%s'",$_GET['school'],$_GET['subject'],$_GET['student_number']);//,$_GET['school_code']);
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

        if (!$result) {
            $data[] = "-1"; echo json_encode($data); exit();
        }

        foreach($result as $row) {
            $sql5 = sprintf("select StudentName from teaching_practice.TeachStud where StudentNo = '%s'",$row->StudentNo);
            $dbo->setQuery($sql5);
            $row3 = $dbo->loadObject();
            $data[] = $row->StudentNo.";".$row->schoolcode.";".$row->SubCode.';'.$row3->StudentName.';'.$row3->seqno;
        }
        echo json_encode($data);
    }

    else if ($_GET['action'] == 'addschool') {
        $sql = sprintf("insert into teaching_practice.SchoolTab (schoolname,address1,address2,address3,address4,postalcode,telephone,faxnumber,email,principal,language,num_students) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%d)",$_GET['schoolname'],$_GET['address1'],$_GET['address2'],$_GET['address3'],$_GET['address4'],$_GET['postcode'],$_GET['tel'],$_GET['fax'],$_GET['email'],$_GET['principal'],$_GET['lang_used'],$_GET['num_students']);
        $dbo->setQuery($sql);
        $result = $dbo->query();
        if(!$result)
        {
            echo '0';
        }
        else{
            echo '1';
        }
    }

    else if ($_GET['action'] == "save_evaluator") {
        $data = array();

        $sql = sprintf("select evalseqno,idnumber,evalname from teaching_practice.Evaluator where idnumber = '%s'", $_POST['id_number']);
        $dbo->setQuery($sql);
        $result = $dbo->query();

        if ($dbo->getNumRows() == 0) {
            $sql1 = sprintf("insert into teaching_practice.Evaluator (evalname,evalsurname,idnumber,address1,address2,address3,address4,postalcode,telephone,cellphone,faxnumber,email,region,category,visits) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%d)", $_POST['evaluator_name'],$_POST['evaluator_surname'],$_POST['id_number'], $_POST['address_1'],$_POST['address_2'],$_POST['address_3'],
                $_POST['address_4'],$_POST['postal_code'],$_POST['telephone_number'],$_POST['cellphone_number'],$_POST['fax_number'],$_POST['email_address'],$_POST['region'],$_POST['category'],$_POST['number_of_visits']);
            $dbo->setQuery($sql1);
            $result1 = $dbo->query();
        //echo $sql1;
           echo '1';
        }
        else{
            echo '0';
        }

    }

    else if($_GET['action'] == 'delete_subject')
    {

        $sql = sprintf("delete from teaching_practice.SchoolGrades2 where sch_grades_id=%d and schoolcode=%d",$_GET['id'],$_GET['sid']);
        $dbo->setQuery($sql);
        $result = $dbo->query();

        if(!$result)
        {
            echo '0';
        }
        else{
            echo $_GET['sid'].';'.$_GET['id'];

        }
    }

    else if($_GET['action'] == 'delete_evaluator')
    {

        $sql = sprintf("delete from teaching_practice.Evaluator where evalseqno=%d",$_GET['eid']);
        $dbo->setQuery($sql);
        $result = $dbo->query();

        if(!$result)
        {
            echo '0';
        }
        else{
            echo '1';

        }
    }

    else if ($_GET['action'] == "display_subjects") {
        $data = array();

        $sql3 = sprintf("SELECT 
          SchoolGrades2.sch_grades_id,
          Grades.GradeDesc,
          Subjects.SubName,
          Subjects.SubCode,
          languages.lang_desc
        FROM
          teaching_practice.Subjects
          INNER JOIN teaching_practice.SchoolGrades2 ON (Subjects.SubCode = SchoolGrades2.SubCode)
          INNER JOIN teaching_practice.languages ON (SchoolGrades2.langid = languages.langid)
          INNER JOIN teaching_practice.Grades ON (SchoolGrades2.GradeSeq = Grades.GradeSeqNo)
        WHERE
          (SchoolGrades2.schoolcode = %d)
        GROUP BY
          Grades.GradeDesc,
          Subjects.SubName,
          languages.lang_desc order by SchoolGrades2.sch_grades_id", $_GET['schoolcode']);

        $dbo->setQuery($sql3);
        $result = $dbo->loadObjectList();

        if (!$result) { $data[] = "-1"; echo json_encode($data); exit(); }

        foreach($result as $row) {
            $data[] = $row->sch_grades_id.";".$row->GradeDesc.";".$row->SubName.";".$row->lang_desc.';'.$row->SubCode.';'.$_GET['schoolcode'];
        }
        echo json_encode($data);
    }

    else if ($_GET['action'] == "update_evaluator") {
        $sql = sprintf("update teaching_practice.Evaluator set evalname='%s',evalsurname='%s',idnumber='%s',address1='%s',address2='%s', address3='%s',address4='%s',
        postalcode='%s',telephone='%s',cellphone='%s',faxnumber='%s',email='%s',region='%s',category='%s',visits=%d
        where evalseqno=%d", $_GET['evaluator_name'], $_GET['evaluator_surname'],$_GET['id_number'],$_GET['address1'],$_GET['address2'],$_GET['address3'],$_GET['address4'],
            $_GET['postcode'],$_GET['tel'],$_GET['cellphone_number'],$_GET['fax'],$_GET['email'],$_GET['region'],$_GET['cat'],$_GET['number_of_visits'],$_GET['evaluator_id_upd']);


        $dbo->setQuery($sql);

        $result = $dbo->query();

       if(!$result)
        {
            echo '0';
        }
        else {

            echo '1';
            }

    }

    else if($_GET['action'] == 'display_students_info')
    {
        $data = array();
        $sql = sprintf("select distinct d.StudentNo, a.StudentName, b.schoolname, g.GradeDesc
                from teaching_practice.TeachStud a, teaching_practice.SchoolTab b,
                    teaching_practice.EvalStud d, teaching_practice.Grades g,teaching_practice.SchoolSubs s
                            where a.StudentNo = d.StudentNo
                                and a.schoolcode = b.schoolcode
                                and s.GradeSeq = g.GradeSeqNo
                                and s.StudentNo = a.StudentNo
                                and d.evalseqno = %d
                                group by d.StudentNo,b.schoolname",$_GET['eval_id']);

        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

        if (!$result) {
            $data[] = "-1";
            echo json_encode($data);
            exit();
        }

        foreach($result as $row) {
            $data[] = $row->StudentNo.";".$row->StudentName.";".$row->schoolname.";".$row->GradeDesc;
        }
        echo json_encode($data);

    }
}
exit();
?>