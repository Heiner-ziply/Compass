<style>

table.b, th.b, td.b {
  border: 1px solid black;
  padding: 15px;
  border-collapse: collapse;
}
</style>
<script src="Hydrogen/sorttable.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  //The following two lines replace link text with left and right arrows as appropriate
  $(".HostDetails").html('<img src="images/doc.png" height="24">');  
  $(".Environments").html('<img src="images/lb.png" height="24">');
  $(".Services").html('<img src="images/service.png" height="24">');
  $(".Databases").html('<img src="images/db.png" height="24">');

    //This function enables the user to toggle the SQL section on and off by clicking
	$("#ToggleSQL").click(function(){
	  $("#SQLEcho").toggle();
	});
	$("#SQLEcho").hide();

});

</script>

<?php
$pagetitle="Team detail";
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">

<?php include 'Hydrogen/elemLogoHeadline.php';  	
if (isset($_GET["id"]))  {
	$ID=sanitizeGetVar("id");
} else $ID=0;
$sql = 'SELECT display_name,id "Team ID",email,home_page,remedy_queue from overdrive.support_team where id='. $ID;

paginate($dds,$page_num);
$result = $dds->setMaxRecs(30);
$result = $dds->setSQL($sql);

$page_count = $dds->getPageCount();
if ($page_count>0) {

	$fieldNames=$dds->getFieldNames();
	$result_row = $dds->getNextRow();
		echo '<h1  style="color:#2222DD;">' . $result_row[0] . '</h1>';
		echo '<table class="b">';
		$length = count($fieldNames) -1;
		for ($i = 1; $i < $length; $i++) {
			$columnvalue= $result_row[$i];
			$hyperlinked = '<a href="http://' . $result_row[$i] . '">' . $result_row[$i] . '</a>';
			if ($fieldNames[$i]=="HOME_PAGE") $columnvalue=$hyperlinked;
			$hyperlinked = '<a href="mailto:' . $result_row[$i] . '">' . $result_row[$i] . '</a>';
			if ($fieldNames[$i]=="EMAIL") $columnvalue=$hyperlinked;
			if (isset($result_row[$i])) echo '<tr><td class="b" style="font-weight: bold;">' . $fieldNames[$i]. ': </td><td class="b">' . $columnvalue. "</td></tr>";
		}
		echo "</table>";

}

//Members
$sql = 'SELECT first_name,last_name,corp_id,jobtitle,email,phone from overdrive.person where team_id='. $ID;
$result = $dds->setSQL($sql);
unset($linkURLs);
unset($linkTargets);
unset($keycols);
unset($invisible);
$linkTargets=null;
$keycols=null;
$invisible[6]=true;
//$address_classes[0]='SvcDetails';
//$address_classes[2]='AppDetails';
//$linkURLs[0]="service.php?id=";
//$linkURLs[2]="application.php?id=";

$page_count = $dds->getPageCount();
if ($page_count>0) {

	echo '<H3>Team members</H3>';
	$table=new HTMLTable($dds->getFieldNames(),$dds->getFieldTypes());
	$table->defineRows($linkURLs,$keycols,$invisible,$address_classes,$linkTargets);
	$table->start();
	while ($result_row = $dds->getNextRow()){
		$table->addRow($result_row);
	}
	$table->finish();

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
