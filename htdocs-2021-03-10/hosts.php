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
  $(".SSH").html('<img src="images/ssh-icon.png" height="24">');
  $('td:contains("Linux")').html('<img src="images/linux.jpg">');
  $('td:contains("Windows")').html('<img src="images/mswin.jpg">');   
  
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
$pagetitle="Host directory";
$headline='<h1>Host directory</h1><button id="ToggleHelp">Show/hide help</button>';
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

$sql="select distinct host_id as Details, concat(hostname,'.',domain) as Hostname, concat(hostname,'.',domain) ";
if ($settings['DEFAULT_DB_TYPE']=='oracle') {
	$sql="select distinct host_id ". ' as "Details", ' ;
	$sql .= "nvl2(domain,hostname||'.'||domain,hostname) as Hostname, '<a href=' || chr(34) || decode(os,'Windows','mstsc://','kitty://') || nvl2(domain,hostname||'.'||domain,hostname) || chr(34) || '><img src=' || chr(34) || 'images/' ||  decode(os,'Windows','mswin.jpg','ssh-icon.png') || chr(34) || ' height=' || chr(34) || '24' || chr(34) ||'></a>' ";
}
$sql .= ' as TERM, os as "OS" ';
$sql=$sql . " from server_directory where 1=1 ";

if (isset($_GET['servicetype'])) $sql=$sql . " and upper(service_type)=upper('" . $stateVar['servicetype'] . "')";
if (isset($_GET['app_id'])) $sql=$sql . " and app_id =" . $stateVar['app_id'] ;
if (isset($_GET['envid'])) $sql=$sql . " and env_id =" . $stateVar['envid'] ;
//if (isset($GET['q'])) $sql=$sql . " and (upper(servicename) like upper('%" . $stateVar['q'] . "%') " . " or upper(hostname) like upper('%" . $stateVar['q'] . "%') )";
if (isset($_GET['q'])) $sql=$sql . " and (upper(servicename) like upper('%" . $stateVar['q'] . "%') or upper(serialnumber) like upper('%" . $stateVar['q'] . "%') or  upper(ip) like upper('%" . $stateVar['q'] . "%') or upper(hostname) like upper('%" . $stateVar['q'] . "%')) ";
if (isset($GET['sort'])) {$sql=$sql . " order by app_name, env_name, service_type";
} else {
$sql=$sql . " order by hostname";
}

if (!isset($_GET['q']) and !isset($_GET['appid']) and !isset($_GET['envid'])  ){
	echo '<span class="w3-bar-item w3-button w3-small w3-hide-small">';
	echo '<form action="hosts.php">';
	echo '<input type="text" name="q" >';
	echo '<input type="submit" value="Host search">';
	echo '</form>';
	echo '</span>';
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
	$invisible[3]=true;
	$invisible[4]=true;
	$linkURLs[0] ="host.php?id=";
	$address_classes[0]='HostDetails';
/*
	$address_classes[4]='stypeFilter';
	if (isset($_GET['servicetype'])) {
		$newState=$stateVar;
	    unset($newState['servicetype']);
		$linkURLs[4] = $_SERVER['SCRIPT_NAME'] . newVars(1,$newState) . '&unset=';
	} else {
		$linkURLs[4] = $_SERVER['SCRIPT_NAME'] . newVars(1) . '&servicetype=';

	}

	$address_classes[6]='envFilter';
	if (isset($_GET['envid'])) {
		$newState=$stateVar;
	    unset($newState['envid']);
		$linkURLs[6] = $_SERVER['SCRIPT_NAME'] . newVars(1,$newState) . '&unset=';
	} else {
		$linkURLs[6] = $_SERVER['SCRIPT_NAME'] . newVars(1) . '&envid=';

	}	
	
	
	$address_classes[8]='appFilter';
	if (isset($_GET['app_id'])) {
		$newState=$stateVar;
	    unset($newState['app_id']);
		$linkURLs[8] = $_SERVER['SCRIPT_NAME'] . newVars(1,$newState) . '&unset=';
	} else {
		$linkURLs[8] = $_SERVER['SCRIPT_NAME'] . newVars(1) . '&app_id=';

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
