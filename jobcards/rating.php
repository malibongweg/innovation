<script type="text/javascript" src="/scripts/jobcards/mootools.min.js"></script>
<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);//realpath(dirname(__FILE__)));
require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );
require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );

$dbo = &JFactory::getDBO();
$doc = &JFactory::getDocument();


$sql = sprintf("select a.job_rating from jobcards.jobcards a where a.cde = '%s'",$_GET['cde']);
$dbo->setQuery($sql);
$result = $dbo->loadResult();
echo "<input type='hidden' id='rating' value='".$result."' />";
?>
<style>
.box {
    -moz-box-shadow: 5px 7px 5px #000000;
    -webkit-box-shadow: 5px 7px 5px #000000;
    box-shadow: 5px 7px 5px #000000;
    width: 640px;
    height: auto;
    margin: 0 auto;
    background: #fcfff4; /* Old browsers */
background: #c5deea; /* Old browsers */
background: -moz-linear-gradient(top,  #c5deea 0%, #8abbd7 31%, #066dab 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#c5deea), color-stop(31%,#8abbd7), color-stop(100%,#066dab)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #c5deea 0%,#8abbd7 31%,#066dab 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #c5deea 0%,#8abbd7 31%,#066dab 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #c5deea 0%,#8abbd7 31%,#066dab 100%); /* IE10+ */
background: linear-gradient(to bottom,  #c5deea 0%,#8abbd7 31%,#066dab 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#c5deea', endColorstr='#066dab',GradientType=0 ); /* IE6-9 */
padding: 10px;
font-family: Verdana,Arial;

}
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

</style>
<script type="text/javascript">
window.addEvent('domready',function(){
	var rating = $('rating').get('value');
	if (parseInt(rating) > 0){
		alert('Jobcard was already rated. Thank you.');
	} else {
		$('rating-div').setStyle('display','block');
	}

	$('submit-rating').addEvent('submit',function(e){
		e.stop();
		var x = new Request({
			//url: 'index.php?option=com_jumi&fileid=104&action=saveRating',
			url: '/scripts/jobcards/rating_db.php',
			method: 'post',
			data: this,
			onComplete: function(response){
				$('rating-div').setStyle('display','none');
				$('rating-thanks').setStyle('display','block');
			}
		}).send();
	});
});

</script>

<div id="rating-div" style="display: none">

<div class="box">
<p style="text-align: center; font-weight: bold; font-size:22px">Cape Peninsula University of Technology</p>
<p style="text-align: center; font-weight: bold; font-size:16px">Maintenance Department</p>
<p style="text-align: center">Please rate our service</p>
	<form id="submit-rating">
	<input type="hidden" name="rating_cde" value="<?php echo $_GET['cde']; ?>" />
	<div style="margin: 0 auto; width: 60%; padding-left: 45%">
		<input type="radio" name="job_rating" checked value="4">Excelent<br />
		<input type="radio" name="job_rating" value="3">Good<br />
		<input type="radio" name="job_rating" value="2">Fair<br />
		<input type="radio" name="job_rating" value="1">Poor<br />
	</div>
	<div style="margin: 0 auto; width: 60%; text-align: center; padding-top: 5px">
		<textarea name="rating_details" cols="50" rows="3" placeholder="Comments..."></textarea><br /><br />
		<input type="submit"  value="   Rate   " class="btn" />
	</div>
	</form>
</div>
</div>


<div id="rating-thanks" style="display: none">

<div class="box">
<p style="text-align: center; font-weight: bold; font-size:22px">Cape Peninsula University of Technology</p>
<p style="text-align: center; font-weight: bold; font-size:16px">Maintenance Department</p>
<p style="text-align: center">Thank you for your input.</p>

</div>
</div>

