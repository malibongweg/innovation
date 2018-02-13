var staff_barcode = 0;
var student_barcode = 1;
var ct = '';
var ref_no = 0;

window.addEvent('domready', function() {

    //root_directory = $('rootdir').get('value');
    //sysdate = $('sysdate').get('value');
    checkDBMode();

    var numbers = [8, 9, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 110, 190];
    $$('.numeric').each(function(item) {
        item.addEvent('keydown', function(key) {
            for ( i = 0; i < numbers.length; i++) {
                if (numbers[i] == key.code) {
                    return true;
                }
            }
            return false;
        });
    });


    $('srch-button').addEvent('click', function() {
        if (objState == true) {
            myCrop.removeEvents();
            myCrop.crop = null;
            myCrop.destroy();
            objState = false;
        }
        $('take-picture').setStyle('display', 'none');
        $('fakeFrame').setStyle('display', 'block');
        $('captured-pic').set('src', '/scripts/idphoto/img/blank.jpg');
        searchEntry(0);
        disableExport();
		//$('rfid').focus();
    });

    $('id-srch').addEvent('click', function() {
        if (objState == true) {
            myCrop.removeEvents();
            myCrop.crop = null;
            myCrop.destroy();
            objState = false;
        }
        $('id-srch').set('value', '');
        $('take-picture').setStyle('display', 'none');
        $('fakeFrame').setStyle('display', 'block');
        $('captured-pic').set('src', '/scripts/idphoto/img/blank.jpg?' + new Date().getTime());
		$('fake-pic').set('src', '/scripts/idphoto/img/blank.jpg?' + new Date().getTime());
		$('id-name').set('value','');
		$('id-aux').set('value','');
		$('id-expire').set('value','');
		//$('rfid').set('value','');
		$('id-barcode').set('value','');
        $('photo-num').set('html', '');
        $('print-button').set('disabled', true);
        $('encode-button').set('disabled', true);
	$('stf-intern').setStyle('display','none');
        $('new-barcode').setStyle('display', 'none');
    });

    $('take-picture').addEvent('click', function() {
        if (objState == true) {
            myCrop.removeEvents();
            myCrop.crop = null;
            myCrop.destroy();
            objState = false;
        }
        this.setStyle('display', 'none');
        snapPic();
    });

    $('new-other').addEvent('click', function() {
        $('lnk-other').set('href', 'index.php?option=com_jumi&fileid=127&tmpl=component');
        $('lnk-other').click();
    });

    $('new-barcode').addEvent('click', function() {
        if (ct == 'S') {
            var x = new Request({
                url : 'index.php?option=com_jumi&fileid=126&action=gen_studbar&id=' + ref_no,
                method : 'get',
                noCache : true,
                async : false,
                onComplete : function(response) {
                    if (parseInt(response) == -1) {
                        alert('Error connecting to database. Contact CTS department.');
                        return false;
                    } else {
                        searchEntry(1);
                        $('id-barcode').setStyle('background-color', '#ffffff');
                    }
                    $('new-barcode').setStyle('display', 'none');
                }
            }).send();
        }

        if (ct == 'T') {
            var x = new Request({
                url : 'index.php?option=com_jumi&fileid=126&action=gen_staffbar&id=' + ref_no,
                method : 'get',
                noCache : true,
                async : false,
                onComplete : function(response) {
                    if (parseInt(response) == -1) {
                        alert('Error connecting to database. Contact CTS department.');
                        return false;
                    } else {
                        searchEntry(1);
                        $('id-barcode').setStyle('background-color', '#ffffff');
                    }
                    $('new-barcode').setStyle('display', 'none');
                }
            }).send();
        }
    });

    $('print-button').addEvent('click', function() {
        var uid = $('id-srch').get('value');
        printCard(uid);
    });

    $('encode-button').addEvent('click', function() {
        encodeCard();
    });

    $('print-button').set('disabled', true);
    $('encode-button').set('disabled', true);
    $('id-srch').focus();

    var x = new Request({
        url : 'index.php?option=com_jumi&fileid=126&action=barcode_rules',
        noCache : true,
        method : 'get',
        async : false,
        onComplete : function(response) {
            var r = response.split(';');
            staff_barcode = parseInt(r[1]);
            student_barcode = parseInt(r[0]);
        }
    }).send();

    var op = $('sysid').get('value');
    var grp = 32;
    checkInsert(op, grp);
    disableExport();
});

