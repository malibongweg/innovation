window.addEvent('domready',function() {    
    
    listChangeRequests();
    $('boss_rec').setStyle('display','none');
    $('cancel').addEvent('click',function() { 
        window.location.reload();
    });
    $('add-new').addEvent('click', function() {
        $('edit_rec').setStyle('display','none');
        $('head').setStyle('display','none');
        $('add_rec').setStyle('display','block');
            openForm();
    });   
    $('get-details').addEvent('click', function() {
        var id = $('srch').get('value'); 
         searchChange(id);
    });   
    $('changeForm').addEvent('submit',function(e) {
        var action = $('change-action').get('value');
		e.stop();
		saveChange(this,action);
    }); 
    $('aprove-record').addEvent('click', function() {
            var logid = $('logID').get('value');  
            var approval = $('approval').getSelected().get('value');
            var comment = $('comment').get('value');
            approveRecord(logid,approval,comment);
        });
    $('change-edit').addEvent('click', function() {
            var logid = $('logID').get('value');  
            var op = $('changeOperator').get('value');  
            var comm1 = $('comment1').get('value');          
            recordEdit(logid,comm1,op);
           // document.getElementById("approval").disabled = true;
        });  
});
////////////////////////////////////////////////////Search Change ID ////////////////////////////////////////////////////////////////////////////////////////////////////////
function searchChange(id) {
 var card;
    $('change-forms').setStyle('display','none');
    $('cards').setStyle('display','none');
    	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-change').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=242&action=display_changeSearch&id='+id,
			noCache: true,
			method: 'get',
                onComplete: function(response) {

		var m = cnt % 2;
		if (m == 1)
		{
                	var color = color1;
		} else {
			var color = color2;
			}  
var rec = response.split(';');
//alert(rec[1]);
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[0] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[1] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[2] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[4] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[5] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[6] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="change-id" onclick="editChange(this.value)" value="';
		html = html + rec[0] + '"</td>';
		html = html + '</tr>';
		++cnt;
		//	}
		//	}
		//});
		html = html + "</table>";
		if (fnd == true)
		{
		$('change-form').set('html',html);
		$('ajax-change').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
}

/////////////////////////////////////////////// edit change data//////////////////////////////////////////////////////////////////////////////////
function recordEdit(logid,comm1,op) {
        $('cards').setStyle('display','none');
	$('md').setStyle('display','none');
	$('change-forms').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=242&action=change_edit&log_id='+logid+'&comm1='+comm1+'&op='+op,
			method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                     //alert(response);
                        if (parseInt(response) == -1)
                        {
                                alert('Error updating product data...');
                        } else if (parseInt(response) == 1) {
                                alert('Product data successfully updated.');
                            //    window.location.reload();
                        }
                        window.location.reload();
            }
        }).send();
}
/////////////////////////////////////////////// Delete Change data//////////////////////////////////////////////////////////////////////////////////
function approveRecord(logid,approval,comment) {
   // var card;
        $('cards').setStyle('display','none');
	$('md').setStyle('display','none');
	$('change-forms').setStyle('display','block');
        $('edit_rec').setStyle('display','block');
        $('add_rec').setStyle('display','none');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=242&action=approve_change&log_id='+logid+'&approval='+approval+'&comment='+comment,
			method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                        if (parseInt(response) == -1)
                        {
                                alert('Error deleting product data...');
                        } else if (parseInt(response) == 1) {
                                alert('Product data successfully deleted.');
                                //window.location.reload();
                        }
                        window.location.reload();
            }
        }).send();
       $('cards').setStyle('display','none'); 
}
////////////////////////////////////////////////Save Change Record///////////////////////////////////////////////////////////////////////////////////
function saveChange(frm,action) { 
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=242&action=save_change',
                        method: 'post',
                        noCache: true,
                        data: frm,
                    onComplete: function(response){
                     //alert(response);
                    if (parseInt(response) == 1){
                                alert('Product saved successfully.');
                              //  window.location.reload();
                        }
                        else if (parseInt(response) == -1)
                        {
                                alert('Product not saved.');
                             //   window.location.reload();
                        } 
                        window.location.reload();
            }
        }).send();
}
///////////////////////////////////////////////listChangeRequests////////////////////////////////////////////////////////////////////////////////
function listChangeRequests() {
    var card;
    $('change-forms').setStyle('display','none');
    $('cards').setStyle('display','none');
    	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-change').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=242&action=display_changeData',
			noCache: true,
			method: 'get',
                onComplete: function(response) {
                 //  alert(response);
		var row = json_parse(response,function(data,text) {
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('ajax-change').setStyle('display','none');
		$('change-form').set('html','No data available.');
		    fnd = false;
		}else {
                    fnd = true;
		var rec = text.split(';');
		var m = cnt % 2;
		if (m == 1)
		{
                	var color = color1;
		} else {
			var color = color2;
			}                                        
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[0] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[1] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[2] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[4] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[5] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[6] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="change-id" onclick="editChange(this.value)" value="';
		html = html + rec[0] + '"</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('change-form').set('html',html);
		$('ajax-change').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
}
///////////////////////////////////////Populated  form//////////////////////////////////////////////////////////////////////////////////
function editChange(id) {
        $('cards').setStyle('display','block');
        $('approvals').setStyle('display','none');
	$('md').setStyle('display','none');
	$('change-forms').setStyle('display','block');
        $('head').setStyle('display','none');
        $('edit_rec').setStyle('display','block');
        $('boss_rec').setStyle('display','none');
         $('add_rec').setStyle('display','none');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=242&action=edit_change&id='+encodeURI(id),
			method: 'get',
			noCache: true,
			onComplete: function(response){
                            //alert(response);
				var r = response.split(';');
                                $('logID').set('value',r[0]);
				$('personnelNo').set('value',r[1]);
				$('pName').set('value',r[2]);
                                $('fac_name').set('value',r[3]);
                                $('extention').set('value',r[4]);
				$('vmName').set('value',r[5]);
                                $('reason').set('value',r[6]);	
                                $('vcpu').set('value',r[7]);
				$('ram').set('value',r[8]);
                                $('hardDisk').set('value',r[9]);
				$('vnic').set('value',r[10]);
                                $('location').set('value',r[11]);
                                $('prod').set('value',r[12]);
                                $('backup').set('value',r[13]);
                                $('comment1').set('value',r[14]);
                                $('comment').set('value',r[15]);
                                var coment = r[14];
                                document.getElementById("personnelNo").readOnly = true;
                                document.getElementById("pName").readOnly = true;
                                document.getElementById("fac_name").readOnly = true;
                                document.getElementById("extention").readOnly = true; 
                                document.getElementById("vmName").readOnly = true;
                                document.getElementById("reason").readOnly = true;
                                document.getElementById("vcpu").readOnly = true;
                                document.getElementById("ram").readOnly = true;
                                document.getElementById("hardDisk").readOnly = true;
                                document.getElementById("vnic").readOnly = true;
                                document.getElementById("location").readOnly = true;
                                document.getElementById("prod").readOnly = true;
                                document.getElementById("backup").readOnly = true;   
                                
                if (coment != '')
		{
		$('approvals').setStyle('display','block');
                $('edit_rec').setStyle('display','none');
                $('boss_rec').setStyle('display','block');
		}
		}
	}).send();
}
///////////////////////////////////////////////////////New Form///////////////////////////////////////////////////////////////////////
function openForm() {
        document.getElementById('personnelNo').value = "";
        document.getElementById('pName').value = "";
        document.getElementById('fac_name').value = "";
        document.getElementById('vmName').value = "";
        document.getElementById('reason').value = "";
        document.getElementById('vcpu').value = "";
        document.getElementById('ram').value = "";
        document.getElementById('extention').value = "";
        document.getElementById('hardDisk').value = "";
        document.getElementById('vnic').value = "";
        document.getElementById('location').value = "";
        document.getElementById('prod').value = "";
        document.getElementById('backup').value = "";
        $('cards').setStyle('display','none');
	$('md').setStyle('display','none');
	$('change-forms').setStyle('display','block');
}
