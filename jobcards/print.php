<script type="text/javascript">
	window.parent.$j.colorbox.resize({width: 650, height: 700});
</script>
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
$pdf->SetAuthor('OPA');
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
////GET SQL
$sql = sprintf("select a.id,a.capture_date,a.assigned_date,a.applicant,concat(a.contact_no, ' (',a.contact_time,')') as contact_no,a.email,a.campus,a.building,a.roomno,a.job_status,a.vandalism,a.job_details,
		concat(b.staff_sname,' ,',b.staff_title,' ',b.staff_fname) as fullname,c.campus_name,d.build_name,e.status_desc
		from jobcards.jobcards a left outer join staff.staff b on (a.applicant=b.staff_no)
		left outer join structure.campus c on (a.campus=c.campus_code) left outer join structure.buildings d on (a.building=d.build_code)
		left outer join jobcards.jobcard_status e on (a.job_status=e.id)
		where a.id = %d",$_GET['jid']);
$dbo->setQuery($sql);
$row = $dbo->loadObject();

// add a page
//10.60.0.122
$pdf->AddPage('P');

$border_style = array('all' => array('width' => 1, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'phase' => 0));

$pdf->SetDrawColor(200,200,200);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetTextColor(0, 0, 0);
$pdf->Rect(10,10,190,20,'DF',$border_style);
$pdf->SetFontSize(24);
$pdf->Cell(0,35,'CPUT MAINTENANCE JOBCARD', 0, 1,'C');

$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(10,40,190,240,'DF',$border_style);


//JOBCARD
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetDrawColor(188,188,188);
$pdf->Rect(15,45,50,10,'DF',$border_style);
$pdf->Text(18,47,'JOBCARD#');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(80,45,110,10,'DF',$border_style);
$pdf->Text(80,47,$row->id);

//DATE CAPTURED
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetDrawColor(188,188,188);
$pdf->Rect(15,60,50,10,'DF',$border_style);
$pdf->Text(18,62,'DATE CAPTURED');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(80,60,110,10,'DF',$border_style);
$pdf->Text(80,62,$row->capture_date);

//APPLCANT
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetDrawColor(188,188,188);
$pdf->Rect(15,75,50,10,'DF',$border_style);
$pdf->Text(18,77,'APPLICANT');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(80,75,110,10,'DF',$border_style);
$pdf->Text(80,77,$row->fullname);

//CONTACT
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetDrawColor(188,188,188);
$pdf->Rect(15,90,50,10,'DF',$border_style);
$pdf->Text(18,92,'CONTACT#');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(80,90,110,10,'DF',$border_style);
$pdf->Text(80,92,$row->contact_no);

//CAMPUS
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetDrawColor(188,188,188);
$pdf->Rect(15,105,50,10,'DF',$border_style);
$pdf->Text(18,107,'CAMPUS');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(80,105,110,10,'DF',$border_style);
$pdf->Text(80,107,$row->campus_name);

//BUILDING
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetDrawColor(188,188,188);
$pdf->Rect(15,120,50,10,'DF',$border_style);
$pdf->Text(18,122,'BUILDING');
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->Rect(80,120,110,10,'DF',$border_style);
$pdf->Text(80,122,$row->build_name);

//ROOMNO
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetDrawColor(188,188,188);
$pdf->Rect(15,135,50,10,'DF',$border_style);
$pdf->Text(18,137,'ROOM#');
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->Rect(80,135,110,10,'DF',$border_style);
$pdf->Text(80,137,$row->roomno);

//VANDALISM
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetDrawColor(188,188,188);
$pdf->Rect(15,150,50,10,'DF',$border_style);
$pdf->Text(18,152,'VANDALISM?');
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->Rect(80,150,10,10,'DF',$border_style);

/*$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);

// 1D BARCODES
// CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
$pdf->write1DBarcode($row->id, 'C39', 100,147, 100, 16, 0.4, $style, 'N');*/



//JOB DETAILS
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(188, 188, 188);
$pdf->SetDrawColor(188,188,188);
$pdf->Rect(15,165,180,30,'DF',$border_style);
$pdf->Text(18,167,'JOB DETAILS');
$pdf->MultiCell(180,30,$row->job_details, 1, 'L', 0, 0, 18,175, true);

//ASSIGNED ARTISANS
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Text(15,198,'ASSIGNED ARTISAN(S)/SUPPLIER');
$pdf->Text(130,198,'HOURS');
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(15,205,130,10,'DF',$border_style);
$pdf->Rect(130,205,65,10,'DF',$border_style);
$pdf->Rect(15,215,130,10,'DF',$border_style);
$pdf->Rect(130,215,65,10,'DF',$border_style);
$pdf->Rect(15,225,130,10,'DF',$border_style);
$pdf->Rect(130,225,65,10,'DF',$border_style);
$ypos = 208;
$sql = sprintf("select ifnull(concat(b.staff_sname,' ',b.staff_title,' ',b.staff_fname,' [',d.trade_description,']'),(select e.supplier_name from jobcards.suppliers e where a.artisan = e.id)) as fullname
from jobcards.jobcard_artisans a left outer join staff.staff b on (a.artisan = b.staff_no)
left outer join jobcards.artisans c on (a.artisan = c.staffno) left outer join jobcards.artisan_trades d on (c.trade_code = d.id)
where a.jobcard = %d limit 3 ",$_GET['jid']);
$dbo->setQuery($sql);
$dbo->query();
$cnt = $dbo->getNumRows();
if (intval($cnt) > 0){
	$result = $dbo->loadObjectList();
	foreach($result as $row){
		$pdf->Text(18,$ypos,$row->fullname);
		$ypos = $ypos + 10;
	}
}

////SIGNATURES
$pdf->Text(15,245,'FOREMAN SIGNATURE:');
$pdf->Line(75,250,190,250);
$pdf->Text(15,255,'CLIENT SIGNATURE:');
$pdf->Line(75,260,190,260);
$pdf->Text(15,265,'COMPLETION DATE:');
$pdf->Line(75,270,190,270);


//Close and output PDF document
$fname = 'jobcard'.time().'.pdf';
$fdir = $_SERVER['DOCUMENT_ROOT'].'/scripts/jobcards/tmp/';
$fsource = '/scripts/jobcards/tmp/'.$fname;
$pdf->Output($fdir.$fname, 'F');

//============================================================+
// END OF FILE
//============================================================+
?>
<iframe src="<?php echo $fsource; ?>" width="100%" height="600" >
</iframe>