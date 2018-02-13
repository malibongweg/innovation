<?php
$dbo = &JFactory::getDBO();
ini_set('error_reporting',0);
ini_set('pcre.backtrack_limit',1000000);
ini_set('pcre.recursion_limit',1000000);

?>
<input type="button" value="Print Report" id="prn" onclick="window.print();" />&nbsp;<input type="button" value="Close Window" onclick="window.parent.$j.colorbox.close();" />
<div style="position: relative; border: 1px solid #000000; padding: 3px; margin: 3px">
Cost Centre: <?php echo $_GET['ccode']; ?>&nbsp;&nbsp;&nbsp;Budget Year: <?php echo $_GET['cyear']; ?>
</div>
<?php
$html = '';
if (isset($_GET['action'])) {
		$cyear = $_GET['cyear'];
		$ccode = $_GET['ccode'];

				
				if ($_GET['action'] == 'summary') {
						$sql = sprintf("select distinct(acc_cat) from budgets.m16_gl_trans_summary where post_year=%d and cc_code='%s' order by acc_cat",$cyear,$ccode);
						$dbo->setQuery($sql);
						$result = $dbo->loadObjectList();
						foreach ($result as $row) {
							
												$balance = 0;$budget = 0;$actuals = 0;$commitments = 0;$balance = 0;
												echo '<table border="1" width="100%" cellspacing="0" cellpadding="0" style="table-layout: fixed; height: 35px">'.
												'<tr><td width="100%" colspan="6" style="font-size: 10px;font-weight: bold; background-color: #3C619C; color: #FFFFFF; text-align:left">Category: '.$row->acc_cat.'</td></tr>'.
												'<tr><td width="15%" style="font-size: 12px; background-color: #3C619C; color: #FFFFFF;   text-align: left" ><b>Acc#</b></td>'.
												'<td width="35%" style="font-size: 12px; background-color: #3c619c; color: #ffffff;   text-align: left"><b>Account Name</b></td>'.
												'<td width="10%" style="font-size: 12px;background-color: #3c619c; color: #ffffff;   text-align: left"><b>Budget</b></td>'.
												'<td width="10%" style="font-size: 12px;background-color: #3c619c; color: #ffffff;   text-align: right"><b>Actuals</b></td>'.
												'<td width="10%" style="font-size: 12px;background-color: #3c619c; color: #ffffff;   text-align: right"><b>Commitments</b></td>'.
												'<td width="20%" style="font-size: 12px;background-color: #3c619c; color: #ffffff;   text-align: right"><b>Balance</b></td>';
											
											$sql = sprintf("select distinct a.acc_code,b.fcdname1,abs(a.actuals) as actuals,abs(a.commitments) as commitments,a.budget,(a.budget - (abs(a.actuals) + abs(a.commitments))) as balance 
												from budgets.m16_gl_trans_summary a 
												left outer join budgets.account_codes b on (a.acc_code=b.fcdacc) where a.cc_code = '%s' and a.post_year = %d and acc_cat = %d order by a.acc_code",$ccode,$cyear,$row->acc_cat);
											$dbo->setQuery($sql);
											$res = $dbo->loadObjectList();
											foreach($res as $r) {
												echo '<tr>';
												echo '<td width="15%" style="font-size: 10px;font-weight: bold; background-color: #d3d3d3; color: #000000;   text-align: left" >'.$r->acc_code.'</td>';
												echo '<td width="35%" style="font-size: 10px;font-weight: bold; background-color: #d3d3d3; color: #000000;   text-align: left" >'.$r->fcdname1.'</td>';
												echo '<td width="10%" style="font-size: 10px;font-weight: bold; background-color: #d3d3d3; color: #000000;   text-align: right" >'.number_format($r->budget,2).'</td>';
												echo '<td width="10%" style="font-size: 10px;font-weight: bold; background-color: #d3d3d3; color: #000000;   text-align: right" >'.number_format($r->actuals,2).'</td>';
												echo '<td width="10%" style="font-size: 10px;font-weight: bold; background-color: #d3d3d3; color: #000000;   text-align: right" >'.number_format($r->commitments,2).'</td>';
												if ($r->balance > 0) {
													echo '<td width="20%" style="font-size: 10px;font-weight: bold; background-color: #d3d3d3; color: #000000;   text-align: right" >'.number_format($r->balance,2).'</td>';
												} else {
													echo '<td width="20%" style="font-size: 10px;font-weight: bold; background-color: #ff0000; color: #000000;   text-align: right" >'.number_format($r->balance,2).'</td>';
												}
												echo '</tr>';
												$balance = $balance + $r->balance; $budget = $budget + $r->budget; $commitments = $commitments + $r->commitments; $actuals = $actuals + $r->actuals;
											}
											echo '<tr>';
											echo '<td width="35%" colspan="2" style="font-size: 10px;font-weight: bold; background-color: #000000; color: #ffffff;   text-align: left" ><b>Sub Totals</b></td>';
											echo '<td width="15%" style="font-size: 10px;font-weight: bold; background-color: #000000; color: #ffffff;  text-align: right" >'.number_format($budget,2).'</td>';
											echo '<td width="10%" style="font-size: 10px;font-weight: bold; background-color: #000000; color: #ffffff;   text-align: right" >'.number_format($actuals,2).'</td>';
											echo '<td width="15%" style="font-size: 10px;font-weight: bold; background-color: #000000; color: #ffffff;   text-align: right" >'.number_format($commitments,2).'</td>';
											if ($balance > 0) {
												echo '<td width="15%" style="font-size: 10px;font-weight: bold; background-color: #000000; color: #ffffff;   text-align: right" ><b>'.number_format($balance,2).'</b></td>';
											} else {
												echo '<td width="15%" style="font-size: 10px;font-weight: bold; background-color: #000000; color: #ff0000;   text-align: right" ><b>'.number_format($balance,2).'</b></td>';
											}
											echo '</tr>';
											echo '</table><br />';
									}
						
		
		}
		/*else if ($_GET['action'] == 'actuals') {
	
		 $SQL="select distinct fgcglacc, fcdname1 from  budgets.fgcsum, budgets.account_codes where fgcglcc = '".$ccode."' and fgcfiny = ".$cyear." and fgcglacc = fcdacc and  for_year = ".$cyear." and (fcdacat NOT IN ('999', '800', '960') or fgcglacc = '83602') and fgcglacc >= 40000 and fgcglacc <=49999  union  select distinct fgcglacc, fcdname1 from  budgets.fgcsum, budgets.account_codes where fgcglcc = '".$ccode."' and fgcfiny = ".$cyear." and fgcglacc = fcdacc and  for_year = ".$cyear." and (fcdacat NOT IN ('999', '800', '960') or fgcglacc = '83602') and fgcglacc >= 60000 and fgcglacc <=69999 order by fgcglacc ";
	

		//echo $sql;exit();
		  $dbo->setQuery($SQL);
		  $result = $dbo->loadObjectList();
		  if (!$result) { echo $dbo->getErrorMsg(); echo "ERROR"; exit(); }
		  foreach($result as $row1)
			{
				$actbal = $actdr = $actcr = $buddr = $budcr = $comm = 0;
			  $html .= "<table border='0' width='100%' style='table-layout: fixed'>";
			$actbal = $budbal = $available = 0;
			$html .= "<tr><td colspan='10' style='background-color: #3C619C; color: #ffff00; border: 1px solid #0f35b5; padding: 5px; font-weight: bold''>".$row1->fgcglacc." ".$row1->fcdname1."</td></tr></table>";
			$buddr = $budcr = $actdr = $actcr = $comm = $bbal = 0;

			$SQL="select fgcglcc, fgcfiny, fgcfinc, fgctrn, fgcrefn, fgcglnte, fgcidte, budcr, buddr, fgccom, actcr, actdr from budgets.fgcsum where fgcglcc = '".$ccode."' and fgcfiny = ".$cyear."  and fgcglacc = '".$row1->fgcglacc."' order by fgcidte";

		$html .= '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="table-layout: fixed">	<tr>'.
		'<td width="15%" style="font-size: 10px;font-weight: bold;border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left" >Date</td>'
		.'<td width="10%" style="font-size: 10px;font-weight: bold;border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">Trn</td>'
		.'<td width="15%" style="font-size: 10px;font-weight: bold;border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">Number</td>'
		.'<td width="20%" style="font-size: 10px;font-weight: bold;border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: left">Note</td>'
					.'<td width="10%" style="font-size: 10px;font-weight: bold;border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: right">Actual Debit</td>'
					.'<td width="10%" style="font-size: 10px;font-weight: bold;border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: right">Actual Credit</td>'
					.'<td width="10%" style="font-size: 10px;font-weight: bold;border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: right">Budget Amount</td>'
					.'<td width="10%" style="font-size: 10px;font-weight: bold;border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 3px; text-align: right">Commit</td>'
				.'</tr>'
			.'</table>';
			$dbo->setQuery($SQL);
			$resultx = $dbo->loadObjectList();
			foreach($resultx as $row)
			  {
			  $actdr += $row->actdr;
			  $actcr += $row->actcr;
			  $buddr += $row->buddr;
			  $budcr += $row->budcr;
			  $comm += $row->fgccom;
			  $bbal += $row->budcr;// - $row->buddr;

				$html .= '<table border="0" width="100%" cellspacing="1" style="table-layout: fixed">';
			  $html .= "<tr><td width='15%' style='font-size:10px'>".$row->fgcidte."</td><td  style='font-size:10px' width='10%'>".$row->fgctrn."</td><td style='font-size:10px' width='15%'>".$row->fgcrefn."</td><td width='20%' style='font-size:10px'>".$row->fgcglnte."</td><td width='10%' style='font-size:10px' align=\"right\">" . Number_format($row->actdr,2) . "</td><td width='10%'  style='font-size:10px' align=\"right\">" . Number_format($row->actcr,2) . "</td><td width='10%' style='font-size:10px' align=\"right\">" . Number_format($bbal,2) . "</td><td width='10%' style='font-size:10px' align=\"right\">" . Number_format($row->fgccom,2) . "</td></tr></table>";

			  }
			 
			$actbal = $actdr - $actcr;
			$budbal = $budcr - $buddr;
			//$available = $budbal + $actbal - $comm;
			$available = ($bbal + $actcr) - ($buddr + $actdr + $comm);
		
			$html .= '<table border="0" width="100%" cellspacing="1" style="table-layout: fixed">';

			$html .= "<tr><td  width='60%' style='font-size:10px;background-color: #bcbcbc; border: 1px solid #909090' colspan=4><b>Totals</b></td><td width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9' align=\"right\">" . Number_format($actdr,2) . "</td><td width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9'  align=\"right\">" . Number_format($actcr,2) . "</td><td width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9'  align=\"right\">" . Number_format($budbal,2) . "</td><td style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9'  width='10%' align=\"right\">" . Number_format($comm,2) . "</td></tr>";

			$html .= "<tr><td  width='50%' style='font-size:10px;background-color: #bcbcbc; border: 1px solid #909090'  colspan=4><b>Balance</b></td>";
			if( $actbal > 0)
			  $html .= "<td  width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9' align=\"right\">" . Number_format(abs($actbal),2) ."</td><td style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9' align=\"right\">0.00</td>";
			else
			  $html .= "<td   width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9' align=\"right\">0.00</td><td style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9' align=\"right\">" . Number_format(abs($actbal),2) ."</td>";

			if( $budbal < 0)
			  $html .= "<td  width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9' align=\"right\"><font color=red>" . Number_format(abs($budbal),2) ."</font></td>";
			else
			  $html .= "<td  width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9' align=\"right\">" . Number_format(abs($budbal),2) ."</td>";

			$html .= "<td   width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9'  align=\"right\">" . number_format($comm,2)."</td></tr>";
			$html .= "<tr><td  width='10%' colspan=7 style='font-size:10px;background-color: #bcbcbc; border: 1px solid #909090' ><b>Available</b></td>";

			if( $available < 0)
			  $html .= "<td   width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9'  align=\"right\"><font color=\"red\"><b>". Number_format($available,2) ."</font></b></td></tr>";
			else
			  $html .= "<td   width='10%' style='font-size:10px;background-color: #e5e5e5; border: 1px solid #c9c9c9'  align=\"right\"><b>". Number_format($available,2) ."</b></td></tr>";

			$html .= "</table>";
			
		   }
		   echo $html;
  
}*/

}

?>