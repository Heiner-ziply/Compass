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
  
  $(".TeamDetails").html('<img src="images/doc.png" height="24">');
  $(".Environments").html('<img src="images/lb.png" height="24">');
  $(".Services").html('<img src="images/service.png" height="24">');
  $(".Databases").html('<img src="images/db.png" height="24">');
  //This function enables the user to toggle the help section on and off by clicking
	$("#ToggleHelp").click(function(){
	  $("#top_help").toggle();
	});
	$("#top_help").hide();
	//This function enables the user to toggle the SQL section on and off by clicking
	  $("#ToggleSQL").click(function(){
	  $("#SQLEcho").toggle();
	});
	$("#SQLEcho").hide();
});


</script>

<?php
//The following four lines provide the variables that pgTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Team directory";
$headline='<h1>Team directory</h1>';
$top_help_text='<h3></h3>';
//$top_help_text=$top_help_text . 'Click on the next icon in the second column to see the environments for each application. Click on the clipboard (<img src="images/doc.png" alt="clipboard">) next to the APP ID to see the application page in the company&rsquo;s master application registry.</p>';
include ('Hydrogen/pgTemplate.php');

?>


<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<div>
<div class="w3-main w3-container w3-padding-16" id="top_help">
<p><?php echo $top_help_text; ?></p>
</div>
</div>

<?php include 'Hydrogen/elemLogoHeadline.php';  	 ?>

<?php

if (isset($_GET["pagenum"]))  {
	$page_num=sanitizeGetVar("pagenum");
} else $page_num=1;

$sql='select id as "Details", display_name as "Name", ';
$sql.=" nvl2(email,'<a href=' || chr(34) || 'mailto:' || email || chr(34) || '>' || email || '</a>','') " ;
$sql.= 'as "email", ';
$sql.=" nvl2(home_page,'<a href=' || chr(34) || home_page || chr(34) || '>' || home_page || '</a>','') " ;
$sql.= 'as "Home Page", remedy_queue as "Remedy ID" from support_team t where id > 0 and not exists (select 8 from support_team c where c.parent_id = t.id)';

$sql=$sql . " order by 2";

function getVars($pg) {
	$retval="?pagenum=" . $pg;
	return $retval;
}

paginate($dds,$page_num);
$result = $dds->setMaxRecs(50);
$result = $dds->setSQL($sql);


$page_count = $dds->getPageCount();
if ($page_count>0) {
	showPagination($dds,$_SERVER['SCRIPT_NAME']);

	unset($address_classes);
	unset($linkURLs);
	unset($linkTargets);
	unset($keycols);
	unset($invisible);
	$linkTargets=null;
	$keycols=null;
	//$invisible[0]=true;
	$invisible[5]=true;

	
	
	$linkURLs[0] = 'team.php?id=';
	$address_classes[0]='TeamDetails';
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
<div class="sql_debug"><p>
<?php 
//echo $sql; 
?>
</p></div>
<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>
</body></html>

