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
$pdf->Cell(0,0,'CPUT Monthly eStore Report', 0, 1,'C');
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0,0,'Report Range: '.$_GET['sdate'].' to '.$_GET['edate'],0,1);
$sql = sprintf("select istore.istores.store_id,istore.istores.store_name from istore.istores
				where istore.istores.store_id = %d",$_GET['si']);
$dbo->setQuery($sql);
$row = $dbo->loadObject();

$pdf->Cell(0,0,'eStore: '.$row->store_name." (".$row->store_id.")",0,1);
	if ($_GET['cc'] == '0000') { $loc_cc = "N/A"; } else { $loc_cc = $_GET['cc']; }
$pdf->Cell(0,0,'Cost Centre: '.$loc_cc,0,1);

$pdf->line(0,30,297,30);

$pdf->ln();
$pdf->ln();
$pdf->ln();


$cc = "";$rec = 0;
$total = 0;
$cc_total = 0;

if ($_GET['cc'] == '0000') {
	$sql = sprintf("select distinct istore.istore_orders.order_no,istore.istore_orders.cost_centre,budgets.costcodes.cc_name
		from istore.istore_orders left outer join budgets.costcodes on
		(istore.istore_orders.cost_centre = budgets.costcodes.detail_cc)
	where istore.istore_orders.order_date >= '%s'	
	and istore.istore_orders.order_date <= '%s'
	and istore.istore_orders.store_id = %d
	and istore.istore_orders.istore_order_status_id = 5 order by istore.istore_orders.cost_centre",$_GET['sdate'],$_GET['edate'],$_GET['si']);
} else {
	$sql = sprintf("select distinct istore.istore_orders.order_no,istore.istore_orders.cost_centre,budgets.costcodes.cc_name
		from istore.istore_orders left outer join budgets.costcodes on
		(istore.istore_orders.cost_centre = budgets.costcodes.detail_cc)
	where istore.istore_orders.order_date >= '%s'	
	and istore.istore_orders.order_date <= '%s'
	and istore.istore_orders.store_id = %d
	and istore.istore_orders.istore_order_status_id = 5
	and istore.istore_orders.cost_centre = '%s' order by istore.istore_orders.cost_centre",$_GET['sdate'],$_GET['edate'],$_GET['si'],$_GET['cc']);
}

$dbo->setQuery($sql);
$cresult = $dbo->loadObjectList();
foreach($cresult as $crow) {

	$filter = "";
	foreach ($cresult as $order) {
		if ($order->cost_centre == $crow->cost_centre) {
			$filter .= "'".$order->order_no."',";
		}
	}
	$filter = substr($filter,0,-1);

	if ($crow->cost_centre <> $cc) {
			if ($rec > 0) {
				$pdf->SetFont('dejavusans', 'B', 10);
				$pdf->Cell(0,0,'CC TOTAL: '.number_format($cc_total,2),0,1,'R',true);
				$pdf->SetFont('dejavusans', '', 10);
				$cc_total = 0;
				$y = $pdf->GetY();
				if ($y > 160) { $pdf->addPage('L'); } else { $pdf->ln(2); }
				
			}
		$rec++;
		$cc = $crow->cost_centre;
		$pdf->SetFillColor(0,0,0);
		$pdf->SetTextColor(255,255,255);
		$pdf->SetFont('dejavusans', 'B', 10);
		$pdf->Cell(0,0,'COST CENTRE: '.$crow->cost_centre." ".$crow->cc_name,0,1,'L',true);

		$pdf->SetFillColor(192,192,192);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('dejavusans', 'B', 10);
		$pdf->Cell(30,0,'DATE: ',0,0,'L',true);
		$pdf->Cell(20,0,'ORDER#: ',0,0,'L',true);
		$pdf->Cell(20,0,'ACC#: ',0,0,'L',true);
		$pdf->Cell(70,0,'CLIENT: ',0,0,'L',true);
		$pdf->Cell(80,0,'ITEM: ',0,0,'L',true);
		$pdf->Cell(30,0,'QTY: ',0,0,'L',true);
		$pdf->Cell(0,0,'TOTAL: ',0,1,'R',true);
		

		$sql = sprintf("select istore_order_items.istore_orders_order_no, istore.istore_orders.order_date,istore.istore_order_items.istore_items_item_id,
		istore.istore_items.account_code, istore.istore_items.item_desc,sum(istore.istore_order_items.qty) as qty,
		sum(istore.istore_order_items.total_price) as total_price,portal.cput_users.name as cname from istore.istore_order_items
		left outer join istore.istore_items on (istore.istore_order_items.istore_items_item_id = istore.istore_items.item_id)
		left outer join istore.istore_orders on (istore.istore_order_items.istore_orders_order_no = istore.istore_orders.order_no)
		left outer join portal.cput_users on (istore.istore_orders.client_uid = portal.cput_users.id)
		where istore.istore_orders.order_no in (%s) group by istore.istore_order_items.istore_items_item_id
		order by istore.istore_order_items.istore_orders_order_no",$filter);
		//echo $sql;
		
		
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
			$pdf->Cell(30,0,$row->order_date,0,0,'L',true);
			$pdf->Cell(20,0,$row->istore_orders_order_no,0,0,'L',true);
			$pdf->Cell(20,0,$row->account_code,0,0,'L',true);
			$pdf->Cell(70,0,$row->cname,0,0,'L',true);
			$pdf->Cell(80,0,$row->item_desc,0,0,'L',true);
			$pdf->Cell(30,0,$row->qty,0,0,'L',true);
			$pdf->Cell(0,0,"R".$row->total_price,0,1,'R',true);
			$cnt++;
			$cc_total = $cc_total + $row->total_price;
			$total = $total + $row->total_price;
		}
	}
}
$pdf->SetFont('dejavusans', 'B', 10);
				$pdf->Cell(0,0,'CC TOTAL: '.number_format($cc_total,2),0,1,'R',true);
				$pdf->SetFont('dejavusans', '', 10);
				$cc_total = 0;
$pdf->ln(5);
$pdf->SetFont('dejavusans', 'B', 11);
$pdf->Cell(0,0,'GRAND TOTAL: R'.number_format($total,2),0,1,'R',true);


//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/scripts/test.pdf', 'F');

//============================================================+
// END OF FILE                                                
//============================================================+
?>
<iframe src="/scripts/test.pdf" width="100%" height="600" >
</iframe>