function checkDBMode() {
    var x = new Request({
        url : 'index.php?option=com_jumi&fileid=126&function=checkDBStatus',
        method : 'get',
        noCache : true,
        async : false,
        onComplete : function(response) {
            var r = response.split(';');
            var ip = r[1].split('.');
            if (parseInt(r[2]) == 1) {
                var l = 'Log Only';
            } else {
                var l = 'DB Interact';
            }
            if (parseInt(r[0]) == 1) {
                $('system-mode').set('value', r[0]);
                $('system-log').set('value', r[2]);
                $('photo-title').setStyle('background-color', '#ff0000');
                $('photo-title').set('html', 'System in Disaster Recovery Mode [Host: ' + ip[3].toString() + '] ' + l);
            } else {
                $('system-mode').set('value', 0);
                $('system-log').set('value', 0);
            }
        }
    }).send();
}

function checkInsert(op, grp) {
    var x = new Request({
        url : 'index.php?option=com_jumi&fileid=126&action=security&operator=' + op + '&grp=' + grp,
        noCache : true,
        method : 'get',
        onComplete : function(response) {
            if (parseInt(response) > 0) {
                $('new-other').setStyle('display', 'block');
                $('new-other').setStyle('disabled', false);
            }
        }
    }).send();
}

function searchEntry(param) {
    $('show-busy').setStyle('display', 'block');
    var id = $('id-srch').get('value');
    var x = new Request({
        url : 'index.php?option=com_jumi&fileid=126&action=search_id&id=' + id,
        noCahce : true,
        method : 'get',
		imeout: 5000,
		onTimeout: function(){
			alert('Timeout error. Please try again.');
		},
        async : false,
        onComplete : function(response) {
            if (parseInt(response) == -1) {
                $('show-busy').setStyle('display', 'none');
                alert('Error connecting to database.' + '\n' + 'Please report to CTS department.');
                return false;
            } else if (parseInt(response) == -2) {
                $('show-busy').setStyle('display', 'none');
                alert('No entry found.');
                $('id-srch').set('value', '');
                $('id-srch').focus();
                return false;
			} else if (parseInt(response) == -9) {
						$('show-busy').setStyle('display', 'none');
						alert('Card re-print for user currently blocked.'+'\n'+'Cause: Card was already printed.'+'\n'+'Please reset at CTS department.');
						$('id-srch').set('value', '');
						$('id-srch').focus();
						return false;
            } else {
                var r = response.split(';');
                $('id-name').set('value', r[1]);
                $('id-aux').set('value', r[5]);
                $('id-expire').set('value', r[2]);
                $('id-barcode').set('value', r[3]);
                ref_no = r[0];
                if (r[4] == 'S') {
                    $('card-header').set('src', '/scripts/idphoto/img/student_small.jpg');
                    $('card-type').set('html', 'STUDENT');
                } else if (r[4] == 'T') {
                    $('card-header').set('src', '/scripts/idphoto/img/staff_small.jpg');
                    $('card-type').set('html', 'STAFF');
		    $('stf-intern').setStyle('display','inline');
                } else if (r[4] == 'C') {
                    $('card-header').set('src', '/scripts/idphoto/img/contractor.jpg');
                    $('card-type').set('html', 'CONTRACTOR');
                    $('new-barcode').setStyle('display', 'none');
                } else if (r[4] == 'I') {
                    $('card-header').set('src', '/scripts/idphoto/img/intern_small.jpg');
                    $('card-type').set('html', 'INTERN');
                    $('new-barcode').setStyle('display', 'none');
                } else if (r[4] == 'W') {
                    $('card-header').set('src', '/scripts/idphoto/img/walk_small.jpg');
                    $('card-type').set('html', 'WALKTHROUGH');
                    $('new-barcode').setStyle('display', 'none');
                } else if (r[4] == 'V') {
                    $('card-header').set('src', '/scripts/idphoto/img/visitor_small.jpg');
                    $('card-type').set('html', 'VISITOR');
                    $('new-barcode').setStyle('display', 'none');
                } else if (r[4] == 'X') {
                    $('card-header').set('src', '/scripts/idphoto/img/cleaner_small.jpg');
                    $('card-type').set('html', 'CLEANER');
                    $('new-barcode').setStyle('display', 'none');
                } else if (r[4] == 'P') {
                    $('card-header').set('src', '/scripts/idphoto/img/pensioner_small.jpg');
                    $('card-type').set('html', 'PENSIONER');
                    $('new-barcode').setStyle('display', 'none');
                }

                $('crd-type').set('value', r[4]);
                ct = r[4];

                if (r[6].toString().length > 0) {
                    $('fake-pic').set('src', r[6] + '?' + new Date().getTime());
                } else {
                    $('fake-pic').set('src', '/scripts/idphoto/img/blank.jpg?' + new Date().getTime());
                }

            }

            $('show-busy').setStyle('display', 'none');
            //Check for barcode
            if (r[4] == 'S' && r[3].toString().length <= 0 && param == 0) {
                $('show-busy').setStyle('display', 'block');
                $('id-barcode').setStyle('background-color', '#ff0000');
                var x = new Request({
                    url : 'index.php?option=com_jumi&fileid=126&action=gen_studbar&id=' + r[0],
                    method : 'get',
                    noCache : true,
                    async : false,
                    onComplete : function(response) {
                        if (parseInt(response) == -1) {
                            alert('Error connecting to database. Contact CTS department.');
                            return false;
                        } else {
                            searchEntry(1);
                            $('id-barcode').setStyle('background-color', '#ffffff');
                        }
                    }
                }).send();
            } else {
                if (r[4] == 'S') {
                    $('new-barcode').setStyle('display', 'inline');
                }
            }

            if (r[4] == 'T' && r[3].toString().length <= 0 && param == 0) {
                $('show-busy').setStyle('display', 'block');
                $('id-barcode').setStyle('background-color', '#ff0000');
                var x = new Request({
                    url : 'index.php?option=com_jumi&fileid=126&action=gen_staffbar&id=' + r[0],
                    method : 'get',
                    noCache : true,
                    async : false,
                    onComplete : function(response) {
                        if (parseInt(response) == -1) {
                            alert('Error connecting to database. Contact CTS department.');
                            return false;
                        } else {
                            searchEntry(1);
                            $('id-barcode').setStyle('background-color', '#ffffff');
                        }
                    }
                }).send();
            } else {
                if (r[4] == 'T') {
                    $('new-barcode').setStyle('display', 'inline');
                }
            }

            var x = new Request({
                url : 'index.php?option=com_jumi&fileid=126&action=get_history&uid=' + r[0],
                method : 'get',
                noCache : true,
                onComplete : function(response) {
                    $('photo-num').set('html', response);
                }
            }).send();

            $('take-picture').setStyle('display', 'inline');
            $('print-button').set('disabled', false);
            $('encode-button').set('disabled', false);
        }
    }).send();

    if (ct == 'S' && param == 0) {
        if (student_barcode == 1) {
            var x = new Request({
                url : 'index.php?option=com_jumi&fileid=126&action=gen_studbar&id=' + ref_no,
                method : 'get',
                noCache : true,
                async : false,
                onComplete : function(response) {
                    if (parseInt(response) == -1) {
                        alert('Error connecting to database. Contact CTS department.');
                        return false;
                    } else {
                        searchEntry(1);
                        $('id-barcode').setStyle('background-color', '#ffffff');
                    }
                    $('new-barcode').setStyle('display', 'none');
                }
            }).send();
        }
    }

    if (ct == 'T' && param == 0) {
        if (staff_barcode == 1) {
            var x = new Request({
                url : 'index.php?option=com_jumi&fileid=126&action=gen_staffbar&id=' + ref_no,
                method : 'get',
                noCache : true,
                async : false,
                onComplete : function(response) {
                    if (parseInt(response) == -1) {
                        alert('Error connecting to database. Contact CTS department.');
                        return false;
                    } else {
                        searchEntry(1);
                        $('id-barcode').setStyle('background-color', '#ffffff');
                    }
                    $('new-barcode').setStyle('display', 'none');
                }
            }).send();
        }
    }
}

