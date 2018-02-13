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

        $sql = "select StudentNo,StudentName from teaching_practice.TeachStud where StudentName like'".$_GET['student_name']."%' group by StudentName";
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

        $data[] = $row->StudentNo.';'.$row->StudentName;

        echo json_encode($data);
    }


   
    else if ($_GET['action'] == "Run_School_Report") {

        $data = array();
		$emails = "";
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

        $sql = sprintf("select distinct a.schoolcode, a.schoolname, a.address1, a.address2, a.principal, a.telephone, a.faxnumber, a.email
                from teaching_practice.SchoolTab a, teaching_practice.TeachStud b
                where a.schoolcode = b.schoolcode
                and a.schoolcode = %d",$_GET['schoolid']);
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

        foreach($result as $row) {
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('dejavusans', '', 13);
            $pdf->SetTextColor(0,0,0);
            $pdf->MultiCell(80, 0, "$row->schoolname\n$row->address1\n$row->address2\n$row->principal", 1, 'L', 0, 0);
            $pdf->Cell(0, 0, "Tel:$row->telephone Fax:$row->faxnumber Email:$row->email", 1, 'C', 0, 0);
             $pdf->ln();
             $pdf->ln();
             $pdf->ln();
             $pdf->ln();
             $pdf->ln();

$emails.= $row->email . ',';

        $tbl =
            '<table border="1px grey" width="100%" ><tr>              
          <th width="30%" style="background-color:#99CCFF;"><b>NAME</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>YEAR</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>PHASE</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>GRADE</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>SUBJECT</b></th>
          <th width="15%" style="background-color:#99CCFF;"><b>EVALUATOR 1</b></th>
           <th width="15%" style="background-color:#99CCFF;"><b>EVALUATOR 2</b></th>
         </tr>';

         


        $sql1 = "select t.StudentNo, p.stud_perstudy, z.phase as phase, g.StudentName, r.GradeDesc,i.SubName, o.schoolname, concat(a.evalname,' ', a.evalsurname) as fullname, g.cellphone
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
             or a.evalname and a.evalsurname is NULL
             group by t.StudentNo";

            $dbo->setQuery($sql1);
            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
            $tbl.="<tr><td>$trow->StudentName ($trow->cellphone)</td>
            <td>$trow->stud_perstudy</td>
            <td>$trow->phase</td>
            <td>$trow->GradeDesc</td>
            <td>$trow->SubName</td>
             ";
            $emails.= $trow->StudentNo . '@abc.co.za' . ',';
            $sql5 = sprintf("select a.evalname, a.email from teaching_practice.Evaluator a, teaching_practice.EvalStud b
                                where a.evalseqno = b.evalseqno and b.StudentNo = '%s'",$trow->StudentNo);
                $dbo->setQuery($sql5);
                $result2 = $dbo->loadObjectList();

                foreach ($result2 as $erow){
					$emails.= $erow->email . ',';
                $tbl.="<td>$erow->evalname</td>";
            }
            
            $tbl.="</tr>";
        }

       

        $tbl.="</table>";


        $pdf->writeHTML($tbl, true, false, false, false, '');
    }

        if($_GET['id'] == '1') {
            $fName = '/scripts/ftr' . $ext . '.pdf';
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/scripts/ftr' . $ext . '.pdf', 'F');
            echo "<iframe src=" . $fName . " width='100%' height='600'>" . "</iframe>";
        }
        else if($_GET['id'] == '2')
        {
        
            $sql7 = sprintf("select distinct schoolcode, schoolname, address1, address2, principal, telephone, faxnumber, email from teaching_practice.SchoolTab where schoolcode = %d",$_GET['schoolid']);
            $dbo->setQuery($sql7);
            $row7 = $dbo->loadObject();

            $sendTo = array();
			$emailz = rtrim($emails, ",");
            $sendTo = $emailz;
            $addresses = serialize($sendTo);
            $details = "Dear Principal, Attached please find the list of students who will be visiting your school for teaching practice. Regards, Bernard Matsoso (Teaching Practice Administrator).";
           /* $details .= 'This is an automatic generated message from the OPA system. Please do not reply to this message as it is an unattended Mailbox.\n\n';
            $details .= 'Below please find your temporal ADS Password.<br /><br />';
            $details .= 'Please NOTE this Password is temporary and you will need to change it to your permanent personal password at the labs.<br /><br />';
            $details .= 'Keep your Password safe to prevent unauthorised access of your credentials.<br /><br />';
            $details .= '<b>Your ADS temporary Password is:</b> '.$row7->email;
            $details .= '<br/><br/>Regards,<br />CTS Department';*/
            
        $fName = $_SERVER['DOCUMENT_ROOT'].'/scripts/ftr' . $ext . '.pdf';
           // $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/scripts/ftr' . $ext . '.pdf', 'F');
            
    $file = $fName;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));

    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // carriage return type (we use a PHP end of line constant)
    $eol = PHP_EOL;

    // main header (multipart mandatory)
    $headers = "From: Online Personal Access - CPUT <no-reply@cput.ac.za>" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol . $eol;

    // message
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
    $headers .= $details . $eol . $eol;

    // attachment
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: application/octet-stream; name=\"" . $file . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: base64" . $eol;
    $headers .= "Content-Disposition: attachment" . $eol . $eol;
    $headers .= $content . $eol . $eol;
    $headers .= "--" . $separator . "--";

    //SEND Mail
     if (mail($sendTo, "School Report", "", $headers)) {
        echo "mail send ... OK"; // or use booleans here
      } else {
        echo "mail send ... ERROR!";
      }
               
        } else {
            echo '0';
        }
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
        $pdf->SetFont('dejavusans', '', 15);
        //$pdf->line(0,20,297,20);

        $pdf->ln();


        if($_GET['campus'] == 'All'){
            $sql = "select distinct t.evalseqno, concat(t.evalname,' ', t.evalsurname) as fullname, t.category, t.cellphone, t.email
        from teaching_practice.Evaluator t, teaching_practice.EvalStud p, teaching_practice.TeachStud q where t.evalseqno = p.evalseqno";

            $dbo->setQuery($sql);
        }else {
            $sql = sprintf("select distinct t.evalseqno, concat(t.evalname,' ', t.evalsurname) as fullname, t.category, t.cellphone, t.email
        from teaching_practice.Evaluator t, teaching_practice.EvalStud p, teaching_practice.TeachStud q where t.evalseqno = p.evalseqno and p.`StudentNo` = q.`StudentNo` and q.`Campus` = '%s'", $_GET['campus']);

            $dbo->setQuery($sql);
        }

        $result = $dbo->loadObjectList();

        foreach($result as $row) {
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
			$pdf->SetFont('dejavusans', 'B', 11);
			$pdf->Cell(0,0,$row->fullname);
                         $pdf->ln();
                        
                             
 $tbl =
            '<table border="1px" width="100%" ><tr>              
          <th width="15%" style="background-color:#99CCFF;"><b>STUDENT#</b></th>
          <th width="30%" style="background-color:#99CCFF;"><b>SCHOOLNAME</b></th>          
          <th width="30%" style="background-color:#99CCFF;"><b>SUBJECT NAME</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>GRADE</b></th>
          <th width="15%" style="background-color:#99CCFF;"><b>CELLPHONE</b></th>
          
         </tr>';


		
         /*$result1 = mysql_query($sql1);
     

        while($trow = mysql_fetch_object($result1)){*/
            if($_GET['campus'] == 'All'){
                $sql1 = "select DISTINCT t.StudentNo,r.schoolname,d.SubName, k.GradeDesc, q.cellphone
                 from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
                  teaching_practice.SchoolSubs s, teaching_practice.SchoolTab r,
                   teaching_practice.Subjects d, teaching_practice.Grades k, teaching_practice.TeachStud q
                 where
                 a.evalseqno = t.evalseqno
                 and r.schoolcode = s.schoolcode
                 and s.SubCode = d.SubCode
                 and s.GradeSeq = k.GradeSeqNo
                and s.StudentNo = t.StudentNo
                and s.StudentNo = q.StudentNo
                 and t.evalseqno =  $row->evalseqno
                 group by t.StudentNo";
                $dbo->setQuery($sql1);
            }else {
                $sql1 = sprintf("select DISTINCT t.StudentNo,r.schoolname,d.SubName, k.GradeDesc, q.cellphone
                 from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
                  teaching_practice.SchoolSubs s, teaching_practice.SchoolTab r,
                   teaching_practice.Subjects d, teaching_practice.Grades k, teaching_practice.TeachStud q
                 where
                 a.evalseqno = t.evalseqno
                 and r.schoolcode = s.schoolcode
                 and s.SubCode = d.SubCode
                 and s.GradeSeq = k.GradeSeqNo
                and s.StudentNo = t.StudentNo
                and s.StudentNo = q.StudentNo
                 and t.evalseqno =  $row->evalseqno
                 and q.campus='%s'
                 group by t.StudentNo", $_GET['campus']);
                $dbo->setQuery($sql1);
            }

            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
                $tbl.="<tr><td>$trow->StudentNo</td>           
            <td>$trow->schoolname</td>
            <td>$trow->SubName</td>
            <td>$trow->GradeDesc</td>
            <td>$trow->cellphone</td>
             ";
                
                  $tbl.="</tr>";
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }
            
             $tbl.="</table>";
        
            $pdf->writeHTML($tbl, true, false, false, false, '');
         }

        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');
        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
    }


    else if ($_GET['action'] == "Run_Evaluator_Report") {

        $data = array();
		$emails = "";
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
		
		$emails .= $row->email . ',';

            $pdf->Cell(0,0,$row->fullname);
            $pdf->ln();
            $pdf->Cell(0,0,$row->cellphone);
            $pdf->ln();
            $pdf->Cell(0,0,$row->email);
            $pdf->ln();
            $pdf->ln();
         

        $tbl =
            '<table border="1px" width="100%" ><tr>              
          <th width="15%" style="background-color:#99CCFF;"><b>STUDENT#</b></th>
          <th width="30%" style="background-color:#99CCFF;"><b>SCHOOLNAME</b></th>
          <th width="30%" style="background-color:#99CCFF;"><b>SUBJECT NAME</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>GRADE</b></th>
          <th width="15%" style="background-color:#99CCFF;"><b>CELLPHONE</b></th>
         </tr>';
  

            $sql1 = "select DISTINCT t.StudentNo,r.schoolname,d.SubName, k.GradeDesc, q.cellphone
                 from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
                  teaching_practice.SchoolSubs s, teaching_practice.SchoolTab r,
                   teaching_practice.Subjects d, teaching_practice.Grades k, teaching_practice.TeachStud q
                 where
                 a.evalseqno = t.evalseqno
                 and r.schoolcode = s.schoolcode
                 and s.SubCode = d.SubCode
                 and s.GradeSeq = k.GradeSeqNo
                and s.StudentNo = t.StudentNo
                 and t.evalseqno =  $row->evalseqno
                 and s.StudentNo = q.StudentNo
                 group by t.StudentNo";
        $dbo->setQuery($sql1);
        $result1 = $dbo->loadObjectList();

        foreach($result1 as $trow){
              $emails .= 'malibongwe@gmail.com' . ',';
                 $tbl.="<tr><td>$trow->StudentNo</td>           
            <td>$trow->schoolname</td>
            <td>$trow->SubName</td>
            <td>$trow->GradeDesc</td>
            <td>$trow->cellphone</td>
             ";
       
                $tbl.="</tr>";
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }
            
             $tbl.="</table>";


        $pdf->writeHTML($tbl, true, false, false, false, '');
        
               
            
 if($_GET['id'] == '1'){
        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');

        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
        }
        else if($_GET['id'] == '2')
        {
         $sql7 = sprintf("select distinct evalseqno, concat(evalname,' ', evalsurname) as fullname, category, cellphone, email
        from teaching_practice.Evaluator where evalseqno =%d",$evalid);
            $dbo->setQuery($sql7);
            $row7 = $dbo->loadObject();

            $sendTo = array();
			$emailz = rtrim($emails, ",");
            $sendTo = $emailz;
            $addresses = serialize($sendTo);
            $details = "Dear Evaluator/Students, Attached please find the list of students you will be evaluating. Regards, Bernard Matsoso (Teaching Practice Administrator).";
         
            
        $fName = $_SERVER['DOCUMENT_ROOT'].'/scripts/ftr' . $ext . '.pdf';
           // $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/scripts/ftr' . $ext . '.pdf', 'F');
            
    $file = $fName;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));

    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // carriage return type (we use a PHP end of line constant)
    $eol = PHP_EOL;

    // main header (multipart mandatory)
    $headers = "From: Online Personal Access - CPUT <no-reply@cput.ac.za>" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol . $eol;

    // message
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
    $headers .= $details . $eol . $eol;

    // attachment
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: application/octet-stream; name=\"" . $file . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: base64" . $eol;
    $headers .= "Content-Disposition: attachment" . $eol . $eol;
    $headers .= $content . $eol . $eol;
    $headers .= "--" . $separator . "--";

    //SEND Mail
     if (mail($sendTo, "Evaluator/Students Report", "", $headers)) {
        echo "mail send ... OK"; // or use booleans here
      } else {
        echo "mail send ... ERROR!";
      }
               
        } else {
            echo '0';
        }
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
        $pdf->SetFont('dejavusans', '', 15);
        $pdf->line(0,20,297,20);

        $pdf->ln();

    
        
 $tbl =
            '<table border="1px" width="100%" ><tr>              
          <th width="50%" style="background-color:#99CCFF;"><b>STUDENT NAME</b></th>
          <th width="50%" style="background-color:#99CCFF;"><b>SCHOOLNAME</b></th>          
          
          
         </tr>';

        if($_GET['campus'] == 'All') {

            $sql1 = "select  g.StudentName, o.schoolname, i.SubName, r.GradeDesc
                 from teaching_practice.SchoolSubs s,teaching_practice.Grades r,
                 teaching_practice.Subjects i, teaching_practice.SchoolTab o,
                  teaching_practice.TeachStud g
                 where g.StudentNo = s.StudentNo
                 and g.schoolcode = s.schoolcode
                 and r.GradeSeqNo = s.GradeSeq
                 and i.SubCode = s.SubCode
                 and o.schoolcode = s.schoolcode
                    group by o.schoolname,g.StudentName";

            $dbo->setQuery($sql1);
        }else{
            $sql1 = sprintf("select  g.StudentName, o.schoolname, i.SubName, r.GradeDesc
                 from teaching_practice.SchoolSubs s,teaching_practice.Grades r,
                 teaching_practice.Subjects i, teaching_practice.SchoolTab o,
                  teaching_practice.TeachStud g
                 where g.StudentNo = s.StudentNo
                 and g.schoolcode = s.schoolcode
                 and r.GradeSeqNo = s.GradeSeq
                 and i.SubCode = s.SubCode
                 and o.schoolcode = s.schoolcode
                 and g.campus='%s'
                    group by o.schoolname,g.StudentName",$_GET['campus']);

            $dbo->setQuery($sql1);

        }

        $result1 = $dbo->loadObjectList();

        foreach($result1 as $trow){
                
                 $tbl.="<tr><td>$trow->StudentName</td>           
            <td>$trow->schoolname</td>
           
             ";
       
                $tbl.="</tr>";
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }
            
               $tbl.="</table>"; 
        
             
              /*$pdf->ln();
        $pdf->SetFont('dejavusans', 'B', 15);
        $pdf->Cell(0,0,'TOTAL APPLICATIONS: ', 0, 1,'L');
        $pdf->SetFont('dejavusans', '', 15);
        $pdf->line(0,20,297,20);

        $pdf->ln();
         $sql9 = "select  g.StudentName, o.schoolname,count(o.schoolname) as nommer, i.SubName, r.GradeDesc
                 from teaching_practice.SchoolSubs s,teaching_practice.Grades r,
                 teaching_practice.Subjects i, teaching_practice.SchoolTab o,
                  teaching_practice.TeachStud g
                 where g.StudentNo = s.StudentNo
                 and g.schoolcode = s.schoolcode
                 and r.GradeSeqNo = s.GradeSeq
                 and i.SubCode = s.SubCode
                 and o.schoolcode = s.schoolcode
                    group by o.schoolname" ;
                
            $result9 = mysql_query($sql9);
            

            while($trow = mysql_fetch_object($result9)){
                
                 $tbl.="<tr><td>$trow->schoolname</td>           
            <td>$trow->nommer</td>
           
             ";
       
                $tbl.="</tr>";
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }*/
                   


        $pdf->writeHTML($tbl, true, false, false, false, '');

         
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

            $pdf->Cell(40,0,'GRADE',0,0,'L',true);
            $pdf->Cell(40,0,'EVALUATOR',0,0,'L',true);
            $pdf->ln();
            $pdf->ln();

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
                 and t.schoolcode = g.schoolcode
                 and t.StudentNo = $row->StudentNo";
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
        
        if($_GET['id'] == '1') {                
        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');

        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
        }
        else if($_GET['id'] == '2') {
                            $sendTo = array();
            //$sendTo = $_GET['studentno'].'@cput.ac.za';
            $sendTo = "dywibibam@cput.ac.za";
            $addresses = serialize($sendTo);
            $details = "Dear Student, Attached please find the list of students you will be evaluating. Regards, Bernard Matsoso (Teaching Practice Administrator).";
        $fName = $_SERVER['DOCUMENT_ROOT'].'/scripts/ftr' . $ext . '.pdf';  
    $file = $fName;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    // a random hash will be necessary to send mixed content
    $separator = md5(time());
    // carriage return type (we use a PHP end of line constant)
    $eol = PHP_EOL;
    // main header (multipart mandatory)
    $headers = "From: Online Personal Access - CPUT <no-reply@cput.ac.za>" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol . $eol;
    // message
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
    $headers .= $details . $eol . $eol;
    // attachment
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: application/octet-stream; name=\"" . $file . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: base64" . $eol;
    $headers .= "Content-Disposition: attachment" . $eol . $eol;
    $headers .= $content . $eol . $eol;
    $headers .= "--" . $separator . "--";
    //SEND Mail
     if (mail($sendTo, "Student Placement Report", "", $headers)) {
        echo "mail send ... OK"; // or use booleans here
      } else {
        echo "mail send ... ERROR!";
      }
               
        } else {
            echo '0';
        }
    
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
    $pdf->Cell(0,0,'Full Schools Report', 0, 1,'C');
    $pdf->ln();
    $pdf->SetFont('dejavusans', '', 15);
    $pdf->line(0,20,297,20);

    if($_GET['campus'] == 'All'){
        $sql = "select a.schoolcode, a.schoolname, a.address1, a.address2, a.principal, a.telephone, a.faxnumber, a.email
            from teaching_practice.SchoolTab a, teaching_practice.TeachStud b
            where a.schoolcode = b.schoolcode group by a.schoolcode"; //and or a.schoolcode = b.schoolcode2
        $dbo->setQuery($sql);

    }else {
        $sql = sprintf("select a.schoolcode, a.schoolname, a.address1, a.address2, a.principal, a.telephone, a.faxnumber, a.email
            from teaching_practice.SchoolTab a, teaching_practice.TeachStud b
            where a.schoolcode = b.schoolcode and b.campus='%s' group by a.schoolcode", $_GET['campus']); //and or a.schoolcode = b.schoolcode2
        $dbo->setQuery($sql);
    }
        $result = $dbo->loadObjectList();

        foreach($result as $row) {
        
        $pdf->SetFillColor(153, 204, 255);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', '', 13);
            

            $pdf->MultiCell(80, 0, "$row->schoolname\n$row->address1\n$row->principal", 1, 'L', 0, 0);
            $pdf->Cell(0, 0, "Tel:$row->telephone Fax:$row->faxnumber Email:$row->email", 1, 'C', 0, 0);
             $pdf->ln();
             $pdf->ln();
             $pdf->ln();
             $pdf->ln();

        $tbl =
            '<table border="1px grey" width="100%" ><tr>              
          <th width="30%" style="background-color:#99CCFF;"><b>NAME</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>YEAR</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>PHASE</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>GRADE</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>SUBJECT</b></th>
          <th width="15%" style="background-color:#99CCFF;"><b>EVALUATOR 1</b></th>
           <th width="15%" style="background-color:#99CCFF;"><b>EVALUATOR 2</b></th>
         </tr>';

        if($_GET['campus'] == 'All') {
            $sql1 = "select t.StudentNo, p.stud_perstudy, z.phase as phase, g.StudentName, r.GradeDesc,i.SubName, o.schoolname, concat(a.evalname,' ', a.evalsurname) as fullname, g.cellphone
             from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
             teaching_practice.SchoolSubs s, teaching_practice.Grades r, teaching_practice.Subjects i,
             teaching_practice.SchoolTab o, teaching_practice.TeachStud g, student.student2015 p, teaching_practice.Criteria z
             where
             a.evalseqno = t.evalseqno
             and g.StudentNo = t.StudentNo
             and g.StudentNo = s.StudentNo
             and g.schoolcode = o.schoolcode
			       and t.schoolcode = s.schoolcode
             and r.GradeSeqNo = s.GradeSeq
             and i.SubCode = s.SubCode
             and s.schoolcode = $row->schoolcode
             or a.evalname and a.evalsurname is NULL
             group by t.StudentNo";

            $dbo->setQuery($sql1);

        }else {
            $sql1 = sprintf("select t.StudentNo, p.stud_perstudy, z.phase as phase, g.StudentName, r.GradeDesc,i.SubName, o.schoolname, concat(a.evalname,' ', a.evalsurname) as fullname, g.cellphone
             from teaching_practice.Evaluator a, teaching_practice.EvalStud t,
             teaching_practice.SchoolSubs s, teaching_practice.Grades r, teaching_practice.Subjects i,
             teaching_practice.SchoolTab o, teaching_practice.TeachStud g, student.student2015 p, teaching_practice.Criteria z
             where
             a.evalseqno = t.evalseqno
             and g.StudentNo = t.StudentNo
             and g.StudentNo = s.StudentNo
             and g.schoolcode = o.schoolcode
			       and t.schoolcode = s.schoolcode
             and r.GradeSeqNo = s.GradeSeq
             and i.SubCode = s.SubCode
             and s.schoolcode = $row->schoolcode
             and g.campus = '%s'
             or a.evalname and a.evalsurname is NULL
             group by t.StudentNo", $_GET['campus']);

            $dbo->setQuery($sql1);
        }

            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
            $tbl.="<tr><td>$trow->StudentName ($trow->cellphone)</td>
            <td>$trow->stud_perstudy</td>
            <td>$trow->phase</td>
            <td>$trow->GradeDesc</td>
            <td>$trow->SubName</td>
             ";
            
            $sql1 = sprintf("select a.evalname from teaching_practice.Evaluator a, teaching_practice.EvalStud b
                                where a.evalseqno = b.evalseqno and b.StudentNo = %d",$trow->StudentNo);

                $dbo->setQuery($sql1);
                $result2 = $dbo->loadObjectList();

                foreach ($result2 as $erow){
                $tbl.="<td>$erow->evalname</td>";
            }
            
            $tbl.="</tr>";
        }

       

        $tbl.="</table>";


        $pdf->writeHTML($tbl, true, false, false, false, '');
    }

    $fName = '/scripts/ftr'.$ext.'.pdf';
    $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');
    echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>";
}
 /*else if ($_GET['action'] == "Run_Student_Report") {
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
            $pdf->Cell(40,0,'GRADE',0,0,'L',true);
            $pdf->Cell(40,0,'EVALUATOR',0,0,'L',true);
            $pdf->ln();
            $pdf->ln();

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
}*/


    else if ($_GET['action'] == "Run_StudentsNotPlaced") {

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
        $pdf->Cell(0,0,'Students Not Placed Report', 0, 1,'C');
        $pdf->SetFont('dejavusans', '', 14);
        $pdf->line(0,20,297,20);

        $pdf->ln();



/*        $result1 = mysql_query($sql1);

        while($trow = mysql_fetch_object($result1)) {*/
        
        $sql = ("select distinct a.stud_numb, concat(b.pers_fname,' ',b.pers_sname) as fullname, a.stud_qual,a.reg_date, a.stud_perstudy as level,
                   z.phase as phase, z.type as stype
                    from student.student2015 a, student.personal b, teaching_practice.Criteria z 
                    where a.stud_numb = b.stud_numb
                    and  a.stud_qual= z.Qual
                    and  a.stud_numb not in (select StudentNo from teaching_practice.TeachStud t where a.stud_numb = t.StudentNo)");
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();
           
            $pdf->SetFillColor(238,238,238);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', 'B', 10);
             $pdf->Cell(40,0,'STUDENT#',0,0,'L',true);
            $pdf->Cell(60,0,'STUDENT NAME',0,0,'L',true);            
            $pdf->Cell(40,0,'LEVEL OF STUDY',0,0,'L',true);
            $pdf->Cell(40,0,'PHASE',0,0,'L',true);            
            $pdf->Cell(40,0,'TYPE',0,0,'L',true);
            
            $pdf->ln();
            $pdf->ln();


        foreach($result as $trow){
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFont('dejavusans', '', 9);
                $pdf->Cell(40,0,$trow->stud_numb,0,0,'L',true);
                $pdf->Cell(60,0,$trow->fullname,0,0,'L',true);                
                $pdf->Cell(40,0,$trow->level,0,0,'L',true);
                $pdf->Cell(40,0,$trow->phase,0,0,'L',true);
                $pdf->Cell(40,0,$trow->stype,0,0,'L',true);
              
                $pdf->ln();$pdf->ln();
                $y = $pdf->GetY();
                if ($y > 190) $pdf->addPage();
            }
        
        $fName = '/scripts/ftr'.$ext.'.pdf';
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');

        echo "<iframe src=". $fName . " width='100%' height='600'>" . "</iframe>"; 
     
 }

    else if ($_GET['action'] == "EmailReport") {
		jimport('tcpdf.tcpdf');
        jimport('tcpdf.config.lang.eng');

        if($_GET['campus'] == 'All'){
            $sql = "select a.schoolcode, a.schoolname, a.address1, a.address2, a.principal, a.telephone, a.faxnumber, a.email
            from teaching_practice.SchoolTab a, teaching_practice.TeachStud b
            where a.schoolcode = b.schoolcode group by a.schoolcode"; //and or a.schoolcode = b.schoolcode2
            $dbo->setQuery($sql);

        }else {
            $sql = sprintf("select a.schoolcode, a.schoolname, a.address1, a.address2, a.principal, a.telephone, a.faxnumber, a.email
            from teaching_practice.SchoolTab a, teaching_practice.TeachStud b
            where a.schoolcode = b.schoolcode and b.campus='%s' group by a.schoolcode", $_GET['campus']); //and or a.schoolcode = b.schoolcode2
            $dbo->setQuery($sql);
        }
        $result = $dbo->loadObjectList();

        $emails = "";

        foreach($result as $row) {
			
			
        $data = array();

        

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
        $pdf->Cell(0,0,'Full Schools Report', 0, 1,'C');
        $pdf->ln();
        $pdf->SetFont('dejavusans', '', 15);
        $pdf->line(0,20,297,20);

            $emails .= $row->email . ",";

            $pdf->SetFillColor(153, 204, 255);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('dejavusans', '', 13);


            $pdf->MultiCell(80, 0, "$row->schoolname\n$row->address1\n$row->principal", 1, 'L', 0, 0);
            $pdf->Cell(0, 0, "Tel:$row->telephone Fax:$row->faxnumber Email:$row->email", 1, 'C', 0, 0);
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $tbl =
                '<table border="1px grey" width="100%" ><tr>
          <th width="30%" style="background-color:#99CCFF;"><b>NAME</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>YEAR</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>PHASE</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>GRADE</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>SUBJECT</b></th>
          <th width="15%" style="background-color:#99CCFF;"><b>EVALUATOR 1</b></th>
           <th width="15%" style="background-color:#99CCFF;"><b>EVALUATOR 2</b></th>
         </tr>';

            if($_GET['campus'] == 'All') {
                $sql1 = "select t.StudentNo, p.stud_perstudy, z.phase as phase, g.StudentName, r.GradeDesc,i.SubName, o.schoolname, concat(a.evalname,' ', a.evalsurname) as fullname, g.cellphone
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
             or a.evalname and a.evalsurname is NULL
             group by t.StudentNo";

                $dbo->setQuery($sql1);

            }else {
                $sql1 = sprintf("select t.StudentNo, p.stud_perstudy, z.phase as phase, g.StudentName, r.GradeDesc,i.SubName, o.schoolname, concat(a.evalname,' ', a.evalsurname) as fullname, g.cellphone
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
             and g.campus = '%s'
             or a.evalname and a.evalsurname is NULL
             group by t.StudentNo", $_GET['campus']);

                $dbo->setQuery($sql1);
            }

            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
                $emails .= "malibongwe@gmail.com" .",";
                $tbl.="<tr><td>$trow->StudentName ($trow->cellphone)</td>
            <td>$trow->stud_perstudy</td>
            <td>$trow->phase</td>
            <td>$trow->GradeDesc</td>
            <td>$trow->SubName</td>
             ";

                $sql1 = sprintf("select a.evalname, a.email from teaching_practice.Evaluator a, teaching_practice.EvalStud b
                                where a.evalseqno = b.evalseqno and b.StudentNo = %d",$trow->StudentNo);

                $dbo->setQuery($sql1);
                $result2 = $dbo->loadObjectList();

                foreach ($result2 as $erow){
                    $emails.= $erow->email . ",";
                    $tbl.="<td>$erow->evalname</td>";
                }

                $tbl.="</tr>";
            }

            $tbl.="</table>";

            $pdf->writeHTML($tbl, true, false, false, false, '');
            $fName = '/scripts/ftr'.$ext.'.pdf';
            $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');

            $emailz = rtrim($emails, ",");

            //Email correspondance to school/students/evaluators

            $sendTo = array();
            $sendTo = $emailz;
            $addresses = serialize($sendTo);
            $details = "Dear Student, Attached please find the list of students you will be evaluating. Regards, Bernard Matsoso (Teaching Practice Administrator).";
            $fName = $_SERVER['DOCUMENT_ROOT'].'/scripts/ftr' . $ext . '.pdf';
            $file = $fName;
            $file_size = filesize($file);
            $handle = fopen($file, "r");
            $content = fread($handle, $file_size);
            fclose($handle);
            $content = chunk_split(base64_encode($content));
            // a random hash will be necessary to send mixed content
            $separator = md5(time());
            // carriage return type (we use a PHP end of line constant)
            $eol = PHP_EOL;
            // main header (multipart mandatory)
            $headers = "From: Online Personal Access - CPUT <no-reply@cput.ac.za>" . $eol;
            $headers .= "MIME-Version: 1.0" . $eol;
            $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
            $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
            $headers .= "This is a MIME encoded message." . $eol . $eol;
            // message
            $headers .= "--" . $separator . $eol;
            $headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
            $headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
            $headers .= $details . $eol . $eol;
            // attachment
            $headers .= "--" . $separator . $eol;
            $headers .= "Content-Type: application/octet-stream; name=\"" . $file . "\"" . $eol;
            $headers .= "Content-Transfer-Encoding: base64" . $eol;
            $headers .= "Content-Disposition: attachment" . $eol . $eol;
            $headers .= $content . $eol . $eol;
            $headers .= "--" . $separator . "--";
            //SEND Mail
            if (mail($sendTo, "School Report", "", $headers)) {
                echo "mail send ... OK"; // or use booleans here
            } else {
                echo "mail send ... ERROR!";
            }

            //end of email

            $tbl = "";
            $emails="";
            $emailz = "";
            $sendTo = "";
			$pdf = "";
        }


    }
 
 else if ($_GET['action'] == "EmailSchoolCorres") {

		$emails ="";
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

        $sql = sprintf("select distinct a.schoolcode, a.schoolname, a.address1, a.address2, a.principal, a.telephone, a.faxnumber, a.email
                from teaching_practice.SchoolTab a, teaching_practice.TeachStud b
                where a.schoolcode = b.schoolcode
                and a.schoolcode = %d",$_GET['schoolid']);
        $dbo->setQuery($sql);
        $result = $dbo->loadObjectList();

        foreach($result as $row) {
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('dejavusans', '', 13);
            $pdf->SetTextColor(0,0,0);
            $pdf->MultiCell(80, 0, "$row->schoolname\n$row->address1\n$row->address2\n$row->principal", 1, 'L', 0, 0);
            $pdf->Cell(0, 0, "Tel:$row->telephone Fax:$row->faxnumber Email:$row->email", 1, 'C', 0, 0);
             $pdf->ln();
             $pdf->ln();
             $pdf->ln();
             $pdf->ln();
             $pdf->ln();

			$emails = $row->email . ',';

        $tbl =
            '<table border="1px grey" width="100%" ><tr>              
          <th width="30%" style="background-color:#99CCFF;"><b>NAME</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>YEAR</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>PHASE</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>GRADE</b></th>
          <th width="10%" style="background-color:#99CCFF;"><b>SUBJECT</b></th>
          <th width="15%" style="background-color:#99CCFF;"><b>EVALUATOR 1</b></th>
           <th width="15%" style="background-color:#99CCFF;"><b>EVALUATOR 2</b></th>
         </tr>';

        $sql1 = "select t.StudentNo, p.stud_perstudy, z.phase as phase, g.StudentName, r.GradeDesc,i.SubName, o.schoolname, concat(a.evalname,' ', a.evalsurname) as fullname, g.cellphone
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
             or a.evalname and a.evalsurname is NULL
             group by t.StudentNo";

            $dbo->setQuery($sql1);
            $result1 = $dbo->loadObjectList();

            foreach($result1 as $trow){
			$emails .= $trow->StudentNo . '@abc.ac.za' . ',';
            $tbl.="<tr><td>$trow->StudentName ($trow->cellphone)</td>
            <td>$trow->stud_perstudy</td>
            <td>$trow->phase</td>
            <td>$trow->GradeDesc</td>
            <td>$trow->SubName</td>
             ";
            
            $sql5 = sprintf("select a.evalname, a.email from teaching_practice.Evaluator a, teaching_practice.EvalStud b
                                where a.evalseqno = b.evalseqno and b.StudentNo = '%s'",$trow->StudentNo);
                $dbo->setQuery($sql5);
                $result2 = $dbo->loadObjectList();

                foreach ($result2 as $erow){
				$emails .= $erow->email . ',';
                $tbl.="<td>$erow->evalname</td>";
            }
            
            $tbl.="</tr>";
        }

       
//$emailz = rtrim($emails, ",");
        $tbl.="</table>";


        $pdf->writeHTML($tbl, true, false, false, false, '');
    }

        /*if($_GET['id'] == '1') {*/
            $fName = '/scripts/ftr' . $ext . '.pdf';
			
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/scripts/ftr' . $ext . '.pdf', 'F');
            echo "<iframe src=" . $fName . " width='100%' height='600'>" . "</iframe>";
			
			$file  = $_SERVER['DOCUMENT_ROOT'].'/scripts/ftr' . $ext . '.pdf';
			
        /*}
        else if($_GET['id'] == '2')
        {*/
        
            /*$sql7 = sprintf("select distinct schoolcode, schoolname, address1, address2, principal, telephone, faxnumber, email from teaching_practice.SchoolTab where schoolcode = %d",$_GET['schoolid']);
            $dbo->setQuery($sql7);
            $row7 = $dbo->loadObject();*/

            $sendTo = array();
			$emailz = rtrim($emails, ",");
            $sendTo = $emailz;
            $addresses = serialize($sendTo);
            $details = "Dear Principal, Attached please find the list of students who will be visiting your school for teaching practice. Regards, Bernard Matsoso (Teaching Practice Administrator).";
           /* $details .= 'This is an automatic generated message from the OPA system. Please do not reply to this message as it is an unattended Mailbox.\n\n';
            $details .= 'Below please find your temporal ADS Password.<br /><br />';
            $details .= 'Please NOTE this Password is temporary and you will need to change it to your permanent personal password at the labs.<br /><br />';
            $details .= 'Keep your Password safe to prevent unauthorised access of your credentials.<br /><br />';
            $details .= '<b>Your ADS temporary Password is:</b> '.$row7->email;
            $details .= '<br/><br/>Regards,<br />CTS Department';*/
            
       // $fName = $_SERVER['DOCUMENT_ROOT'].'/scripts/ftr' . $ext . '.pdf';
           // $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/scripts/ftr' . $ext . '.pdf', 'F');
            
    $file = $fName;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));

    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // carriage return type (we use a PHP end of line constant)
    $eol = PHP_EOL;

    // main header (multipart mandatory)
    $headers = "From: Online Personal Access - CPUT <no-reply@cput.ac.za>" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol . $eol;

    // message
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
    $headers .= $details . $eol . $eol;

    // attachment
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: application/octet-stream; name=\"" . $file . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: base64" . $eol;
    $headers .= "Content-Disposition: attachment" . $eol . $eol;
    $headers .= $content . $eol . $eol;
    $headers .= "--" . $separator . "--";

    //SEND Mail
     if (mail($sendTo, "School Report", "", $headers)) {
        echo "mail send ... OK"; // or use booleans here
      } else {
        echo "mail send ... ERROR!";
      }
     
 }
 
}
exit();
?>
