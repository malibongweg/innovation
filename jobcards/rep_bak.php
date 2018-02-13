<script type="text/javascript" src="/scripts/jobcards/mootools.min.js"></script>
<script type="text/javascript" src="/scripts/json.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load('visualization', '1.0', {'packages':['corechart']});
</script>
<?php
		define('_JEXEC', 1);
		define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);//realpath(dirname(__FILE__)));
		require_once ( JPATH_BASE .'/includes/defines.php' );
		require_once ( JPATH_BASE .'/includes/framework.php' );
		require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );

		$dbo = &JFactory::getDBO();
		$doc = &JFactory::getDocument();
?>
<style>statusReportData&cmp='+data.Records[i].campus_code,
.btn {
  background: #CACCC7;
  background-image: -webkit-linear-gradient(top, #CACCC7, #CACCC7);
  background-image: -moz-linear-gradient(top, #CACCC7, #CACCC7);
  background-image: -ms-linear-gradient(top, #CACCC7, #CACCC7);
  background-image: -o-linear-gradient(top, #CACCC7, #CACCC7);
  background-image: linear-gradient(to bottom, #CACCC7, #CACCC7);
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0px;
  font-family: Arial;
  color: #000000;
  font-size: 12px;
  padding: 8px 10px 8px 10px;
  text-decoration: none;
}

.btn:hover {
  background: #8e9599;
  background-image: -webkit-linear-gradient(top, #8e9599, #8e9599);
  background-image: -moz-linear-gradient(top, #8e9599, #8e9599);
  background-image: -ms-linear-gradient(top, #8e9599, #8e9599);
  background-image: -o-linear-gradient(top, #8e9599, #8e9599);
  background-image: linear-gradient(to bottom, #8e9599, #8e9599);
  text-decoration: none;
}


.pagebreak { page-break-before: always; }
@page {
    size: 21cm 29.7cm;
    margin: 5mm 5mm 5mm 5mm; /* change the margins as you want them to be. */
}
body {
        margin: 0 auto;
    }
</style>
<script type="text/javascript">
window.addEvent('domready',function(){

	window.parent.$j.colorbox.resize({width: 600, height: 600});

	var x = new Request({
		url: '/scripts/jobcards/chart_data.php?action=statusCampusEntries',
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			var data = json_parse(response);
			for (var i = 0; i<data.Records.length; ++i){
				var x = new Request({
					url: '/scripts/jobcards/chart_data.php?action=statusReportRatings&cmp='+data.Records[i].campus_code,
					method: 'get',
					noCahce: true,
					async: false,
					onComplete: function(response){
						new Element('div',{ 'id': 'chart-'+data.Records[i].campus_code, 'style': 'border: 1px solid #c8c8c8; margin: 20px' }).inject($('charts'));
						drawChart(response,data.Records[i].campus_code,data.Records[i].campus_name);
						if ((i % 2) == 1) {
							new Element('div',{ 'class': 'pagebreak' }).inject($('charts'));
						}
					}
				}).send();
			}
		}
	}).send();


});

    	function drawChart(dbData,cde,cmp) {
        // Create the data table.
        var data = new google.visualization.DataTable(dbData);
        /* Set chart options */
        var options = {'title': cmp,
                       'width':500,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart-'+cde));
        chart.draw(data, options);
      }
    </script>

<div style="background-color: #c8c8c8; padding: 3px; text-align: center">
<p style="font-family: Verdana, Arial; font-size:14px">CPUT MIANTENANCE DEPARTMENT</p>
<p style="font-family: Verdana, Arial; font-size:12px">Jobcard Ratings Report</p>
<p style="font-family: Verdana, Arial; font-size:12px">Report Date: <?php echo Date('Y-m-d'); ?></p>
<input type="button" value="Print" class="btn" onclick="window.print();" />
</div>
<div id="charts"></div>