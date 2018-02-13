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

$sql = "select a.form_no,a.form_date,a.staff_no,concat(b.staff_title,' ',b.staff_fname,' ',b.staff_sname) as applicant,concat(b.fac_name,' [',b.fac_code,']') as faculty,
concat(b.dept_name,' [',b.dept_code,']') as department
from over_time.authorization a left outer join staff.staff b on (a.staff_no=b.staff_no)
where a.form_no = ".$_GET['id'];
$dbo->setQuery($sql);
$row = $dbo->loadObject();

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->Image($_SERVER['DOCUMENT_ROOT']."/scripts/overtime/cput_bw.png", 0, 12, 60,20, 'PNG');
$pdf->ln(40);

$pdf->SetFont('dejavusans', 'BI', 12);
$pdf->Cell(0,0,'Authority for overtime duties by', 0, 1,'R');
$pdf->Cell(0,0,'Administrative, technical and general personnel.', 0, 1,'R');


$pdf->ln(3);


$ps = $pdf->getPageDimensions();

$pdf->SetFont('dejavusans', 'B', 10);
$pdf->Cell(0,0,'Form# '.$row->form_no,0,1,'L',false);
$pdf->ln(3);

$pdf->Rect($pdf->GetX(),$pdf->GetY()+3,$ps['wk']-20,23);

		$pdf->ln(5);
		$pdf->SetFont('dejavusans', 'B', 10);
		$pdf->Cell(0,0,'Authority forms must be completed and approved BEFORE the overtime is worked.',0,1,'C',false);
		
		$pdf->ln(5);
		$pdf->Cell(0,0,'Original to be returned to the Head of Department and copy to the',0,1,'C',false);
		$pdf->Cell(0,0,'Payroll Section together with claim form.',0,1,'C',false);
		
		$pdf->ln(10);
		$pdf->SetFont('dejavusans', '', 10);
		$pdf->Cell(0,0,'Personnel Number: '.$row->staff_no,0,1,'L',false);
		$pdf->Cell(0,0,'Name of Personnel Member: '.$row->applicant,0,1,'L',false);
		$pdf->Cell(0,0,'Faculty/Department: '.$row->faculty.'   /   '.$row->department,0,1,'L',false);

$pdf->Rect($pdf->GetX(),$pdf->GetY()+15,$ps['wk']-20,40);

		$pdf->ln(20);
		$pdf->SetFont('dejavusans', 'B', 10);
		$pdf->Cell(0,0,'Signature of Employee:  _____________________________ ',0,0,'L',false);
		$pdf->Cell(0,0,'Date: ___________________',0,1,'R',false);
		$pdf->ln();
		$pdf->Cell(0,0,'Signature of Supervisor:  ____________________________ ',0,0,'L',false);
		$pdf->Cell(0,0,'Date: ___________________',0,1,'R',false);
		$pdf->ln();
		$pdf->Cell(0,0,'Authority by HOD:  ___________________________________ ',0,0,'L',false);
		$pdf->Cell(0,0,'Date: ___________________',0,1,'R',false);

		$pdf->SetFont('dejavusans', 'B', 8);
		$pdf->ln(7);
		$pdf->Cell(0,0,'All overtime claims to be pre-approved by HOD.',0,1,'C',false);

$pdf->Rect($pdf->GetX(),$pdf->GetY()+15,$ps['wk']-20,70);

		$pdf->SetFont('dejavusans', 'U', 10);
		$pdf->ln(20);
		$pdf->Cell(0,0,'Indication of when overtime will be worked.',0,1,'L',false);

		$pdf->SetFont('dejavusans', '', 10);
		$pdf->ln(2);

$sql = "select concat(CASE a.day_type WHEN 1 THEN 'Weekday' WHEN 2 THEN 'Saturday' WHEN 3 THEN 'Sunday' WHEN 4 THEN 'Holiday' END,
' - ',a.start_date,' [Hours: ',a.auth_hours,'] - Duties: ',a.duties) as work_entry
from over_time.authdate a where a.form_no = ".$_GET['id'];
$dbo->setQuery($sql);
$result = $dbo->loadObjectList();

		foreach($result as $row){
			$pdf->Cell(0,0,$row->work_entry,0,1,'L',false);
		}

		$pdf->ln(60);
		$pdf->SetFont('dejavusans', 'B', 12);
		$pdf->Cell(0,0,'PROCESSED BY PAYROLL OFFICE.',0,0,'L',false);
		
		
		
//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/overtime/forms/auth-'.$_GET['id'].'.pdf', 'F');
echo '<iframe src="/scripts/overtime/forms/auth-'.$_GET['id'].'.pdf" width="100%" height="600" ></iframe>';
//============================================================+
// END OF FILE                                                
//============================================================+
?>
