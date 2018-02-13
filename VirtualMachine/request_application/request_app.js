var seqno;

window.addEvent('domready',function() {

    //if($('username').get('value') == 'dywibibam')
    //{
        ListRequests();
   // }
	/*new DatePicker($('service_date'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler1',
    useFadeInOut: !Browser.ie
});*/

	//ListRequests();

     $('vm-request-form').addEvent('submit',function(e) {         
		e.stop();                
                //var date = $('request-date').get('value');
		var action = $('request-action').get('value');
		InsertRequest(this,action);
	});   
        
        $('request-vm').addEvent('click', function() {
            document.getElementById("vm-request-form").reset();
            $('vm-request-div').setStyle('display','block');
            $('md').setStyle('display','none');
        });
        
        $('cancel').addEvent('click', function() {
            window.location.reload();
        });
        
        $('ok').addEvent('click', function() {
            window.location.reload();
        });
        
        
        
         $('okbut22').addEvent('click', function() {
            window.location.reload();
        });
        
        
         $('cancel2').addEvent('click', function() {
            window.location.reload();
        });
        
        $('recomend').addEvent('click', function() {
            sendReccomendation(seqno);
        });
        
        $('get-vm').addEvent('click', function() {
            var id = $('idvm').get('value');
            showRequest(id);
        });
        
        $('approvevm').addEvent('click', function() {
            sendApproval(seqno);
        });
        
        /*$('submit-vm').addEvent('click', function() {
           window.location.reload();
        });*/
        
        /* $('staff-cancel-update').addEvent('click',function() {       
        $('staff-forms').setStyle('display','none');
        $('cards').setStyle('display','block');
        $('md').setStyle('display','block');            
    });
	listStaff();
        
        $('srch-details-form').addEvent('submit',function(e){
		e.stop();
		console.log(this.toQueryString());
		list_users(this.toQueryString());
	});
        
        $('delete-record').addEvent('click', function() {
            var staffno = $('staffNo').get('value');                        
            deleteRecord(staffno);
        });
                
        $('load-data').addEvent('click', function() {
            loadData();

        });
        
         $('upt-ext').addEvent('click', function() {
            var stafno = $('staffNo').get('value');                        
            var ext = $('sExt').get('value');                   
            updateExt(stafno,ext);
        });*/
});

function sendApproval(seqnum){
    var approval = $('approval').getSelected().get('value');
    var comment = $('approval-comment').get('value');
            
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=236&action=send_approval&seqno='+seqnum+'&comment='+comment+'&approval_status=' + approval,
                        method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                     //alert(response);
                     //return false;
                        if (parseInt(response) == 1)
                        {
                                alert('Approval sent succesfully...');
                                window.location.reload();
                        } else if (parseInt(response) == 0) {
                                alert('Error sending approval...');
                                window.location.reload();
                        }                        
            }
        }).send();
}

function sendReccomendation(snum){
    var msg = $('reccomendation').get('value');
            
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=236&action=send_reccomendation&seqno='+snum+'&msg='+msg,
                        method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                     //alert(response);
                     //return false;
                        if (parseInt(response) == 1)
                        {
                                alert('Reccomendation sent succesfully...');
                                window.location.reload();
                        } else if (parseInt(response) == 0) {
                                alert('Error sending reccomendation...');
                                window.location.reload();
                        }                        
            }
        }).send();
}

function loadData(){
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=229&action=load_data',
                        method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                    // alert(response);
                        if (parseInt(response) == -1)
                        {
                                alert('Error loading staff data...');
                        } else if (parseInt(response) == 1) {
                                alert('Staff data successfully loaded.');
                                window.location.reload();
                        }
                        listStaff();
            }
        }).send();
}


function deleteRecord(staffno){
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=229&action=delete_record&staff_no2='+staffno,
                        method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                     //alert(response);
                        if (parseInt(response) == -1)
                        {
                                alert('Error deleting staff data...');
                        } else if (parseInt(response) == 1) {
                                alert('Staff data successfully deleted.');
                                window.location.reload();
                        }
                        listStaff();
            }
        }).send();
}


function updateExt(stafno,ext){
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=229&action=update_ext&staff_no='+stafno+'&ext_no='+ext,
                        method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                     //alert(response);
                        if (parseInt(response) == -1)
                        {
                                alert('Could not update extention number. Please try again.');
                        } else if (parseInt(response) == 1) {
                                alert('Extention number updated.');
                                window.location.reload();
                        }
                        listStaff();
            }
        }).send();
}

