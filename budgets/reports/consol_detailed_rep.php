<?php

define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/json.js');

$code_no = $_POST['code_no'];
if(isset($_POST['sel_year'])){  $sel_year = $_POST['sel_year']; }
$consol = $code_no;
$exp_code = intval($_POST['exp_code']);

$cost_array = array();

$sql1 = sprintf("select detail_cc,consol_cc from budgets.budget_consol where for_year = %d and consol_cc = '%s'",$sel_year,$consol);
$dbo->setQuery($sql1);
$dbo->query();
	if($dbo->getNumRows() > 0)
	{
		$result1 = $dbo->loadObjectList();
		foreach($result1 as $row1)
		{
			$sql2 = sprintf("select detail_cc,consol_cc from budgets.budget_consol where for_year = %d and consol_cc = '%s'",$sel_year,$row1->detail_cc);
			$dbo->setQuery($sql2);
				$result2 = $dbo->query();
				if ($dbo->getNumRows() > 0)
				{
					$result2 = $dbo->loadObjectList();
					foreach($result2 as $row2)
					{
						$sql3 = sprintf("select detail_cc,consol_cc from budgets.budget_consol where for_year = %d and consol_cc = '%s'",$sel_year,$row2->detail_cc);
						$dbo->setQuery($sql3);
							$dbo->query();
								if ($dbo->getNumRows() > 0)
								{
									$result3 = $dbo->loadObjectList();
									foreach($result3 as $row3)
									{
										$sql4 = sprintf("select detail_cc,consol_cc from budgets.budget_consol where for_year = %d and consol_cc = '%s'",$sel_year,$row3->detail_cc);
										$dbo->setQuery($sql4);
										$dbo->query();
												if ($dbo->getNumRows() > 0)
													{
														$result4 = $dbo->loadObjectList();
															foreach($result4 as $row4)
															{
																$sql5 = sprintf("select detail_cc,consol_cc from budgets.budget_consol where for_year = %d and consol_cc = '%s'",$sel_year,$row4->detail_cc);
																$dbo->setQuery($sql5);
																$dbo->query();
																	if ($dbo->getNumRows() > 0)
																	{
																		$result5 = $dbo->loadObjectList();
																			foreach($result5 as $row5)
																			{

																				$sql6 = sprintf("select detail_cc,consol_cc from budgets.budget_consol where for_year = %d and consol_cc = '%s'",$sel_year,$row5->detail_cc);
																				$dbo->setQuery($sql6);
																				$dbo->query();
																					if ($dbo->getNumRows() > 0)
																					{
																						$result6 = $dbo->loadObjectList();
																						foreach($result6 as $row6)
																						{
																							$sql7 = sprintf("select detail_cc,consol_cc from budgets.budget_consol where for_year = %d and consol_cc = '%s'",$sel_year,$row6->detail_cc);
																							$dbo->setQuery($sql7);
																							$dbo->query();
																								if ($dbo->getNumRows() > 0)
																								{
																									$result7 = $dbo->loadObjectList();
																									foreach($result7 as $row7)
																									{
																										$sql8 = sprintf("select detail_cc,consol_cc from budgets.budget_consol where for_year = %d and consol_cc = '%s'",$sel_year,$row7->detail_cc);
																										$dbo->setQuery($sql8);
																										$dbo->query();
																										if ($dbo->getNumRows() > 0)
																												{


																												} else $cost_array[] = $row7->detail_cc;

																									}

																								}  else $cost_array[] = $row6->detail_cc;
																						}

																					}  else $cost_array[] = $row5->detail_cc;


																			}
																      } else $cost_array[] = $row4->detail_cc;

															}
													} else $cost_array[] = $row3->detail_cc;
									}

								} else $cost_array[] = $row2->detail_cc;

					}
				} else $cost_array[] = $row1->detail_cc;
		}

	}


foreach ($cost_array as $key=>$value)
{
	$cc .= "'".$value."',";
}
$cc = substr($cc,0,(strlen($cc)-1));
switch ($exp_code) {

case 1:
$exp_name = "INCOME";
$sql = "select A.account_code,B.fcdname1,sum(A.budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc between 50000 and 59999 and account_code != '50033' and account_code = fcdacc and display_rec = 1 and A.cost_code in ({$cc}) group by A.account_code,B.fcdname1";
break;

case 2:
$exp_name = "SUBSIDY";
$sql = "select A.account_code,B.fcdname1,sum(A.budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc =50033 and account_code = fcdacc and display_rec = 1 and A.cost_code in ({$cc}) group by A.account_code,B.fcdname1";
break;

case 3:
$exp_name = "EXPENSE";
$sql = "select A.account_code,B.fcdname1,sum(A.budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc between 40000 and 49999 and account_code = fcdacc and display_rec = 1 and A.cost_code in ({$cc}) group by A.account_code,B.fcdname1";
break;

case 4:
$exp_name = "MANPOWER";
$sql = "select A.account_code,B.fcdname1,sum(A.budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc between 30020 and 39999 and account_code = fcdacc and display_rec = 1 and A.cost_code in ({$cc}) group by A.account_code,B.fcdname1";
break;

case 5:
$exp_name = "CAPITAL";
$sql = "select A.account_code,B.fcdname1,sum(A.budget_amount) as amnt from budgets.budget_accounts A, budgets.account_codes B where fcdfunct = 'D' and A.for_year = B.for_year and B.for_year = '$sel_year' and fcdacc between 60330 and 86699 and account_code = fcdacc and display_rec = 1 and A.cost_code in ({$cc}) group by A.account_code,B.fcdname1";
break;

}


$sql1 = "select distinct cc_name from budgets.costcodes where detail_cc = '$code_no'";
$dbo->setQuery($sql1);
$row1 = $dbo->loadResult();


print "<table><tr>";
print "<td><b>Consolidated Report for ".$code_no." [".$row1."]</b></td>";
print "</tr>";
print "<tr>";
print "<td><b>CATEGORY: $exp_name</b></td>";
print "</tr></table>";


print "<table width='100%' style='border: 1px solid #c8c8c8'><tr>";
print "<td style='border: 1px solid #c8c8c8'><b>Account</b></td>";
print "<td style='border: 1px solid #c8c8c8'> <b>Account Code</b></td>";
print "<td style='border: 1px solid #c8c8c8'><b>Amount</b></td>";
print "</tr>";

$dbo->setQuery($sql);
$result = $dbo->loadObjectList();
$total = 0;
foreach($result as $row)
{
print "<tr>";
print "<td style='border: 1px solid #c8c8c8'>{$row->account_code}</td>";
print "<td style='border: 1px solid #c8c8c8'>{$row->fcdname1}</td>";
print "<td style='border: 1px solid #c8c8c8'>{$row->amnt}</td>";
$total = $total + $row->amnt;
print "</tr>";
}
print "<tr>";
print "<td colspan=\"2\">&nbsp;</td>";
print "<td><b>".number_format($total,2)."</b></td>";
print "</tr></table>";