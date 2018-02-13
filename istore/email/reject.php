<!doctype html>
<head>
<script type="text/javascript" src="/scripts/istore/email/mootools.min.js"></script>
</head>
<body>
<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);//realpath(dirname(__FILE__)));
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
require_once($_SERVER['DOCUMENT_ROOT']."/scripts/system/functions.php");
$dbo = JFactory::getDBO();

$sql = sprintf("select order_no from istore.istore_approvals where cde = '%s' and processed = 0",$_GET['cde']);
$dbo->setQuery($sql);
$dbo->query();
if ($dbo->getNumRows() == 0) {
	?>
		
				<span style="font-family: Verdana, Arial; font-size: 14px; font-weight: bold">
					eStore Order Approval
				</span>
				<p style="font-size: 12px">Order was not found.<br />Thank you.</p>
			</body>
		</html>
	<?php
} else {
	$order = $dbo->loadResult();
	$sql = sprintf("select istore_order_status_id,store_id,client_uid,creator_uid,order_no,order_date,cost_centre,comments,istore.istore_order_status.`status`
				from istore.istore_orders
				left outer join istore.istore_order_status on (istore.istore_orders.istore_order_status_id = istore.istore_order_status.id) where order_no='%s'",$order);
		
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$creator_uid = intval($row->creator_uid);
		$client_uid = intval($row->client_uid);
		$orderno=$row->order_no;
		$cost_centre = $row->cost_centre;
		$account_code = $row->account_code;
		$comments = $row->comments;
		$order_date = $row->order_date;
		$storeid = intval($row->store_id);
		$order_status = intval($row->istore_order_status_id);
		$status_desc = $row->status;
		?>

	
		<span style="font-family: Verdana, Arial; font-size: 14px; font-weight: bold">eStore Order Approval</span>
		<?php
			if ($order_status != 2) {
		?>
		<p>Order# <?php echo $order." current status is ".$status_desc; ?>.<br />No action taken. Thank you.</p>
		<?php
			} else {
				$sql = sprintf("update istore.istore_orders set istore_order_status_id = 4 where order_no = '%s'",$order);
				$dbo->setQuery($sql);
				$dbo->query();
		?>
					<p >Order# <?php echo $order; ?> was REJECTED.<br /> Requestor and client will be notified of current order status.<br />
					Thank you.</br />CTS Department</p>
					<span id="show-emailing" style="display: none"><img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="">Sending email...please wait.</span>
				
				<script type="text/javascript">
				window.addEvent('domready',function(){
						$('show-emailing').setStyle('display','block');
							var x= new Request({
							url: '/index.php?option=com_jumi&fileid=157&action=sendReject&orderno=<?php echo $order; ?>',
							method: 'get',
							noCache: true,
							onComplete: function(response) {
								$('show-emailing').setStyle('display','none');
							}
						}).send();
				});
				</script>
		<?php		
			}
}

?>

</body>
</html>