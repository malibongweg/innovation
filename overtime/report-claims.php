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

$pdf->AddPage('L');

$sql = sprintf("select date_add((select a.cutoff_date from over_time.cutoffdates a where MONTH(a.cutoff_date) = MONTH('%s') -1), interval 1 day)",$_GET['cutoff']);
$dbo->setQuery($sql);
$previous_month = $dbo->loadResult();

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->Image($_SERVER['DOCUMENT_ROOT']."/scripts/overtime/cput_bw.png", 0, 12, 60,20, 'PNG');
$pdf->ln(40);

$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0,0,'Overtime Claim Report ['.$previous_month.' until '.$_GET['cutoff'].']', 0, 1,'C');

$pdf->ln(3);


$ps = $pdf->getPageDimensions();


$sql = sprintf("select a.auth_no,a.form_no,a.claim_date,a.staff_no,concat(b.staff_title,' ',b.staff_fname,' ',b.staff_sname,' [',b.staff_no,']') as applicant,b.dept_name
from over_time.claims a left outer join staff.staff b on (a.staff_no=b.staff_no)
where a.stat in ('A','F') and a.claim_date >= '%s' and a.claim_date <= '%s' order by a.form_no",$previous_month,$_GET['cutoff']);

$dbo->setQuery($sql);
$result = $dbo->loadObjectList();
foreach($result as $row){
$pdf->SetFont('dejavusans', 'B', 10);
$pdf->Cell(25,0,'CLAIM#',1,0,'C',false);
$pdf->Cell(25,0,'AUTH#',1,0,'C',false);
$pdf->Cell(35,0,'CLAIM DATE',1,0,'C',false);
$pdf->Cell(100,0,'STAFF MEMBER',1,0,'C',false);
$pdf->Cell(80,0,'DEPARTMENT',1,1,'C',false);

$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(25,0,$row->form_no,0,0,'C',false);
$pdf->Cell(25,0,$row->auth_no,0,0,'C',false);
$pdf->Cell(35,0,$row->claim_date,0,0,'C',false);
$pdf->Cell(100,0,$row->applicant,0,0,'C',false);
$pdf->Cell(80,0,$row->dept_name,0,1,'C',false);

$sql = sprintf("select a.claim_date_item,a.start_time,a.end_time,a.hours,a.duties from over_time.claimdates a where a.form_no = %d",$row->form_no);
$dbo->setQuery($sql);
$claims = $dbo->loadObjectList();
foreach($claims as $crow){
	$pdf->Cell(25,0,'Date: '.$crow->claim_date_item."   Start Time: ".$crow->start_time."    End Time: ".$crow->end_time."    Hours: ".$crow->hours."    Duties: ".$crow->duties,0,1,'L',false);
}
$sql = sprintf("SELECT  SEC_TO_TIME(SUM(TIME_TO_SEC(a.hours))) from over_time.claimdates a where form_no = %d",$row->form_no);
$dbo->setQuery($sql);
$total = $dbo->loadResult();
$pdf->SetFont('dejavusans', 'B', 10);
$pdf->Cell(80,0,'TOAL HOURS: '.$total,0,1,'L',false);
$pdf->ln(10);
}

		
		
//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/overtime/forms/claims-'.$_GET['cutoff'].'.pdf', 'F');
echo '<iframe src="/scripts/overtime/forms/claims-'.$_GET['cutoff'].'.pdf" width="100%" height="600" ></iframe>';
//============================================================+
// END OF FILE                                                
//============================================================+
?>
