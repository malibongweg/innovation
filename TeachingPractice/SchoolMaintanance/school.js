var tref = 0;
var subid = "";
var sid;
var id, school_id,subb,school_id_delete;
var html = '<table width="100%" border="0" style="table-layout: fixed">';

var school_id_update;

window.addEvent('domready', function() {   
    listSchools();
    
   $('new-school').addEvent('click', function(){
        $('header-details').setStyle('display', 'none');
        $('new-school').setStyle('display', 'none');
        $('main-dv').setStyle('display', 'block');     
        $('buttons2').setStyle('display', 'block');  
    });
    
    $('srch').value = '';
    $('srch').focus();

    $('srch').addEvent('keydown', function() {
        $('list-schools').setStyle('display', 'none');
        clearTimeout(tref);
        tref = setTimeout('srchSchool()', 1500);
    });

    $('school-info').addEvent('submit', function(e) {
        e.stop();
        var x = new Request({
            url: 'index.php?option=com_jumi&fileid=211&action=save_school',
            method: 'post',
            data: this, //$('criteria-form')
            async: false,
            onComplete: function(response) {
               // alert(response);
                if (response == 0) {
                    alert('Error inserting school or grade/subject combination.');

                } else {
                    var r = response.split(';');
                   
                     id = r['0'];
                     school_id = r['1'];
                     school_id_delete = school_id;
                     var subj = r['2'];
                     var gra = r['3'];
                     //var la = r['4'];
                    
                    listSubjects(school_id);
                }


            }
        }).send();

    });


    $('add-sch').addEvent('click', function() {
        $('main-dv').setStyle('display', 'block');
        $('test').setStyle('display', 'none');
    });
    
    $('edit-sch').addEvent('click', function() {
        $('test').setStyle('display', 'block');
        $('main-dv').setStyle('display', 'none');
    });

    $('remove-sch').addEvent('click', function() {
        $('test').setStyle('display', 'block');
        $('main-dv').setStyle('display', 'none');
        $('add-sub').setStyle('display', 'none');
        $('delete-but').setStyle('display', 'block');
        
    });

    $('finish-but').addEvent('click', function() {
        $('main-dv').setStyle('display', 'none');
        window.location.reload();
    });
    
    $('back-home').addEvent('click', function() {        
        window.location.reload();
    });
    
    $('go-back').addEvent('click', function() {        
        window.location.reload();
    });
    
    $('delete-but').addEvent('click', function() {
        deleteSchool(school_id_delete);
        $('main-dv').setStyle('display', 'none');
    });

    $('getSchool').addEvent('click', function() {
         school_id = parseInt($('school-List').getSelected().get('value'));
         school_id_delete = school_id;
        if (typeof school_id == 'number' && school_id > 0) {
            $('list-schools').setStyle('display', 'none');
            $('srch').value = '';
            displaySchoolInfo(school_id);
            listSubjects(school_id);
        } else {
            alert('Please select school object.');
            $('srch').value = '';
            $('srch').focus();
        }
    });
    
 function deleteSchool(school_id_delete)
{
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=211&sid='+school_id_delete+'&action=delete_school',
        method: 'get',
        onComplete: function(response) {
            if (response == 1)
            {
                alert('School Deleted');
                window.location.reload();
                //listSubjects();
            } else {
                alert('Delete operation unsuccessful!');
                window.location.reload();
            }

        }
    }).send();
}

 $('update-but').addEvent('click', function(e) {
        var schoolname = $('school-name').get('value');
        var address1 = $('address-1').get('value');
        var address2 = $('address-2').get('value');
        var address3 = $('address-3').get('value');
        var address4 = $('address-4').get('value');
        var postcode = $('postal-code').get('value');
        var tel = $('telephone-number').get('value');
        var fax = $('fax-number').get('value');
        var email = $('email-address').get('value');
        var principal = $('principal-name').get('value');
        var liaison = $('liaison-person').get('value');
        var stype = $('school-type').getSelected().get('value');
        var lang_used = $('language-used').getSelected().get('value');
        var num_students = $('number-of-students').value;
        var grade = $('grade-id').getSelected().get('value');
        var subject = $('subject-id').getSelected().get('value');
        var catergory = $('school-cat').getSelected().get('value');
       // var language = $$('input[name=language]:checked').get('value');
        var x = new Request({            
            url: 'index.php?option=com_jumi&fileid=211&action=update_school&school_id_upd='+school_id_update+'&schoolname=' + schoolname + '&address1=' + address1 + '&address2=' + address2 + '&address3=' + address3 + '&address4=' + address4 + '&postcode=' + postcode + '&tel=' + tel + '&fax=' + fax + '&email=' + email + '&principal=' + principal + '&liaison=' + liaison + '&stype=' +  stype + '&lang_used=' + lang_used + '&num_students=' + num_students + '&sid='+school_id + '&grade='+grade+'&subject='+subject+'&schtype='+stype+'&catergory='+catergory,
            method: 'get',       
            async: false,
            onComplete: function(response) {

                if (response == 0) {
                    alert('Error updating record.');

                } else {
                    alert('School updated.');
                    window.location.reload();
                }


            }
        }).send();
    });

    function showObject(obj) {
        alert(obj.stud_numb);
    }

    function addSubject(id, school_id,subj,gra,la)
    {
        subid = id;
        sid= school_id;

        html = html + '<td style="overflow: hidden; font-size: 10px; width: 19%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
        html = html + subj + '</td>';

        html = html + '<tr>';
        html = html + '<td style="overflow: hidden; font-size: 10px; width: 44%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
        html = html + gra + '</td>';

        html = html + '<td style="overflow: hidden; font-size: 10px; width: 25%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000">';
        html = html + la + '</td>';

        html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid #a5badc; background-color: #a5badc; color: #000000"><input type="radio" name="subj" id="subj-id" onclick="deleteSubject(subid,sid)" value="';
        html = html + subid + '"</td>';

        html = html + '</tr>';

        $('sub-list-data').set('html', html);
    }

    function AddSchool() {
        var schoolname = $('school-name').get('value');
        var address1 = $('address-1').get('value');
        var address2 = $('address-2').get('value');
        var address3 = $('address-3').get('value');
        var address4 = $('address-4').get('value');
        var postcode = $('postal-code').get('value');
        var tel = $('telephone-number').get('value');
        var fax = $('fax-number').get('value');
        var email = $('email-address').get('value');
        var principal = $('principal-name').get('value');
        var liaison = $('liaison-person').get('value');
        var stype = $('school-type').getSelected().get('value');
        var lang_used = $('language-used').getSelected().get('value');
        var num_students = $('number-of-students').value;
$('buttons').setStyle('display','block');
        var x = new Request({
            url: 'index.php?option=com_jumi&fileid=211&action=addschool&schoolname=' + schoolname + '&address1=' + address1 + '&address2=' + address2 + '&address3=' + address3 + '&address4=' + address4 + '&postcode=' + postcode + '&tel=' + tel + '&fax=' + fax + '&email=' + email + '&principal=' + principal + '&liaison=' + liaison + '&lang_used=' + lang_used + '&num_students=' + num_students + '&dt=' + new Date().getTime(),
            method: 'get',
            onComplete: function(response) {
                if (response == 1)
                {
                    alert('School added succesfully.');
                }
                else
                {
                    alert('School not added.');
                }
            }
        }).send();
    }
});

