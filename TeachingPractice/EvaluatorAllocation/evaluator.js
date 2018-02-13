var tref = 0;
var sub_ject;
var schid;

var html = '<table width="100%" border="0" style="table-layout: fixed">';

var eval_id_update;
var eval_id_delete;

window.addEvent('domready', function() {

    listEvaluators();

    $('new-evaluator').addEvent('click', function () {
        $('header-details').setStyle('display', 'none');
        $('new-evaluator').setStyle('display', 'none');
        $('main-dv').setStyle('display', 'block');

    });

    $('srch').value = '';
    $('srch').focus();

    $('srch').addEvent('keydown', function () {
        $('list-evaluators').setStyle('display', 'none');

        clearTimeout(tref);
        tref = setTimeout('srchSchool()', 500);

    });

    $('getEvaluator').addEvent('click', function () {
        $('evaluators-buttons').setStyle('display', 'none');
        var eval_id = parseInt($('evaluator-List').getSelected().get('value'));
        if (typeof eval_id == 'number' && eval_id > 0) {
            $('list-evaluators').setStyle('display', 'none');
            $('srch').value = '';
            displayEvaluatorInfo(eval_id);
        } else {
            alert('Please select user object.');
            $('srch').value = '';
            $('srch').focus();
        }
    });

    $('go-back').addEvent('click', function() {
        window.location.reload();
    });

    $('search-evaluator').addEvent('click', function () {

        $('evaluators-buttons').setStyle('display', 'none');
        $('header-details').setStyle('display', 'none');
        $('main-dv').setStyle('display', 'none');
        $('test').setStyle('display', 'block');

    });

    //get school language of instruction and filter schools
    $('category2').addEvent('change', function (e) {

        listSchools();

        $('header-details3').setStyle('display', 'none');
    });
   
    $('subject-id').addEvent('change', function (e) {
        listSchoolSubjects();
        $('header-details3').setStyle('display', 'none');
    });

    $('evaluator-info').addEvent('submit', function (e) {
        
        
        e.stop();
        var x = new Request({
            url: 'index.php?option=com_jumi&fileid=215&action=save_evaluator',
            method: 'post',
            data: this,
            async: false,
            onComplete: function (response) {
                //alert(response);
                if (response == 1) {
                    alert('Eval added');
                    window.location.reload();

                } else {
                    alert('eval exist');
                }


            }
        }).send();

    });


    $('delete-but').addEvent('click', function () {
        deleteEvaluator(eval_id_delete);
        $('main-dv').setStyle('display', 'none');
    });

    $('save-allocation-but').addEvent('click', function () {
        checkChecked();
        //document.location.reload();

    });

    $('delete-student').addEvent('click', function () {
        deleteChecked();
    });

    $('update-but').addEvent('click', function(e) {
        var evaluator_name = $('evaluator-name').get('value');
        var evaluator_surname = $('evaluator-surname').get('value');
        var id_number = $('id-number').get('value');
        var address1 = $('address-1').get('value');
        var address2 = $('address-2').get('value');
        var address3 = $('address-3').get('value');
        var address4 = $('address-4').get('value');
        var postcode = $('postal-code').get('value');
        var tel = $('telephone-number').get('value');
        var cellphone_number = $('cellphone-number').get('value');
        var fax = $('fax-number').get('value');
        var email = $('email-address').get('value');
        var region = $('region').get('value');

        var cat = $('category').getSelected().get('value');
        var number_of_visits = $('number-of-visits').value;

        var x = new Request({
            url: 'index.php?option=com_jumi&fileid=215&evaluator_id_upd='+eval_id_update+'&evaluator_name=' + evaluator_name + '&evaluator_surname=' + evaluator_surname + '&id_number=' + id_number + '&address1=' + address1 + '&address2=' + address2 + '&address3=' + address3 + '&address4=' + address4
            + '&postcode=' + postcode + '&tel=' + tel + '&cellphone_number=' + cellphone_number + '&fax=' + fax + '&email=' + email + '&region=' + region + '&cat=' + cat + '&number_of_visits='+  number_of_visits+'&action=update_evaluator',
            method: 'get',
            async: false,
            onComplete: function(response) {

                if (response == 0) {
                    alert('Error updating record.');

                } else {
                    alert('Evaluator updated.');
                    window.location.reload();

                }


            }
        }).send();

    });

});


