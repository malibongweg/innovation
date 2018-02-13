var html = '<table width="100%" border="0" style="table-layout: fixed">';
var  subcode;
var GradeSeqNo, schoolcode, studNo;

window.addEvent('domready',function() {
    var numbers = [8,48,49, 50, 51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105];
        $$('.numeric').each(function(item) {
            item.addEvent('keydown', function(key) {
                for (i = 0; i < numbers.length; i++) {
                    if(numbers[i] == key.code) {
                        return true;
                    }
                }
                return false;
            });
        });

        
        //This is the first function that is called in the js
        //it populates the student details that is logged in 
    displayInfo();

    //displayInfo2();

    $('select-school').addEvent('change', function(e) {
        var schoolname = $('select-school').get('value');
        //alert(schoolname); 
       listSubs(schoolname);
    });

    $('subs-offered').addEvent('change', function(e) {
        var schoolname = $('select-school').get('value');
        var subname = $('subs-offered').get('value');
        listGrades(schoolname, subname);
    });

    /*$('grade').addEvent('change', function(e) {
        var schoolname = $('select-school').get('value');
        var subname = $('subs-offered').get('value');
        listGrades(schoolname, subname);
    });*/

   // $('subs-offered').addEvent('change', function(e) {
        //var subj = $('subs-offered').get('value');
        //listGrades(subj);
        //var scname = $('select-school').get('value');
        //var subcode = $('subs-offered').get('value');
        //alert(schoolname);
        // (subcode);
    //});

    $('add-school').addEvent('click',function(){
       // window.location.reload();
    });
    // submit is clicked and criteria-form is submitted
    $('criteria-form').addEvent('submit', function(e){
       //var grade_desc = $('grade').get('value');
        //alert(response);
         e.stop();
         var x = new Request({
             //A request is sent to the fileid 213 which is the student_ajax.php file aand the action it will execute is the save_criteria that executes sql statements

             url: 'index.php?option=com_jumi&fileid=213&action=save_criteria',
             method: 'post',
             data: this, //$('criteria-form')
             async: false,
             
             //Remember a request was sent and a response is expected once its complete it must do something
             onComplete: function(response){
                 //alert(response);
                 //this parses the response coming from the student_ajax.php to data and uses it
                 //var data = json_parse(response);

                    //error checking
                 if (response == "-1") {
                        $('ajax').setStyle('display','none');
                        alert('Student already exist.');
                 }else if (response == "-2"){
                     $('ajax').setStyle('display','none');
                        alert('Criteria not added'+'\n'+'Please try again.');
                 }               
                     else if (response=='2') {
                     alert('Student already chosen the selection');
                 }
                 else if(response == '4')
                 {
                     alert('subject/grade combination not available for school.');
                 }
                 else if (response=='-7') {
                     alert('Student can teach at one school only');
                 }
                 
                 else{
                     //alert(response);
//alert(response);

           
                    var subid = response;
//alert(sub); 
                             addSubject2(subid);
                            // $('grade').empty();
                             // new Element('option',{ 'value':r[2],'text':r[3]}).inject($('grade'));

                         }
                    
                                //for (var i = 0 ; i < data.Records.length; ++i){

                                //}

                                //if the response comes back with correct data this function below is executed
                               //addSubject();

                        //add subject with seqno from the SchoolSubs table.

                            
             }
        }).send();
    });
      
         //addSubject();
});

function addSubject2(sub){
    $subject = $('subs-offered').getSelected().get('value');
    $grade = $('grade').getSelected().get('value');
    //$language = $$('input[name=lang]:checked').get('value');

    /*subs = SubCode;
     grade = GradeSeqNo;
     scode = schoolcode;
     snum = studNo;
     alert($grade + ' ' + $subject + ' ' + $language);
     '<td>'+data.Record[i].stud_numb+'</td>'*/

    html = html + '<tr>';
    html = html + '<td style="overflow: hidden; font-size: 10px; width: 50%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
    html = html + $subject + '</td>';
    html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
    html = html + $grade + '</td>';
    html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000"><input type="radio" name="sub_id" id="sub-id" onclick="deleteSubject(this.value)" value="';
    html = html +sub+ '"</td>';
    html = html + '</tr>';

    $('sub-list-data').set('html',html);
}

