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
$pagetitle="My Accounts";
$headline="<h1>My Database Accounts</h1>";
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
?>


<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  ?>
<!-- <p>Below are accounts belonging to you which are not authenticated by Active Directory.<p> -->
<?php

if (isset( $_SESSION['username'])) {

    $sql = "select replace(global_name,'.WORLD','') " .' as "SID" , account_status "Account Status",created "Created", Last_login "Last Login",ptime "Password changed" from overdrive.global_users ';
    $sql .= " where upper(username)=upper('" . $_SESSION['username'] . "') order by replace(global_name,'.WORLD','')";

    
    paginate($dds,$page_num);
    $result = $dds->setMaxRecs(90);
    $result = $dds->setSQL($sql);


    $page_count = $dds->getPageCount();
    if ($page_count>0) {
        echo "<h3>Oracle Database</h3>";
        showPagination($dds,$_SERVER['SCRIPT_NAME']);

        unset($address_classes);
        unset($linkURLs);
        unset($linkTargets);
        unset($keycols);
        unset($invisible);
        $linkTargets=null;
        $keycols=null;
        $invisible[5]=true;
/*
        $linkURLs[0] ="database.php?id=";
        $address_classes[0]='DBDetails';
        $linkURLs[2] ="service.php?id=";
        $address_classes[2]='SvcDetails';
        $linkURLs[7] ="host.php?id=";
        $address_classes[7]='HostDetails';
        $address_classes[4]='brandFilter';
        if (isset($_GET['brand'])) {
            $newState=$stateVar;
            unset($newState['brand']);
            $linkURLs[4] = $_SERVER['SCRIPT_NAME'] . newVars(1,$newState) . '&unset=';
        } else {
            $linkURLs[4] = $_SERVER['SCRIPT_NAME'] . newVars(1) . '&brand=';

        }
*/
        $table=new HTMLTable($dds->getFieldNames(),$dds->getFieldTypes());
        $table->defineRows($linkURLs,$keycols,$invisible,$address_classes,$linkTargets);
        $table->start();
        while ($result_row = $dds->getNextRow()){
            $table->addRow($result_row);
        }
        $table->finish();
        showPagination($dds,$_SERVER['SCRIPT_NAME']);
    }



}

?>

<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>

</body></html>