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

    //This function enables the user to toggle the SQL section on and off by clicking
	$("#ToggleSQL").click(function(){
	  $("#SQLEcho").toggle();
	});
	$("#SQLEcho").hide();

	  //This function enables the user to toggle the help section on and off by clicking
	  $("#ToggleHelp").click(function(){
	  $("#top_help").toggle();
	});
	$("#top_help").hide();

});
</script>

<?php
//The following four lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Batch Jobs";
$headline='<h1>Batch Job directory</h1><button id="ToggleHelp">Show/hide help</button>';
$top_help_text='<h2>Help for this page:</h2><p>This page is not a complete list of all batch jobs. See platform-specific pages for all jobs known to be running on those platforms. </p>';
include ('Hydrogen/pgTemplate.php');
?>
<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">

<div>
<div class="w3-main w3-container w3-padding-16" id="top_help">
<p><?php echo $top_help_text; ?></p>
</div>
</div>

<?php include 'Hydrogen/elemLogoHeadline.php';  	


$sql = 'select j.batch_job_id "ID" ,j.job_name "Name", count(n.batch_job_id) as "SQLAgent Jobs", count(c.batch_job_id) as "Cron Jobs", count(d.batch_job_id) as "DBMS_JOB Jobs", ';
$sql .=" nvl2(documentation_url,'<a href='|| chr(34) || documentation_url || chr(34) ||'>' || documentation_url || '</a>','') ";
$sql .=' as "Documentation" from batch_job j left join overdrive.nw1_sqlagent_jobs n on n.batch_job_id=j.batch_job_id  
left join global_cron c on c.batch_joB_id=j.BATCH_JOB_ID left join global_jobs d on d.batch_joB_id=j.BATCH_JOB_ID '; 
$sql .= " group by j.batch_job_id,j.job_name, nvl2(documentation_url,'<a href='|| chr(34) || documentation_url || chr(34) ||'>' || documentation_url || '</a>','') ";
$sql .= ' having count(c.batch_job_id) > 0 or count(d.batch_job_id) > 0  or count(n.batch_job_id) > 0 order by upper(j.job_name) ';

paginate($dds,$page_num);
$result = $dds->setMaxRecs(30);
$result = $dds->setSQL($sql);
echo '<P>See platform-specific job lists here: <a href="jobs_cron.php">Cron</a>, <a href="jobs_dbms.php">DBMS_JOB</a>, <a href="jobs_dbms_scheduler.php">DBMS_SCHEDULER</a>, <a href="jobs_sqlagent.php">SQL Agent</a></P>
';
$page_count = $dds->getPageCount();
if ($page_count>0) {
	showPagination($dds,$_SERVER['SCRIPT_NAME'],true);

	unset($address_classes);
	unset($linkURLs);
	unset($linkTargets);
	unset($keycols);
	unset($invisible);
	$linkTargets=null;
	$keycols=null;
	$invisible[6]=true;

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
