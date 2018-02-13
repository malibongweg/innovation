<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/jobcards/jtable/js/jquery-1.10.2.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery-ui-1.10.4.js');
$doc->addScript('scripts/jobcards/jtable/jquery.jtable.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery.validationEngine.js');
$doc->addScript('scripts/jobcards/jtable/js/jquery.validationEngine-en.js');

$doc->addStyleSheet('scripts/jobcards/jtable/css/custom-theme/jquery-ui-1.10.4.custom.css');
$doc->addStyleSheet('scripts/jobcards/jtable/themes/metro/blue/jtable.css');
$doc->addStyleSheet('scripts/jobcards/jtable/css/validationEngine.jquery.css');
$doc->addScript("/scripts/json.js");
?>
<script type="text/javascript">
window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({width: 700, height: 450});
	window.parent.$j.colorbox.settings.escKey = false;
	window.parent.$j.colorbox.settings.overlayClose = false;
	var jid = $('jid').get('value');
	displayDelayed(jid);
});
function displayDelayed(jid) {
	jt('#tableCompletion').jtable({
            title: 'COMPLETION ENTRY FOR JOBCARD# '+jid,
			paging: false, // Enable paging
            pageSize: 15, // Set page size (default: 10)
            sorting: false, // Enable sorting
			selecting: true, // Enable selecting,
			dialogWidth: 300,
			loadingAnimationDelay: 0,
			jqueryuiTheme: true,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=104&action=listAssignedArtisans&jid='+jid,
				updateAction: 'index.php?option=com_jumi&fileid=104&action=editCompletion'
            },
            toolbar: {
				items: [{
					icon: '/scripts/jobcards/images/complete.png',
					text: 'Complete Jobcard',
					click: function () {
						if (confirm('This will put the jobcard into a completion status which cannot be un-done. Are you sure?')){
							jt.ajax({
								url:      'index.php?option=com_jumi&fileid=104&action=saveCompletion&jid='+jid,
								dataType: 'text',
								type:     'GET',
								async: false,
								success:  function(data){
									jt('#complete-ajax').css('display','block');
									jt.ajax({
										url:      'index.php?option=com_jumi&fileid=104&action=emailCompletion&jid='+jid,
										dataType: 'text',
										type:     'GET',
										async: false,
										success:  function(data){
											jt('#complete-ajax').css('display','none');
											window.parent.refreshTable();
											window.parent.$j.colorbox.close();
										}
									});
								}
							});
						}
					}
			},
			{
				icon: '/scripts/jobcards/images/cancel.png',
				text: 'Close Window',
				click: function () {
						window.parent.$j.colorbox.close();
				}
			}
			]},
            fields: {
				id: {
					list: false,
					key: true
				},
				jobcard: {
					list: false,
					create: false,
					edit: true,
					type: 'hidden',
					defaultValue: jid
				},
				artisan: {
					title: 'ARTISAN',
					list: false,
					create: false,
					edit: false
				},
				fullname: {
					title: 'ASSIGNED ARTISAN',
					width: '40%',
					list: true,
					create: false,
					edit: false

				},
				trade_description: {
					title: 'TRADE',
					width: '35%',
					create: false,
					edit: false
				},
				hours: {
					title: 'HOURS',
					width: '25%',
					list: true,
					create: false,
					edit: true
				},
				material_cost: {
					title: 'MATERIAL COSTS',
					width: '25%',
					list: false,
					create: false,
					edit: true
				}

            },
            	rowUpdated: function (event, data) {
            		jt('#tableCompletion').jtable('reload');
            	},
            	formCreated: function (event, data) {
                data.form.find('input[name="hours"]').addClass('validate[required],custom[number]');
                data.form.find('input[name="materal"]').addClass('validate[required],custom[number]');
                data.form.validationEngine({promptPosition : 'topLeft', scroll: false});
                data.form.validationEngine();
				},
				//Validate form when it is being submitted
				formSubmitting: function (event, data) {
					return data.form.validationEngine('validate');
				},
				//Dispose validation logic when form is closed
				formClosed: function (event, data) {
					data.form.validationEngine('hide');
					data.form.validationEngine('detach');
				}
        });

		jt('#tableCompletion').jtable('load');
}
</script>
<div id="complete-ajax" style="top: 10px; left: 10px; z-order: 1000; border: 1px solid #c8c8c8; background-color: #d8d8d8;padding: 3px; display: none">
	<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Sending email...Please wait.
</div>

<input type="hidden" id="jid" value="<?php echo $_GET['jid'];?>" />

<div id="tableCompletion">
		</div>