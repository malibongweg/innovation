<?php
defined('_JEXEC') or die('Restricted access');
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$doc =& JFactory::getDocument();
$dbo = &JFactory::getDBO();

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'search_school') {

        $sql = "select schoolcode,schoolname from teaching_practice.SchoolTab where schoolname like'".$_GET['school_name']."%'";

        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        $data = array();

        foreach($result as $row){
            $data[] = $row->schoolcode.';'.$row->schoolname;
        }
        echo json_encode($data);

    }

    else if ($_GET['action'] == 'display_school_info') {
        $data = array();
        $sql = sprintf("select schoolcode, schoolname, address1, address2, address3, address4, postalcode, telephone, faxnumber, email, principal, language, num_students, schooltype from teaching_practice.SchoolTab where schoolcode=%d", $_GET['school_id']);

           $dbo->setQuery($sql);
            $row = $dbo->loadObject();
            $data[] = $row->schoolname.';'.$row->address1.';'.$row->address2.';'.$row->address3.';'.$row->address4.';'.$row->postalcode.';'.$row->telephone.';'.$row->faxnumber.';'.$row->email.';'.$row->principal.';'.$row->language.';'.$row->num_students.';'.$row->schooltype;
            echo json_encode($data);
    }

    else if ($_GET['action'] == 'search_evaluator') {

        $sql = "select evalseqno,evalname,evalsurname,idnumber,address1,address2,address3,address4,postalcode,telephone,cellphone,faxnumber,email,category,visits from teaching_practice.Evaluator where evalname like'".$_GET['evaluator_name']."%'";

        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        $data = array();

        foreach($result as $row){
            $data[] = $row->evalseqno.';'.$row->evalname.';'.$row->evalsurname;
        }
        echo json_encode($data);

    }

    else if ($_GET['action'] == 'display_evaluator_info') {

        $data = array();
        $sql = sprintf("select evalseqno,evalname,evalsurname,email,category,visits from teaching_practice.Evaluator where evalseqno=%d", $_GET['evalid']);
        $dbo->setQuery($sql);
        $row = $dbo->loadObject();
        $data[] = $row->evalname.';'.$row->evalsurname.';'.$row->email.';'.$row->category.';'.$row->visits.';'.$_GET['evalid'];
        echo json_encode($data);
    }

    else if ($_GET['action'] == 'search_student') {

        $sql = "select StudentNo,StudentName from teaching_practice.TeachStud where StudentNo like'".$_GET['student_no']."%'";

        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
        $data = array();

        foreach($result as $row){
            $data[] = $row->StudentNo.';'.$row->StudentName;
        }
        echo json_encode($data);

    }

    else if ($_GET['action'] == "display_student_info") {
        $data = array();
        $sql = sprintf("select StudentNo,StudentName from teaching_practice.TeachStud where StudentNo='%s'",$_GET['student_id']);
        $dbo->setQuery($sql);
        $row = $dbo->loadObject();
        $data = array();

        $data[] = $row->StudentNo.';'.$row->StudentName;

        echo json_encode($data);
    }


    else if ($_GET['action'] == "Students_Not_Placed_Report") {
        $data = array();
        $sql = ("select StudentNo,StudentName from teaching_practice.TeachStud");
        $dbo->setQuery($sql);
        $row = $dbo->loadObjectList();

        foreach($result as $row){
            $data[] = $row->StudentNo.';'.$row->StudentName;
        }
        echo json_encode($data);
    }

    else if ($_GET['action'] == "Run_School_Report") {
        $data = array();

        jimport('tcpdf.tcpdf');
        jimport('tcpdf.config.lang.eng');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Michael Stevens');
        $pdf->SetTitle('CPUT');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10,30,10);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage('L');

        $pdf->SetFont('dejavusans', '', 15);
        $pdf->Cell(0,0,'School Report', 0, 1,'C');
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->line(0,20,297,20);

        $pdf->ln();

        $sql = sprintf("select a.schoolcode, a.schoolname, a.address1, a.address2, a.principal, a.telephone, a.faxnumber, a.email
                from teaching_practice.SchoolTab a, teaching_practice.TeachStud b
                where a.schoolcode = b.schoolcode
                and a.schoolcode = %d",$_GET['schoolid']);
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

        foreach($result as $row) {
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', 'B', 11);
            $pdf->Cell(0,0,'School Name: '.$row->schoolname);
            $pdf->ln();
            $pdf->Cell(0,0,'Address 1: '.$row->address1);
            $pdf->ln();
            $pdf->Cell(0,0,'Address 2: '.$row->address2);
            $pdf->ln();
            $pdf->Cell(0,0,'Principal: '.$row->principal);
            $pdf->ln();
            $pdf->Cell(0,0,'Tel: '.$row->telephone);
            $pdf->ln();
            $pdf->Cell(0,0,'Fax: '.$row->faxnumber);
            $pdf->ln();
            $pdf->Cell(0,0,'Email: '.$row->email);
            $pdf->ln();
            $pdf->ln();



            $pdf->SetFillColor(0,138,204);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', 'B', 12);
            $pdf->Cell(40,0,'STUDENT#',0,0,'L',true);
            $pdf->Cell(40,0,'STUDENT NAME',0,0,'L',true);
            $pdf->Cell(40,0,'YEAR',0,0,'L',true);
            $pdf->Cell(40,0,'PHASE',0,0,'L',true);
            $pdf->Cell(40,0,'GRADE',0,0,'L',true);
            $pdf->Cell(40,0,'SUBJECT',0,0,'L',true);
            $pdf->Cell(40,0,'EVALUATOR',0,0,'L',true);
            $pdf->ln();


            $sql1 = "select t.StudentNo, p.stud_perstudy, z.phase as phase, g.StudentName, r.GradeDesc,i.SubName, o.schoolname, concat(a.evalname,' ', a.evalsurname) as fullname
                 from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
                 teaching_practice.SchoolSubs s, teaching_practice.Grades r, teaching_practice.Subjects i,
                 teaching_practice.SchoolTab o, teaching_practice.TeachStud g, student.student2015 p, teaching_practice.Criteria z
                 where
                 a.evalseqno = t.evalseqno
                 and g.StudentNo = t.StudentNo
                 and g.StudentNo = s.StudentNo
                 and g.schoolcode = o.schoolcode
                 and r.GradeSeqNo = s.GradeSeq
                 and i.SubCode = s.SubCode
                 and s.schoolcode = $row->schoolcode
                 group by t.StudentNo";
            $dbo->setQuery($sql1);
            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont('dejavusans', '', 9);

                $pdf->Cell(40,0, $trow->StudentNo,0,0,'L',true);
                $pdf->Cell(40,0,$trow->StudentName,0,0,'L',true);
                $pdf->Cell(40,0,$trow->stud_perstudy,0,0,'L',true);
                $pdf->Cell(40,0,$trow->phase,0,0,'L',true);
                $pdf->Cell(40,0,$trow->GradeDesc,0,0,'L',true);
                $pdf->Cell(40,0,$trow->SubName,0,0,'L',true);
                $pdf->Cell(40,0,$trow->fullname,0,0,'L',true);
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }

        }

        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');
        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
    }

    else if ($_GET['action'] == "Run_Full_Schools_Report1") {
        $data = array();

        jimport('tcpdf.tcpdf');
        jimport('tcpdf.config.lang.eng');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Michael Stevens');
        $pdf->SetTitle('CPUT');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10,30,10);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage('L');

        $pdf->SetFont('dejavusans', '', 15);
        $pdf->Cell(0,0,'Schools Report', 0, 1,'C');
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->line(0,20,297,20);


        $sql = ("select a.schoolcode, a.schoolname, a.address1, a.address2, a.principal, a.telephone, a.faxnumber, a.email
                from teaching_practice.SchoolTab a, teaching_practice.TeachStud b
                where a.schoolcode = b.schoolcode");
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

        foreach($result as $row) {
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', 'B', 11);
            $pdf->Cell(0,0,'School Name: '.$row->schoolname);
            $pdf->ln();
            $pdf->Cell(0,0,'Address 1: '.$row->address1);
            $pdf->ln();
            $pdf->Cell(0,0,'Address 2: '.$row->address2);
            $pdf->ln();
            $pdf->Cell(0,0,'Principal: '.$row->principal);
            $pdf->ln();
            $pdf->Cell(0,0,'Tel: '.$row->telephone);
            $pdf->ln();
            $pdf->Cell(0,0,'Fax: '.$row->faxnumber);
            $pdf->ln();
            $pdf->Cell(0,0,'Email: '.$row->email);
            $pdf->ln();
            $pdf->ln();


            $pdf->SetFillColor(0,138,204);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', 'B', 12);
            $pdf->Cell(40,0,'STUDENT#',0,0,'L',true);
            $pdf->Cell(40,0,'STUDENT NAME',0,0,'L',true);
            $pdf->Cell(40,0,'YEAR',0,0,'L',true);
            $pdf->Cell(40,0,'PHASE',0,0,'L',true);
            $pdf->Cell(40,0,'GRADE',0,0,'L',true);
            $pdf->Cell(40,0,'SUBJECT',0,0,'L',true);
            $pdf->Cell(40,0,'EVALUATOR',0,0,'L',true);
            $pdf->ln();


            $sql1 = "select t.StudentNo, p.stud_perstudy, z.phase as phase, g.StudentName, r.GradeDesc,i.SubName, o.schoolname, concat(a.evalname,' ', a.evalsurname) as fullname
                 from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
                 teaching_practice.SchoolSubs s, teaching_practice.Grades r, teaching_practice.Subjects i,
                 teaching_practice.SchoolTab o, teaching_practice.TeachStud g, student.student2015 p, teaching_practice.Criteria z
                 where
                 a.evalseqno = t.evalseqno
                 and g.StudentNo = t.StudentNo
                 and g.StudentNo = s.StudentNo
                 and g.schoolcode = o.schoolcode
                 and r.GradeSeqNo = s.GradeSeq
                 and i.SubCode = s.SubCode
                 and s.schoolcode = $row->schoolcode
                 group by t.StudentNo";

            $dbo->setQuery($sql1);
            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont('dejavusans', '', 9);

                $pdf->Cell(40,0, $trow->StudentNo,0,0,'L',true);
                $pdf->Cell(40,0,$trow->StudentName,0,0,'L',true);
                $pdf->Cell(40,0,$trow->stud_perstudy,0,0,'L',true);
                $pdf->Cell(40,0,$trow->phase,0,0,'L',true);
                $pdf->Cell(40,0,$trow->GradeDesc,0,0,'L',true);
                $pdf->Cell(40,0,$trow->SubName,0,0,'L',true);
                $pdf->Cell(40,0,$trow->fullname,0,0,'L',true);
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }

        }

        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');
        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
    }

    else if ($_GET['action'] == "Run_Full_Evaluators_Report") {
        $data = array();
       
        jimport('tcpdf.tcpdf');
        jimport('tcpdf.config.lang.eng');
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Michael Stevens');
        $pdf->SetTitle('CPUT');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10,30,10);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        $pdf->AddPage('L');

        $pdf->SetFont('dejavusans', 'B', 15);
        $pdf->Cell(0,0,'Evaluator Report', 0, 1,'C');
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->line(0,20,297,20);

        $pdf->ln();
        
        $sql = ("select distinct t.evalseqno, concat(t.evalname,' ', t.evalsurname) as fullname, t.category, t.cellphone, t.email
        from teaching_practice.Evaluator t, teaching_practice.EvalStud p where t.evalseqno = p.evalseqno");
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

        foreach($result as $row) {
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
			$pdf->SetFont('dejavusans', 'B', 11);
			$pdf->Cell(0,0,$row->fullname);
            $pdf->ln();

			$pdf->SetFillColor(0,138,204);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('dejavusans', 'B', 12);
			$pdf->Cell(40,0,'STUDENT#',0,0,'L',true);
			$pdf->Cell(60,0,'SCHOOLNAME',0,0,'L',true);
			$pdf->Cell(60,0,'SUBJECT NAME',0,0,'L',true);
			$pdf->Cell(40,0,'GRADE',0,0,'L',true);
            $pdf->ln();
            $pdf->ln();

             $sql1 = "select t.StudentNo, r.GradeDesc,i.SubName, o.schoolname, t.evalseqno
                 from teaching_practice.Evaluator a, teaching_practice.EvalStud t, teaching_practice.SchoolSubs s, teaching_practice.Grades r, teaching_practice.Subjects i, teaching_practice.SchoolTab o, teaching_practice.TeachStud g
                 where
                 a.evalseqno = t.evalseqno
                 and g.StudentNo = t.StudentNo
                 and g.StudentNo = s.StudentNo
                 and s.StudentNo = t.StudentNo
                 and g.schoolcode = s.schoolcode
                 and r.GradeSeqNo = s.GradeSeq
                 and i.SubCode = s.SubCode
                 and o.schoolcode = s.schoolcode
                 and t.evalseqno = $row->evalseqno";
                 $dbo->setQuery($sql1);
                 $result1 = $dbo->loadObjectList();
					
            foreach($result1 as $trow){
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);
				$pdf->SetFont('dejavusans', '', 9);
				$pdf->Cell(40,0,$trow->StudentNo,0,0,'L',true);
				$pdf->Cell(60,0,$trow->schoolname,0,0,'L',true);
				$pdf->Cell(60,0,$trow->SubName,0,0,'L',true);
				$pdf->Cell(40,0,$trow->GradeDesc,0,0,'L',true);
                $pdf->ln();$pdf->ln();
				$y = $pdf->GetY();
				if ($y > 190) $pdf->addPage();
			}

         }

        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');
        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
    }


    else if ($_GET['action'] == "Run_Evaluator_Report") {
        $data = array();

        jimport('tcpdf.tcpdf');
        jimport('tcpdf.config.lang.eng');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Michael Stevens');
        $pdf->SetTitle('CPUT');


        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10,30,10);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage('L');

        $pdf->SetFont('dejavusans', 'B', 15);
        $pdf->Cell(0,0,'Evaluator Report', 0, 1,'C');
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->line(0,20,297,20);

        $pdf->ln();

        $evalid = $_GET['evalid'];
        
        $sql = sprintf("select distinct t.evalseqno, concat(t.evalname,' ', t.evalsurname) as fullname, t.category, t.cellphone, t.email
        from teaching_practice.Evaluator t, teaching_practice.EvalStud p where t.evalseqno =%d",$evalid);
        $dbo->setQuery($sql);
        $row = $dbo->loadObject();

             $pdf->SetFillColor(238,238,238);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', '', 11);
            $pdf->Cell(0,0,$row->fullname);
            $pdf->ln();
            $pdf->Cell(0,0,$row->cellphone);
            $pdf->ln();
            $pdf->Cell(0,0,$row->email);
            $pdf->ln();
            $pdf->Cell(0,0,$row->category);
            $pdf->ln();


            $pdf->SetFillColor(238,238,238);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', 'B', 9);
            $pdf->Cell(40,0,'STUDENT#',0,0,'L',true);
            $pdf->Cell(60,0,'SCHOOLNAME',0,0,'L',true);
            $pdf->Cell(60,0,'SUBJECT NAME',0,0,'L',true);
            $pdf->Cell(40,0,'GRADE',0,0,'L',true);
            $pdf->ln();
            $pdf->ln();

            $sql1 = "select t.StudentNo, r.GradeDesc,i.SubName, o.schoolname, t.evalseqno
                 from teaching_practice.Evaluator a, teaching_practice.EvalStud t, teaching_practice.SchoolSubs s, teaching_practice.Grades r, teaching_practice.Subjects i, teaching_practice.SchoolTab o, teaching_practice.TeachStud g
                 where
                 a.evalseqno = t.evalseqno
                 and g.StudentNo = t.StudentNo
                 and g.StudentNo = s.StudentNo
                 and s.StudentNo = t.StudentNo
                 and g.schoolcode = s.schoolcode
                 and r.GradeSeqNo = s.GradeSeq
                 and i.SubCode = s.SubCode
                 and o.schoolcode = s.schoolcode
                 and t.evalseqno = $row->evalseqno";
            $dbo->setQuery($sql1);
            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont('dejavusans', '', 9);
                $pdf->Cell(40,0,$trow->StudentNo,0,0,'L',true);
                $pdf->Cell(60,0,$trow->schoolname,0,0,'L',true);
                $pdf->Cell(60,0,$trow->SubName,0,0,'L',true);
                $pdf->Cell(40,0,$trow->GradeDesc,0,0,'L',true);
                $pdf->ln();$pdf->ln();
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }

        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');

        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
    }

    else if ($_GET['action'] == "Run_Full_Students") {
        $data = array();

        jimport('tcpdf.tcpdf');
        jimport('tcpdf.config.lang.eng');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Michael Stevens');
        $pdf->SetTitle('CPUT');


        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10,30,10);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage('L');

        $pdf->SetFont('dejavusans', 'B', 15);
        $pdf->Cell(0,0,'Students Placement Report', 0, 1,'C');
        $pdf->SetFont('dejavusans', '', 14);
        $pdf->line(0,20,297,20);

        $pdf->ln();

      
        
        $sql = sprintf("select DISTINCT t.StudentNo, t.StudentName from teaching_practice.TeachStud t");
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

         foreach($result as $row) {
             $pdf->SetFillColor(238,238,238);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', '', 12);
            $pdf->Cell(0,0,'STUDENT DETAIL: '.$row->StudentNo. ' '.$row->StudentName);
            $pdf->ln();
            


            $pdf->SetFillColor(238,238,238);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', 'B', 10);
            $pdf->Cell(40,0,'STUDENT NAME#',0,0,'L',true);
            $pdf->Cell(60,0,'SCHOOLNAME',0,0,'L',true);
            $pdf->Cell(60,0,'SUBJECT NAME',0,0,'L',true);
           // $pdf->Cell(40,0,'PHASE',0,0,'L',true);
            //$pdf->Cell(40,0,'LEVEL',0,0,'L',true);
            $pdf->Cell(40,0,'GRADE',0,0,'L',true);
            $pdf->Cell(40,0,'EVALUATOR',0,0,'L',true);
            $pdf->ln();
            $pdf->ln();
//-- c.phase, c.type,
     //         --and g.Qualification= c.Qual -- , Criteria c
            $sql1 = "select  g.StudentName, o.schoolname, i.SubName, r.GradeDesc, concat(a.evalname,' ', a.evalsurname) as fullname
                 from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
                 teaching_practice.SchoolSubs s,teaching_practice.Grades r,
                 teaching_practice.Subjects i, teaching_practice.SchoolTab o,
                  teaching_practice.TeachStud g  
                 where a.evalseqno = t.evalseqno
                 and g.StudentNo = t.StudentNo
                 and g.StudentNo = s.StudentNo
                 and s.StudentNo = t.StudentNo
                 and g.schoolcode = s.schoolcode
                 and r.GradeSeqNo = s.GradeSeq
                 and i.SubCode = s.SubCode
                 and o.schoolcode = s.schoolcode               
                 and t.StudentNo = $row->StudentNo ";
            $dbo->setQuery($sql1);
            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont('dejavusans', '', 9);
                $pdf->Cell(40,0,$trow->StudentName,0,0,'L',true);
                $pdf->Cell(60,0,$trow->schoolname,0,0,'L',true);
                $pdf->Cell(60,0,$trow->SubName,0,0,'L',true);
                $pdf->Cell(40,0,$trow->GradeDesc,0,0,'L',true);
                $pdf->Cell(40,0,$trow->fullname,0,0,'L',true);
                $pdf->ln();$pdf->ln();
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }
         }
        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');

        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
}


    else if ($_GET['action'] == "Run_Student_Report") {
        $data = array();

        jimport('tcpdf.tcpdf');
        jimport('tcpdf.config.lang.eng');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Michael Stevens');
        $pdf->SetTitle('CPUT');


        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10,30,10);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage('L');

        $pdf->SetFont('dejavusans', 'B', 15);
        $pdf->Cell(0,0,'Student Placement Report', 0, 1,'C');
        $pdf->SetFont('dejavusans', '', 14);
        $pdf->line(0,20,297,20);

        $pdf->ln();



        $sql = sprintf("select DISTINCT t.StudentNo, t.StudentName from teaching_practice.TeachStud t where t.StudentNo='%s'",$_GET['studentno']);
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

        foreach($result as $row) {
            $pdf->SetFillColor(238,238,238);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', '', 12);
            $pdf->Cell(0,0,'STUDENT DETAIL: '.$row->StudentNo. ' '.$row->StudentName);
            $pdf->ln();



            $pdf->SetFillColor(238,238,238);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', 'B', 10);
            $pdf->Cell(40,0,'STUDENT NAME#',0,0,'L',true);
            $pdf->Cell(60,0,'SCHOOLNAME',0,0,'L',true);
            $pdf->Cell(60,0,'SUBJECT NAME',0,0,'L',true);
            // $pdf->Cell(40,0,'PHASE',0,0,'L',true);
            //$pdf->Cell(40,0,'LEVEL',0,0,'L',true);
            $pdf->Cell(40,0,'GRADE',0,0,'L',true);
            $pdf->Cell(40,0,'EVALUATOR',0,0,'L',true);
            $pdf->ln();
            $pdf->ln();
//-- c.phase, c.type,
            //         --and g.Qualification= c.Qual -- , Criteria c
            $sql1 = "select  g.StudentName, o.schoolname, i.SubName, r.GradeDesc, concat(a.evalname,' ', a.evalsurname) as fullname
                 from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
                 teaching_practice.SchoolSubs s,teaching_practice.Grades r,
                 teaching_practice.Subjects i, teaching_practice.SchoolTab o,
                  teaching_practice.TeachStud g
                 where a.evalseqno = t.evalseqno
                 and g.StudentNo = t.StudentNo
                 and g.StudentNo = s.StudentNo
                 and s.StudentNo = t.StudentNo
                 and g.schoolcode = s.schoolcode
                 and r.GradeSeqNo = s.GradeSeq
                 and i.SubCode = s.SubCode
                 and o.schoolcode = s.schoolcode
                 and t.StudentNo = $row->StudentNo ";
            $dbo->setQuery($sql1);
            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont('dejavusans', '', 9);
                $pdf->Cell(40,0,$trow->StudentName,0,0,'L',true);
                $pdf->Cell(60,0,$trow->schoolname,0,0,'L',true);
                $pdf->Cell(60,0,$trow->SubName,0,0,'L',true);
                $pdf->Cell(40,0,$trow->GradeDesc,0,0,'L',true);
                $pdf->Cell(40,0,$trow->fullname,0,0,'L',true);
                $pdf->ln();$pdf->ln();
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }
        }
        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');

        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
    }

    else if ($_GET['action'] == "Run_Full_Schools_Report") {
    $data = array();

    jimport('tcpdf.tcpdf');
    jimport('tcpdf.config.lang.eng');

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, " ", 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Michael Stevens');
    $pdf->SetTitle('CPUT');

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $pdf->SetMargins(10,30,10);

    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage('L');

    $pdf->SetFont('dejavusans', '', 15);
    $pdf->Cell(0,0,'Schools Report', 0, 1,'C');
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->line(0,20,297,20);


    $sql = ("select a.schoolcode, a.schoolname, a.address1, a.address2, a.principal, a.telephone, a.faxnumber, a.email
            from teaching_practice.SchoolTab a, teaching_practice.TeachStud b
            where a.schoolcode = b.schoolcode");
    $dbo->setQuery($sql);
    $result = $dbo->loadObjectList();

    foreach($result as $row) {

        $tbl =
            "<table border='1px' width='50%'><tr>
          <th width='34%'><b>SCHOOL</b></th>
          <th width='12%'><b>STUDENT#</b></th>
          <th width='30%'><b>NAME</b></th>
          <th width='2%'><b>YEAR</b></th>
          <th width='4%'><b>PHASE</b></th>
          <th width='5%'><b>GRADE</b></th>
          <th width='13%'><b>SUBJECT</b></th>
          <th width='20%'><b>EVALUATOR</b></th>
         </tr>

         <tr>
          <td rowspan='1'>$row->schoolname<br/>
          $row->address1<br/>
          $row->principal<br/>
          $row->telephone<br/>
          $row->faxnumber<br/>
          $row->email</td>";


        $sql1 = "select t.StudentNo, p.stud_perstudy, z.phase as phase, g.StudentName, r.GradeDesc,i.SubName, o.schoolname, concat(a.evalname,' ', a.evalsurname) as fullname
             from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
             teaching_practice.SchoolSubs s, teaching_practice.Grades r, teaching_practice.Subjects i,
             teaching_practice.SchoolTab o, teaching_practice.TeachStud g, student.student2015 p, teaching_practice.Criteria z
             where
             a.evalseqno = t.evalseqno
             and g.StudentNo = t.StudentNo
             and g.StudentNo = s.StudentNo
             and g.schoolcode = o.schoolcode
             and r.GradeSeqNo = s.GradeSeq
             and i.SubCode = s.SubCode
             and s.schoolcode = $row->schoolcode
             group by t.StudentNo";

        $dbo->setQuery($sql1);
        $result1 = $dbo->loadObjectList();

        //$i = 0;

        foreach($result1 as $trow){
            //if($i == 0){
                $tbl.="<td>$trow->StudentNo</td>
            <td>$trow->StudentName</td>
            <td>$trow->stud_perstudy</td>
            <td>$trow->phase</td>
            <td>$trow->GradeDes</td>
            <td>$trow->SubName</td>
            <td>$trow->fullname</td></tr>";
           /* }else{
            $tbl.= "<tr>

            <td>$trow->StudentNo</td>
            <td>$trow->StudentName</td>
            <td>$trow->stud_perstudy</td>
            <td>$trow->phase</td>
            <td>$trow->GradeDes</td>
            <td>$trow->SubName</td>
            <td>$trow->fullname</td>
         </tr>";}*/
//$i++;
           // $y = $pdf->GetY();
            //if ($y > 190) $pdf->addPage();
        }


        $tbl.="</table>";

        $pdf->writeHTML($tbl, true, false, false, false, '');
    }
      //  $pdf->writeHTML($tbl, true, false, false, false, '');


    $fName = '/scripts/ftr'.$ext.'.pdf';
    $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');
    echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
}

}
exit();
?>
