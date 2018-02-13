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
	displaySuppliers();
});

function displaySuppliers() {
	jt('#tableSuppliers').jtable({
            title: 'SUPPLIERS',
			paging: false, // Enable paging
            pageSize: 15, // Set page size (default: 10)
            sorting: false, // Enable sorting
			selecting: true, // Enable selecting,
			dialogWidth: 300,
			loadingAnimationDelay: 0,
			jqueryuiTheme: true,
            actions: {
                listAction: 'index.php?option=com_jumi&fileid=104&action=listSuppliers',
                createAction: 'index.php?option=com_jumi&fileid=104&action=createSuppliers',
				updateAction: 'index.php?option=com_jumi&fileid=104&action=editSuppliers',
				deleteAction: 'index.php?option=com_jumi&fileid=104&action=deleteSuppliers'
            },
            fields: {
				id: {
					list: false,
					key: true
				},
				supplier_name: {
					list: true
					title: 'SUPPLIER NAME',
					width: '100%',
					create: true,
					edit: true
				}
            },
            	rowUpdated: function (event, data) {
            		jt('#tableSuppliers').jtable('reload');
            	},
            	formCreated: function (event, data) {
                data.form.find('input[name="supplier_name"]').addClass('validate[required]');
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

		jt('#tableSuppliers').jtable('load');
}
</script>
<div id="supplier-ajax" style="top: 10px; left: 10px; z-order: 1000; border: 1px solid #c8c8c8; background-color: #d8d8d8;padding: 3px; display: none">
	<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Sending email...Please wait.
</div>

<div id="tableSuppliers">
		</div>