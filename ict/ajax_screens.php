<?php
defined('_JEXEC') or die('Restricted access');
$doc = & JFactory::getDocument();
$doc->addScript('templates/portal/css/template.css');
$dbo =& JFactory::getDBO();

if (isset($_GET['screen']))
{
	/////Get step order for status///////
	if ($_GET['screen'] == 1) {
		?>



			<div class="art-block">
            <div class="art-block-tl"></div>
            <div class="art-block-tr"></div>
            <div class="art-block-bl"></div>
            <div class="art-block-br"></div>
            <div class="art-block-tc"></div>

            <div class="art-block-bc"></div>
            <div class="art-block-cl"></div>
            <div class="art-block-cr"></div>
            <div class="art-block-cc"></div>
				<div class="art-block-body">
					<div class="art-blockheader">
						<div class="l"></div>
						<div class="r"></div>
							 <h3 class="t" id='bh'>
							Acquisitions</h3>
					</div>
				</div>
		</div>

		<!--DISPLAY SAVED RECORDS-->
		<table border='0'  width='100%'>
		<tr>
		<td width='100%'>
		<form name='form_srch' method='post' action='index.php?option=com_jumi&view=application&fileid=4&Itemid=115'>
		<input type='text' name='srch' id='srch' size='15' maxlength='15' onkeyup='this.value=this.value.toUpperCase();'>&nbsp;
		<?php
			echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		?>
		<input type='submit' name='button_srch' id='button_srch' value='Search Code' class='button art-button'></span>
		</form>
		</td>
		</tr>
		</table>
		<table border='0' width='100%'  style='padding: 2px'>
		<tbody>
		<tr>
		<th width='5%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>&nbsp;</th>
		<th width='15%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>DATE CAPTURED</th>
		<th width='10%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>REF#</th>
		<th width='15%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>DATE LOGGED</th>
		<th width='10%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>CALL ID</th>
		<th width='25%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>ACQUISITION TYPE</th>
		<th width='20%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>STATUS</th>
		</tr>
		</tbody>
		</table>

	<?php
		if (isset($_GET['srch'])) {
		$sql = sprintf("select ict_acquisitions.id,ict_acquisitions.capture_date,ict_acquisitions.ref_no,
										ict_acquisitions.call_id,ict_acquisition_types.a_name,ict_acquisitions_status.status_name
										from ict_acquisitions left outer join ict_acquisition_types on
										(ict_acquisitions.acquisition_type = ict_acquisition_types.id)
										left outer join ict_acquisitions_status on
										(ict_acquisitions.status = ict_acquisitions_status.step_order)
										where ict_acquisitions.ref_no like '%s'
										order by ict_acquisitions.capture_date limit 20",$_GET['srch']."%");
	} else
		if (isset($_POST['srch'])) {
						$sql = sprintf("select ict_acquisitions.id,ict_acquisitions.capture_date,ict_acquisitions.ref_no,
										ict_acquisitions.call_id,ict_acquisition_types.a_name,ict_acquisitions_status.status_name
										from ict_acquisitions left outer join ict_acquisition_types on
										(ict_acquisitions.acquisition_type = ict_acquisition_types.id)
										left outer join ict_acquisitions_status on
										(ict_acquisitions.status = ict_acquisitions_status.step_order)
										where ict_acquisitions.ref_no like '%s'
										order by ict_acquisitions.capture_date limit 20",$_POST['srch']."%");
	} else {
						$sql = sprintf("select ict_acquisitions.id,ict_acquisitions.capture_date,ict_acquisitions.ref_no,
										ict_acquisitions.call_id,ict_acquisition_types.a_name,ict_acquisitions_status.status_name
										from ict_acquisitions left outer join ict_acquisition_types on
										(ict_acquisitions.acquisition_type = ict_acquisition_types.id)
										left outer join ict_acquisitions_status on
										(ict_acquisitions.status = ict_acquisitions_status.step_order)
										order by ict_acquisitions.capture_date desc limit 20");
	}
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$cnt = 1;
		echo "\n<form name='generic_form' id='generic_form' method='post' action='index.php?option=com_jumi&view=application&fileid=4&Itemid=115'>";
		echo "\n<input type='hidden' name='action'>";
		echo "\n<input type='hidden' name='step_order'>";
		echo "\n<table border='0' width='100%'>";
		echo "<tbody>";
		while ($row = mysqli_fetch_object($result)) {
		++$cnt;
		if ($cnt % 2 == 0) { $col = '#D4D4D4'; } else { $col = '#FFFFFF'; }
		echo "<tr>";
		echo "<td width='5%' style='padding: 3px;background-color: ".$col.";'>";
		echo "\n<input type='radio' name='id' value='".$row->id."'></td>";
		echo "\n<td width='15%' style='padding: 1px;background-color: ".$col.";'>".$row->capture_date."</td>";
		echo "\n<td width='10%' style='padding: 1px;background-color: ".$col.";'>".$row->ref_no."</td>";
		echo "\n<td width='15%' style='padding: 1px;background-color: ".$col.";'>".$row->call_date."</td>";
		echo "\n<td width='10%' style='padding: 1px;background-color: ".$col.";'>".$row->call_id."</td>";
		echo "\n<td width='25%' style='padding: 1px;background-color: ".$col.";'>".$row->a_name."</td>";
		echo "\n<td width='20%' style='padding: 1px;background-color: ".$col.";font-size: 10px'>".$row->status_name."</td>";
		echo "\n</tr>";
		}
		echo "\n</tbody></table>";

		echo "<table border='0' width='100%'>";
		echo "<tr>";
		echo "<td width='100%'>";
		///Button New////
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "\n<input type='button' name='button_new' id='button_new' value='New' class='button art-button' onclick=\"displayNewForm()\">";
		echo "</span>";
		///Button Edit////
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "\n<input type='button' name='button_edit' id='button_edit' value='Edit' class='button art-button' onclick=\"displayEditForm()\">";
		echo "</span>";
		///////////////////Button Cancel////////////////
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "\n<input type='button' name='button_cancel' id='button_cancel' value='Cancel' class='button art-button'>&nbsp;";
		echo "</span>";
		///////////////Button Complete///////////////////////
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "\n<input type='submit' name='button_complete' id='button_complete' value='Complete Entry' class='button art-button' onclick=\"document.forms['form_stock'].step_order.value=3\">&nbsp;";
		echo "</span>";
		///////////////////Button Admin/////////////
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "\n<input type='submit' name='button_admin' id='button_admin' value='Admin' class='button art-button' onclick=\"document.forms['form_stock'].action.value=99\">&nbsp;";
		echo "</span>";

		echo "\n</td>";
		echo "\n</tr>";
		echo "\n</table>";
		echo "\n</form>";
	}

/////////////////////New acquisition/////////////
if ($_GET['screen'] == 2) {
				?>
			<div class="art-block">
            <div class="art-block-tl"></div>
            <div class="art-block-tr"></div>
            <div class="art-block-bl"></div>
            <div class="art-block-br"></div>
            <div class="art-block-tc"></div>

            <div class="art-block-bc"></div>
            <div class="art-block-cl"></div>
            <div class="art-block-cr"></div>
            <div class="art-block-cc"></div>
				<div class="art-block-body">
					<div class="art-blockheader">
						<div class="l"></div>
						<div class="r"></div>
							 <h3 class="t" id='bh'>
							 New Acquisitions
							</h3>
					</div>
				</div>
		</div>

		<!--NEW ACQUISITION-->

		<?php
		$sql = sprintf("select connect_string,trim(user_name) as user_name,trim(password) as password from system_setup where system_name = 'helpdesk'");
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$row = mysqli_fetch_object($result);
		$ora = oci_connect($row->user_name,$row->password,$row->connect_string);
		//$ora = oci_connect('helpdesk','deskhelp',$row->connect_string);
		if (!$ora) {
			echo "\n<script type='text/javascript'>";
			echo "\nsetMessages('Error Connecting To Oracle Helpdesk System.');";
			echo "\n</script>";
			exit;
		}
		///Fetch next id/////
		$sql = sprintf("SELECT ict_proc_01() as idx");
		$dbo->setQuery($sql);
		$resultx = $dbo->query();
		$rowx = mysqli_fetch_object($resultx);

		$sql = sprintf("select id,capture_date,call_id,caller_id,call_loggedby,call_details,cost_centre,acquisition_type,approval_email
		from ict_acquisitions where id = %d",$rowx->idx);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$row = mysqli_fetch_object($result);

		?>
		<div id='ac_details' style='float: left; height: auto; width: 55%; padding-left: 3px; margin-left: 3px; border: 1px solid #A9BFCB; background-color: #CFDCE2'>
		<div style='margin: 3px; height: auto: width: 95%; border: 1px solid #A9BFCB; padding: 3px; background-color: #4F7183; color: white; font-weight: bold'>TICKET DETAILS</div>
		<?php
		echo "\n<form name='generic_new' id='generic_new' method='post' action='index.php?option=com_jumi&view=application&fileid=4&Itemid=115'>";
		echo "\n<input type='hidden' name='id' id='id' value='".$row->id."'>";
		echo "<input type='hidden' name='action' id='action' value='1'>";
		echo "<input type='hidden' name='dbaction' id='dbaction' value='2'>";
		echo "\n<table border='0' width='100%'>";


		echo "<table border='0' width='100%'>";
		echo "<tr>";
		echo "<td width='30%'>";
		echo "Capture Date:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='cap_date' id='cap_date' value='".$row->capture_date."' size='15' readonly>";
		echo "<input type=\"button\" id='cap_date_sel' value=\"...\" onclick=\"NewCssCal('cap_date','yyyymmdd');\">";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%'>";
		echo "Call ID:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='call_id' id='call_id' value='".$row->call_id."' size='15' onBlur='getCallDetails(this.value)'>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%'>";
		echo "Caller ID:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='caller_id' id='caller_id' value='".$row->caller_id."' size='50' readonly>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%' valign='top'>";
		echo "Call Details:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<textarea name='call_details' id='call_details' rows='3' cols='47' readonly>".$row->call_details."</textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%'>";
		echo "Call Logged By:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='logged_by' id='logged_by' size='50' value='".$row->call_loggedby."' readonly>";
		echo "</td>";
		echo "</tr>";


		echo "<tr>";
		echo "<td width='30%'>";
		echo "Acquisition Type:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "\n<select name='ac_type' id='ac_type' size='1' style='width: 26em'>";
			$sql = sprintf("select id,a_name from ict_acquisition_types order by id");
			$dbo->setQuery($sql);
			$result = $dbo->query();
			while ($rowx = mysqli_fetch_object($result)) {
				if ($rowx->id == $row->acquisition_type) {
					echo "\n<option value='".$rowx->id."' selected>".$rowx->a_name."</option>";
				} else {
					echo "\n<option value='".$rowx->id."'>".$rowx->a_name."</option>";
				}
			}
		echo "\n</select>";
		echo "</td>";
		echo "</tr>";


		echo "<tr>";
		echo "<td width='30%'>";
		echo "Cost Centre:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "\n<select name='cost_centre' id='cost_centre' size='1' style='width: 26em'>";
			$sql = sprintf("select distinct budgets.costcodes.detail_cc,budgets.costcodes.cc_name from budgets.costcodes order by budgets.costcodes.cc_name");
			$dbo->setQuery($sql);
			$resulty = $dbo->query();
			while ($rowy = mysqli_fetch_object($resulty)) {
				if ($rowy->detail_cc == $row->cost_centre) {
					echo "\n<option value='".$rowy->detail_cc."' selected>".$rowy->cc_name." [".$rowy->detail_cc."]</option>";
				} else {
					echo "\n<option value='".$rowy->detail_cc."'>".$rowy->cc_name." [".$rowy->detail_cc."]</option>";
				}
			}
		echo "\n</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%'>";
		echo "Approval Email:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='app_email' id='app_email' value='".$row->approval_email."' size='50' value='benjaminf@cput.ac.za'>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='100%' colspan='2'>";
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "\n<input type='submit' name='button_save_ac' id='button_save_ac' value='Save' class='button art-button'>&nbsp;";
		echo "</span>";
		///////////////////Button Cancel////////////////
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "\n<input type='button' name='button_cancel' id='button_cancel' value='Cancel' class='button art-button' onclick=\"loadMainScreen()\" >&nbsp;";
		echo "</span>";

		echo "\n</td>";
		echo "\n</tr>";
		echo "\n</table>";
		echo "\n</form>";
		echo "</div>";

		echo "<div id='ac_items' style='float: right; height: auto; width: 40%; padding-left: 3px; margin-right: 3px; border: 1px solid #A9BFCB; background-color: #CFDCE2'>";
		echo "</div>";

	}

	if ($_GET['screen'] == 3) {
				?>
			<div class="art-block">
            <div class="art-block-tl"></div>
            <div class="art-block-tr"></div>
            <div class="art-block-bl"></div>
            <div class="art-block-br"></div>
            <div class="art-block-tc"></div>

            <div class="art-block-bc"></div>
            <div class="art-block-cl"></div>
            <div class="art-block-cr"></div>
            <div class="art-block-cc"></div>
				<div class="art-block-body">
					<div class="art-blockheader">
						<div class="l"></div>
						<div class="r"></div>
							 <h3 class="t" id='bh'>
							 Edit Acquisitions
							</h3>
					</div>
				</div>
		</div>
<!--EDIT ACQUISITION-->

		<?php
		$sql = sprintf("select id,capture_date,call_id,caller_id,call_loggedby,call_details,cost_centre,acquisition_type,approval_email
		from ict_acquisitions where id = %d",$_GET['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$row = mysqli_fetch_object($result);

		?>
		<div id='ac_details' style='float: left; height: auto; width: 55%; padding-left: 3px; margin-left: 3px; border: 1px solid #A9BFCB; background-color: #CFDCE2'>
		<div style='margin: 3px; height: auto: width: 95%; border: 1px solid #A9BFCB; padding: 3px; background-color: #4F7183; color: white; font-weight: bold'>TICKET DETAILS</div>
		<?php
		echo "\n<form name='generic_edit' id='generic_edit' method='post' action='index.php?option=com_jumi&view=application&fileid=4&Itemid=115'>";
		echo "\n<input type='hidden' name='id' id='id' value='".$row->id."'>";
		echo "<input type='hidden' name='action' id='action' value='2'>";
		echo "<input type='hidden' name='dbaction' id='dbaction' value='2'>";
		echo "\n<table border='0' width='100%'>";


		echo "<table border='0' width='100%'>";
		echo "<tr>";
		echo "<td width='30%'>";
		echo "Capture Date:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='cap_date' id='cap_date' value='".$row->capture_date."' size='15' readonly>";
		echo "<input type=\"button\" id='cap_date_sel' value=\"...\" onclick=\"NewCssCal('cap_date','yyyymmdd');\">";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%'>";
		echo "Call ID:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='call_id' id='call_id' value='".$row->call_id."' size='15' onBlur='getCallDetails(this.value)'>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%'>";
		echo "Caller ID:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='caller_id' id='caller_id' value='".$row->caller_id."' size='50' readonly>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%' valign='top'>";
		echo "Call Details:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<textarea name='call_details' id='call_details' rows='3' cols='47' readonly>".$row->call_details."</textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%'>";
		echo "Call Logged By:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='logged_by' id='logged_by' size='50' value='".$row->call_loggedby."' readonly>";
		echo "</td>";
		echo "</tr>";


		echo "<tr>";
		echo "<td width='30%'>";
		echo "Acquisition Type:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "\n<select name='ac_type' id='ac_type' size='1' style='width: 26em'>";
			$sql = sprintf("select id,a_name from ict_acquisition_types order by id");
			$dbo->setQuery($sql);
			$result = $dbo->query();
			while ($rowx = mysqli_fetch_object($result)) {
				if ($rowx->id == $row->acquisition_type) {
					echo "\n<option value='".$rowx->id."' selected>".$rowx->a_name."</option>";
				} else {
					echo "\n<option value='".$rowx->id."'>".$rowx->a_name."</option>";
				}
			}
		echo "\n</select>";
		echo "</td>";
		echo "</tr>";


		echo "<tr>";
		echo "<td width='30%'>";
		echo "Cost Centre:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "\n<select name='cost_centre' id='cost_centre' size='1' style='width: 26em'>";
			$sql = sprintf("select distinct budgets.costcodes.detail_cc,budgets.costcodes.cc_name from budgets.costcodes order by budgets.costcodes.cc_name");
			$dbo->setQuery($sql);
			$result = $dbo->query();
			while ($rowy = mysqli_fetch_object($result)) {
				if ($rowy->detail_cc == $row->cost_centre) {
					echo "\n<option value='".$rowy->detail_cc."' selected>".$rowy->cc_name." [".$rowy->detail_cc."]</option>";
				} else {
					echo "\n<option value='".$rowy->detail_cc."'>".$rowy->cc_name." [".$rowy->detail_cc."]</option>";
				}
			}
		echo "\n</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='30%'>";
		echo "Approval Email:";
		echo "</td>";
		echo "<td width='70%'>";
		echo "<input type='text' name='app_email' id='app_email' value='".$row->approval_email."' size='50' value='benjaminf@cput.ac.za'>";
		echo "</td>";
		echo "</tr>";

		///Button New////
		echo "<tr>";
		echo "<td width='100%' colspan='2'>";
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "\n<input type='submit' name='button_save_ac' id='button_save_ac' value='Save' class='button art-button'>&nbsp;";
		echo "</span>";
		///////////////////Button Cancel////////////////
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "\n<input type='button' name='button_cancel' id='button_cancel' value='Cancel' class='button art-button' onclick=\"loadMainScreen()\" >&nbsp;";
		echo "</span>";

		echo "\n</td>";
		echo "\n</tr>";
		echo "\n</table>";
		echo "\n</form>";
		echo "</div>";

		echo "<div id='ac_items' style='float: right; height: auto; width: 40%; padding-left: 3px; margin-right: 3px; border: 1px solid #A9BFCB; background-color: #CFDCE2'>";
		echo "</div>";
	}

	else if ($_GET['screen'] == 4) {
		///////////////////ITEMS////////////////////
		echo "<div id='items-ajax' style='margin: 3px; height: auto: width: 95%; border: 1px solid #A9BFCB; padding: 3px; background-color: #4F7183; color: white; font-weight: bold'>ATTACHED ITEMS</div>";
		echo "<table border='0' width='100%'>";
		echo "<tr><td width='100%'>";
		echo "<select name='ac_items_list' id='ac_items_list' size='10' style='width:100%'>";
		echo "</select></td></tr>";
		echo "<tr><td width='100%'>";
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "<input type='button' name='add_item' id='add_item' value='Add' class='button art-button' onclick=\"loadAddItemsScreen($('id').value)\">";
		echo "</span>";
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "<input type='button' name='del_item' id='del_item' value='Delete' class='button art-button' onclick=\"deleteItem($('id').value)\" >";
		echo "</span>";
		echo "</td></tr>";
		echo "</table>";

	}

	else if ($_GET['screen'] == 5) {
		///////////////////ADD ITEMS////////////////////
		echo "<form>";
		echo "<input type='hidden' name='newid' id='newid' value='".$_GET['id']."' >";
		echo "</form>";
		echo "<div id='items-ajax' style='margin: 3px; height: auto: width: 95%; border: 1px solid #A9BFCB; padding: 3px; background-color: #4F7183; color: white; font-weight: bold'>ATTACHED ITEMS</div>";
		echo "<table border='0' width='100%'>";
		echo "<tr>";
		echo "<td width='20%'>Qty:</td>";
		echo "<td width='80%'><input type='text' size='5' name='qty' id='qty'></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width='20%'>Stock#:</td>";
		echo "<td width='80%'>";
		echo "<select name='stock_select' id='stock_select' size='1' style='width: 100%'>";
		$sql = sprintf("select stock_code,stock_description from ict_acquisition_stock order by stock_description");
		$dbo->setQuery($sql);
		$result = $dbo->query();
		while ($row = mysqli_fetch_object($result)) {
			echo "<option value='".$row->stock_code."'>".$row->stock_description."</option>";
		}
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td width='100%' colspan='2'>";
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "<input type='button' name='save_item' id='save_item' value='Save' class='button art-button' onclick=\"saveAddItems()\">";
		echo "</span>";
		echo "<span class='art-button-wrapper'><span class='art-button-l'></span><span class='art-button-r'></span>";
		echo "<input type='button' name='cancel_item' id='cancel_item' value='Cancel' class='button art-button' onclick=\"loadItemsScreen($('id').value); getItems($('id').value)\" >";
		echo "</span>";
		echo "</td></tr>";
		echo "</table>";

	}
}
exit;
?>