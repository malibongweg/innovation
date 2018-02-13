var tref = 0;
var evalid;
var school_code;
var studentno;

window.addEvent('domready', function() {
    $('radd-card-no').addEvent('click', function () {
        RunStudentReport();
    });
});

function SearchSchool()
{
    var x = new Request({
        url: 'index.php?option=com_jumi&fileid=198&school_name=' + $('srch-school').value + '&action=search_school&dt=' + new Date().getTime(),
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
