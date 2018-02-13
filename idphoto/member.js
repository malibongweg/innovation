var stdOK = false;
var localstdNo = "";
var orgId = 0;
var tabCancel = false;
var branch = "";
var clr = true;

window.addEvent("domready",function(){

	$("srch-button").addEvent("click",function(e){
		var member = $("srch-std").get("value");
		searchMember(member);
	});

	$("srch-std").addEvent("focus",function(e){
		var member = $("srch-std").set("value","");
		clearAll();
		disableFields();
		$("new-div1").setStyle("display","none");
		$("new-div2").setStyle("display","none");
		$("new-div3").setStyle("display","none");
	});

	$("new-member").addEvent("click",function(e){
		addNewMember();
	});

	$("prn-member").addEvent("click",function(e){
		var uid = $("mem-stdno").get("value");
		printCard(uid);
	});

	$("del-member").addEvent("click",function(e){
		delMember();
	});

	$("mem-stdno").addEvent("blur",function(){
		var stdno = $("mem-stdno").get("value");
		lookupStudent(stdno,false);
	});

	$("mem-stdno").addEvent("focus",function(){
		if (clr == true){
			$("mem-stdno").set("value","");
		} else { clr = true };
	});
	

	clearAll();
		disableFields();
		$("new-div1").setStyle("display","none");
		$("new-div2").setStyle("display","none");
		$("new-div3").setStyle("display","none");
});

function searchMember(stdno){
	$("mem-org").empty();
	var x = new Request({
		url: "/scripts/idphoto/members.php?action=search_member&uid="+stdno,
		noCache: true,
		async: false,
		onComplete: function(response){
			var data = json_parse(response);
			if (parseInt(data.code) < 0){
				alert("No record found. Please insert new record.");
				enableFields();
				$("new-div1").setStyle("display","inline-block");
				clr = false;
				$("mem-stdno").focus();
				$("mem-stdno").set("value",stdno);
				orgId = 0;
			} else {
				orgId = data.Record.org_id;
				disableFields();
				$("new-div2").setStyle("display","inline-block");
				$("new-div3").setStyle("display","inline-block");
				branch = data.Record.branch;
				orgId = data.Record.org_id;
				lookupStudent(stdno,true);
			}
		}
	}).send();
}

function lookupStudent(stdno,flag){
	var x = new Request({
		url: "/scripts/idphoto/members.php?action=lookup_member&uid="+stdno,
		noCache: true,
		async: false,
		onComplete: function(response){
			var data = json_parse(response);
			if (parseInt(data.code) < 0){
				alert("No record found.");
				$("mem-stdno").set("value","");
				$("mem-stdno").focus();
				disableFields();
			} else {
				if (flag == true){
					$("mem-stdno").set("value",data.Record.stud_numb);
					$("mem-name").set("value",data.Record.membername);
					$("mem-branch").set("value",branch);
					loadOrg(orgId);
				} else {
					$("mem-branch").set("value",data.Record.branch);
					$("mem-name").set("value",data.Record.membername);
					$("mem-branch").focus();
				}
			}
			loadOrg(orgId);
		}
	}).send();
}

function loadOrg(id){
	$("mem-org").empty();
	var x = new Request({
		url: "/scripts/idphoto/members.php?action=load_orgs",
		noCache: true,
		async: true,
		onComplete: function(response){
			var data = json_parse(response);
			if (parseInt(data.code) < 0){
				alert("No organizations found.");
			} else {
				for (var i=0;i<data.Record.length ;i++ ){
					if (data.Record[i].id == id){
						new Element('option',{ 'value': data.Record[i].id,'text':data.Record[i].org_name,'selected':true}).inject($('mem-org'));
					} else {
						new Element('option',{ 'value': data.Record[i].id,'text':data.Record[i].org_name}).inject($('mem-org'));
					}
				}
			}
		}
	}).send();
}

function enableFields(){
	$("mem-stdno").disabled = false;
	$("mem-name").disabled = false;
	$("mem-branch").disabled = false;
	$("mem-org").disabled = false;
}

function disableFields(){
	$("mem-stdno").disabled = true;
	$("mem-name").disabled = true;
	$("mem-branch").disabled = true;
	$("mem-org").disabled = true;
}

function clearAll(){
	$("mem-stdno").set("value","");
	$("mem-name").set("value","");
	$("mem-branch").set("value","");
	$("mem-org").empty();
}

function addNewMember(){
	if ($('mem-branch').get('value').length == 0) {
		alert("Enter branch name.");
		return false;
	} else {
		var smem = $("mem-stdno").get("value");
		var sname = $("mem-name").get("value");
		var sbranch = $("mem-branch").get("value");
		var sorg = $("mem-org").get("value");
		var x = new Request({
			url: "/scripts/idphoto/members.php?action=save_mem",
			noCache: true,
			async: true,
			data: $("mem-form"),
			method: "post",
			onComplete: function(response){
				$("new-div1").setStyle("display","none");
				$("new-div2").setStyle("display","inline-block");
				$("new-div3").setStyle("display","inline-block");
				disableFields();
				alert("Saved.");
			}
		}).send();
	}
}

function delMember(){
	if (confirm("Are you sure ?")) {
		enableFields();
		var x = new Request({
			url: "/scripts/idphoto/members.php?action=del_mem",
			noCache: true,
			async: true,
			data: $("mem-form"),
			method: "post",
			onComplete: function(response){
				$("new-div1").setStyle("display","none");
				$("new-div2").setStyle("display","none");
				$("new-div3").setStyle("display","none");
				disableFields();
				alert("Deleted.");
			}
		}).send();
	}
}

function printCard(uid) {
		var org = $("mem-org").getSelected().get("value");
        $('mem-card-lnk').set('href', 'index.php?option=com_jumi&fileid=238&tmpl=component&uid=' + uid);
	    $('mem-card-lnk').click();
}
