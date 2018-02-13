window.addEvent('domready',function() {
    
     $('staffForm').addEvent('submit',function(e) {
         
		e.stop();
		var action = $('staff-action').get('value');
		saveStaff(this,action);
	});
        
         $('staff-cancel-update').addEvent('click',function() {
        
        $('staff-forms').setStyle('display','none');
        $('cards').setStyle('display','block');
        $('md').setStyle('display','block');
            //listStudents();
             //window.location.reload();
            
    });

	listStaff();
        
        $('srch-details-form').addEvent('submit',function(e){
		e.stop();
		console.log(this.toQueryString());
		list_users(this.toQueryString());
	});
});
///////////////////////////////////////////////List Staff////////////////////////////////////////////////////////////////////////////////
function listStaff() {
    var card;

    $('staff-forms').setStyle('display','none');
    	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-staff').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=223&action=display_staffData',
			noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('staff-form').set('html','Time-out error...');
				$('ajax-staff').setStyle('display','none');
				x.cancel();
			},
                onComplete: function(response) {
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
		//$('staff-data').setStyle('display','none');
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
        //alert("id = " + id + '   filter = ' + scond);
        
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=223&action=list_users&id='+id+'&scond='+scond,
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
		//$('staff-data').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
}
///////////////////////////////////////Populated Edit form//////////////////////////////////////////////////////////////////////////////////
function editStaff(id) {
        var card;
	//$('vfd-action').set('value','edit');
        $('cards').setStyle('display','none');
	$('md').setStyle('display','none');
	$('staff-forms').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=223&action=edit_staff&id='+encodeURI(id),
			method: 'get',
			noCache: true,
			onComplete: function(response){
//echo $row->STAFFNO.';'. $row->TITLE . ';' . $row->LOGIN_NAME . ';' . $row->USERNAME . ';' . $row->EMAIL.';'. $row->AKA . ';' . $row->FACULTY . ';' . $row->DEPARTMENT.';'. $row->PCAMPUS . ';' . $row->PEXTENTION . ';' . $row->CARDNO2;           
				var r = response.split(';');
                   if(r[11])
                    {
                        card = (r[10]+';'+r[11]);
                    }
                    else{
                        card = r[10];
                    }
				$('staffNo').set('value',r[0]);
                                $('sTitle').set('value',r[1]);
				$('fName').set('value',r[2]);
                                $('uName').set('value',r[3]);
				$('sEmail').set('value',r[4]);
				$('sAKA').set('value',r[5]);
				$('sFac').set('value',r[6]);
				$('sDept').set('value',r[7]);
				$('sCampus').set('value',r[8]);
                                $('sExt').set('value',r[9]);
                                $('cardNo').set('value',card);
				
		}
	}).send();
}
/////////////////////////////////////Save Editted form////////////////////////////////////////////////////////////////////////////////////
function saveStaff(frm,action) {    
  //alert('here');
    //if (action == 'edit')
    //{      //alert('here'); 
        var x = new Request({
           
                url: 'index.php?option=com_jumi&fileid=223&action=save_edit_staff',
                        method: 'post',
                        noCache: true,
                        data: frm,
                    onComplete: function(response){
                     
                        if (parseInt(response) == -1)
                        {
                                alert('Error saving student data...');
                        } else {
                                alert('Staff data saved.');
                                window.location.reload();
                        }
                        listStaff();
            }
        }).send();
    //} 
}