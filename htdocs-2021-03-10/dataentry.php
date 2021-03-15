<style>
ul.pagination li {
   display: inline;
   }
   
ul.pagination {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
</style>
<script src="Hydrogen/sorttable.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  //The following two lines replace link text with left and right arrows as appropriate
  $(".nextlink").html('<img src="images/next.png" height="16">');
  $(".prevlink").html('<img src="images/prev.png" height="16">');
  $(".SvcDetails").html('<img src="images/doc.png" height="24">'); 
  $(".DBDetails").html('<img src="images/doc.png" height="24">'); 
   $(".HostDetails").html('<img src="images/doc.png" height="24">'); 
  $('a.SQL:contains("orcl")').each(function(){ 
            //var oldUrl = $(this).attr("href"); // Get current url
            var newUrl = $(this).text(); // Create new url
            $(this).attr("href", newUrl); // Set href value
        });
		
  $('a.SQL:contains("orcl")').html('<img src="images/sqlplus.png" height="24">');  
  
<?php
	echo '$(".appFilter").html(';
	echo "'<img";
	
	if(isset($_GET['app_id'])) {
	  echo ' src="images/filter-off.png" ';
	} else {
	  echo ' src="images/filter-on.png" ';
	}
	
	echo 'height="24"';
	echo ">');";
?>	 
     
  
<?php

	echo '$(".envFilter").html(';
	echo "'<img";
	
	if(isset($_GET['envid'])) {
	  echo ' src="images/filter-off.png" ';
	} else {
	  echo ' src="images/filter-on.png" ';
	}
	
	echo 'height="24"';
	echo ">');";
?>	
 
     
<?php
	echo '$(".brandFilter").html(';
	echo "'<img";
	
	if(isset($_GET['brand'])) {
	  echo ' src="images/filter-off.png" ';
	} else {
	  echo ' src="images/filter-on.png" ';
	}
	
	echo 'height="24"';
	echo ">');";
?>	 
     
  
  //This function enables the user to toggle the help section on and off by clicking
	$("#ToggleHelp").click(function(){
	  $("#top_help").toggle();
	});
	$("#top_help").hide();
});


</script>
<?php
//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Data Entry";
$headline="<h1>Data Entry</h1>";
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
?>


<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  ?>
<?php

//Only allow logged-in users to create new data
if (isset( $_SESSION['username'])) {

  //This page will present itself differently in progressive steps.
  // 1. (No GET values) Ask the user what kind of entity they want to add
  // 2. (GET an entity value) Ask for data for the new entity
  // 3. (GET new data ) Insert the required record(s) and confirm. Give links to see the new one, to create another like it, or do something else

  //increase default record limit for drop-downs
  $dds->setMaxRecs(999);
  $step=1;
  if (isset($_GET['entity'])) $step=2;
  if (isset($_GET['newdata'])) $step=3;

  if($step==1) {
    echo '<form action="dataentry.php">
    <p>I want to register a new:</p>
    
    <input type="radio" id="app" name="entity" value="1">
    <label for="app">Application</label><br>
    
    <input type="radio" id="host" name="entity" value="2">
    <label for="host">Server (host machine)</label><br>
    
    <input type="radio" id="service" name="entity" value="3">
    <label for="service">Service (database, etc)</label><br>
    
    
    <input type="radio" id="vendor" name="entity" value="4">
    <label for="vendor">Vendor</label>
    
    <br><br>
    <input type="submit" value="Submit">
    
    </form>
    </P>
    ';

  } //Step one

  if($step>1) {
    if ($_GET['entity']==1) require_once ('forms/new_app.inc.php');
    if ($_GET['entity']==2) require_once ('forms/new_server.inc.php');
    if ($_GET['entity']==3) require_once ('forms/new_service.inc.php');
    if ($_GET['entity']==4) require_once ('forms/new_vendor.inc.php');
    if ($_GET['newdata']=="app") require_once ('forms/new_app.inc.php');
    if ($_GET['newdata']=="service") require_once ('forms/new_service.inc.php');
    if ($_GET['newdata']=="server") require_once ('forms/new_server.inc.php');
    if ($_GET['newdata']=="vendor") require_once ('forms/new_vendor.inc.php');
    if ($step==2) show_form();
    if ($step==3) parse_new_data();
  }


} //if logged in
unset ($settings['show_sql']);
?>

<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>

</body></html>