function listSchools() {
    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="width: auto; height: 250px; overflow: scroll; position: relative; padding: 5px">';
    $('ajax-schools').setStyle('display','block');
    var x = new Request ({
        url: 'index.php?option=com_jumi&fileid=211&action=display_schools',
        noCache: true,
        method: 'get',
        onComplete: function(response) {
            var row = json_parse(response,function(data,text) {
            if (typeof text == 'string') {
                if (parseInt(text) == -1)
                {
                        $('ajax-schools').setStyle('display','none');
                        $('school-form').set('html','No data available.');
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
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="schoo_id" id="schoo-id" onclick="displaySchoolInfo(this.value)" value="';
                        html = html + rec[0] + '"</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 40%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[1] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[4] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[2] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[3] + '</td>';                        
                        html = html + '</tr>';

                        ++cnt;
                }
            }
        });
        html = html + "</table>";
        if (fnd == true)
        {
                $('school-form').set('html',html);
                $('ajax-schools').setStyle('display','none');
                //$('vehicle-data').setStyle('display','none');
                $('header-details').setStyle('display','block');
        }
    }
    }).send();
}


function srchSchool()
{
    $('list-schools').setStyle('display', 'block');

    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=211&school=' + $('srch').value + '&action=srch&dt=' + new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            $('school-List').empty();
            var row = json_parse(response, function(data, text) {
                if (typeof text == 'string') {
                    var r = text.split(';');

                    new Element('option', {'value': r[0], 'text': r[1]}).inject($('school-List'));
                }
            });
            $('list-schools').setStyle('display', 'block');
        }
    }).send();

}

