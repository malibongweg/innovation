<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$session =& JFactory::getSession();
$usr = $session->get('id');
$groups = $user->get('groups');

if(array_key_exists("acquisitions",$groups)) {

$dbo =& JFactory::getDBO();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/ict/ict.js');
JHTML::_('behavior.mootools');
?>



<?php

		if (isset($_GET['action'])) {
			if ($_GET['action'] == 3) {
				$sql = sprintf("insert into ict_acquisition_stock (stock_code,stock_description,price)
				values ('%s','%s',%0.2f)",$_POST['scode'],$_POST['sdesc'],$_POST['sprice']);
				$dbo->setQuery($sql);
				$result = $dbo->query();
				echo "<script type='text/javascript'>";
					echo "alert('Record Saved.');";
					echo "window.location.href='index.php?option=com_jumi&view=application&fileid=5&Itemid=116&srch=".$_POST['scode']."'";
				echo "</script>";

			}
			else if ($_GET['action'] == 4) {
				$sql = sprintf("update ict_acquisition_stock set stock_code='%s',stock_description='%s',price=%0.2f,active=%d
				where id = %d",$_POST['scode'],$_POST['sdesc'],$_POST['price'],$_POST['sactive'],$_POST['id']);
				$dbo->setQuery($sql);
				$result = $dbo->query();
				echo "<script type='text/javascript'>";
					echo "alert('Record Saved.');";
					echo "window.location.href='index.php?option=com_jumi&view=application&fileid=5&Itemid=116&srch=".$_POST['scode']."'";
				echo "</script>";

			}
		}




		if (isset($_GET['action'])) {
			if ($_GET['action'] == 1) {
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
							 <h3 class="t">
							New Stock Record</h3>
					</div>
				</div>
				</div>
				<form name='generic_new' method='post' action='index.php?option=com_jumi&view=application&fileid=5&Itemid=116&action=3' onSubmit='return validateAcquisitionFields(this)'>
				<table border='0' width='100%'>
					<tr>
						<td width='20%'>Stock Code:</td>
						<td width='80%'><input type='text' name='scode' id='scode' size='10' maxlength='15' onKeyUp='this.value=this.value.toUpperCase();'></td>
					</tr>
					<tr>
						<td width='20%'>Stock Description:</td>
						<td width='80%'><input type='text' name='sdesc' id='sdesc' size='80' maxlength='255' onKeyUp='this.value=this.value.toUpperCase();'></td>
					</tr>
					<tr>
						<td width='20%'>Stock Price:</td>
						<td width='80%'><input type='text' name='sprice' id='sprice' size='8' maxlength='255' onKeyUp = "if ( !isDigit ( event ) ) {
						alert ( 'Oops! numbers only please.' ); this.value=''; return false }"></td>
					</tr>
					<tr>
					<td width='20%'>&nbsp;</td>
					<td width='80%'>
						<input type='submit' value='Save' class='button art-button'>&nbsp;
						<input type='button' value='Cancel' class='button art-button' onclick="if (confirm('Are you sure?')) { window.location.href='index.php?option=com_jumi&view=application&fileid=5&Itemid=116'; }">
					</td>
					</tr>
				</table>
				</form>
				<script type='text/javascript'> $('scode').focus(); </script>
				<?php
			}
		 else if ($_GET['action'] == 2) {
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
							 <h3 class="t">
							Edit Stock Record</h3>
					</div>
				</div>
				</div>
				<form name='generic_edit' method='post' action='index.php?option=com_jumi&view=application&fileid=5&Itemid=116&action=4' onSubmit='return validateAcquisitionFields(this)'>
				<?php
						$sql = sprintf("select stock_code,stock_description,price,active from ict_acquisition_stock
										where id = %d",$_GET['id']);
							$dbo->setQuery($sql);
							$result = $dbo->query();
							$row = mysqli_fetch_object($result);
				?>
				<input type='hidden' name='id' id='id' value='<?php echo $_GET['id']; ?>'>
				<table border='0' width='100%'>
					<tr>
						<td width='20%'>Stock Code:</td>
						<td width='80%'><input type='text' name='scode' id='scode' size='10' value='<?php echo $row->stock_code; ?>' maxlength='15' onKeyUp='this.value=this.value.toUpperCase();'></td>
					</tr>
					<tr>
						<td width='20%'>Stock Description:</td>
						<td width='80%'><input type='text' name='sdesc' id='sdesc' size='80' value='<?php echo $row->stock_description; ?>' maxlength='255' onKeyUp='this.value=this.value.toUpperCase();'></td>
					</tr>
					<tr>
						<td width='20%'>Stock Price:</td>
						<td width='80%'><input type='text' name='sprice' id='sprice' size='8' value='<?php echo $row->price; ?>' maxlength='255' onKeyUp = "if ( !isDigit ( event ) ) {
						alert ( 'Oops! numbers only please.' ); this.value=''; return false }"></td>
					</tr>
					<tr>
						<td width='20%'>Status:</td>
						<td width='80%'><input type='radio' name='sactive' id='sactive' value='1' <?php if ($row->active ==1) { echo 'checked'; } ?>>Active
						<input type='radio' name='sactive' id='sactive' value='0' <?php if ($row->active ==0) { echo 'checked'; } ?>>Not Active
						</td>
					</tr>
					<tr>
					<td width='20%'>&nbsp;</td>
					<td width='80%'>
						<input type='submit' value='Save' class='button art-button'>&nbsp;
						<input type='button' value='Cancel' class='button art-button' onclick="if (confirm('Are you sure?')) { window.location.href='index.php?option=com_jumi&view=application&fileid=5&Itemid=116'; }">
					</td>
					</tr>
				</table>
				</form>
				<script type='text/javascript'> $('scode').focus(); </script>
				<?php
		}
		}
		else {
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
							 <h3 class="t">
							Acquisition Stock</h3>
					</div>
				</div>
		</div>

		<!--DISPLAY SAVED RECORDS-->
		<table border='0' width='100%'>
		<tr>
		<td width='100%'>
		<form name='form_srch' method='post' action='index.php?option=com_jumi&view=application&fileid=5&Itemid=116'>
		<input type='text' name='srch' id='srch' size='15' maxlength='15' onkeyup='this.value=this.value.toUpperCase();'>&nbsp;
		<input type='submit' name='button_srch' id='button_srch' value='Search Code' class='button art-button'>
		</form>
		</td>
		</tr>
		</table>
		<table border='0' width='100%'  style='padding: 2px'>
		<tr>
		<td width='5%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>&nbsp;</td>
		<td width='15%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>STOCK CODE</td>
		<td width='65%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>DESCRIPTION</td>
		<td width='15%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>PRICE</td>
		</tr>
		</table>

	<?php
		if (isset($_GET['srch'])) {
		$sql = sprintf("select ict_acquisition_stock.id,ict_acquisition_stock.stock_code,ict_acquisition_stock.stock_description,
						ict_acquisition_stock.price
						from ict_acquisition_stock
						where ict_acquisition_stock.active=1 and ict_acquisition_stock.stock_code like '%s' limit 20",$_GET['srch']."%");
	} else
		if (isset($_POST['srch'])) {
						$sql = sprintf("select ict_acquisition_stock.id,ict_acquisition_stock.stock_code,ict_acquisition_stock.stock_description,
						ict_acquisition_stock.price
						from ict_acquisition_stock
						where ict_acquisition_stock.active=1 and ict_acquisition_stock.stock_code like '%s' limit 20",$_POST['srch']."%");
	} else {
						$sql = sprintf("select ict_acquisition_stock.id,ict_acquisition_stock.stock_code,ict_acquisition_stock.stock_description,
						ict_acquisition_stock.price
						from ict_acquisition_stock
						where ict_acquisition_stock.active=1 limit 20");
	}
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$cnt = 1;
		echo "<form name='form_stock' method='post' action='index.php?option=com_jumi&view=application&fileid=5&Itemid=116' onSubmit='return validateAcquisitionsStock(this)'>";
		echo "<input type='hidden' name='action'>";
		echo "<table border='0' width='100%'>";
		while ($row = mysqli_fetch_object($result)) {
		++$cnt;
		if ($cnt % 2 == 0) { $col = '#D4D4D4'; } else { $col = '#FFFFFF'; }
		echo "<tr>";
		echo "<td width='5%' style='padding: 1px;background-color: ".$col.";'>";
		echo "<input type='radio' name='id' value='".$row->id."'></td>";
		echo "<td width='15%' style='padding: 1px;background-color: ".$col.";'>".$row->stock_code."</td>";
		echo "<td width='65%' style='padding: 1px;background-color: ".$col.";'>".$row->stock_description."</td>";
		echo "<td width='15%' style='padding: 1px;background-color: ".$col.";'>".$row->price."</td>";
		echo "</tr>";
		}
		echo "</table>";

		echo "<table border='0' width='100%'>";
		echo "<tr>";
		echo "<td width='100%'>";
		echo "<input type='submit' name='button_new' id='button_new' value='New' class='button art-button' onclick=\"document.forms['form_stock'].action.value=1\">&nbsp;";
		echo "<input type='submit' name='button_edit' id='button_edit' value='Edit' class='button art-button' onclick=\"document.forms['form_stock'].action.value=2\">&nbsp;";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</form>";
		}
}
	?>
