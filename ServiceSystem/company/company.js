window.addEvent('domready',function() 
{   
     $('add-new').addEvent('click', function() {
         $('edit_rec').setStyle('display','none');
        $('add_rec').setStyle('display','block');
            openForm();
        });
    
     $('companyForm').addEvent('submit',function(e) {         
		e.stop();
		var action = $('company-action').get('value');
		saveCompany(this,action);
	});   
        $('company-cancel-update').addEvent('click',function() {       
        $('company-forms').setStyle('display','none');
        $('mainDiv').setStyle('display','block'); 
        $('addNewCompany').setStyle('display','block');
        });
        listCompany();
        
        $('delete-record').addEvent('click', function() {
            var compid = $('coID').get('value');                        
            deleteRecord(compid);
        }); 
        
        $('company-edit').addEvent('click', function() {
            var coid = $('coID').get('value'); 
            var cname = $('companyName').get('value');  
            var cnum = $('contactNumber').get('value');  
            var eml = $('email').get('value');
            recordEdit(coid,cname,cnum,eml);
        }); 
});
function saveCompany(frm,action) {    
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=231&action=save_company',
                             method: 'post',
                        noCache: true,
                        data: frm,
                    onComplete: function(response){
                    // alert(response);
                    if (parseInt(response) == -1){
                                alert('Company saved successfully.');
                               // window.location.reload();
                        }
                        else if (parseInt(response) == 0)
                        {
                                alert('Company not saved.');
                                //window.location.reload();
                        } 
                        window.location.reload();
            }
        }).send();
}
///////////////////////////////////////////////List Product////////////////////////////////////////////////////////////////////////////////
function listCompany() {
    $('company-forms').setStyle('display','none');
    	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-company').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=231&action=display_companyData',
			noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('company-form').set('html','Time-out error...');
				$('ajax-company').setStyle('display','none');
				x.cancel();
			},
                onComplete: function(response) {
		var row = json_parse(response,function(data,text) {
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('ajax-company').setStyle('display','none');
		$('company-form').set('html','No data available.');
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
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[1] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[2] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="company-id" onclick="editCompany(this.value)" value="';
		html = html + rec[0] + '"</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('company-form').set('html',html);
		$('ajax-company').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
        }
        function openForm() {
           // var card;
                document.getElementById('companyName').value = "";
                document.getElementById('contactNumber').value = "";
                document.getElementById('email').value = "";
                $('addNewCompany').setStyle('display','none');
                $('mainDiv').setStyle('display','none');
                $('company-forms').setStyle('display','block');
        }
///////////////////////////////////////Populated edit form//////////////////////////////////////////////////////////////////////////////////
        function editCompany(id) {
            var card;
                $('addNewCompany').setStyle('display','none');
                $('mainDiv').setStyle('display','none');
                $('company-forms').setStyle('display','block');
                $('edit_rec').setStyle('display','block');
                $('add_rec').setStyle('display','none');
                var x = new Request({
                        url: 'index.php?option=com_jumi&fileid=231&action=edit_company&id='+encodeURI(id),
                                method: 'get',
                                noCache: true,
                                onComplete: function(response){
                                   // alert(response);
                                        var r = response.split(';');
                                        $('coID').set('value',r[0]);
                                        $('companyName').set('value',r[1]);
                                        $('contactNumber').set('value',r[2]);
                                        $('email').set('value',r[3]);				
                        }
                }).send();
}
/////////////////////////////////////////////// delete product data//////////////////////////////////////////////////////////////////////////////////
function deleteRecord(compid){
   // var card;
        $('addNewCompany').setStyle('display','none');
	$('mainDiv').setStyle('display','none');
	$('company-forms').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=231&action=delete_company&comp_id='+compid,
			method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                    // alert(response);
                        if (parseInt(response) == -1)
                        {
                                alert('Error deleting company data...');
                        } else if (parseInt(response) == 1) {
                                alert('Company data successfully deleted.');
                                window.location.reload();
                        }
                        window.location.reload();
            }
        }).send();
}
/////////////////////////////////////////////// edit company data//////////////////////////////////////////////////////////////////////////////////
function recordEdit(coid,cname,cnum,eml) {
        $('addNewCompany').setStyle('display','none');
	$('mainDiv').setStyle('display','none');
	$('company-forms').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=231&action=company_edit&coid='+coid+'&cname='+cname+'&cnum='+cnum+'&eml='+eml,
			method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                     alert(response);
                        if (parseInt(response) == -1)
                        {
                                alert('Error updating company data...');
                        } else if (parseInt(response) == 1) {
                                alert('Company data successfully updated.');
                                window.location.reload();
                        }
                        window.location.reload();
            }
        }).send();
}