/*this function populates the top part of the screen with data once the action display_stud_info is envoked 
it checks the userid that is equals to the login-name and populates the field based on that */
function displayInfo() {
    $.noConflict();
    $('ajax').setStyle('display','block');
    
    // a request is sent through with the action display_stud_info and also checks the person that logged and and will do the request as per the uid
	var y = new Request({
		url: 'index.php?option=com_jumi&fileid=213&action=display_stud_info&uid='+$('login-name').value,
        noCache: true,
		method: 'get',
		timeout: 15000,
		onTimeout: function() {
            $('ajax').setStyle('display','none');
            alert('Error retrieving details.'+'\n'+'Please try later.');
            y.cancel();
		},
        onComplete: function(response) {
           // alert(response);
          //once a response comes back it is passed to json format
            var data = json_parse(response);
            if (data.Result == 'ERR')
            {
                $('ajax').setStyle('display','none');
                alert('Error retrieving details.'+'\n'+'Please try later.');
            } else {
                    //for (var i = 0 ; i < data.Records.length; ++i){
                    //}
                    //addSubject(data);
                   //this is when a response comes back and and populates the the fields based on the student that logged in

                    $('stud-nr').set('value',data.Record[0].stud_numb);
                    $('email-address').set('value',data.Record[0].email);
                    $('f-name').set('value',data.Record[0].fullname);
                    $('s-qual').set('value',data.Record[0].qual_desc);
                    $('s-ot').set('value',data.Record[0].ot);
                    $('s-type').set('value',data.Record[0].stype);
                    $('s-phase').set('value',data.Record[0].phase);
                    $('s-level').set('value',data.Record[0].level);
                    $('r-date').set('value',data.Record[0].reg_date);
             
                    
                    var stno= data.Record[0].stud_numb;
                    
                    showSubjects(stno);
                                     
            }
            $('ajax').setStyle('display','none');
            
            
        }
	}).send();
        
}

function showSubjects(stno)
{   
    ////////////////////////////////////////////////////
    ///Check for subjects registered
//    var cnt=0;
//    var sch1 = '';
//    var sch2 = '';
//    var x = new Request({
//        url: 'index.php?option=com_jumi&fileid=213&action=check_for_subj&stno='+stno,
//        noCache: true,
//        method: 'get',
//        async: false,
//        onComplete: function(response) {
//            var data = json_parse(response);
//            cnt = data.Record.cnt;
//            sch1 = data.Record.sch1;
//            sch2 = data.Record.sch2;
//        }
//    }).send();
//
//    if ((lvl ==1) && (cnt > 0)){
//       $('can-select-school').setStyle('display','none');
//       listSubsCode(sch1);
//    }
    /////////////////////////////////////////////////////
    
    
    
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
    var y = new Request({
        url: 'index.php?option=com_jumi&fileid=213&action=show_subjects&stno='+stno,
        noCache: true,
        method: 'get',
        async: false,
        onComplete: function(response) {
            //alert(response);
            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    var r = text.split(';');                    
                    html = html + '<tr>';
                    html = html + '<td style="overflow: hidden; font-size: 10px; width: 50%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
                    html = html + r[0] + '</td>';
                    html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
                    html = html + r[1] + '</td>';
                    html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000"><input type="radio" name="sub_id" id="sub-id" onclick="deleteSubject(this.value)" value="';
                    html = html +r[2]+ '"</td>';    
                    html = html + '</tr>';

                    $('sub-list-data').set('html',html);
    
                }
            });
        }
    }).send();
}

