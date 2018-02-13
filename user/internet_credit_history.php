<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo = &JFactory::getDBO();
$sql = "select user_name,password,database_name,host from cput_system_setup where system_name='internet'";
$dbo->setQuery($sql);
$row = $dbo->loadObject();

$option = array(); //prevent problems
$option['driver']   = 'mysqli';            // Database driver name
$option['host']     = $row->host;
$option['user']     = $row->user_name;
$option['password'] = $row->password;
$option['database'] = $row->database_name;
$option['prefix']   = '';             // Database prefix (may be empty)
 
$db = & JDatabase::getInstance( $option );
?>
<style type="text/css">
	body {
	font-family: tahoma, arial;
	font-size: 12px;
	overflow-x: hidden;
	overflow-y: scroll;
	background-color: #dbdbdb;
}
</style>

<div style="position: relative; width: auto; height: auto">
<p style="font-color: #f67858; font-weight: bold">Please note...only the last 50 entries displayed.</p>
<table border="0" width="550">
<tr>
	<td width="125" style="border: 1px solid #808080; background-color: #c0c0c0; font-weight: bold">DATE</td>
	<td width="200" style="border: 1px solid #808080; background-color: #c0c0c0; font-weight: bold">CREDIT TYPE</td>
	<td width="225" style="border: 1px solid #808080; background-color: #c0c0c0; font-weight: bold; text-align: right">AMOUNT</td>
</tr>
<?php
$sql = sprintf("select uid from squid.global_users where login_name = '%s'",$_GET['login']);
$db->setQuery($sql);
$uid = $db->loadResult();
$sql = sprintf("select entry_date,credit_type,amount from squid.credit_log where uid = %d order by entry_date desc limit 50",$uid);
$db->setQuery($sql);
$db->query();

if ($db->getNumRows() == 0) {
	echo "<tr>";
	echo '<td width="550" colspan="3" style="border: 1px solid #808080">No records...</td>';
	echo "<tr>";
} else {
	$result = $db->loadObjectList();
	foreach ($result as $row){
		echo "<tr>";
		echo '<td width="125" style="border: 1px solid #808080">'.$row->entry_date.'</td>';
		echo '<td width="200" style="border: 1px solid #808080">'.$row->credit_type.'</td>';
		echo '<td width="225" style="border: 1px solid #808080;text-align: right">'.$row->amount.'</td>';
		echo "</tr>";
	}
}

?>
</table>
</div>

<script type="text/javascript">
	window.parent.$j.colorbox.resize({'width': 620,'height': 400});
</script>