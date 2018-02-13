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
$pdf->SetMargins(1,3,1);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// add a page
$sql = sprintf("select a.caseid,a.staffno,a.costcentre,a.transno,a.captured,concat(b.staff_sname,' ',b.staff_fname) as fullname,b.dept_name
 from cput_cartridge_issue a left outer join staff.staff b on (a.staffno = b.staff_no) where a.transno =%d",$_GET['id']);
 $dbo->setQuery($sql);
 $row = $dbo->loadObject();
 $sql = sprintf("select staff_wtel from staff.staff_detail where staff_num=%d",$row->staffno);
 $dbo->setQuery($sql);
 $dbo->query();
 if ($dbo->getNumRows() == 0) { $tel = ""; } else {
 $rowx = $dbo->loadObject();
 $tel = $rowx->staff_wtel; 
 }

$page = array(80,190);
$pdf->AddPage('P',$page);

$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(0,0,'CPUT', 0, 1,'C');
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0,0,'CTS Department', 0, 1,'C');
$pdf->Cell(0,0,'Consumable Receipt', 0, 1,'C');
$pdf->line(0,20,80,20);

$pdf->ln();
$pdf->SetFont('dejavusans', '', 8);
$pdf->Cell(30,0,'Case#:', 0, 0,'R');
$pdf->Cell(0,0,$row->caseid, 0, 1,'L');


$pdf->Cell(30,0,'Name:', 0, 0,'R');
$pdf->Cell(0,0,$row->fullname, 0, 1,'L');

$pdf->Cell(30,0,'Staff#:', 0, 0,'R');
$pdf->Cell(0,0,$row->staffno, 0, 1,'L');

$pdf->Cell(30,0,'Deptartmnent:', 0, 0,'R');
$pdf->Cell(0,0,$row->dept_name, 0, 1,'L');

$pdf->Cell(30,0,'Tel:', 0, 0,'R');
$pdf->Cell(0,0,$tel, 0, 1,'L');

$pdf->Cell(30,0,'Cost Centre:', 0, 0,'R');
$pdf->Cell(0,0,$row->costcentre, 0, 1,'L');

$pdf->Cell(30,0,'Slip#:', 0, 0,'R');
$pdf->Cell(0,0,$row->transno, 0, 1,'L');


$pdf->Cell(30,0,'Date:', 0, 0,'R');
$pdf->Cell(0,0,$row->captured, 0, 1,'L');
$pdf->Rect(5,21,70,30);

$pdf->ln();

$pdf->SetFont('dejavusans', '', 8);
$pdf->Cell(20,0,'Code:', 0, 0,'L');
$pdf->Cell(10,0,'Qty', 0, 0,'L');
$pdf->Cell(20,0,'Price', 0, 0,'L');
$pdf->Cell(0,0,'Total', 0, 1,'L');

$sql = sprintf("select distinct a.stockcode,a.qty,a.price,a.totalprice,c.cartname from cput_cartridge_issue_items a 
left outer join cput_cartridge_stock b on (a.stockcode = b.stockid)
left outer join cput_cartridges c on (b.cartridge = c.code) where a.transno=%d",$_GET['id']);
$dbo->setQuery($sql);
$result = $dbo->loadObjectList();
$gt = 0;$cnt = 0;

foreach($result as $row) {
	$pdf->SetFont('dejavusans', '', 8);
	$pdf->Cell(20,0,$row->stockcode, 0, 0,'L');
	$pdf->Cell(10,0,$row->qty, 0, 0,'L');
	$pdf->Cell(20,0,$row->price, 0, 0,'L');
	$pdf->Cell(0,0,$row->totalprice, 0, 1,'L');
	$gt = $gt + $row->totalprice;
	$cnt = $cnt + 1;
}
$pdf->ln();
$pdf->ln();
$pdf->ln();
$y1 = $pdf->GetY()+8;
$pdf->SetFont('dejavusans', 'B', 8);
$pdf->Cell(50,0,'Total Cost:', 0, 0,'R');
$pdf->Cell(20,0,number_format($gt,2), 0, 1,'L');

$pdf->Cell(50,0,'Total Cartridges:', 0, 0,'R');
$pdf->Cell(20,0,$cnt, 0, 1,'L');
$pdf->ln();

$pdf->Cell(40,0,'Captured By:', 0, 0,'R');
$pdf->Cell(0,0,'_______________', 0, 1,'L');
$pdf->ln();

$pdf->Cell(40,0,'Budget Controller:', 0, 0,'R');
$pdf->Cell(0,0,'_______________', 0, 1,'L');
$pdf->ln();

$pdf->Cell(40,0,'Requester:', 0, 0,'R');
$pdf->Cell(0,0,'_______________', 0, 1,'L');

$pdf->Rect(5,$y1,70,23);
$pdf->ln();

$pdf->SetFont('dejavusans', 'B', 10);
$pdf->Cell(0,0,'FOR OFFICE USE', 0, 1,'C');
$y1 = $pdf->GetY();
$pdf->ln();


$pdf->Cell(40,0,'Delivered By:', 0, 0,'R');
$pdf->Cell(0,0,'_______________', 0, 1,'L');
$pdf->ln();

$pdf->Cell(40,0,'Date of Delivery:', 0, 0,'R');
$pdf->Cell(0,0,'_______________', 0, 1,'L');
$pdf->ln();

$pdf->Cell(40,0,'System Updated:', 0, 0,'R');
$pdf->Cell(0,0,'_______________', 0, 1,'L');
$pdf->ln();

$pdf->Cell(40,0,'Tested:', 0, 0,'R');
$pdf->Cell(0,0,'_______________', 0, 1,'L');
$pdf->ln();

$pdf->Cell(40,0,'Comments:', 0, 0,'R');
$pdf->Cell(0,0,'_______________', 0, 1,'L');
$pdf->Rect(5,$y1,70,48);


//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/pdf/slip.pdf', 'F');

//============================================================+
// END OF FILE                                                
//============================================================+
?>
<iframe src="/scripts/pdf/slip.pdf" width="100%" height="600" >
</iframe>