function showInfo(evalseqno) {
     eval_id_update = evalseqno;
     eval_id_delete = evalseqno;
    $('header-details').setStyle('display','none');
    $('evaluators-buttons').setStyle('display','none');
    $('user-details').setStyle('display', 'block');

    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=215&evalid='+evalseqno+'&action=info&dt='+new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            $('evaluator-List').empty();
            var row = json_parse(response, function (data, text) {
                if (typeof text == 'string') {
                    var rec = text.split(';');

                    $('f-name').value = rec[0];
                    $('s-name').value = rec[1];
                    $('email').value = rec[2];
                    $('location').value = rec[6];
                    $('visits').value = rec[4];
                    $('category').value = rec[3];

                    $('user-details').setStyle('display','block');

                    $('allocation-details').setStyle('display', 'block');

                    listEvaluatorStudents(rec[5]);

                }
            });
        }


    }).send();

}


function deleteEvaluator(eval_id_delete)
{
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=215&eid='+eval_id_delete+'&action=delete_evaluator',
        method: 'get',
        onComplete: function(response) {
            if (response == 1)
            {
                alert('Evaluator Deleted');
                window.location.reload();
            } else {
                alert('Delete operation unsuccessful!');
                window.location.reload();
            }

        }
    }).send();
}


function listEvaluators() {

    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
    $('ajax-evaluators').setStyle('display','block');
    var x = new Request ({
        url: 'index.php?option=com_jumi&fileid=215&action=display_evaluators',
        noCache: true,
        method: 'get',
        onComplete: function(response) {
            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    if (parseInt(text) == -1)
                    {
                        $('ajax-evaluators').setStyle('display','none');
                        $('evaluator-form').set('html','No data available.');
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
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="evaluator_id" id="evaluator-id" onclick="showInfo(this.value)" value="';
                        html = html + rec[0] + '"</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 40%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[1] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[2] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[3] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[4] + '</td>';
                        html = html + '</tr>';

                        ++cnt;
                    }


                }
            });
            html = html + "</table>";
            if (fnd == true)
            {
                $('evaluator-form').set('html',html);
                $('ajax-evaluators').setStyle('display','none');
                $('header-details').setStyle('display','block');
            }
        }
    }).send();
}

function srchSchool() {
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=215&evaluator=' + $('srch').value + '&action=srch&dt=' + new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            $('evaluator-List').empty();
            var row = json_parse(response, function(data, text) {
                if (typeof text == 'string') {
                    var r = text.split(';');

                    new Element('option', {'value': r[0], 'text': r[1]}).inject($('evaluator-List'));
                }
            });
            $('list-evaluators').setStyle('display', 'block');
        }
    }).send();

}

function displayEvaluatorInfo(eval_id) {

    $('evaluators-buttons').setStyle('display', 'none');
    eval_id_update = eval_id;
    eval_id_delete = eval_id;
    $('header-details').setStyle('display', 'none');
    $('new-evaluator').setStyle('display', 'none');
    $('main-dv').setStyle('display', 'none');

    $('buttons').setStyle('display','block');
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=215&evalid=' + eval_id + '&action=evalinfo&dt=' + new Date().getTime(),
        method: 'get',
        onComplete: function(response) {

            var row = json_parse(response, function(data, text) {
                if (typeof text == 'string') {
                    var r = text.split(';');
                    $('evaluator-name').set('value', r[1]);
                    $('evaluator-surname').set('value', r[2]);
                    $('id-number').set('value', r[3]);
                    $('address-1').set('value', r[4]);
                    $('address-2').set('value', r[5]);
                    $('address-3').set('value', r[6]);
                    $('address-4').set('value', r[7]);
                    $('postal-code').set('value', r[8]);
                    $('telephone-number').set('value', r[9]);
                    $('cellphone-number').set('value', r[10]);
                    $('fax-number').set('value', r[11]);
                    $('email-address').set('value', r[12]);
                    $('category').getElements('option').each(function(el) {
                        if (el.value == r[13])
                        {
                            el.selected = true;
                        }
                    });

                    $('number-of-visits').set('value', r[14]);
                    $('add-evaluator').setStyle('display', 'none');
                }
            });
            $('main-dv').setStyle('display', 'block');
            $('evaluators-buttons').setStyle('display', 'block');
        }
    }).send();

}


