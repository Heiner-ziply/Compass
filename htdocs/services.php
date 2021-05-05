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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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

  //This function enables the user to toggle the SQL section on and off by clicking
	$("#ToggleSQL").click(function(){
	  $("#SQLEcho").toggle();
	});
	$("#SQLEcho").hide();
	
});


</script>

<?php
//The following four lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Service directory";
$headline='<h1>Service directory</h1><button id="ToggleHelp">Show/hide help</button>';
$top_help_text='<h2>Help for this page:</h2><p>To use the hyperlinks (<img src="images/ssh-icon.png" alt="ssh link">,<img src="images/mswin.jpg" alt="windows link">) below, you will need to have a corresponding client configured. <a href="help.php"> See instructions here</a>. </p>';
//$top_help_text=$top_help_text . '<p>The below results include all hosts in the HOST table and information in related tables only insofar as the data has been entered. For this reason, a particular host may not appear in any filtered search results. Also, duplication may be seen where a host has multiple associated services.</p>';
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">

<div>
<div class="w3-main w3-container w3-padding-16" id="top_help">
<p><?php echo $top_help_text; ?></p>
</div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  	


$sql = 'SELECT service_id as "Service", servicename as "Service Name",  host_id as "Host",';
if ($settings['DEFAULT_DB_TYPE']=='oracle') { 
	$sql .=" nvl2(domain,hostname||'.'||domain,hostname) as Hostname, '" . '<a href="';
	$sql .= "' || decode(OS,'Windows','mstsc://','kitty://') || nvl2(domain,hostname||'.'||domain,hostname) || '" . '"';
	$sql .= ">'" . " || decode(OS,'Windows','" . '<img src="images/mswin.jpg" height="24">'. "','" ;
	$sql .= '<img src="images/ssh-icon.png" height="24">' . "') " . " || '</a>' as TERM, ";} 
else {$sql .= " concat(hostname,'.',domain) as Hostname, concat(hostname,'.',domain) as TERM,";}
$sql .= ' os as "OS", service_type as "(st)", service_type as "Svc Type",';
$sql .= ' nvl(env_id,99999) as "(env)", env_name as "Env Name", nvl(app_id,99999) as "(app)",';
$sql .= ' app_name as "App Name", nvl(app_id,99999) as "App"';
if($settings['DEFAULT_DB_TYPE']=='oracle') {$sql .= " from server_directory where upper(nvl(service_type,'UNKNOWN')) !='UNKNOWN' ";}
else {$sql .= " from server_directory where 1=1";}

if (isset($_GET['servicetype'])) $sql=$sql . " and upper(service_type)=upper('" . $stateVar['servicetype'] . "')";
if (isset($_GET['app_id'])) $sql=$sql . " and app_id =" . $stateVar['app_id'] ;
if (isset($_GET['envid'])) $sql=$sql . " and env_id =" . $stateVar['envid'] ;
if (isset($_GET['q'])) {
	//replace hex "*" with "%" wildcard
	$stateVar['q'] = str_replace('%2A','%',$stateVar['q']);
	$sql=$sql . " and (upper(servicename) like upper('%" . $stateVar['q'] . "%') " . " or upper(IP) like upper('%" . $stateVar['q'] . "%') or upper(hostname) like upper('%" . $stateVar['q'] . "%') or upper(app_name) like upper('%" . $stateVar['q'] . "%') or service_id in (select instance_id from database where upper(name) like upper('%" . $stateVar['q'] . "%')))";
}
if (isset($_GET['sort'])) {$sql=$sql . " order by app_name, env_name, service_type";
} else {
$sql=$sql . " order by servicename";
}


paginate($dds,$page_num);
$result = $dds->setMaxRecs(30);
$result = $dds->setSQL($sql);

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
	$invisible[5]=true;
	$invisible[13]=true;
	//$linkURLs[1] ="kitty://";
	$address_classes[0]='SvcDetails';
	$address_classes[2]='HostDetails';
	$address_classes[12]='AppDetails';
	$linkURLs[0]="service.php?id=";
	$linkURLs[2]="host.php?id=";
	$linkURLs[12]="application.php?id=";

	$address_classes[6]='stypeFilter';
	if (isset($_GET['servicetype'])) {
		$newState=$stateVar;
	    unset($newState['servicetype']);
		$linkURLs[6] = $_SERVER['SCRIPT_NAME'] . newVars(1,$newState) . '&unset=';
	} else {
		$linkURLs[6] = $_SERVER['SCRIPT_NAME'] . newVars(1) . '&servicetype=';

	}

	$address_classes[8]='envFilter';
	if (isset($_GET['envid'])) {
		$newState=$stateVar;
	    unset($newState['envid']);
		$linkURLs[8] = $_SERVER['SCRIPT_NAME'] . newVars(1,$newState) . '&unset=';
	} else {
		$linkURLs[8] = $_SERVER['SCRIPT_NAME'] . newVars(1) . '&envid=';

	}	
	
	
	$address_classes[10]='appFilter';
	if (isset($_GET['app_id'])) {
		$newState=$stateVar;
	    unset($newState['app_id']);
		$linkURLs[10] = $_SERVER['SCRIPT_NAME'] . newVars(1,$newState) . '&unset=';
	} else {
		$linkURLs[10] = $_SERVER['SCRIPT_NAME'] . newVars(1) . '&app_id=';

	}

	$table=new HTMLTable($dds->getFieldNames(),$dds->getFieldTypes());
	$table->defineRows($linkURLs,$keycols,$invisible,$address_classes,$linkTargets);
	$table->start();
	while ($result_row = $dds->getNextRow()){
		$table->addRow($result_row);
	}
	$table->finish();
	showPagination($dds,$_SERVER['SCRIPT_NAME'],true);
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
