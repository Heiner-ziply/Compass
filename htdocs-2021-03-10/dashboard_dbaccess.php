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
  

  //This function enables the user to toggle the help section on and off by clicking
	$("#ToggleHelp").click(function(){
	  $("#top_help").toggle();
	});
	$("#top_help").hide();
});


</script>
<?php
//The following four lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Database Access";
$headline='<h1>Database access dashboard</h1><button id="ToggleHelp">Show/hide help</button>';
$top_help_text='<h2>Help for this page:</h2>';
//$top_help_text=$top_help_text . '<p>To use the hyperlinks (<img src="images/ssh-icon.png" alt="ssh link">,<img src="images/mswin.jpg" alt="windows link">) below, you will need to have a corresponding client configured. <a href="help.php"> See instructions here</a>. </p>';
$top_help_text=$top_help_text . '<p>The below results include any known
 database instances except those known to be administered by a
 team other than the OSS Core DB team. </p>';
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">

<div>
<div class="w3-main w3-container w3-padding-16" id="top_help">
<p><?php echo $top_help_text; ?></p>
</div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  	

//pass a dummy crypto key because the 'hostusers' view expects one 
//  even though we will not be selecting encrypted data
$crypto="BEGIN dbms_session.set_identifier('foo'); END;";
$result = $dds->setSQL($crypto);

$skip_summary=false;
if (isset($_GET['pagenum'])) {
	if ($_GET['pagenum']!="1") $skip_summary=true;
}
if (!$skip_summary) {
//summary 
$sql = "select * from (select server_brand DBMS, count(s.servicename) INST_COUNT,
count(nvl(u.username,replace(z.comments,'| SSH KEY','SSH KEY'))) as host_access_count, 
count(decode(server_brand,'MSSQL','xxxxxxx',nvl(x.username,ltrim(replace(z.comments,'| SSH KEY','SSH KEY'))))) as sudo_access_count,
count(t.display_name) support_team
from overdrive.service s
left join (select * from overdrive.hostuser where upper(username)='KHH8615') u on s.host_id =u.host_id
left join (select * from overdrive.hostuser ) z on s.host_id =z.host_id and s.host_username=z.username
left join (select * From overdrive.sudo_user where upper(username)=user) x on s.host_id=x.host_id and s.host_username=x.sudo_name
left join overdrive.support_team t on s.support_team_id=t.id
where service_type like 'Dat%'
and status='Active'
and NVL(t.id,1)=1
group by server_brand
UNION
select 'TOTAL' DBMS, count(s.servicename) DBMS_COUNT,
count(nvl(u.username,replace(z.comments,'| SSH KEY','SSH KEY'))) as host_access_count, 
count(decode(server_brand,'MSSQL','xxxxxxx',nvl(x.username,ltrim(replace(z.comments,'| SSH KEY','SSH KEY'))))) as sudo_access_count,
count(t.display_name) support_team
from overdrive.service s
left join (select * from overdrive.hostuser where upper(username)='KHH8615') u on s.host_id =u.host_id
left join (select * from overdrive.hostuser ) z on s.host_id =z.host_id and s.host_username=z.username
left join (select * From overdrive.sudo_user where upper(username)=user) x on s.host_id=x.host_id and s.host_username=x.sudo_name
left join overdrive.support_team t on s.support_team_id=t.id
where service_type like 'Dat%'
and status='Active'
and NVL(t.id,1)=1
) order by decode(dbms,'TOTAL',1,0)";

$result = $dds->setSQL($sql);
unset($address_classes);
unset($linkURLs);
unset($linkTargets);
unset($keycols);
unset($invisible);
$linkTargets=null;
$keycols=null;
$invisible[5]=true;
echo "<h2>Summary</h2>";
$table=new HTMLTable($dds->getFieldNames(),$dds->getFieldTypes());
$table->defineRows($linkURLs,$keycols,$invisible,$address_classes,$linkTargets);
$table->start();
while ($result_row = $dds->getNextRow()){
	$table->addRow($result_row);
}
$table->finish();

} //end if: skip summary


//details
$sql = "select s.servicename,
s.server_brand, decode(server_brand,'MSSQL','N/A',s.host_username) host_username,
replace(nvl(u.username,replace(z.comments,'| SSH KEY','SSH KEY')),'khh8615','OSS DB Core') as host_access, 
decode(server_brand,'MSSQL','N/A',nvl(x.username,ltrim(replace(z.comments,'| SSH KEY','SSH KEY')))) as sudo_access,
t.display_name support_team
from overdrive.service s
left join (select * from overdrive.hostuser where upper(username)='KHH8615') u on s.host_id =u.host_id
left join (select * from overdrive.hostuser ) z on s.host_id =z.host_id and s.host_username=z.username
left join (select * From overdrive.sudo_user where upper(username)=user) x on s.host_id=x.host_id and s.host_username=x.sudo_name
left join overdrive.support_team t on s.support_team_id=t.id
where service_type like 'Dat%'
and status='Active'
and NVL(t.id,1)=1
order by s.server_brand, upper(s.servicename)";


paginate($dds,$page_num);
$result = $dds->setMaxRecs(30);
$result = $dds->setSQL($sql);

$page_count = $dds->getPageCount();
if ($page_count>0) {
	echo "<br><br>";
	showPagination($dds,$_SERVER['SCRIPT_NAME']);

	unset($address_classes);
	unset($linkURLs);
	unset($linkTargets);
	unset($keycols);
	unset($invisible);
	$linkTargets=null;
	$keycols=null;
	$invisible[6]=true;

	echo "<h2>Details</h2>";
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
