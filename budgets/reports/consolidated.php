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
?>
<style>
	body {
		font-size: 12px;
		font-family: Verdana, Arial
	}
</style>
<script type="text/javascript" src="/media/system/js/core.js"></script>
<script type="text/javascript" src="/media/system/js/mootools-core.js"></script>
<script type="text/javascript" src="/media/system/js/mootools-more.js"></script>
<script type="text/javascript">
	window.addEvent('domready',function(){
		window.parent.$j.colorbox.resize({ 'width': '30%','height': '30%' });

		$('consol-params').addEvent('submit',function(e){
			e.stop();
			var x = new Request({
				url: '/scripts/budgets/reports/consolidated2.php',
				data: this,
				noCache: true,
				method: 'post',
				onComplete: function(response){
					window.parent.$j.colorbox.resize({ 'width': '60%','height': '70%' });
					$('rep-content').set('html',response);
				}
			}).send();
		});

		$('cost-code').focus();
	});
</script>

<div style="text-align: center; margin: 0 auto; font-size: 12px" id="rep-content">
	<p>Select cost centre and report year.</p>
	<form id="consol-params">
	<input type="text" name="cost_code" id="cost-code" size="4" maxlength="4" onKeyUp="javascript: this.value=this.value.toUpperCase();" /><br /><br />
	<select name="budget_year" id="budget-year" size="1">
		<?php
			$yr = intval(date('Y'));
			for ($i = ($yr+1); $i > ($yr-2); $i--){
				echo '<option value="'.$i.'">'.$i.'</option>';
			}
		?>
	</select><br /><br />
	<input type="submit" value="Run Report" />&nbsp;
	<input type="button" value="Close" onclick="javascript: window.parent.$j.colorbox.close();" />
	</form>
</div>