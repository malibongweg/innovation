<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
//$doc->addScript('scripts/overtime/index.js');
$doc->addScript('scripts/overtime/jtable/js/jquery-1.10.2.js');
$doc->addScript('scripts/overtime/jtable/js/jquery-ui-1.10.4.js');
$doc->addScript('scripts/overtime/jtable/jquery.jtable.js');
$doc->addScript('scripts/overtime/jtable/js/jquery.validationEngine.js');
$doc->addScript('scripts/overtime/jtable/js/jquery.validationEngine-en.js');

$doc->addStyleSheet('scripts/overtime/jtable/css/custom-theme/jquery-ui-1.10.4.custom.css');
$doc->addStyleSheet('scripts/overtime/jtable/themes/metro/blue/jtable.css');
$doc->addStyleSheet('scripts/overtime/jtable/css/validationEngine.jquery.css');

$doc->addScript('scripts/overtime/datetime.js');
$doc->addStyleSheet('scripts/overtime/datetime.css');

$doc->addScript('scripts/json.js');

?>
<style type="text/css">
	.label-left {
		text-align: left;
		font-size: 12px;
	}
</style>

<a href="#" class="modalizer" id="print-report"></a>
<input type="hidden" id="uid" value="<?php echo $user->id; ?>" />
<input type="hidden" id="login-name" value="<?php echo $user->username; ?>" />

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
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<span id="store-name"></span>
       </h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">

	
	<div id="form-list" style="display: block; padding: 10px">
		Select payroll cutoff date:
		<select name="claimscutoff" id="claims-cutoff" size="1" style="width: 20%">
		<?php
			$mth = date('m');
			$sql = "select a.cutoff_date,DATE_FORMAT(a.cutoff_date,'%m') as month_code from over_time.cutoffdates a where a.year = YEAR(current_date) order by a.month_code";
			$dbo->setQuery($sql);
			$result = $dbo->loadObjectList();
			foreach($result as $row){
				if (intval($row->month_code) == intval($mth)){
					echo '<option selected value="'.$row->cutoff_date.'">'.$row->cutoff_date.'</option>';
				} else {
					echo '<option value="'.$row->cutoff_date.'">'.$row->cutoff_date.'</option>';
				}
			}
		?>
		</select>&nbsp;<input type="button" value="Run Report" id="claims-report-run" />
	</div>
	

</div></div></div></div>

<script type="text/javascript">

window.addEvent('domready',function(){

	$('claims-report-run').addEvent('click',function(){
		var cdate = $('claims-cutoff').getSelected().get('value');
		$('print-report').set('href','/scripts/overtime/report-claims.php?cutoff='+cdate);
		$('print-report').click();
	});

});



</script>