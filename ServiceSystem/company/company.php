<?php
//header('Content-Type: text/plain; charset=ISO-8859-1');
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/ServiceSystem/company/company.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");

?>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Service System - Company Data" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
</form>
			<div class="art-block">
            <div class="art-block-tl"></div>

            <div class="art-block-tr"></div>
            <div class="art-block-bl"></div>
            <div class="art-block-br"></div>
            <div class="art-block-tc"></div>
            <div class="art-block-bc"></div>
            <div class="art-block-cl"></div>
            <div class="art-block-cr"></div>
            <div class="art-block-cc"></div>
            <div class="art-block-body" id="bh">

            <div class="art-blockheader">
            <div class="l"></div>
            <div class="r"></div>
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Service System - Company Data</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
 <!----------------------------------------------New product----------------------------------------------------------------------------------------------------------------->                
  <div class="main-div" id="addNewCompany" style="display: block">
        <div style = "margin-left: 450px">
        <input type="button" id="add-new" class="button art-button" value="Add New Company" />
         
	<div style="clear: both"></div>
        </div>
    
</div>                             
                

<!-----------------------------------------------------------Product Data---------------------------------------------------------------------------------------------------------------->
<div class="main-div" id="mainDiv">
<div class="main-header"><span id="frame-title" style="font-weight: bold">Company Data</span></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	
		<div id="header-details" style="position: relative; display: block">
				<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
						<table width="100%" border="0" style="table-layout: fixed">
						<tr>
                                                        <th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">COMPANY ID</th> 
							<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">COMPANY NAME</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">CONTACT #</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">EMAIL</th>
                                                        <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
						</tr>
						</table>
				</div>
							<div id="ajax-company" style="position: relative; width: auto; height: auto; display: none">
								<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
							</div>

                      <div id="company-form" style="position: relative; width: auto; height: 500px;  display: block; overflow: scroll"></div>
	    </div>

</div>
<!----------------------------------------------Staff Data Form----------------------------------------------------------------------------------------------------------------->
        <div class="main-div" id="company-forms">
                    <div class="main-header"><strong>Company Data</span></strong></div>
                    <!--form-->
                    <div id="companyData" style="position: relative; width: auto; height: auto; display: block">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>    
                        <form id="companyForm">
                            <input type="hidden" id="company-action" />
                                <input type="hidden" size="35" name="coID" id="coID" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/>
                                    <br />
                                <label class="input_label">Company Name:</label>
                                    <input type="text" size="35" name="companyName" id="companyName" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Contact #:</label>
                                    <input type="text" size="35" name="contactNumber" id="contactNumber" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Email:</label>
                                    <input type="text" size="35" name="email" id="email" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                    <br/>
                                     <div align="center" style="position: relative;">
                                         <div id="add_rec" style="display: none"><input type="submit" value="Save" id="company-save" class="button art-button"/></div>&nbsp;
                                         <div id="edit_rec" style="display: none" align="center"><input type="button" value="Edit Company" id="company-edit" class="button art-button"/>
                                        <input type="button" value="Delete Record" id="delete-record" class="button art-button" />
                                        </div>
                                         <br/>
                                        <input type="button" value="Close" id="company-cancel-update" class="button art-button"/>
                                     </div>                    
<!-----------------------------------------
                                     <div align="center" style="position: relative;"><br/>
                                         <div id="add_rec" style="display: none"> <input type="submit" value="Save New" id="product-save" class="button art-button"/></div>&nbsp;
                                         <div id="edit_rec" style="display: none" align="center"><input type="button" value="Edit Product" id="product-edit" class="button art-button"/>&nbsp;
                                        <input type="button" id="delete-record" class="button art-button" value="Delete Record" />
                                        </div>
                                         <br/>
                                        <input type="button" value="Close" id="product-cancel-update" class="button art-button"/>
                                     </div>   ---->
                        </form>
                            <!--End form-->
                    </div>                                        
                </div>
<!----------------------------------------------End of Staff Data Form---------------------------------------------------------------------------------------------------------->
                
</div> </div></div></div>
