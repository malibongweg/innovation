var tref = 0;
var evalid;
var school_code;
var studentno;

window.addEvent('domready', function() {
    //search school
    $('srch-school').value = '';
    $('srch-school').focus();
    $('srch-school').addEvent('keydown', function () {
        $('school-details').setStyle('display', 'none');
        $('list-schools').setStyle('display', 'none');

        clearTimeout(tref);
        tref = setTimeout('SearchSchool()', 500);

    });

    $('school-report').addEvent('click', function () {
        $('search-evaluator').setStyle('display', 'none');
        $('evaluator-details').setStyle('display', 'none');
        $('search-student').setStyle('display', 'none');
        $('student-details').setStyle('display', 'none');
        $('search-school').setStyle('display', 'block');
    });


    $('get-school').addEvent('click', function () {
        var school_id = parseInt($('school-list').getSelected().get('value'));
        school_code=school_id;
        if (typeof school_id == 'number' && school_id > 0) {
            $('list-schools').setStyle('display', 'none');
            $('srch-school').value = '';
            displaySchoolInfo(school_id);
        } else {
            alert('Please select user object.');
            $('srch-school').value = '';
            $('srch-school').focus();
        }
    });


    //search evaluator
    $('srch-evaluator').value = '';
    $('srch-evaluator').focus();

    $('srch-evaluator').addEvent('keydown', function () {
        $('evaluator-details').setStyle('display', 'none');
        $('list-evaluator').setStyle('display', 'none');
        clearTimeout(tref);
        tref = setTimeout('SearchEvaluator()', 500);

    });


    $('get-evaluator').addEvent('click', function () {
        var evaluator_id = parseInt($('evaluator-list').getSelected().get('value'));
        evalid=evaluator_id;
        if (typeof evaluator_id == 'number' && evaluator_id > 0) {
            $('list-evaluator').setStyle('display', 'none');
            $('srch-evaluator').value = '';
            displayEvaluatorInfo(evaluator_id);
        } else {
            alert('Please select user object.');
            $('srch-evaluator').value = '';
            $('srch-evaluator').focus();
        }
    });

    $('evaluator-placement-report').addEvent('click', function () {

        $('search-school').setStyle('display', 'none');
        $('school-details').setStyle('display', 'none');
        $('search-student').setStyle('display', 'none');
        $('student-details').setStyle('display', 'none');
        $('search-evaluator').setStyle('display', 'block');
    });


    //search student
    $('srch-student').value = '';
    $('srch-student').focus();

    $('srch-student').addEvent('keydown', function () {

        $('student-details').setStyle('display', 'none');
        $('list-student').setStyle('display', 'none');
        clearTimeout(tref);
        tref = setTimeout('SearchStudent()', 500);

    });

    $('get-student').addEvent('click', function () {
        var student_id = parseInt($('student-list').getSelected().get('value'));
        studentno=student_id;
        if (typeof student_id == 'number' && student_id > 0) {
            $('list-student').setStyle('display', 'none');
            $('srch-student').value = '';
            displayStudentInfo(student_id);
        } else {
            alert('Please select user object.');
            $('srch-student').value = '';
            $('srch-student').focus();
        }
    });

    $('student-placement-report').addEvent('click', function () {
        $('search-school').setStyle('display', 'none');
        $('school-details').setStyle('display', 'none');
        $('search-evaluator').setStyle('display', 'none');
        $('evaluator-details').setStyle('display', 'none');
        $('search-student').setStyle('display', 'block');
    });

    $('students-not-placed-report').addEvent('click', function () {
        $('search-school').setStyle('display', 'none');
        $('school-details').setStyle('display', 'none');
        $('search-evaluator').setStyle('display', 'none');
        $('evaluator-details').setStyle('display', 'none');
        $('search-student').setStyle('display', 'none');
        $('student-details').setStyle('display', 'none');
        StudentsNotPlacedReport();
    });

    $('run-school-report').addEvent('click', function () {

        RunSchoolReport();
    });

    $('email-school-report').addEvent('click', function () {

        EmailSchoolReport();
    });

    $('full-schools-report').addEvent('click', function () {

        RunFullSchoolsReport();
    });


    $('full-evaluators-report').addEvent('click', function () {
        RunEvaluatorsReport();
        
    });

    $('run-evaluator-report').addEvent('click', function () {
       
        RunEvaluatorReport();

    });
    
    $('email-evaluator-report').addEvent('click', function () {

        EmailEvaluatorReport();
    });

    /*$('student-placement-report').addEvent('click', function () {
        RunFullStudentsReport();
    });*/

     $('full-students-report').addEvent('click', function () {
        RunFullStudentsReport();
    });

    $('run-student-report').addEvent('click', function () {
        RunStudentReport();
    });

    $('email-student-report').addEvent('click', function () {
        EmailStudentReport();
    });

    $('correspondance').addEvent('click', function () {
        EmailReport();
    });
	
	$('sch-correspondance').addEvent('click', function () {
        EmailSchoolCorres();
    });
});

function SearchSchool()
{
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=206&school_name=' + $('srch-school').value + '&action=search_school&dt=' + new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            $('school-list').empty();
            var row = json_parse(response, function(data, text) {
                if (typeof text == 'string') {
                    var r = text.split(';');
                    new Element('option', {'value': r[0], 'text': r[1]}).inject($('school-list'));
                }
            });
            $('list-schools').setStyle('display', 'block');
        }
    }).send();
}

