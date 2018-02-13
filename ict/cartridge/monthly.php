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
$pdf->AddPage('L');

$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(0,0,'CPUT Monthly Cartridge Usage Report', 0, 1,'C');
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0,0,'Report Month: '.str_pad($_GET['mth'],2,'0',STR_PAD_LEFT)."/".$_GET['yr'],0,1,'L');
$pdf->line(0,20,297,20);

$pdf->ln();
$pdf->ln();
$pdf->ln();

$cc = "";$rec = 0;
$total = 0;
$cc_total = 0;
if (strlen($_GET['sdate']) > 0) {
	$dbo->setQuery("select distinct costcentre from #__cartridge_issue where date(captured) >= '".$_GET['sdate']."' and date(captured) <= '".$_GET['edate']."' order by costcentre");
}
else { 
	$dbo->setQuery("select distinct costcentre from #__cartridge_issue where year(captured) = ".$_GET['yr']." and month(captured) = ".$_GET['mth']." and status='DELIVERED' order by costcentre");
}
$cresult = $dbo->loadObjectList();
foreach($cresult as $crow) {
	if ($crow->costcentre <> $cc) {
			if ($rec > 0) {
				$pdf->SetFont('dejavusans', 'B', 11);
				$pdf->Cell(265,0,'CC TOTAL: '.number_format($cc_total,2),0,1,'R',true);
				$pdf->SetFont('dejavusans', '', 10);
				$cc_total = 0;
				$y = $pdf->GetY();
				if ($y > 160) { $pdf->addPage('L'); } else { $pdf->ln(2); }
				
			}
		$rec++;
		$cc = $crow->costcentre;
		$pdf->SetFillColor(0,0,0);
		$pdf->SetTextColor(255,255,255);
		$pdf->SetFont('dejavusans', 'B', 10);
		$pdf->Cell(50,0,'COST CENTRE: '.$cc,0,1,'L',true);
		
		$pdf->SetTextColor(167,167,167);
		$pdf->SetFont('dejavusans', 'B', 10);
		$pdf->Cell(20,0,'DATE',0,0,'L',true);
		$pdf->Cell(20,0,'CASEID',0,0,'C',true);
		$pdf->Cell(70,0,'STAFF MEMBER',0,0,'L',true);
		$pdf->Cell(75,0,'CARTRIDGE',0,0,'L',true);
		$pdf->Cell(10,0,'CC',0,0,'C',true);
		$pdf->Cell(20,0,'QTY',0,0,'C',true);
		$pdf->Cell(20,0,'PRICE',0,0,'R',true);
		$pdf->Cell(30,0,'TOTAL PRICE',0,1,'R',true);
	
		if (strlen($_GET['sdate']) > 0) 
		{
			$sql = sprintf("select a.caseid,a.captured,a.costcentre,concat('[',b.staff_no,'] ',b.staff_sname,',',b.staff_fname) as fullname,
		concat('[',c.stockcode,'] ',e.cartname) as stock,c.qty,c.price,c.totalprice
		from cput_cartridge_issue a
		left outer join staff.staff b on (a.staffno = b.staff_no)
		left join cput_cartridge_issue_items c on (a.transno = c.transno)
		left join cput_cartridge_stock d on (c.stockcode = d.stockid)
		left join cput_cartridges e on (d.cartridge = e.code)
		where a.costcentre = '%s' and date(captured) >= '".$_GET['sdate']."' and date(captured) <= '".$_GET['edate']."'",$cc);
		//echo $sql;
		}
		else {
		$sql = sprintf("select a.caseid,a.captured,a.costcentre,concat('[',b.staff_no,'] ',b.staff_sname,',',b.staff_fname) as fullname,
		concat('[',c.stockcode,'] ',e.cartname) as stock,c.qty,c.price,c.totalprice
		from cput_cartridge_issue a
		left outer join staff.staff b on (a.staffno = b.staff_no)
		left join cput_cartridge_issue_items c on (a.transno = c.transno)
		left join cput_cartridge_stock d on (c.stockcode = d.stockid)
		left join cput_cartridges e on (d.cartridge = e.code)
		where a.costcentre = '%s' and month(a.captured) = %d and year(captured) = %d",$cc,$_GET['mth'],$_GET['yr']);
		}
		$dbo->setQuery($sql);
		$result = $dbo->loadObjectList();
		$pdf->SetFont('dejavusans', '', 9);
		$pdf->SetTextColor(0,0,0);
		$cnt = 0;
		foreach($result as $row) {
			if (($cnt % 2) == 0) {
				$pdf->SetFillColor(255,255,255);
			} else {
				$pdf->SetFillColor(205,205,205);
			}
			$pdf->Cell(20,0,$row->captured,0,0,'L',true);
			$pdf->Cell(20,0,$row->caseid,0,0,'C',true);
			$pdf->Cell(70,0,$row->fullname,0,0,'L',true);
			$pdf->Cell(75,0,$row->stock,0,0,'L',true);
			$pdf->Cell(10,0,$row->costcentre,0,0,'C',true);
			$pdf->Cell(20,0,$row->qty,0,0,'C',true);
			$pdf->Cell(20,0,$row->price,0,0,'R',true);
			$pdf->Cell(30,0,$row->totalprice,0,1,'R',true);
			$cnt++;
			$cc_total = $cc_total + $row->totalprice;
			$total = $total + $row->totalprice;
		}
	}
}
$pdf->ln(2);
$pdf->SetFont('dejavusans', 'B', 11);
$pdf->Cell(265,0,'GRAND TOTAL: '.number_format($total,2),0,1,'R',true);


//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/test.pdf', 'F');

//============================================================+
// END OF FILE                                                
//============================================================+
?>
<iframe src="/scripts/test.pdf" width="100%" height="600" >
</iframe>