//add subject lists the selected fields in the selection criteria and lists them 
function addSubject(){
    $subject = $('subs-offered').getSelected().get('value');
    $grade = $('grade').getSelected().get('value');
    $language = $$('input[name=lang]:checked').get('value');

    /*subs = SubCode;
    grade = GradeSeqNo;
    scode = schoolcode;
    snum = studNo;
    alert($grade + ' ' + $subject + ' ' + $language);
    '<td>'+data.Record[i].stud_numb+'</td>'*/

    html = html + '<tr>';
    html = html + '<td style="overflow: hidden; font-size: 10px; width: 36%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
    html = html + $subject + '</td>';
    html = html + '<td style="overflow: hidden; font-size: 10px; width: 28%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
    html = html + $grade + '</td>';
    html = html + '<td style="overflow: hidden; font-size: 10px; width: 28%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
    html = html + $language + '</td>';
   /*html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="sub_id" id="sub-id" onclick="deleteSubject(this.value)" value="';
    html = html + $subject + '"</td>';*/
    //html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000"><input type="radio" name="subs_offered" id="subs-offered" onclick="deleteSubject(this.value)" value="';
    //html = html + subs + '"</td>';
    html = html + '</tr>';
    
   $('sub-list-data').set('html',html);
  }


function  listSubs(schoolname)
{
    $('subs-offered').empty();
    $('grade').empty();
    var y = new Request({
        url: 'index.php?option=com_jumi&fileid=213&action=displaysubs&sname='+schoolname,
        noCache: true,
        method: 'get',
        async: false,
        onComplete: function(response) {
            //alert(response);
            new Element('option',{'value':'Choose Subjects','text':'Choose Subjects'}).inject($('subs-offered'));

            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    var r = text.split(';');
                    new Element('option',{'value':r[1],'text':r[1]}).inject($('subs-offered'));
                    // $('grade').empty();
                    // new Element('option',{ 'value':r[2],'text':r[3]}).inject($('grade'));

                }
            });

            //listGrades(schoolname, subname);
        }
    }).send();

}

function listGrades(schoolname, subname)
{
    //$('subs-offered').empty();
    $('grade').empty();
    var y = new Request({
        url: 'index.php?option=com_jumi&fileid=213&action=displaygrades&sname='+schoolname+'&subname='+subname,
        noCache: true,
        method: 'get',
        async: false,
        onComplete: function(response) {
            //alert(response);
            new Element('option',{'value':'Choose Grade','text':'Choose Grade'}).inject($('grade'));

            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    var r = text.split(';');
                    new Element('option',{'value':r[1],'text':r[1]}).inject($('grade'));
                    // $('grade').empty();
                    // new Element('option',{ 'value':r[2],'text':r[3]}).inject($('grade'));

                }
            });
        }
    }).send();
}

function listSubsCode(cde)
{
    $('subs-offered').empty();
    //$('grade').empty();
    var y = new Request({
		url: 'index.php?option=com_jumi&fileid=213&action=displaysubs&sname='+schoolname,
        noCache: true,
        method: 'get',
        async: false,
        onComplete: function(response) {
            //alert(response);
            new Element('option',{'value':'Choose Subjects','text':'Choose Subjects'}).inject($('subs-offered'));
            
            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    var r = text.split(';');
                    new Element('option',{'value':r[1],'text':r[1]}).inject($('subs-offered'));
                   // $('grade').empty();
                   // new Element('option',{ 'value':r[2],'text':r[3]}).inject($('grade'));

                }
            });
        
        
        
        }
    }).send();
}

/*function listGrades(subj)
{
    $('grade').empty();
    var y = new Request({
        url: 'index.php?option=com_jumi&fileid=213&action=display_grades&subj='+subj,
        noCache: true,
        method: 'get',
        async: false,
        onComplete: function(response) {
            //alert(response);
            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    var r = text.split(';');
                    new Element('option',{'value':r[1],'text':r[1]}).inject($('grade'));
                }
            });
        }
    }).send();
}*/