function listSchools() {
    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
    $('ajax-schools').setStyle('display','block');
    var lang_id = $('category2').getSelected().get('value');

$('header-details3').setStyle('display','block');
    var subj_id = $('subject-id').getSelected().get('value');

    var x = new Request ({

        url: 'index.php?option=com_jumi&fileid=215&lang='+lang_id+'&subj_id='+subj_id+'&action=display_schools',
        noCache: true,
        method: 'get',
        onComplete: function(response) {
$('header-details3').setStyle('display', 'block');
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
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 50%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[1] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[4] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[3] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="checkbox" name="schoo_id" id="schoo-id" onclick="listStudents(this.value)" value="';
                        html = html + rec[0] + '"</td>';
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
                $('header-details').setStyle('display','none');
            }
        }
    }).send();
}



function listSchoolSubjects() {
    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
    $('ajax-schools').setStyle('display','block');

    var lang_id = $('category2').getSelected().get('value');
    var subj_id = $('subject-id').getSelected().get('value');
    $('header-details3').setStyle('display','block');

    var x = new Request ({

        url: 'index.php?option=com_jumi&fileid=215&subj_id='+subj_id+'&lang='+lang_id+'&action=display_schools_subs',
        noCache: true,
        method: 'get',
        onComplete: function(response) {
$('header-details3').setStyle('display', 'block');
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

                        sub_ject = rec[5];

                        html = html + '<tr>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 50%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[1] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[4] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[3] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="checkbox" name="schoo_id" id="schoo-id" onclick="listStudents(this.value, sub_ject)" value="';
                        html = html + rec[0] + '"</td>';
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
                $('header-details').setStyle('display','none');
            }
        }
    }).send();
}



function listStudents(schoolid) {
var index =0;
    var school_ids = [];

    $('header-details3').setStyle('display', 'block');
    var school_lang = $('category2').getSelected().get('value');

    var subject = $('subject-id').getSelected().get('value');

    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
   

    $$('input[type=checkbox]').each(function(e) {
        if (e.checked) {

            val = e.value;


            school_ids[index] = val;
            index++;

        }

    });
     $('ajax-students').setStyle('display','block');
    var x = new Request ({
        url: 'index.php?option=com_jumi&fileid=215&subject_id='+subject+'&action=display_students',
        noCache: true,
        method: 'get',
        data: {myArray: school_ids},
        
        onComplete: function(response) {
            $('header-details3').setStyle('display', 'block');

            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    if (parseInt(text) == -1)
                    {
                        $('ajax-students').setStyle('display','none');
                        $('students-form').set('html','No data available.');
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
                        html = html + rec[0] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[3] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[1] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[4] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="checkbox" name="student1" id="student-no1" value="';
                        html = html + rec[0] + '"';
                        html = html + '</tr>';

                        ++cnt;
                    }
                }
            });
            html = html + "</table>";
            if (fnd == true)
            {
                $('students-form').set('html',html);
                $('ajax-students').setStyle('display','none');
                $('header-details').setStyle('display','none');
            }
        }
    }).send();
    

}


