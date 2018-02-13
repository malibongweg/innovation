window.addEvent('domready',function() 
{   
     $('add-new').addEvent('click', function() {
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
            var staffno = $('staffNo').get('value');                        
            deleteRecord(staffno);
        });    
});
function saveCompany(frm,action) {    
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=254&action=save_company',
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
                       // listStaff();
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
		url: 'index.php?option=com_jumi&fileid=254&action=display_companyData',
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
        ///////////////////////////////////////Populated delete form//////////////////////////////////////////////////////////////////////////////////
        function editCompany(id) {
            var card;
                $('addNewCompany').setStyle('display','none');
                $('mainDiv').setStyle('display','none');
                $('company-forms').setStyle('display','block');
                var x = new Request({
                        url: 'index.php?option=com_jumi&fileid=254&action=edit_company&id='+encodeURI(id),
                                method: 'get',
                                noCache: true,
                                onComplete: function(response){
                                   // alert(response);
        //$data[] = $row->unittype.";".$row->model.";".$row->serialno.";".$row->address;
                                        var r = response.split(';');
                                        $('companyName').set('value',r[0]);
                                        $('contactNumber').set('value',r[1]);
                                        $('email').set('value',r[2]);
                                       // $('address').set('value',r[3]);				
                        }
                }).send();
        
}