<?php
defined('_JEXEC') or die('Restricted access');
//JHTML::_('behavior.mootools');
$doc =& JFactory::getDocument();
//$doc->setMimeEncoding('application/pdf');
$dbo =& JFactory::getDBO();

jimport('tcpdf.tcpdf');
jimport('tcpdf.config.lang.eng');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Michael Stevens');
$pdf->SetTitle('CPUT');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(10,3,10);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// add a page
//10.60.0.122
$pdf->AddPage('P');

$pdf->SetFont('dejavusans', '', 16);
$pdf->Cell(0,0,'TEMPORARY IDENTIFICATION', 0, 1,'C');
$pdf->line(0,20,297,20);
$pdf->ln();
$pdf->ln();

$sql = sprintf("select location from identity.photos where userid = %d",$_GET['uid']);
$dbo->setQuery($sql);
$location = $dbo->loadResult();

$pdf->Image($_SERVER['DOCUMENT_ROOT'].$location,90,30,"","","jpeg","",false,300);
$yr = Date("Y");
$sql = "select A.stud_numb, concat(A.pers_fname,' ',A.pers_sname) as fullname, A.pers_dob, B.qual_desc from student.personal_curr A, structure.qualification B,student.student".$yr." C 
		where A.stud_numb =".$_GET['uid']." and A.stud_numb = C.stud_numb and C.stud_qual = B.qual_code and B.qual_year = ".$yr;
$dbo->setQuery($sql);
$row = $dbo->loadObject();
//214114910
$pdf->SetY(80);
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0,0,'STUDENT NUMBER',0,1,'C');
$pdf->Cell(0,0,$row->stud_numb,0,1,'C');
$pdf->ln();
$pdf->Cell(0,0,'NAME',0,1,'C');
$pdf->Cell(0,0,$row->fullname,0,1,'C');
$pdf->ln();
$pdf->Cell(0,0,'QUALIFICATION',0,1,'C');
$pdf->Cell(0,0,$row->qual_desc,0,1,'C');
$pdf->ln();
$pdf->ln();
		
$pdf->Cell(0,0,'DATE',0,1,'C');	
$pdf->Cell(0,0,Date("Y-m-d H:i"),0,1,'C');	
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0,0,'EXPIRES WITHIN 24 HOURS',0,1,'C');			

//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/tempcards/images/card.pdf', 'F');

//============================================================+
// END OF FILE                                                
//============================================================+
?>
<iframe src="/scripts/tempcards/images/card.pdf" width="100%" height="600" >
</iframe>