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

$sql = "select a.id,a.date_requested,a.requester,a.campus,a.building,a.faculty,a.dept,a.location,concat(b.staff_title,' ',b.staff_init,' ',b.staff_sname) as staffmember,
				c.campus_name,d.fac_desc,e.dept_desc,f.build_name
				from portal.cput_assets_renewal a left outer join staff.staff b on (a.requester = b.staff_no)
				left outer join structure.campus c on (a.campus = c.campus_code)
				left outer join structure.faculty d on (a.faculty = d.fac_code)
				left outer join structure.department e on (a.dept = e.dept_code)
				left outer join structure.buildings f on (a.building = f.build_code)
				where a.id = ".$_GET['id'];
$dbo->setQuery($sql);
$row = $dbo->loadObject();

$pdf->SetFont('dejavusans', '', 11);
$pdf->Cell(0,0,'Application for writing off of moveable assets only. (No provision for replacement and renewal at CPUT)', 0, 1,'C');
$pdf->SetFont('dejavusans', '', 10);


$pdf->line(0,10,297,10);

$pdf->ln(3);

		$pdf->Cell(0,0,'Document# '.$row->id,0,1,'L',false);
		$pdf->Cell(0,0,'Date: '.$row->date_requested,0,1,'L',false);
		$pdf->Cell(0,0,'Requester: '.$row->staffmember,0,1,'L',false);
		$pdf->Cell(0,0,'Campus: '.$row->campus_name,0,1,'L',false);
		$pdf->Cell(0,0,'Building: '.$row->build_name,0,1,'L',false);
		$pdf->Cell(0,0,'Faculty: '.$row->fac_desc,0,1,'L',false);
		$pdf->Cell(0,0,'Department: '.$row->dept_desc,0,1,'L',false);
		$pdf->ln(2);

		//$pdf->SetXY(110, 200);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->Image($_SERVER['DOCUMENT_ROOT']."/scripts/assets/cput.jpg", 200, 12, 0,0, 'JPG');

		$pdf->SetFillColor(192,192,192);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('dejavusans', 'B', 10);
		$pdf->Cell(30,0,'Item#',1,0,'C',true);
		$pdf->Cell(50,0,'Description',1,0,'C',true);
		$pdf->Cell(40,0,'Barcode/Sticker',1,0,'C',true);
		$pdf->Cell(30,0,'Serial#',1,0,'C',true);
		$pdf->Cell(60,0,'Reason for Replacement',1,0,'L',true);
		$pdf->Cell(40,0,'Purchase Year',1,0,'C',true);
		$pdf->Cell(30,0,'In Use? (Y/N)',1,1,'C',true);

		for($i=0;$i<23;++$i){
			$pdf->Cell(30,0,'',1,0,'C',false);
			$pdf->Cell(50,0,'',1,0,'C',false);
			$pdf->Cell(40,0,'',1,0,'C',false);
			$pdf->Cell(30,0,'',1,0,'C',false);
			$pdf->Cell(60,0,'',1,0,'L',false);
			$pdf->Cell(40,0,'',1,0,'C',false);
			$pdf->Cell(30,0,'',1,1,'C',false);
		}
		
		$pdf->ln(5);
		$pdf->SetFont('dejavusans', '', 10);
		$pdf->Cell(60,0,'Applicant:',0,0,'L',false);
		$pdf->Cell(80,0,'Name:__________________________________',0,0,'L',false);
		$pdf->Cell(100,0,'Signature:_____________________________   Date:_____________   Ext:_____________',0,0,'L',false);
		
		$pdf->ln(8);
		$pdf->Cell(60,0,'Approved (HOD):',0,0,'L',false);
		$pdf->Cell(80,0,'Name:__________________________________',0,0,'L',false);
		$pdf->Cell(100,0,'Signature:_____________________________   Date:_____________   Ext:_____________',0,0,'L',false);
		
		$pdf->ln(8);
		$pdf->Cell(60,0,'Apporved (Dean of Faculty):',0,0,'L',false);
		$pdf->Cell(80,0,'Name:__________________________________',0,0,'L',false);
		$pdf->Cell(100,0,'Signature:_____________________________   Date:_____________   Ext:_____________',0,0,'L',false);
		


//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/assets/forms/'.$_GET['id'].'.pdf', 'F');

$email_details = "Cape Peninsula University of Technology.<br /><br />";
$email_details .= "Dear user...<br />";
$email_details .= "Please fill in all required fields and send the form back to the assets department.<br />";
$email_details .= "Thank you....";

$mymail = array();
$mymail[] = $_GET['email'];
$email_addr = serialize($mymail);

sendMail($email_addr,"Asset(s) write off document(s) request.",$email_details,$_SERVER['DOCUMENT_ROOT'].'/scripts/assets/forms/'.$_GET['id'].'.pdf');

echo "OK";
//============================================================+
// END OF FILE                                                
//============================================================+
?>
