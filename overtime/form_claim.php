<?php

define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo = &JFactory::getDBO();
//$user = & JFactory::getUser();

defined('_JEXEC') or die('Restricted access');
require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");
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

$sql = "select a.form_no,a.claim_date,a.staff_no,concat(b.staff_title,' ',b.staff_fname,' ',b.staff_sname) as applicant,concat(b.fac_name,' [',b.fac_code,']') as faculty,
concat(b.dept_name,' [',b.dept_code,']') as department,auth_no
from over_time.claims a left outer join staff.staff b on (a.staff_no=b.staff_no)
where a.form_no = ".$_GET['id'];
$dbo->setQuery($sql);
$row = $dbo->loadObject();

$form = $row->form_no;
$auth = $row->auth_no;

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->Image($_SERVER['DOCUMENT_ROOT']."/scripts/overtime/cput_bw.png", 0, 12, 60,20, 'PNG');
$pdf->ln(40);


$ps = $pdf->getPageDimensions();

$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0,0,'OVERTIME CLAIM FORM# '.$form." [AUTHORIZATION# ".$auth."]",0,1,'C',false);
$pdf->ln(10);

$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0,0,'Personnel# '.$row->staff_no,0,1,'L',false);
$pdf->Cell(0,0,'Name of Staff Member: '.$row->applicant,0,1,'L',false);
$pdf->Cell(0,0,'Faculty/Department: '.$row->faculty." / ".$row->department,0,1,'L',false);
$pdf->ln(10);
//$pdf->Rect($pdf->GetX(),$pdf->GetY()+3,$ps['wk']-20,23);

$rowCount = 0;
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(30,0,'DAY',1,0,'C',false);
$pdf->Cell(25,0,'DATE',1,0,'C',false);
$pdf->Cell(25,0,'FROM',1,0,'C',false);
$pdf->Cell(25,0,'TO',1,0,'C',false);
$pdf->Cell(25,0,'HOURS',1,0,'C',false);
$pdf->Cell(60,0,'DUTIES',1,1,'C',false);
$pdf->SetFont('dejavusans', '', 10);

$sql = sprintf("select dayname(a.claim_date_item) as dname,a.claim_date_item,a.start_time,a.end_time,a.hours,a.duties
from over_time.claimdates a where a.form_no = %d",$form);
$dbo->setQuery($sql);
$result = $dbo->loadObjectList();
foreach ($result as $row) {
$pdf->Cell(30,0,$row->dname,1,0,'C',false);
$pdf->Cell(25,0,$row->claim_date_item,1,0,'C',false);
$pdf->Cell(25,0,$row->start_time,1,0,'C',false);
$pdf->Cell(25,0,$row->end_time,1,0,'C',false);
$pdf->Cell(25,0,$row->hours,1,0,'C',false);
$pdf->Cell(60,0,$row->duties,1,1,'C',false);
++$rowCount;
}
while ($rowCount < 10) {
$pdf->Cell(30,0,'',1,0,'C',false);
$pdf->Cell(25,0,'',1,0,'C',false);
$pdf->Cell(25,0,'',1,0,'C',false);
$pdf->Cell(25,0,'',1,0,'C',false);
$pdf->Cell(25,0,'',1,0,'C',false);
$pdf->Cell(60,0,'',1,1,'C',false);
++$rowCount;
}

$sql = sprintf("SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(a.hours))) from over_time.claimdates a where form_no = %d",$form);
$dbo->setQuery($sql);
$total = $dbo->loadResult();
$pdf->Cell(30,0,'',1,0,'C',false);
$pdf->Cell(25,0,'',1,0,'C',false);
$pdf->Cell(25,0,'',1,0,'C',false);
$pdf->Cell(25,0,'HOURS',1,0,'C',false);
$pdf->Cell(25,0,$total,1,0,'C',false);
$pdf->Cell(60,0,'',1,1,'C',false);

$pdf->Rect($pdf->GetX(),$pdf->GetY()+5,$ps['wk']-20,50);
$pdf->ln(10);
$pdf->Cell(0,0,'Signature of Employee:       ____________________________',0,0,'L',false);
$pdf->Cell(0,0,'Date:     ___________________',0,1,'R',false);
$pdf->ln();

$pdf->Cell(0,0,'Signature of Supervisor:     ____________________________',0,0,'L',false);
$pdf->Cell(0,0,'Date:     ___________________',0,1,'R',false);
$pdf->ln();

$pdf->Cell(0,0,'Approved by HOD:              ____________________________',0,0,'L',false);
$pdf->Cell(0,0,'Date:     ___________________',0,1,'R',false);
$pdf->ln();

$pdf->Cell(0,0,'Approved by Dean in Faculty: ____________________________',0,0,'L',false);
$pdf->Cell(0,0,'Date:     ___________________',0,1,'R',false);
$pdf->ln();
$pdf->Cell(0,0,'All overtime claims to be pre-approved. Please attach pre-approval documentation.',0,1,'C',false);

$pdf->Rect($pdf->GetX(),$pdf->GetY()+5,$ps['wk']-20,40);
$pdf->ln(10);
$pdf->SetFont('dejavusans', 'U', 10);
$pdf->Cell(0,0,'Summary of hours',0,1,'L',false);
$pdf->ln(3);
$pdf->SetFont('dejavusans', '', 10);


$pdf->Cell(0,0,'Weekday/Saturday  _______________      Rate per hour   __________',0,0,'L',false);
$pdf->Cell(0,0,'Amount  _____________',0,1,'R',false);
$pdf->ln();

$pdf->Cell(0,0,'Sunday/Holiday       _______________      Rate per hour   __________',0,0,'L',false);
$pdf->Cell(0,0,'Amount  _____________',0,1,'R',false);
$pdf->ln();

$pdf->Cell(0,0,'TOTAL HOURS    __________________',0,0,'L',false);
$pdf->Cell(0,0,'TOTAL AMOUNT  __________________',0,1,'R',false);
$pdf->ln(8);

$pdf->SetFont('dejavusans', '', 8);
$pdf->Cell(0,0,'An employer may not require or permit an employee to work overtime except',0,1,'L',false);
$pdf->Cell(0,0,'(a) in accordance with an agreement and',0,1,'L',false);
$pdf->Cell(0,0,'(b) work more than		(i)  three hours overtime a day or   (ii) ten hours overtime a week.',0,1,'L',false);
$pdf->Cell(0,0,'Overtime Rate:    Monday to Saturday - one half times employee\'s wage',0,1,'L',false);
$pdf->Cell(0,0,'                            Sundays and Public Holidays - double time employee\'s wage',0,1,'L',false);

$pdf->ln(5);

$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0,0,'Porcessed by Payroll Dept: ___________________________     Date: ______________________',0,1,'L',false);


//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/overtime/forms/claim-'.$_GET['id'].'.pdf', 'F');
echo '<iframe src="/scripts/overtime/forms/claim-'.$_GET['id'].'.pdf" width="100%" height="600" ></iframe>';
//============================================================+
// END OF FILE                                                
//============================================================+
?>
