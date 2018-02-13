<!doctype>
<html>
<head>
<script type="text/javascript" src="/scripts/jobcards/mootools.min.js"></script>
<script type="text/javascript" src="/scripts/jobcards/jspdf.min.js"></script>
<script type="text/javascript" src="/scripts/json.js"></script>
</head>
<body>
<input type="hidden" id="campus" value="<?php echo $_GET['campus']; ?>" />
<input type="hidden" id="start-date" value="<?php echo $_GET['sdate']; ?>" />
<input type="hidden" id="end-date" value="<?php echo $_GET['edate']; ?>" />

<script type="text/javascript">
var line = 35;

jsPDF.API.myText = function(txt, options, x, y) {
    options = options ||{};
    if( options.align == "center" ){
        // Get current font size
        var fontSize = this.internal.getFontSize();

        // Get page width
        var pageWidth = this.internal.pageSize.width;
        txtWidth = this.getStringUnitWidth(txt)*fontSize/this.internal.scaleFactor;
        x = ( pageWidth - txtWidth ) / 2;
    } else if ( options.align == "right" ){
    	var fontSize = this.internal.getFontSize();
    	var pageWidth = this.internal.pageSize.width;
        txtWidth = this.getStringUnitWidth(txt)*fontSize/this.internal.scaleFactor;
        x = ( pageWidth - txtWidth )-7;
    }

    this.text(txt,x,y);
};

jsPDF.API.getPageWidth = function() {
    var pageWidth = this.internal.pageSize.width;
    return (pageWidth-10);
};

function setHeader() {
	doc.setFont('helvetica');
	doc.setTextColor(0,0,0);
	doc.setFontSize(14);
	doc.setDrawColor(3,60,103);
	var pw = doc.getPageWidth();
	doc.rect(5,5,pw,20);
	var imgData = 'data:image/jpeg;base64,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';//+ Base64.encode('/scripts/jobcards/images/cput.jpg');
	doc.addImage(imgData, 'JPEG', 5, 5, 80, 20);
	doc.myText('Maintenance Department',{align: 'right'},1,10);
	doc.setFontSize(10);
	doc.myText('Campus Building Repair Cost',{align: 'right'},1,15);
	doc.setFontSize(10);
	doc.myText('Date: '+repDate,{align: 'right'},1,20);
	doc.myText('Report Date Range: '+$('start-date').get('value')+' to '+$('end-date').get('value'),{align: 'right'},1,24);
}

function drawPageNo() {
	doc.setFontSize(10);
	doc.setTextColor(0,0,0);
	doc.text(170,285, 'Page: ' + pageNo);
	++pageNo;
}

var doc = new jsPDF('p','mm','a4');
var cnt = 1;
var pageNo = 1;
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10) {
    dd='0'+dd;
}
if(mm<10) {
    mm='0'+mm;
}
var repDate = dd+'/'+mm+'/'+yyyy;

function reportCosts(){
	setHeader();
	var sdate = $('start-date').get('value');
	var edate = $('end-date').get('value');
	var campus = $('campus').get('value');
	var y = new Request({
		url: '/scripts/jobcards/chart_data.php?action=getCampusName&campus='+campus,
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			doc.setFontSize(12);
			doc.setFontStyle('bold');
			doc.text(response,7,line);
			line = line + 7;
				/*Get all buildings*/
				var z = new Request({
					url: '/scripts/jobcards/chart_data.php?action=getCampusBuildings&campus='+campus,
					method: 'get',
					noCache: true,
					async: false,
					onComplete: function(response){
						var data = json_parse(response);
						for (var i=0;i<data.Records.length; ++i){
								doc.setFontSize(12);
								doc.setFontStyle('bold');
								doc.text(data.Records[i].build_name,7,line);
								line = line + 5;
								var x = new Request({
								url: '/scripts/jobcards/chart_data.php?action=getCosts&sdate='+sdate+'&edate='+edate+'&campus='+campus+'&build='+data.Records[i].build_code,
								method: 'get',
								noCache: true,
								async: false,
								onComplete: function(response){
									var pw = doc.getPageWidth();
									doc.setFontSize(10);
									doc.setDrawColor(192,192,192);
									doc.setFillColor(192,192,192);
									doc.rect(5,line,pw,5,'FD');
									line = line + 4;
									doc.setFontSize(8);
									doc.text('JOBCARD#',7,line);
									doc.text('CAPETURE DATE',50,line);
									doc.text('MATERIAL COST',150,line);
									doc.text('LABOUR COST',180,line);
									doc.setFontStyle('normal');
									line = line + 5;
									var data2 = json_parse(response);
									var mat_total = 0;
									var lab_total = 0;
									for (var x=0;x<data2.Records.length;++x){
										doc.text(data2.Records[x].id,7,line);
										doc.text(data2.Records[x].capture_date,50,line);
										doc.text(data2.Records[x].material_cost,150,line);
										doc.text(data2.Records[x].labour_cost,180,line);
										mat_total = mat_total + parseFloat(data2.Records[x].material_cost);
										lab_total = lab_total + parseFloat(data2.Records[x].labour_cost);
										line = line + 5;
									}
									doc.setDrawColor(192,192,192);
									doc.setFillColor(192,192,192);
									doc.rect(145,line-3,20,5,'FD');
									doc.rect(175,line-3,20,5,'FD');
									doc.text(mat_total.toFixed(2),150,line);
									doc.text(lab_total.toFixed(2),180,line);
									line = line + 10;
								}
								}).send();
								if (line >=250) {
									drawPageNo();
									doc.addPage();
									line=35;
									setHeader();
								}
						}
						doc.save('/scripts/jobcards/tmp/costs.pdf');
						window.parent.$j.colorbox.close();
					}
				}).send();
		}
	}).send();

}
window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({width: 600, height: 200});
	setTimeout('reportCosts();',500);
});
</script>


<div id="gen-charts" style="font-family: Verdana,Arial; font-size: 12px">
<img alt="" src="/images/kit-ajax.gif" style="vertical-align: middle"> Generating report...Please wait.
</div>
</body>
</html>