function showRequest(id)
{
        var username = $('uname').get('value');
	$('md').setStyle('display','block');
        $('vm-request-div').setStyle('display','none');
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-requests').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=236&action=show_request&loginname='+username+'&id=' + id,
			noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('VM-form').set('html','Time-out error...');
				$('ajax-requests').setStyle('display','none');
				x.cancel();
			},
                onComplete: function(response) {
                    //alert(response);
                    //return false;
		var row = json_parse(response,function(data,text) {
                    
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('ajax-requests').setStyle('display','none');
		$('VM-form').set('html','No data available.');
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
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[11] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[2] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[5]  + '</td>';
			html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
			html = html + rec[6] + '</td>';

			html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
			html = html + rec[19] + '</td>';

                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="staff-id" onclick="editRequest(this.value)" value="';
		html = html + rec[0] + '"</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('VM-form').set('html',html);
		$('ajax-requests').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
}
///////////////////////////////////////////////List Staff////////////////////////////////////////////////////////////////////////////////
function ListRequests() {
    var username = $('uname').get('value');
	$('md').setStyle('display','block');
    $('vm-request-div').setStyle('display','none');
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-requests').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=236&action=list_requests&loginname='+username,
			noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('VM-form').set('html','Time-out error...');
				$('ajax-requests').setStyle('display','none');
				x.cancel();
			},
                onComplete: function(response) {
                    //alert(response);
                    //return false;
		var row = json_parse(response,function(data,text) {
                    
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('ajax-requests').setStyle('display','none');
		$('VM-form').set('html','No data available.');
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
//$data[] = $row->seqno.';'.$row->staffno.';'.$row->username.';'.$row->staffname.';'.$row->faculty.';'.$row->extention.';'.$row->operator.';'.$row->motivation.';'.$row->backuprequirement.';'.$row->retentionperiod.';'.$row->numcopies.';'.$row->requestdate.';'.$row->cpu.';'.$row->ram.';'.$row->harddisk.';'.$row->vnic.';'.$row->os.';'.$row->location.';'.$row->prodtest;

//alert(rec[1]);
//$data[] = $row->requestdate.';'.$row->username .';'.$row->staffname .';'.$row->extention .';'.$row->operator . ';' . $row->faculty;
// $data[] = $row->seqno.';'.$row->staffno.';'.$row->username.';'.$row->staffname.';'.$row->faculty.';'.$row->extention.';'.$row->operator.';'.$row->motivation.';'.$row->backuprequirement.';'.$row->retentionperiod.';'.$row->numcopies.';'.$row->requestdate.';'.$row->cpu.';'.$row->ram.';'.$row->harddisk.';'.$row->vnic.';'.$row->os.';'.$row->location.';'.$row->prodtest;
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[11] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[2] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[5]  + '</td>';
			html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
			html = html + rec[6] + '</td>';

			html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
			html = html + rec[19] + '</td>';

                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="staff-id" onclick="editRequest(this.value)" value="';
		html = html + rec[0] + '"</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('VM-form').set('html',html);
		$('ajax-requests').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
}
///////////////////////////////////////////////List Staff after Searching//////////////////////////////////////////////////////////////
function list_users(data) {
    var card;
	var q = data.split('&');
	var qq = q[0].split('=');
	var qqq = q[1].split('=');
	var scond = qqq[1];
	var id = qq[1];
	$('ajax-staff').setStyle('display','block');	
        var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-staff').setStyle('display','block');
        
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=229&action=list_users&id='+id+'&scond='+scond,
		method: 'get',
		timeout: 10000,
		onTimeout: function() {
			x.cancel();
			$('ajax-staff').setStyle('display','none');
			clearTimeout(t);
			alert('Time-out error from database. Please try again.');
		},
		onComplete: function(response) {
                    //alert(response);
		var row = json_parse(response,function(data,text) {
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('ajax-staff').setStyle('display','none');
		$('staff-form').set('html','No data available.');
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
                if(rec[4])
                         {
                          card = (rec[3]+';'+rec[4]);
                         }
                         else{
                         card = rec[3];
                              }        			 
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[0] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[1] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[2] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + card + '</td>';
			html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="staff-id" onclick="editStaff(this.value)" value="';
		html = html + rec[0] + '"</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('staff-form').set('html',html);
		$('ajax-staff').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
}
///////////////////////////////////////Populated Edit form//////////////////////////////////////////////////////////////////////////////////
function editRequest(id) {
    var j = jQuery.noConflict();
    seqno = id;
    var username = $('uname').get('value');
     // alert(username);
    //var username = 'gqalangilel';
    $('buts').setStyle('display','none');
	$('md').setStyle('display','none');
	$('vm-request-div').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=236&action=edit_request&id='+encodeURI(id),
			method: 'get',
			noCache: true,
			onComplete: function(response){
				//alert(response);

				var r = response.split(';');

//				alert(r[0] + '-' + r[1]+ '-' + r[2]+ '-' + r[3]+ '-' + r[4]+ '-' + r[5]+ '-' + r[5]+ '-' + r[6]+ '-' + r[7]+ '-' + r[8]+ '-' + r[9]+ '-' + r[10]+ '-' + r[11]+ '-' + r[12]+ '-' + r[13] +
//				r[14]+ '-' + r[15]+ '-' + r[16]+ '-' + r[17]+ '-' + r[18]);

				//$('service_date').set('value',r[11]);
                                
                               // $('p_num').prop("readonly", true);
                                document.getElementById('p_num').readOnly = true; 
                                document.getElementById('u_name').readOnly = true; 
                                document.getElementById('p_name').readOnly = true; 
                                document.getElementById('f_name').readOnly = true; 
                                document.getElementById('exten').readOnly = true; 
                                document.getElementById('motivation').readOnly = true; 
                                document.getElementById('back_req').readOnly = true; 
                                document.getElementById('ret_period').readOnly = true; 
                                document.getElementById('num_copies').readOnly = true; 
                                document.getElementById('vm_cpu').readOnly = true; 
                                document.getElementById('ram').readOnly = true; 
                                document.getElementById('hard_disk').readOnly = true;                                 
                                document.getElementById('vm_nic').readOnly = true; 
                                document.getElementById('op_system').readOnly = true; 
                                document.getElementById('location').readOnly = true; 
                                document.getElementById('prod_test').readOnly = true; 
                                document.getElementById('status').readOnly = true; 
                                
				$('p_num').set('value',r[1]);
				$('u_name').set('value',r[2]);
				$('p_name').set('value',r[3]);
				$('f_name').set('value',r[4]);
				$('exten').set('value',r[5]);
				$('motivation').set('value',r[7]);
				$('back_req').set('value',r[8]);
				$('ret_period').set('value',9);

				$('num_copies').set('value',r[10]);
				$('vm_cpu').set('value',r[12]);
				$('ram').set('value',r[13]);
				$('hard_disk').set('value',r[14]);
				$('vm_nic').set('value',r[15]);
				$('op_system').set('value',r[16]);
				$('location').set('value',r[17]);
				$('prod_test').set('value',r[18]);
                                //$('show-status').setStyle('display','block');
                                $('status').set('value',r[19]);
                                
                                $('reccomendation').set('value',r[20]);                               
                                $('approval-comment').set('value',r[21]);
                               
                            $('approval').getElements('option').each(function(el){
					if (el.value == r[19])
					{
						el.selected = true;
                                               //$('option:not(:selected)').prop('disabled', true);
					}
				});
                                
                                if(r[19] == 'Submitted')
                                {
                                    $('show-reccomendation').setStyle('display','block');
                                    $('show-recbut').setStyle('display','block');
                                    $('show-approval-comment').setStyle('display','none');                                
                                }        
                                else if ((r[19] == 'Pending Approval') && (username =='gqalangilel'))
                                {
                                  
                                   document.getElementById('reccomendation').readOnly = true; 
                                    $('show-reccomendation').setStyle('display','block');
                                    $('okbut22').setStyle('display','block');
                                    $('show-recbut').setStyle('display','none');
                                    $('show-approval-comment').setStyle('display','none');                                       
                                    $('show-approval-but').setStyle('display','none');   
                                    
                                    
                                    
                                }
                                else if(r[19] == 'Approved')
                                {
                                    document.getElementById('reccomendation').readOnly = true; 
                                    document.getElementById('approval-comment').readOnly = true; 
                                    $('show-recbut').setStyle('display','none');
                                     $('show-reccomendation').setStyle('display','block');
                                     $('show-approval-comment').setStyle('display','block');                                
                                     $('show-approval-but').setStyle('display','none');   
                                     //$('approval').prop("readOnly",true);
                                     $('okbut22').setStyle('display','block');
                                     //$('approval').setStyle('display','block');
                                     
                                      j("#approval").prop("disabled", true);       
                                
                              //  $('approval').attr("disabled", true);
                                     
                                     
                                }
                               
                                else if(r[19] == 'Rejected')
                                {
                                    document.getElementById('reccomendation').readOnly = true; 
                                    document.getElementById('approval-comment').readOnly = true; 
                                    $('show-reccomendation').setStyle('display','block');
                                    $('show-approval-comment').setStyle('display','block');
                                    j("#approval").prop("disabled", true);       
                                    $('okbut22').setStyle('display','block');
                                }
                                
                                  if ((r[19] == 'Pending Approval') && (username == 'dywibibam'))
                                {              //alert(username);       
                                    document.getElementById('reccomendation').readOnly = true; 
                                    $('show-reccomendation').setStyle('display','block');
                                    $('show-approval-comment').setStyle('display','block');
                                    $('okbut22').setStyle('display','none');
                                    $('show-approval-but').setStyle('display','block');   
                                }
                           /*if(username = 'dywibibam')
                                {
                                    $('show-recbut').setStyle('display','none');
                                    $('show-approval-comment').setStyle('display','block');                                
                                    $('show-approval-but').setStyle('display','block');                                    
                                }*/
                                
		}
	}).send();
}
/////////////////////////////////////Save Editted form////////////////////////////////////////////////////////////////////////////////////
function InsertRequest(frm,action) {
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=236&action=insert_request',
                        method: 'post',
                        noCache: true,
                        data: frm,
                    onComplete: function(response){
                     //alert(response);
                    if (parseInt(response) == 1){
                            alert('VM Request added successfully.');
                            window.location.reload();
                        }
                        else if (parseInt(response) == 0)
                        {
                            alert('Error inserting request.');
                           // window.location.reload();
                        }
                        else if (parseInt(response) == 2)
                        {
                            alert('Staff member already exist.');
                            window.location.reload();
                        }
                        //window.location.reload();
                        //listStaff();
            }
        }).send();
}