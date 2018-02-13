window.addEvent('domready',function() {

    $('student-data').addEvent('submit',function(e) {
		e.stop();
		var action = $('student-action').get('value');
		saveStudent(this,action);
	});
        
    $('student-cancel-update').addEvent('click',function() {
        
        $('student-pop').setStyle('display','none');
        $('filter-block').setStyle('display','block');
        $('md').setStyle('display','block');            
            
    });
    
    listStudents();
        
    $('srch-details-form').addEvent('submit',function(e){             
		e.stop();
		console.log(this.toQueryString());
		list_filtered_students(this.toQueryString());	
    });
	
});

function list_filtered_students(data) {
    var card;
    $('student-form').set('html','');
	var q = data.split('&');
	var qq = q[0].split('=');
	var qqq = q[1].split('=');
        
        var id = qq[1];        
	var scond = qqq[1];
	
        var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-students').setStyle('display','block');
	
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=221&action=list_filtered_users&id='+id+'&filter='+scond+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 10000,
		onTimeout: function() {
			x.cancel();
			$('ajax').setStyle('display','none');
			clearTimeout(t);
			alert('Time-out error from database. Please try again.');
		},
			onComplete: function(response) {
                            if(response == -1)
                            {
                                html = html + 'No data available.';	
                                $('ajax-students').setStyle('display','none');
                                $('student-form').set('html','No data available.');
                                return false;
                             }
                                var row = json_parse(response,function(data,text) {
                                if (typeof text == 'string') {
                                        if (parseInt(text) == -1)
                                        {
                                                $('ajax-students').setStyle('display','none');
                                                
                                                $('student-form').set('html','No data available.');
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
                                                html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                                                html = html + rec[0] + '</td>';
                                                html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                                                html = html + rec[1] + '</td>';
                                                html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                                                html = html + rec[2] + '</td>';
                                                html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                                                html = html + card + '</td>';								
                                                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="std_no" id="std-no" onclick="editStudent(this.value)" value="';
                                                html = html + rec[0] + '"</td>';
                                                html = html + '</tr>';

                                                ++cnt;
                                        }
                        }
				});
				html = html + "</table>";
				if (fnd == true)
				{
					$('student-form').set('html',html);
					$('ajax-students').setStyle('display','none');
					//$('student-data').setStyle('display','none');
					$('header-details').setStyle('display','block');
				}
			}
	}).send();
}

function listStudents() {
    var card;
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-students').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=221&action=display_students',
			noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('student-form').set('html','Time-out error...');
				$('ajax-students').setStyle('display','none');
				x.cancel();
			},
				onComplete: function(response) {
                                    var card;
                                   // alert('response');
						var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								$('ajax-students').setStyle('display','none');
								$('student-form').set('html','No data available.');
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
								html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
								html = html + rec[0] + '</td>';
								html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
								html = html + rec[1] + '</td>';
								html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
								html = html + rec[2] + '</td>';
								html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
								html = html + card + '</td>';								
                                                                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="student_no" id="student-no" onclick="editStudent(this.value)" value="';
								html = html + rec[0] + '"</td>';
								html = html + '</tr>';

								++cnt;
							}
					}
				});
				html = html + "</table>";
				if (fnd == true)
				{
					$('student-form').set('html',html);
					$('ajax-students').setStyle('display','none');
					//$('student-data').setStyle('display','none');
					$('header-details').setStyle('display','block');
				}
			}
	}).send();
}

function editStudent(id) {
    var card;
	$('student-action').set('value','edit');
	$('filter-block').setStyle('display','none');
        $('md').setStyle('display','none');
	$('student-pop').setStyle('display','block');
	var x = new Request({
            url: 'index.php?option=com_jumi&fileid=221&action=edit_student&id='+encodeURI(id),
            method: 'get',
            noCache: true,
            onComplete: function(response){
                //alert(response);             
                    var r = response.split(';');                                
                    
                     if(r[8])
                    {
                        card = (r[7]+';'+r[8]);
                    }
                    else{
                        card = r[7];
                    }
                            //echo $row->STDNO.';'. $row->FULLNAME . ';' . $row->DESCRIPTION . ';' . $row->FACULTYNAME . ';' . $row->DEPTNAME . ';' . $row->MAGSTRIPE. ';' . $row->BARCODE. ';' . $row->CARDNO2;;          
                    $('f-name').set('value',r[1]);
                    $('std-no').set('value',r[0]);
                    $('email-id').set('value',r[0] + '@mycput.ac.za');
                    $('qualification-id').set('value',r[2]);
                    $('faculty-id').set('value',r[3]);
                    $('department-id').set('value',r[4]);  
                    $('magstrip-id').set('value',r[5]); 
                    $('barcode-id').set('value',r[6]); 
                    $('card-no').set('value',card);				
            }
	}).send();
}

function saveStudent(frm,action) {    

    if (action == 'edit')
    {       
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=221&action=save_edit_student',
                        method: 'post',
                        noCache: true,
                        data: frm,
                    onComplete: function(response){
                        //alert(response);
                         if (response == 1)
                            {                              
                                    alert('Student Card number updated successfully.');
                                window.location.reload();                              
                          
                        }
                        else if (response == 0)
                        {
                                alert('Card number asigned to another user.');
                                 window.location.reload();
                        }
                           
                      
            }
        }).send();
    } 
}

