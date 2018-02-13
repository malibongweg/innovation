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
$pdf->SetMargins(10,30,10);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//sedistinct cmustno,cmuname,cmuinit,cmuidno,cmuqual,cmuyear,cmusubjreg,cmusubjpassed,cmupercpass,cmubal,cmubaldue,cmuperc_used,cmupayments from eacmutab where cmustno=%d""select distinct cmustno,cmuname,cmuinit,cmuidno,cmuqual,cmuyear,cmusubjreg,cmusubjpassed,cmupercpass,cmubal,cmubaldue,cmuperc_used,cmupayments from eacmutab where cmuyear = 2014 and cmustno=%d" some language-dependent strings
//$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// add a page
//10.60.0.122

$dbo->setQuery("select connect_string,user_name,password from cput_system_setup where system_name='its'");
$row = $dbo->loadObject();
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;
$con = oci_connect($oci_user,$oci_pass,$oci_connect_string);

$sql = sprintf("select distinct cmustno,cmuname,cmuinit,cmuidno,cmuqual,cmuyear,cmusubjreg,cmusubjpassed,cmupercpass,cmubal,cmubaldue,cmuperc_used,cmupayments from eacmutab where cmuyear = 2014 and cmustno=%d",$_GET['stno']);
				$result = oci_parse($con,$sql);
				oci_execute($result);
				$row = oci_fetch_object($result);

$pdf->AddPage('P');

$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0,0,'Cape Peninsula University of Technology', 0, 1,'C');

$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0,0,'Finance Clearance Report', 0, 1,'C');
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0,0,'Date: '.date('d-m-Y'),0,1,'L');
$pdf->line(10,45,205,45);

$pdf->ln(2);

$pdf->Cell(40,0,'Student#: ',0,0,'L');
$pdf->Cell(0,0,$row->CMUSTNO,0,1,'L');
$pdf->Cell(40,0,'Student Name: ',0,0,'L');
$pdf->Cell(0,0,$row->CMUINIT.'. '.$row->CMUNAME,0,1,'L');
$pdf->Cell(40,0,'Identity#: ',0,0,'L');
$pdf->Cell(0,0,$row->CMUIDNO,0,1,'L');

$pdf->ln(5);

$pdf->Cell(40,0,'Year: ',0,0,'L');
$pdf->Cell(0,0,$row->CMUYEAR,0,1,'L');

$pdf->Cell(40,0,'Qualification: ',0,0,'L');
$pdf->Cell(0,0,$row->CMUQUAL,0,1,'L');

$pdf->Cell(40,0,'Subjects Reg: ',0,0,'L');
$pdf->Cell(0,0,$row->CMUSUBJREG,0,1,'L');

$pdf->Cell(40,0,'Subjects Passed: ',0,0,'L');
$pdf->Cell(0,0,$row->CMUSUBJPASSED,0,1,'L');

$pdf->Cell(40,0,'Balance: ',0,0,'L');
$pdf->Cell(0,0,$row->CMUSUBJPASSED,0,1,'L');

$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(40,0,'Balance: ',0,0,'L');
$pdf->Cell(0,0,'R '.number_format($row->CMUBAL,0,'.',','),0,1,'L');

$pdf->Cell(40,0,'Minimum Due: ',0,0,'L');
$pdf->Cell(0,0,'R '.number_format($row->CMUBALDUE,0,'.',','),0,1,'L');

$pdf->SetFont('dejavusans', '', 10);

$pdf->ln(30);

$pdf->Cell(40,0,'Stamp/Signature: _____________________________________',0,1,'L');

//Close and output PDF document
$ext = time();
$fName = '/scripts/ftr'.$ext.'.pdf';
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/ftr'.$ext.'.pdf', 'F');

//============================================================+
// END OF FILE                                                
//============================================================+
?>
<iframe src="<?php echo $fName; ?>" width="100%" height="600" >
</iframe>