function printAndSaveCard(id, photo_location) {
    var x = new Request({
        url : 'index.php?option=com_jumi&fileid=126&action=save_photo&id=' + id + '&loc=' + encodeURIComponent(photo_location),
        noCache : true,
        method : 'get',
        onComplete : function(response) {
            if (parseInt(response) == -1) {
                alert('Error saving photo location.');
            }
        }
    }).send();
}

function printAndSaveNewCard(id, photo_location) {
    var x = new Request({
        url : 'index.php?option=com_jumi&fileid=126&action=save_new_photo&id=' + id + '&loc=' + encodeURIComponent(photo_location),
        noCache : true,
        method : 'get',
        onComplete : function(response) {
            if (parseInt(response) == -1) {
                alert('Error saving photo location.');
            }
        }
    }).send();
}


function bomb() {
    if ( typeof myCrop == 'object') {
        myCrop.destroy();
        myCrop = undefined;
    }
}

function printCard(uid) {
		var ct = $('crd-type').get('value');
		var sysid = $('sysid').get('value');
		var loc_userid = $('id-srch').get('value');
		$('captured-pic').set('src', '/scripts/idphoto/img/blank.jpg?' + new Date().getTime());
		$('fake-pic').set('src', '/scripts/idphoto/img/blank.jpg?' + new Date().getTime());
		//$('rfid').set('value','');
		$('id-barcode').set('value','');
		$('stf-intern').setStyle('display','none');
        $('id-name').set('value','');
        $('id-expire').set('value','');
        $('id-aux').set('value','');
        $('id-srch').set('value','');
        $('id-srch').focus();	
	$('stf-intern-box').set('value','0');
	var loc_sysdate = $('sysdate').get('value');
    if (ct == 'S') {
		printAndSaveCard(loc_userid,'/scripts/idphoto/images/thumbs/'+loc_sysdate+'/'+loc_userid+'.jpg');
        $('lnk-card').set('href', 'index.php?option=com_jumi&fileid=130&tmpl=component&uid=' + uid + '&op=' + sysid + '&intern=0');
    } else if (ct == 'T' || ct == 'I' || ct == 'C') {
	if ($('stf-intern-box').checked) { var intern = 1; } else { var intern = 0; }
		printAndSaveCard(loc_userid,'/scripts/idphoto/images/thumbs/'+loc_sysdate+'/'+loc_userid+'.jpg');
        $('lnk-card').set('href', 'index.php?option=com_jumi&fileid=128&tmpl=component&uid=' + uid + '&op=' + sysid + '&intern='+intern);
    } else {
		printAndSaveCard(loc_userid,'/scripts/idphoto/images/thumbs/'+loc_sysdate+'/'+loc_userid+'.jpg');
        $('lnk-card').set('href', 'index.php?option=com_jumi&fileid=131&tmpl=component&uid=' + uid + '&op=' + sysid + '&intern=0');
    }
  	$('captured-pic').set('src', '/scripts/idphoto/img/blank.jpg?' + new Date().getTime());
    $('lnk-card').click();
}

function encodeCard() {
    var bc = $('id-barcode').get('value');
    var mag = bc.substring(4);
    $('lnk-encode').set('href', 'index.php?option=com_jumi&fileid=129&tmpl=component&mag=' + mag);
    $('lnk-encode').click();
}

function disableExport(){
    var sm = $('system-mode').get('value');
    var sl = $('system-log').get('value');
    if (parseInt(sm) == 1) {
        if (parseInt(sl) == 1){
            $('encode-button').set('value','Encoding Disabled');
            $('encode-button').set('disabled',true);
            $('new-other').set('value','Other Disabled');
            $('new-other').set('disabled',true);
            $('new-barcode').set('value','Barcode Disabled');
            $('new-barcode').set('disabled',true);
        }
    }
}
