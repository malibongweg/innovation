{source}
<?php
     $dbo =& JFactory::getDBO();
	 JHtml::_('jquery.framework');
?>

<script type="text/javascript">

function showDetails(id){
	alert('Show details->'+id);
}

function showDefault(id){
	alert('Show default->'+id);
}

</script>

<div class="container">
        <div class="row">
        <div class="col-xs-12 left block hidden-md hidden-lg hidden-sm">
            <?php
                $dbo->setQuery("select id,app_title,app_description,app_url,app_image from opa_applications order by favourite desc");
                $result = $dbo->loadObjectList();
                foreach($result as $row){
                    echo "<div class='row'>";
                    echo "<div class='col-xs-12 left block hidden-md hidden-lg hidden-sm'>";
					echo "<div style='margin: 5px; border: 1px solid #c5c5c5; background-color: #d5d5d5; overflow: hidden'>";
                        echo "<div style='float: left; margin: 0 5px 0 0'><img src='".$row->app_image."' width='100' height='100' class='img-responsive' border='0' alt=''>";
                        echo "</div>";
                        echo "<p style='font-weight: bold; font-size: 14px'>".$row->app_title."</p>";

						echo "<div style='margin: 5px 5px 5px 105px; padding: 3px; border: 1px solid #b8b8b8'>";
						echo "<p id='xs-".$row->id."'  onmouseover='javascript: showDetails(".$row->id.",\"xs-".$row->id."\");' onmouseout='javascript: showDefault(".$row->id.",\"xs-".$row->id."\");'>All Campuses</p>";
						echo "</div>";

                    echo "</div>";
                    echo "</div>";
					echo "</div>";
                }
            ?>
        </div>
        </div>
      

 
        <div class="row">
        <div class="col-sm-12 left block hidden-xs hidden-md hidden-lg">
            <?php
                $dbo->setQuery("select count(*) as cnt from opa_applications");
                $cnt = $dbo->loadResult();
                $dbo->setQuery("select id,app_title,app_description,app_url,app_image from opa_applications order by favourite desc");
                $result = $dbo->loadObjectList();
                $rec = 0;
                $closeRow = false;
                foreach($result as $row){
                        if ($rec == 0) {
                            echo "<div class='row'>\n";
                        }
                        if (($rec % 2 == 0) && $rec > 0) {
                            echo "</div>\n";
                            echo "<div class='row'>\n";
                            $closeRow = true;
                        }
                        
                    echo "<div class='col-sm-6'>\n";
                    echo "<div style='margin: 5px; border: 1px solid #c5c5c5; background-color: #d5d5d5; overflow: hidden'>";
                        echo "<div style='float: left; margin: 0 5px 0 0'>\n";
                        echo "<img src='".$row->app_image."' width='100' height='100' border='0' class='img-responsive' alt=''>\n";
                        echo "</div>\n";
                        echo "<p style='font-weight: bold; font-size: 14px'>".$row->app_title."</p>\n";
                    echo "</div>\n";
                    echo "</div>";
                    
                    ++$rec;
                }
            if ($closeRow == true) {
                echo "</div>";
            }
            ?>
        </div>
        </div>
       
        <div class="row">
        <div class="col-md-12 left block hidden-xs hidden-sm">
                <?php
                $dbo->setQuery("select count(*) as cnt from opa_applications");
                $cnt = $dbo->loadResult();
                $dbo->setQuery("select id,app_title,app_description,app_url,app_image from opa_applications order by favourite desc");
                $result = $dbo->loadObjectList();
                $rec = 0;
                $closeRow = false;
                foreach($result as $row){
                        if ($rec == 0) {
                            echo "<div class='row'>\n";
                        }
                        if (($rec % 4 == 0) && $rec > 0) {
                            echo "</div>\n";
                            echo "<div class='row'>\n";
                            $closeRow = true;
                        }
                        
                    echo "<div class='col-md-3'>\n";
					 echo "<div style='margin: 5px; border: 1px solid #c5c5c5; background-color: #d5d5d5; overflow: hidden'>";
                        echo "<div style='float: left; margin: 0 5px 0 0'>\n";
                        echo "<img src='".$row->app_image."' width='100' height='100' border='0' class='img-responsive' alt=''>\n";
                        echo "</div>\n";
                        echo "<p style='font-weight: bold; font-size: 14px'>".$row->app_title."</p>\n";
                    echo "</div>\n";
					echo "</div>";
                    
                    ++$rec;
                }
            if ($closeRow == true) {
                echo "</div>";
            }
            ?>
        </div>
        </div>

        
</div>
{/source}