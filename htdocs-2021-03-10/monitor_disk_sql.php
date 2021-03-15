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
  $(".HostDetails").html('<img src="images/doc.png" height="24">');  
  $(".SvcDetails").html('<img src="images/doc.png" height="24">');
  $(".AppDetails").html('<img src="images/doc.png" height="24">');
  $(".Environments").html('<img src="images/lb.png" height="24">');
  $(".Services").html('<img src="images/service.png" height="24">');
  $(".Databases").html('<img src="images/db.png" height="24">');

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
	echo '$(".stypeFilter").html(';
	echo "'<img";
	
	if(isset($_GET['servicetype'])) {
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
$pagetitle="Disk usage";
$headline = '<h1>SQL Server disk usage</h1>' ;
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  ?>
<?php

$sql = 'select h.host_id as "Host", l.* from nw1_disk_usage l left join host h on upper(l.Hostname) = upper(h.hostname) where Pct_Used > 60 order by Pct_Used desc';

paginate($dds,$page_num);
$result = $dds->setMaxRecs(30);
$result = $dds->setSQL($sql);

$page_count = $dds->getPageCount();
if ($page_count>0) {
	showPagination($dds,$_SERVER['SCRIPT_NAME']);

	unset($address_classes);
	unset($linkURLs);
	unset($linkTargets);
	unset($keycols);
    unset($invisible);
    $invisible[7]=true;
    $linkURLs[0]="host.php?id=";
    $address_classes[0]="HostDetails";

	$table=new HTMLTable($dds->getFieldNames(),$dds->getFieldTypes());
	$table->defineRows($linkURLs,$keycols,$invisible,$address_classes,$linkTargets);
	$table->start();
	while ($result_row = $dds->getNextRow()){
		$table->addRow($result_row);
	}
	$table->finish();
	showPagination($dds,$_SERVER['SCRIPT_NAME']);
}



?>
<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>
</body></html>