function displaySchoolInfo(school_id) {
    school_id_update = school_id;
    $('header-details').setStyle('display', 'none');
    $('new-school').setStyle('display', 'none');
    $('main-dv').setStyle('display', 'none');
    $('buttons').setStyle('display','block');
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=211&school_id=' + school_id + '&action=info&dt=' + new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            $('school-List').empty();
            var row = json_parse(response, function(data, text) {
                if (typeof text == 'string') {
                    var r = text.split(';');
                    //  alert(r[0]);
                    $('school-name').set('value', r[0]);
                    $('address-1').set('value', r[1]);
                    $('address-2').set('value', r[2]);
                    $('address-3').set('value', r[3]);
                    $('address-4').set('value', r[4]);
                    $('postal-code').set('value', r[5]);
                    $('telephone-number').set('value', r[6]);
                    $('fax-number').set('value', r[7]);
                    $('email-address').set('value', r[8]);
                    $('principal-name').set('value', r[9]);
                    $('liaison-person').set('value', r[10]);
                    $('language-used').getElements('option').each(function(el) {
                        if (el.value == r[11])
                        {
                            el.selected = true;
                        }
                    });
                    
                     $('school-type').getElements('option').each(function(el) {
                        if (el.value == r[13])
                        {
                            el.selected = true;
                        }
                    });
                    
                    $('number-of-students').set('value', r[12]);
                    listSubjects(school_id);
                }
            });
            $('main-dv').setStyle('display', 'block');
        }
    }).send();

}

function deleteSubject(id,sid)
{
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=211&id='+id+'&sid='+sid+'&action=delete_subject',
        method: 'get',
        onComplete: function(response) {
                alert('Subject Deleted');
               var r = response.split(';');
               s = r[0];
               g = r[1]
                listSubjects(s);

        }
    }).send();
}

//

function listSubjects(scode) {
    $('sub-list-data').set('html','');
    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
    //('ajax-vehicles').setStyle('display','block');
    school_id_delete =scode;
    var x = new Request ({
        url: 'index.php?option=com_jumi&fileid=211&action=display_subjects&schoolcode='+scode,
        noCache: true,
        method: 'get',		
                onComplete: function(response) {
                    var row = json_parse(response,function(data,text) {
                    if (typeof text == 'string') {
                        if (parseInt(text) == -1)
                        {
                            $('sub-list-data').set('html','No data available.');
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
                            subb = rec[0];
                            schoo= rec[4];
                            html = html + '<tr>';								
                            html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                            html = html + rec[1] + '</td>';
                            html = html + '<td style="overflow: hidden; font-size: 10px; width: 60%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                            html = html + rec[2] + '</td>';
                            html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="vehicle-id" onclick="deleteSubject(this.value,schoo)" value="';
                            html = html + rec[0] + '"</td>';
                            html = html + '</tr>';

                            ++cnt;
                        }
                    }
                });
                
                html = html + "</table>";
                if (fnd == true)
                {
                        $('sub-list-data').set('html',html);
                }
        }
    }).send();
}