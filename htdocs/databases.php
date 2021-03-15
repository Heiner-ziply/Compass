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
$pagetitle="Database directory";
$headline='<h1>Database directory</h1><button id="ToggleHelp">Show/hide help</button>';
$top_help_text='<h2>Help for this page:</h2><p>To use the hyperlinks (<img src="images/ssh-icon.png" alt="ssh link">,<img src="images/mswin.jpg" alt="windows link">) below, you will need to have a corresponding client configured. <a href="help.php"> See instructions here</a>. </p>';
$top_help_text=$top_help_text . '<p>There are a limited number of ways to filter results. This can be done either by manually editing the URL in your address bar or by clicking filter icons (<img src="images/filter-on.png" height="24" alt="filter icon">) in the table below.';
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">

<div>
<div class="w3-main w3-container w3-padding-16" id="top_help">
<p><?php echo $top_help_text; ?></p>
</div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  	

$sql='select distinct x.database_id as "Details", substr(x.name,1,25) as "Name", d.Service_id as "Service", d.servicename as "Instance", d.server_brand as "(DBMS)", d.server_brand as "DBMS", ';
$sql     .= " decode(d.server_brand,'Oracle',  nvl2(o.db_url,'<a href=' || chr(34) || o.db_url || chr(34) || '>     <img src=' || chr(34) || 'images/sqlplus.png' || chr(34) || ' height=' || chr(34) || '24' || chr(34) || '></a>','')    ,'') " . ' as "CLI", ';
if($settings['DEFAULT_DB_TYPE']=='mysql') $sql .= ' d.hosT_id as "Host", d.hostname as "Hostname", ' . " concat(d.hostname,'.',d.domain) " . ' as "TERM" '  ;
if($settings['DEFAULT_DB_TYPE']=='oracle') {
	$sql .= ' d.host_id as "Host", ' . " nvl2(d.domain,d.hostname||'.'||d.domain,d.hostname) as Hostname, '" . '<a href="';
	$sql .= "' || decode(OS,'Windows','mstsc://','kitty://') || nvl2(d.domain,d.hostname||'.'||d.domain,d.hostname) || '" . '"';
	$sql .= ">'" . " || decode(OS,'Windows','" . '<img src="images/mswin.jpg" height="24">'. "','" ;
	$sql .= '<img src="images/ssh-icon.png" height="24">' . "') " . " || '</a>' as TERM ";
}
//$sql .= ' , env_id as "(env)", env_name as "Environment" , app_id as "(app)", app_name as "Application"';
$sql=$sql . " from database_directory d inner join database x on x.instance_id=d.service_id left join instance_dbms_oracle o on o.sid=d.servicename where x.name not in ('master','model','tempdb','msdb') ";

if (isset($_GET['brand'])) $sql=$sql . " and upper(server_brand)=upper('" . $stateVar['brand'] . "')";
if (isset($_GET['app_id'])) $sql=$sql . " and app_id =" . $stateVar['app_id'] ;
if (isset($_GET['envid'])) $sql=$sql . " and env_id =" . $stateVar['envid'] ;
if (isset($_GET['q'])) $sql=$sql . " and upper(x.name) like upper('%" . $stateVar['q'] . "%') ";
if (isset($_GET['sort'])) {$sql=$sql . " order by app_name, env_name, server_brand";
} else {
$sql=$sql . " order by lower(substr(x.name,1,25))";
}

if (!isset($_GET['q']) and !isset($_GET['appid']) and !isset($_GET['envid'])  ){
	echo '<span class="w3-bar-item w3-button w3-small w3-hide-small">';
	echo '<form action="databases.php">';
	echo '<input type="text" name="q" >';
	echo '<input type="submit" value="DB Name search">';
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
	$invisible[0]=true;
	$invisible[10]=true;
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