function displaySchoolInfo(school_id) {
    $('school-details').setStyle('display', 'block');
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=206&school_id=' + school_id + '&action=display_school_info&dt=' + new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            $('school-list').empty();
            var row = json_parse(response, function(data, text) {
                if (typeof text == 'string') {
                    var rec = text.split(';');
                    $('school-name').set('value', rec[0]);
                    $('address-1').set('value', rec[1]);
                    $('school-type').set('value', rec[12]);
                    //$data[] = $row->schoolname.';'.$row->address1.';'.$row->address2.';'.$row->address3.';'.$row->address4.';'.$row->postalcode.';'.$row->telephone.';'.$row->faxnumber.';'.$row->email.';'.$row->principal.';'.$row->language.';'.$row->num_students.';'.$row->schooltype;
                    $('school-email').set('value', rec[8]);
                }
            });

        }

    }).send();
}


//Evaluator
function SearchEvaluator()
{
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=206&evaluator_name=' + $('srch-evaluator').value + '&action=search_evaluator&dt=' + new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            $('evaluator-list').empty();
            var row = json_parse(response, function(data, text) {
                if (typeof text == 'string') {
                    var r = text.split(';');
                    new Element('option', {'value': r[0], 'text': r[1]}).inject($('evaluator-list'));
                }
            });
            $('list-evaluator').setStyle('display', 'block');
        }
    }).send();
}

function displayEvaluatorInfo(evaluator_id) {

    $('evaluator-details').setStyle('display', 'block');
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=206&evalid='+evaluator_id+'&action=display_evaluator_info&dt='+new Date().getTime(),
        method: 'get',
        onComplete: function(response) {

            var row = json_parse(response, function (data, text) {
                if (typeof text == 'string') {
                    var rec = text.split(';');

                    $('f-name').set('value', rec[0]);
                    $('s-name').set('value', rec[1]);
                    $('email').set('value', rec[2]);
                    $('visits').set('value', rec[4]);
                    $('category').set('value', rec[3]);
                    //new Element('option', {'value': r[3], 'text': r[3]}).inject($('student-list'));

                }
            });
        }


    }).send();

}

//Student
function SearchStudent()
{
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=206&student_name=' + $('srch-student').value + '&action=search_student&dt=' + new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            $('student-list').empty();
            var row = json_parse(response, function(data, text) {
                if (typeof text == 'string') {
                    var r = text.split(';');
                    new Element('option', {'value': r[0], 'text': r[1]}).inject($('student-list'));
                }
            });
            $('list-student').setStyle('display', 'block');
        }
    }).send();
}

function displayStudentInfo(student_id) {
    $('student-details').setStyle('display', 'block');
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=206&student_id='+student_id+'&action=display_student_info&dt='+new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            var row = json_parse(response, function (data, text) {
                if (typeof text == 'string') {
                    var rec = text.split(';');

                    $('student-number').set('value', rec[0]);
                    $('student-name').set('value', rec[1]);
                    $('student-email').set('value', "dywibibam"+'@mycput.ac.za');

                }
            });
        }


    }).send();

}

function StudentsNotPlacedReport() {
  $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_StudentsNotPlaced');
    $('rep-discount').click();
}

function RunSchoolReport() {
    $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_School_Report&schoolid='+school_code+'&id=1');
    $('rep-discount').click();
}

function EmailSchoolReport() {
    $('rep-discount2').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_School_Report&schoolid='+school_code+'&id=2');
    $('rep-discount2').click();
}

function RunStudentReport() {
    $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_Student_Report&studentno='+studentno+'&id=1');
    $('rep-discount').click();
}

function EmailStudentReport() {
    $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_Student_Report&studentno='+studentno+'&id=2');
    $('rep-discount').click();
}

function RunFullSchoolsReport() {
    var campus = $('campus').getSelected().get('value');
    $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_Full_Schools_Report&campus='+campus);
    $('rep-discount').click();
}

function RunEvaluatorReport() {
  
    $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_Evaluator_Report&evalid='+evalid+'&id=1');
    $('rep-discount').click();
}

function EmailEvaluatorReport() {
    $('rep-discount2').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_Evaluator_Report&evalid='+evalid+'&id=2');
    $('rep-discount2').click();
}

function RunEvaluatorsReport() {
    var campus = $('ecampus').getSelected().get('value');
    $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_Full_Evaluators_Report&campus='+campus);
    $('rep-discount').click();

}

function RunFullStudentsReport() {
    var campus = $('scampus').getSelected().get('value');
    $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=Run_Full_Students&campus='+campus);
    $('rep-discount').click();

}

function EmailReport() {
    var campus = $('campus').getSelected().get('value');
    $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=EmailReport&campus='+campus);
    $('rep-discount').click();

}

function EmailSchoolCorres() {
    var campus = $('campus').getSelected().get('value');
    $('rep-discount').set('href','index.php?option=com_jumi&fileid=206&tmpl=component&action=EmailSchoolCorres&campus='+campus+'&schoolid='+school_code);
    $('rep-discount').click();

}