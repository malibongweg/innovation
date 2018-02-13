var seqno;

window.addEvent('domready',function() {
     ListServers();
     
    $('add-server-form').addEvent('submit',function(e) {        
        e.stop();                        
        var action = $('server-action').get('value');
        InsertServer(this,action);
    });
    
    $('add-server').addEvent('click', function() {
    $('buts').setStyle('display','block');        
    $('update_buts').setStyle('display','none');            
    
        $('md').setStyle('display','none');
        $('add-server-div').setStyle('display','block');        
    });
    
    $('update-server').addEvent('click',function() {          
        var id = $('server_id').get('value');
        var ip = $('ip_address').get('value');
        var server_name = $('server_name').get('value');
        var cost_centre = $('cost_centre').get('value');
        var status = $('status').getSelected().get('value');
        UpdateServer(id, ip, server_name, cost_centre, status);
    }); 
    
    $('cancel').addEvent('click', function() {
        window.location.reload();
    });
    
    $('update-cancel').addEvent('click', function() {
        window.location.reload();
    });
    
    $('delete-server').addEvent('click',function() {             
        var id = $('server_id').get('value');        
        DeleteServer(id);
    }); 
    
    $('get-id').addEvent('click', function() {
            var id = $('server-id').get('value'); 
            ShowRequest(id);
        });
        
});

function ListServers() {        
    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
    $('ajax-requests').setStyle('display','block');
    
    var x = new Request ({
            url: 'index.php?option=com_jumi&fileid=246&action=list_servers',
                    noCache: true,
                    method: 'get',
                    timeout: 5000,
                    onTimeout: function() {
                        $('sms-info').set('html','Time-out error...');
                        $('ajax-requests').setStyle('display','none');
                        x.cancel();
                    },
            onComplete: function(response) {                
                var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                if (parseInt(text) == -1)
                {
                    $('ajax-requests').setStyle('display','none');
                    $('sms-info').set('html','No data available.');
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
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                html = html + rec[0] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                html = html + rec[1] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 40%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                html = html + rec[2] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                html = html + rec[3]  + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                html = html + rec[4] + '</td>';            
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="staff-id" onclick="editServer(this.value)" value="';
                html = html + rec[0] + '"</td>';
                html = html + '</tr>';
                ++cnt;
                }
               }
            });
            html = html + "</table>";
            if (fnd == true)
            {
            $('sms-info').set('html',html);
            $('buts').setStyle('display','none');        
            $('update_buts').setStyle('display','none');        
            
            $('ajax-requests').setStyle('display','none');
            $('header-details').setStyle('display','block');
            }
    }
    }).send();
}

function InsertServer(frm,action) {
    $('buts').setStyle('display','block');        
    $('update_buts').setStyle('display','none');            
            
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=246&action=insert_server',
                        method: 'post',
                        noCache: true,
                        data: frm,
                    onComplete: function(response){    
                        
                    if (parseInt(response) == 1){
                            alert('SMS Server added successfully.');
                            window.location.reload();
                        }
                        else if (parseInt(response) == 0)
                        {
                            alert('Error inserting SMS Server.');
                            window.location.reload();
                        }                        
            }
        }).send();
}

function editServer(id) {
    
    var j = jQuery.noConflict();     
	$('md').setStyle('display','none');
        $('add-server-div').setStyle('display','block');        
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=246&action=edit_server&id='+encodeURI(id),
			method: 'get',
			noCache: true,
			onComplete: function(response){
				var r = response.split(';');
                                //echo $row->ID.';'.$row->IPADDRESS.';'.$row->SERVERNAME.';'.$row->COSTCENTRE.';'.$row->STATUSYN;
                                //document.getElementById('p_num').readOnly = true; 
                                $('serverid').setStyle('display','block');
				$('server_id').set('value',r[0]);
                                document.getElementById('server_id').readOnly = true; 
                                
				$('ip_address').set('value',r[1]);
				$('server_name').set('value',r[2]);
				$('cost_centre').set('value',r[3]);
				
                                $('status').getElements('option').each(function(el){
					if (el.value == r[4])
					{
						el.selected = true;                                               
					}
				});
                                                              
		}
	}).send();
        
        $('buts').setStyle('display','none');
        $('update_buts').setStyle('display','block');
        
}

function UpdateServer(id, ip, server_name, cost_centre, status)
{
    $('buts').setStyle('display','none');        
    $('update_buts').setStyle('display','block');            
    
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=246&action=update_server&id='+id+'&ip='+ip+'&server_name='+server_name+'&cost_centre='+cost_centre+'&status='+status,
        method: 'get',
        noCache: true,
        onComplete: function(response){
            //alert(response);             
             if (response == 1)
                    {                              
                        alert('SMS Server updated successfully.');
                        window.location.reload();
                }
                else if (response == 0)
                {
                        alert('Error updating SMS server.');
                         window.location.reload();
                }                
        }
    }).send();
}

function DeleteServer(id)
{
    $('buts').setStyle('display','none');        
    $('update_buts').setStyle('display','none');        
            
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=246&action=delete_server&id='+id,
        method: 'get',
        noCache: true,
        onComplete: function(response){
            
             if (response == 1)
                    {                              
                        alert('SMS Server deleted successfully.');
                        window.location.reload();
                }
                else if (response == 0)
                {
                        alert('Error deleting SMS server.');
                         window.location.reload();
                }
                ListServers();
        }
    }).send();
}

function ShowRequest(id) {    
    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
    $('ajax-requests').setStyle('display','block');
    
    var x = new Request ({
            url: 'index.php?option=com_jumi&fileid=246&action=show_server&id='+id,
                    noCache: true,
                    method: 'get',
                    timeout: 5000,
                    onTimeout: function() {
                        $('sms-info').set('html','Time-out error...');
                        $('ajax-requests').setStyle('display','none');
                        x.cancel();
                    },
            onComplete: function(response) {                
                if(response == -1)
                {
                    $('ajax-requests').setStyle('display','none');
                    $('sms-info').set('html','No data available ...');
                    return false;
                }
                var m = cnt % 2;
		if (m == 1)
		{
                    var color = color1;
		} else {
                    var color = color2;
		}
                var rec = response.split(';');
                
                html = html + '<tr>';							
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                html = html + rec[0] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                html = html + rec[1] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 40%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                html = html + rec[2] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                html = html + rec[3]  + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                html = html + rec[4] + '</td>';            
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="staff-id" onclick="editServer(this.value)" value="';
                html = html + rec[0] + '"</td>';
                html = html + '</tr>';
  
            html = html + "</table>";

            $('sms-info').set('html',html);
            $('buts').setStyle('display','none');        
            $('update_buts').setStyle('display','none');        
            
            $('ajax-requests').setStyle('display','none');
            $('header-details').setStyle('display','block');
            
    }
    }).send();
}