<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$session =& JFactory::getSession();
$usr = $session->get('id');
$groups = $user->get('groups');
//print "<pre>";print_r($groups);print "</pre>";
if(array_key_exists("Super Users",$groups)) {

$dbo =& JFactory::getDBO();

JHTML::_('behavior.mootools');
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
							Database Connectors</h3>
					</div>
				</div>
		</div>

		<!--DISPLAY SAVED RECORDS-->
		<table border='0' width='100%'  style='padding: 2px'>
		<tr>
		<td width='30%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>SYSTEM NAME</td>
		<td width='20%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>SYSTEM TYPE</td>
		<td width='10%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>HOST</td>
		<td width='40%' style='padding: 3px;background-color: #CFDCE2;font-weight: bold'>CONNECT STRING</td>
		</tr>
		</table>

	<?php
		$sql = sprintf("select system_setup.system_name,system_types.system_name as systype,
		system_setup.host,trim(system_setup.connect_string) as connect_string
		from system_setup left outer join (system_types)
		on (system_setup.system_type = system_types.id)");
		$dbo->setQuery($sql);
		$result = $dbo->query();
		$cnt = 1;
		echo "<table border='0' width='100%'  style='padding: 2px'>";
		while ($row = mysqli_fetch_object($result)) {
		++$cnt;
		if ($cnt % 2 == 0) { $col = '#D4D4D4'; } else { $col = '#FFFFFF'; }
		echo "<tr>";
		echo "<td width='30%' style='padding: 3px;background-color: ".$col.";'>".$row->system_name."</td>";
		echo "<td width='20%' style='padding: 3px;background-color: ".$col.";'>".$row->systype."</td>";
		echo "<td width='10%' style='padding: 3px;background-color: ".$col.";'>".$row->host."</td>";
		echo "<td width='40%' style='padding: 3px;background-color: ".$col.";font-size:8px'>".$row->connect_string."</td>";
		echo "</tr>";
		}
		echo "</table>";

}
	?>
