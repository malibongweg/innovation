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
       $sql = sprintf("select schoolcode, schoolname, telephone, language from teaching_practice.SchoolTab");
       $dbo->setQuery($sql);
       $result = $dbo->loadObjectList();
       
        if (!$result) {
            $data[] = "-1"; echo json_encode($data); exit();             
        }
        
        foreach($result as $row) {
            $data[] = $row->schoolcode.";".$row->schoolname.";".$row->telephone.";".$row->language;
        }
        echo json_encode($data);
    }
        
    if ($_GET['action'] == 'srch') {
        /*$sql = sprintf("select schoolname from teaching_practice.SchoolTab where schoolname like '%s'", $_GET['school'] ."%");
                
        $dbo->setQuery($sql);     
        $result = $dbo->loadObjectList();
        $data = array();
        
         foreach($result as $row){
             $data[] = $row->schoolname;
        }
       echo json_encode($data);*/
     
       //sql 2
        
        $sql = sprintf("select schoolcode, schoolname, address1, address2, address3, address4, postalcode, telephone, faxnumber, email, principal, language, num_students from teaching_practice.SchoolTab where schoolname like '%s'", $_GET['school'] ."%");
                
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
                $sql = sprintf("select schoolcode, schoolname, address1, address2, address3, address4, postalcode, telephone, faxnumber, email, principal, language, num_students from teaching_practice.SchoolTab where schoolcode=%d", $_GET['school_id']);
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$data[] = $row->schoolname.';'.$row->address1.';'.$row->address2.';'.$row->address3.';'.$row->address4.';'.$row->postalcode.';'.$row->telephone.';'.$row->faxnumber.';'.$row->email.';'.$row->principal.';'.$row->language.';'.$row->num_students;
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

       /* if (!$result)
        {
             // echo '0';
              echo $sql;
         }
         else
        {
           // echo '1';
            echo $sql;
        }*/
       
    else if ($_GET['action'] == "save_school") {
        //do sql here.....
         $data = array();
        
         $sql = sprintf("select schoolcode schoolname from teaching_practice.SchoolTab where schoolname = '%s'", $_POST['school_name']);
         $dbo->setQuery($sql);
         $result = $dbo->query();
         
         if ($dbo->getNumRows() == 0) {             
             
        $sql1 = sprintf("insert into teaching_practice.SchoolTab (schoolname,address1,address2,address3,address4,postalcode,telephone,faxnumber,email,principal,language,num_students)
               values ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%d)",$_POST['school_name'], $_POST['address_1'],$_POST['address_2'],$_POST['address_3'],$_POST['address_4'],$_POST['postal_code'],$_POST['telephone_number'],$_POST['fax_number'],$_POST['email_address'],$_POST['principal_name'],$_POST['language_used'],$_POST['number_of_students'],$_POST['school_type']);
        $dbo->setQuery($sql1);
        
        $result1 = $dbo->query();
        //echo $sql1;
        //echo $_POST['school_name'];
        
        if(!$result1)
        {
            echo '0';
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

            $sql5 = sprintf("select langid from teaching_practice.languages where lang_desc='%s'", $_POST['language']);
            $dbo->setQuery($sql5);
            $row5 = $dbo->loadObject();
            
             $sql99 = sprintf("select TypeSeqNo from teaching_practice.PlaceTypes where TypeDesc='%s'", $_POST['school_type']);
            $dbo->setQuery($sql99);
            $row99 = $dbo->loadObject();
            
            $sql6 = sprintf("insert into teaching_practice.SchoolGrades2(schoolcode, GradeSeq, SubCode, langid, TypeSeqNo)
            values(%d,%d,%d,%d,%d)", $row2->schoolcode, $row3->GradeSeqNo, $row4->SubCode, $row5->langid, $row99->TypeSeqNo);        
            $dbo->setQuery($sql6);         
            $result = $dbo->query();
            //echo $sql6;
            
            if (!$result){
                //$data['Result'] = 'ERR';
                echo '0';
            } else {
                $sql7 = sprintf("select sch_grades_id, schoolcode, GradeSeq, SubCode, langid, TypeSeqNo from teaching_practice.SchoolGrades2 where schoolcode = %d and GradeSeq = %d and SubCode = %d and langid = %d and TypeSeqNo = %d", 
                        $row2->schoolcode, $row3->GradeSeqNo, $row4->SubCode, $row5->langid, $row7.TypeSeqNo);
                $dbo->setQuery($sql7);
                $row = $dbo->loadObject();
                
                $sql8 = sprintf("SELECT 
          SchoolGrades2.sch_grades_id, 
          Grades.GradeDesc,
          Subjects.SubName,
          Subjects.SubCode,
          languages.lang_desc,
          PlaceTypes.TypeSeqNo,
          PlaceTypes.TypeDesc
        FROM
          teaching_practice.Subjects
          INNER JOIN teaching_practice.SchoolGrades2 ON (Subjects.SubCode = SchoolGrades2.SubCode)
          INNER JOIN teaching_practice.languages ON (SchoolGrades2.langid = languages.langid)
          INNER JOIN teaching_practice.Grades ON (SchoolGrades2.GradeSeq = Grades.GradeSeqNo)
          INNER JOIN teaching_practice.PlaceTypes ON (SchoolGrades2.TypeSeqNo = PlaceTypes.TypeSeqNo)
        WHERE
          (SchoolGrades2.sch_grades_id = %d)
        GROUP BY
          Grades.GradeDesc,
          PlaceTypes.TypeSeqNo,
          Subjects.SubName,
          languages.lang_desc", $row->sch_grades_id);
                //echo $sql18;
                $dbo->setQuery($sql8);
                $row9 = $dbo->loadObject();
                //echo $sql7;
                //$data['Result'] = $row->sch_grades_id;
                //$data['Result'] = 'OK';
                echo $row->sch_grades_id.';'.$row->schoolcode.';'.$row9->SubName.';'.$row9->GradeDesc.';'.$row9->lang_desc.';'.$row9->TypeDesc;
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

            $sql5 = sprintf("select langid from teaching_practice.languages where lang_desc='%s'", $_POST['language']);
            $dbo->setQuery($sql5);
            $row5 = $dbo->loadObject();          
            
            $sql6 = sprintf("insert into teaching_practice.SchoolGrades2(schoolcode, GradeSeq, SubCode, langid)
            values(%d,%d,%d,%d)", $row2->schoolcode, $row3->GradeSeqNo, $row4->SubCode, $row5->langid);        
            $dbo->setQuery($sql6);         
            $result = $dbo->query();
            //echo $sql6;
            
            $sql99 = sprintf("select TypeSeqNo from teaching_practice.PlaceTypes where TypeDesc='%s'", $_POST['school_type']);
            $dbo->setQuery($sql99);
            $row99 = $dbo->loadObject();
            
            $sql6 = sprintf("insert into teaching_practice.SchoolGrades2(schoolcode, GradeSeq, SubCode, langid, TypeSeqNo)
            values(%d,%d,%d,%d,%d)", $row2->schoolcode, $row3->GradeSeqNo, $row4->SubCode, $row5->langid, $row99->TypeSeqNo);        
            $dbo->setQuery($sql6);         
            $result = $dbo->query();
            
            if (!$result){
                //$data['Result'] = 'ERR';
                echo '0';
            } else {
                $sql7 = sprintf("select sch_grades_id, schoolcode, GradeSeq, SubCode, langid, TypeSeqNo from teaching_practice.SchoolGrades2 where schoolcode = %d and GradeSeq = %d and SubCode = %d and langid = %d and TypeSeqNo = %d", 
                        $row2->schoolcode, $row3->GradeSeqNo, $row4->SubCode, $row5->langid, $row99->TypeSeqNo);
                $dbo->setQuery($sql7);
                $row = $dbo->loadObject();
                
                $sql8 = sprintf("SELECT 
          SchoolGrades2.sch_grades_id, 
          Grades.GradeDesc,
          Subjects.SubName,
          Subjects.SubCode,
          languages.lang_desc,
          PlaceTypes.TypeSeqNo,
          PlaceTypes.TypeDesc
        FROM
          teaching_practice.Subjects
          INNER JOIN teaching_practice.SchoolGrades2 ON (Subjects.SubCode = SchoolGrades2.SubCode)
          INNER JOIN teaching_practice.languages ON (SchoolGrades2.langid = languages.langid)
          INNER JOIN teaching_practice.Grades ON (SchoolGrades2.GradeSeq = Grades.GradeSeqNo)
          INNER JOIN teaching_practice.PlaceTypes ON (SchoolGrades2.TypeSeqNo = PlaceTypes.TypeSeqNo)
        WHERE
          (SchoolGrades2.sch_grades_id = %d)
        GROUP BY
          Grades.GradeDesc,
          PlaceTypes.TypeSeqNo,
          Subjects.SubName,
          languages.lang_desc", $row->sch_grades_id);
                //echo $sql18;
                $dbo->setQuery($sql8);
                $row9 = $dbo->loadObject();
                //echo $sql7;
                //$data['Result'] = $row->sch_grades_id;
                //$data['Result'] = 'OK';
                echo $row->sch_grades_id.';'.$row->schoolcode.';'.$row9->SubName.';'.$row9->GradeDesc.';'.$row9->lang_desc.';'.$row9->TypeDesc;
            }
        }
         //echo json_encode($data);
    }
    
    else if($_GET['action'] == 'delete_subject')
    {
        
        /*$sql7 = sprintf("select sch_grades_id from teaching_practice.SchoolGrades2 where sch_grades_id = %d", $_POST['id']); 
        
  
                $dbo->setQuery($sql7); 
                $row = $dbo->loadObject();*/
                        
        $sql = sprintf("delete from teaching_practice.SchoolGrades2 where sch_grades_id=%d and schoolcode=%d",$_GET['id'],$_GET['sid']);
        $dbo->setQuery($sql);
        $result = $dbo->query();
         //$row = $dbo->loadObject();
        //echo $sql;
       // echo $_GET['id'];
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
        
        /*$sql7 = sprintf("select sch_grades_id from teaching_practice.SchoolGrades2 where sch_grades_id = %d", $_POST['id']); 
        
  
                $dbo->setQuery($sql7); 
                $row = $dbo->loadObject();*/
                        
        $sql = sprintf("delete from teaching_practice.SchoolTab where schoolcode=%d",$_GET['sid']);
        $dbo->setQuery($sql);
        $result = $dbo->query();
         //$row = $dbo->loadObject();
        //echo $sql;
       // echo $_GET['id'];
        if(!$result)
        {
            echo '0';
        }
        else{
            echo '1';
            
        }
    }
    
   
    
    //
    else if ($_GET['action'] == "display_subjects") {
        $data = array();

        /*$sql2 = sprintf("select schoolcode from teaching_practice.SchoolTab where schoolname='%s'", $_GET['school']);
        $dbo->setQuery($sql2);
        $row2 = $dbo->loadObject();*/

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
        
    else if ($_GET['action'] == "update_school") {
        
        $sql2 = sprintf("select schoolcode from teaching_practice.SchoolTab where schoolname='%s'", $_GET['schoolname']);
        //echo $sql2;
        $dbo->setQuery($sql2);
        $row = $dbo->loadObject();
            
        //do sql here.....
        $data = array();            
                
        $sql1 = sprintf("update teaching_practice.SchoolTab set schoolname='%s',address1='%s',address2='%s',address3='%s',address4='%s',postalcode='%s',telephone='%s',faxnumber='%s',email='%s',principal='%s',language='%s',num_students=%d where schoolcode=%d",
               $_GET['schoolname'], $_GET['address1'],$_GET['address2'],$_GET['address3'],$_GET['address4'],$_GET['postcode'],$_GET['tel'],$_GET['fax'],$_GET['email'],$_GET['principal'],$_GET['lang_used'],$_GET['num_students'],$row->schoolcode);
        $dbo->setQuery($sql1);
//echo $sql1;
        $result1 = $dbo->query();
        //echo $sql1;
        //echo $_POST['school_name'];

        if(!$result1)
        {
            echo '0';
        }
        else {
          /* $sql3 = sprintf("select GradeSeqNo from teaching_practice.Grades where GradeDesc='%s'", $_GET['grade']);
            $dbo->setQuery($sql3);
            $row3 = $dbo->loadObject();

            $sql4 = sprintf("select SubCode from teaching_practice.Subjects where SubName='%s'", $_GET['subject']);
            $dbo->setQuery($sql4);
            $row4 = $dbo->loadObject();

            $sql5 = sprintf("select langid from teaching_practice.languages where lang_desc='%s'", $_GET['language']);
            $dbo->setQuery($sql5);
            $row5 = $dbo->loadObject();

            $sql6 = sprintf("update teaching_practice.SchoolGrades2 set schoolcode=%d, GradeSeq=%d, SubCode=%d, langid=%d", 
                    $row->schoolcode, $row3->GradeSeqNo, $row4->SubCode, $row5->langid);        
            $dbo->setQuery($sql6);         
            $result = $dbo->query();
            //echo $sql6;

            if (!$result){
                //$data['Result'] = 'ERR';
                echo '8';
            } else {*/
                /*$sql7 = sprintf("select sch_grades_id, schoolcode, GradeSeq, SubCode, langid from teaching_practice.SchoolGrades2 where schoolcode = %d and GradeSeq = %d and SubCode = %d and langid = %d", 
                        $_GET['sid'], $row3->GradeSeqNo, $row4->SubCode, $row5->langid);
                $dbo->setQuery($sql7);
                $row = $dbo->loadObject();
                //echo $sql7;
                //$data['Result'] = $row->sch_grades_id;
                //$data['Result'] = 'OK';
                echo $row->sch_grades_id;*/
                echo '1';
            //}              
        }
     }          
}          
exit();
?>