function checkChecked()
{
    var students2 = [];


    var index=0;
    var val;
    var evaluator_name,evaluator_surname;
    $$('input[type=checkbox]').each(function(e) {
    if(e.checked)
    {
        val = e.value;
        students2[index] = val;
        evaluator_name = $('f-name').get('value');
         evaluator_surname = $('s-name').get('value');
        index++;
    }
    else
    {
       var x=1;
    }
    });

    if(index >= 0) {
        var x = new Request({
            url: 'index.php?option=com_jumi&fileid=215&stnum=' + val + '&eval_name=' + evaluator_name + '&eval_sname=' + evaluator_surname + '&action=allocate_student',
            method: 'get',
            data: {myArray: students2},
            onComplete: function (response) {
                if(response == 7)
                {
                    alert('Number of visits depleted');
                }
                else if (response == 0) {
                    alert('Error allocating student');
                }
                else if(response == -1)
                {
                    alert('Note: cannot evaluate same student twice');
                }
                else {
                    alert('Students Successfully Allocated');

                }

            }

        }).send();

    }
}

function deleteChecked()
{
    var students = [];
    var index=0;
    var val;
    var evaluator_name,evaluator_surname;

    $$('input[type=checkbox]').each(function(e) {
        if(e.checked)
        {
            val = e.value;
            students[index] = val;
            evaluator_name = $('f-name').get('value');
            evaluator_surname = $('s-name').get('value');
            index++;
        }
        else
        {
            var x=1;
        }
    });

    if(index >= 0) {
        var x = new Request({
            url: 'index.php?option=com_jumi&fileid=215&stnum=' + val + '&eval_name=' + evaluator_name + '&eval_sname=' + evaluator_surname + '&action=delete_student',
            method: 'get',
            data: {myArray: students},
            onComplete: function (response) {
               if (response == 0) {
                    alert('Student not found');
                }
                else
                {
                    alert('Student Deleted Successfully');
                    listEvaluatorStudents(response);
                }

            }

        }).send();

    }
}

//List selected Students

function listSelectedStudent(student_number,sch,subj)
{
    $('selected_students_list_header').setStyle('display', 'block');

    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="table-layout: fixed">';
    $('ajax-selected_students').setStyle('display','block');
    var x = new Request ({
        url: 'index.php?option=com_jumi&fileid=215&student_number='+student_number+'&subject='+subj+'&school='+sch+'&action=display_selected_student',
        noCache: true,
        method: 'get',
        onComplete: function(response) {
            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    if (parseInt(text) == -1)
                    {
                        $('ajax-students').setStyle('display','none');
                        $('students-form').set('html','No data available.');
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
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[0] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[3] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 35%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[1] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="student2" id="student-no2" onclick="displaySchoolInfo(this.value)" value="';
                        html = html + rec[4] + '"</td>>';
                        html = html + '</tr>';

                        ++cnt;
                    }
                }
            });
            html = html + "</table>";
            if (fnd == true)
            {
                $('students-form').set('html',html);
                $('ajax-students').setStyle('display','none');
                $('header-details').setStyle('display','none');
            }
        }
    }).send();
}


function listEvaluatorStudents(eval_id) {

    var color1 = '#a5badc';
    var color2 = '#FFFFFF';
    var cnt = 1;
    var fnd = true;
    var html = '<table width="100%" border="0" style="table-layout: fixed">';

    var x = new Request ({
        url: 'index.php?option=com_jumi&fileid=215&action=display_students_info&eval_id='+eval_id,
        noCache: true,
        method: 'get',
        onComplete: function(response) {
            var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                    if (parseInt(text) == -1)
                    {
                        $('school-form').set('html','No data available.');
                        $('students-form').set('html','No data available.');
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
                        html = html + rec[0] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
                        html = html + rec[1] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 30%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[2] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
                        html = html + rec[3] + '</td>';
                        html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="checkbox" name="student" id="student-id" value="';
                        html = html + rec[0] + '"</td>';
                        html = html + '</tr>';

                        ++cnt;
                    }
                }
            });

            html = html + "</table>";
            if (fnd == true)
            {

                $('students-form').set('html',html);
            }
        }
    }).send();
}