function deleteSubject(subj)
{
   var school_name = $('select-school').getSelected().get('value');
   //alert(school_name);
   // alert($('login-name').value);
   //var stno = $('login-name').value;
    var level = $('s-level').get('value');
   
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=213&sqno='+subj+'&stno='+$('login-name').value+'&school_name='+school_name+'&level='+level+'&action=delete_subject',
        //url: 'index.php?option=com_jumi&fileid=172&subid='+subj,
        method: 'get',
        onComplete: function(response) {
           //alert(response);
            if(response == 0)
            {
                alert('error deleting subjects');
            }
            else if(response == 1) {
                alert('Subject Deleted');
                list_subs();

            }
            else if(response == 2)
            {
                alert('student deleted');
                list_subs();
            }
        }
    }).send();
}

function list_subs()
{
    $('sub-list-data').set('html','');
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
    var x = new Request({
       url: 'index.php?option=com_jumi&fileid=213&stno='+$('login-name').value+'&action=list_subs',
        method: 'get',
        onComplete: function(response){

            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    var r = text.split(';');

                    html = html + '<tr>';
                    html = html + '<td style="overflow: hidden; font-size: 10px; width: 50%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
                    html = html + r[0] + '</td>';
                    html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
                    html = html + r[1] + '</td>';
                    html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000"><input type="radio" name="sub_id" id="sub-id" onclick="deleteSubject(this.value)" value="';
                    html = html +r[2]+ '"</td>';
                    html = html + '</tr>';

                    $('sub-list-data').set('html',html);

                }
            });

        }
    }).send();

}
//function deleteSubject(subcode)//radeSeqNo, schoolcode, studNo)
//{
//    alert(subcode);
//    //$.noConflict(); 
//    $('ajax').setStyle('display','block');
//	var y = new Request({
//		url: 'index.php?option=com_jumi&fileid=213&action=delete_subj&code='+subcode,
//                      // +'&grade='+GradeSeqNo+'&scode='+schoolcode+'&snum='+studNo
//			method: 'get',
//			//timeout: 15000,
////			onTimeout: function() {
////				$('ajax').setStyle('display','none');
////				alert('Error retrieving details.'+'\n'+'Please try later.');
////				y.cancel();
////			},
//                         
//			onComplete: function(response) {
//                                                          
//                                  alert (response);
//                               // var data = json_parse(response);
//                       
////                                if (data.Result == 'ERR')
////				{	
////                                 
////					$('ajax').setStyle('display','none');
////					alert('Error retrieving details.'+'\n'+'Please try later.');
////				} else 
////                                    {
////                                        alert('Succesfully Deleted');
////                                    }
//                        }                   
//     });
// }

/*this function populates the top part of the screen with data once the action display_stud_info is envoked
 it checks the userid that is equals to the login-name and populates the field based on that */
function displayInfo2() {
    $.noConflict();
    $('ajax').setStyle('display','block');

    // a request is sent through with the action display_stud_info and also checks the person that logged and and will do the request as per the uid
    var y = new Request({
        url: 'index.php?option=com_jumi&fileid=213&action=display_stud_info2&uid='+$('login-name').value,
        noCache: true,
        method: 'get',
        timeout: 15000,
        onTimeout: function() {
            $('ajax').setStyle('display','none');
            alert('Error retrieving details.'+'\n'+'Please try later.');
            y.cancel();
        },
        onComplete: function(response) {

            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    var r = text.split(';');

                    $('stud-nr').set('value',$('login-name').value);
                    $('email-address').set('value',r[0]);
                    $('f-name').set('value',r[1]);
                    $('s-qual').set('value',r[2]);
                    $('r-date').set('value',r[3]);

                }
            });

            $('ajax').setStyle('display','none');
        }
    }).send();
}