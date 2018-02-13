<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();

if (isset($_GET['action'])) {
    
    if ($_GET['action'] == "display_schools") {
       $data = array();
       $sql = sprintf("select schoolcode, schoolname, telephone, language, schooltype from teaching_practice.SchoolTab");
       $dbo->setQuery($sql);
       $result = $dbo->loadObjectList();
       
        if (!$result) {
            $data[] = "-1"; echo json_encode($data); exit();             
        }
        
        foreach($result as $row) {
            $data[] = $row->schoolcode.";".$row->schoolname.";".$row->telephone.";".$row->language.";".$row->schooltype;
        }
        echo json_encode($data);
    }
        
    if ($_GET['action'] == 'srch') {
        
        $sql = sprintf("select schoolcode, schoolname, address1, address2, address3, address4, postalcode, telephone, faxnumber, email, principal,liaison, language, num_students from teaching_practice.SchoolTab where schoolname like '%s'", $_GET['school'] ."%");
                
        $dbo->setQuery($sql);     
        $result = $dbo->loadObjectList();
        $data = array();
        
         foreach($result as $row){
             $data[] = $row->schoolcode.';'.$row->schoolname;
        }
       echo json_encode($data);
       
    }
    
    else if ($_GET['action'] == 'info') {
		$data = array();
                $sql = sprintf("select schoolcode, schoolname, address1, address2, address3, address4, postalcode, telephone, faxnumber, email, principal,liaison, language, num_students, schooltype from teaching_practice.SchoolTab where schoolcode=%d", $_GET['school_id']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$data[] = $row->schoolname.';'.$row->address1.';'.$row->address2.';'.$row->address3.';'.$row->address4.';'.$row->postalcode.';'.$row->telephone.';'.$row->faxnumber.';'.$row->email.';'.$row->principal.';'.$row->liaison.';'.$row->language.';'.$row->num_students.';'.$row->schooltype;
                echo json_encode($data);
	}
        
    else if ($_GET['action'] == 'addschool') {
        $sql = sprintf("insert into teaching_practice.SchoolTab (schoolname,address1,address2,address3,address4,postalcode,telephone,faxnumber,email,principal,liaison,language,num_students) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%d)",$_GET['schoolname'],$_GET['address1'],$_GET['address2'],$_GET['address3'],$_GET['address4'],$_GET['postcode'],$_GET['tel'],$_GET['fax'],$_GET['email'],$_GET['principal'],$_GET['liaison'],$_GET['lang_used'],$_GET['num_students']);
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
       
    else if ($_GET['action'] == "save_school") {
         $data = array();
        
         $sql = sprintf("select schoolcode schoolname from teaching_practice.SchoolTab where schoolname = '%s'", $_POST['school_name']);
         $dbo->setQuery($sql);
         $result = $dbo->query();
         
         if ($dbo->getNumRows() == 0) {             
             
        $sql1 = sprintf("insert into teaching_practice.SchoolTab (schoolname,address1,address2,address3,address4,postalcode,telephone,faxnumber,email,principal,liaison,language,num_students,schooltype)
               values ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%d,'%s')",$_POST['school_name'], $_POST['address_1'],$_POST['address_2'],$_POST['address_3'],$_POST['address_4'],$_POST['postal_code'],$_POST['telephone_number'],$_POST['fax_number'],$_POST['email_address'],$_POST['principal_name'],$_POST['liaison'],$_POST['language_used'],$_POST['number_of_students'],$_POST['school_type']);
        $dbo->setQuery($sql1);
        
        $result1 = $dbo->query();
        
        if(!$result1)
        {
            echo '1'; //error inserting school
        }
        else {
        
            $sql2 = sprintf("select schoolcode from teaching_practice.SchoolTab where schoolname='%s'", $_POST['school_name']);
            $dbo->setQuery($sql2);
            $row2 = $dbo->loadObject();

            $sql3 = sprintf("select GradeSeqNo from teaching_practice.Grades where GradeDesc='%s'", $_POST['grade']);
            $dbo->setQuery($sql3);
            $row3 = $dbo->loadObject();

            $sql4 = sprintf("select SubCode from teaching_practice.Subjects where SubName='%s'", $_POST['subject']);
            $dbo->setQuery($sql4);
            $row4 = $dbo->loadObject();
            
            $sql00 = sprintf("select TypeSeqNo from teaching_practice.PlaceTypes where TypeDesc='%s'", $_POST['school_type']);
            $dbo->setQuery($sql00);
            $row00 = $dbo->loadObject();
            
            $sql6 = sprintf("insert into teaching_practice.SchoolGrades2(schoolcode, GradeSeq, SubCode, TypeSeqNo)
            values(%d,%d,%d,%d)", $row2->schoolcode, $row3->GradeSeqNo, $row4->SubCode,$row00->TypeSeqNo);
            $dbo->setQuery($sql6);         
            $result = $dbo->query();
            
            if (!$result){
                echo '2'; //error inserting grade-language combination
            } else {
                $sql7 = sprintf("select sch_grades_id, schoolcode, GradeSeq, SubCode, TypeSeqNo from teaching_practice.SchoolGrades2 where schoolcode = %d and GradeSeq = %d and SubCode = %d and TypeSeqNo = %d",
                        $row2->schoolcode, $row3->GradeSeqNo, $row4->SubCode, $row00->TypeSeqNo);
                $dbo->setQuery($sql7);
                $row = $dbo->loadObject();
                
                $sql8 = sprintf("SELECT 
          SchoolGrades2.sch_grades_id, 
          Grades.GradeDesc,
          Subjects.SubName,
          Subjects.SubCode,
          PlaceTypes.TypeDesc
        FROM
          teaching_practice.Subjects
          INNER JOIN teaching_practice.SchoolGrades2 ON (Subjects.SubCode = SchoolGrades2.SubCode)
          INNER JOIN teaching_practice.Grades ON (SchoolGrades2.GradeSeq = Grades.GradeSeqNo)
          INNER JOIN teaching_practice.PlaceTypes ON (SchoolGrades2.TypeSeqNo = PlaceTypes.TypeSeqNo)
        WHERE
          (SchoolGrades2.sch_grades_id = %d)
        GROUP BY
          Grades.GradeDesc,
          Subjects.SubName,
          PlaceTypes.TypeDesc", $row->sch_grades_id);
                $dbo->setQuery($sql8);
                $row9 = $dbo->loadObject();
                echo $row->sch_grades_id.';'.$row->schoolcode.';'.$row9->SubName.';'.$row9->GradeDesc.';'.$row9->TypeDesc;
            }          
         
        }
        }
        else {
           $sql2 = sprintf("select schoolcode from teaching_practice.SchoolTab where schoolname='%s'", $_POST['school_name']);
            $dbo->setQuery($sql2);
            $row2 = $dbo->loadObject();

            $sql3 = sprintf("select GradeSeqNo from teaching_practice.Grades where GradeDesc='%s'", $_POST['grade']);
            $dbo->setQuery($sql3);
            $row3 = $dbo->loadObject();

            $sql4 = sprintf("select SubCode from teaching_practice.Subjects where SubName='%s'", $_POST['subject']);
            $dbo->setQuery($sql4);
            $row4 = $dbo->loadObject();
            
            $sql00 = sprintf("select TypeSeqNo from teaching_practice.PlaceTypes where TypeDesc='%s'", $_POST['school_type']);
            $dbo->setQuery($sql00);
            $row00 = $dbo->loadObject();
            
            $sql6 = sprintf("insert into teaching_practice.SchoolGrades2(schoolcode, GradeSeq, SubCode, TypeSeqNo)
            values(%d,%d,%d,%d)", $row2->schoolcode, $row3->GradeSeqNo, $row4->SubCode, $row00->TypeSeqNo);
            $dbo->setQuery($sql6);         
            $result = $dbo->query();

            if (!$result){
                echo '3';
            } else {
                $sql7 = sprintf("select sch_grades_id, schoolcode, GradeSeq, SubCode, TypeSeqNo from teaching_practice.SchoolGrades2 where schoolcode = %d and GradeSeq = %d and SubCode = %d and TypeSeqNo = %d",
                        $row2->schoolcode, $row3->GradeSeqNo, $row4->SubCode, $row00->TypeSeqNo);
                $dbo->setQuery($sql7);
                $row = $dbo->loadObject();
                $sql19 = sprintf("SELECT 
          SchoolGrades2.sch_grades_id,
          Grades.GradeDesc,
          Subjects.SubName,
          Subjects.SubCode
          PlaceTypes.TypeDesc
        FROM
          teaching_practice.Subjects
          INNER JOIN teaching_practice.SchoolGrades2 ON (Subjects.SubCode = SchoolGrades2.SubCode)
          INNER JOIN teaching_practice.Grades ON (SchoolGrades2.GradeSeq = Grades.GradeSeqNo)
          INNER JOIN teaching_practice.PlaceTypes ON (SchoolGrades2.TypeSeqNo = PlaceTypes.TypeSeqNo)
        WHERE
          (SchoolGrades2.sch_grades_id = %d)
        GROUP BY
          Grades.GradeDesc,
          Subjects.SubName,
          PlaceTypes.TypeDesc", $row->sch_grades_id);
                $dbo->setQuery($sql19);
                $row10 = $dbo->loadObject();
                echo $row->sch_grades_id.';'.$row->schoolcode.';'.$row10->SubName.';'.$row10->GradeDesc.';'.$row9->TypeDesc;
            }
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
    
    else if($_GET['action'] == 'delete_school')
    {
                        
        $sql = sprintf("delete from teaching_practice.SchoolTab where schoolcode=%d",$_GET['sid']);
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
          Subjects.SubCode
        FROM
          teaching_practice.Subjects
          INNER JOIN teaching_practice.SchoolGrades2 ON (Subjects.SubCode = SchoolGrades2.SubCode)
          INNER JOIN teaching_practice.Grades ON (SchoolGrades2.GradeSeq = Grades.GradeSeqNo)
        WHERE
          (SchoolGrades2.schoolcode = %d)
        GROUP BY
          Grades.GradeDesc,
          Subjects.SubName
         order by SchoolGrades2.sch_grades_id", $_GET['schoolcode']);
            
        $dbo->setQuery($sql3);
        $result = $dbo->loadObjectList();

        if (!$result) { $data[] = "-1"; echo json_encode($data); exit(); }
        
        foreach($result as $row) {
                $data[] = $row->sch_grades_id.";".$row->GradeDesc.";".$row->SubName.';'.$row->SubCode.';'.$_GET['schoolcode'];
        }
        echo json_encode($data);
    }
        
    else if ($_GET['action'] == "update_school") {
        
        $sql2 = sprintf("select schoolcode, schoolname from teaching_practice.SchoolTab where schoolname='%s'", $_GET['schoolname']);

        $dbo->setQuery($sql2);
        $row = $dbo->loadObject();

        $data = array();            
                
        $sql1 = sprintf("update teaching_practice.SchoolTab set schoolname='%s',address1='%s',address2='%s',address3='%s',address4='%s',postalcode='%s',telephone='%s',faxnumber='%s',email='%s',principal='%s',liaison='%s',language='%s',num_students=%d,schooltype='%s' where schoolcode=%d",
               $_GET['schoolname'], $_GET['address1'],$_GET['address2'],$_GET['address3'],$_GET['address4'],$_GET['postcode'],$_GET['tel'],$_GET['fax'],$_GET['email'],$_GET['principal'],$_GET['liaison'],$_GET['lang_used'],$_GET['num_students'],$_GET['schtype'],$_GET['school_id_upd']);

        $dbo->setQuery($sql1);

        $result1 = $dbo->query();

        if(!$result1)
        {
            echo '0';
        }
        else {
                echo '1';
        }
     }          
}          
exit();
?>