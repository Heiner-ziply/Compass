<style>

table.b, th.b, td.b {
  border: 1px solid black;
  padding: 15px;
  border-collapse: collapse;
}
</style>
<script src="Hydrogen/sorttable.js"></script>
<script src="Hydrogen/jquery.min.js"></script>


<script>
$(document).ready(function(){
  $(".AppDetails").html('<img src="images/doc.png" height="24">');  
  $(".SvcDetails").html('<img src="images/doc.png" height="24">');
	 
  
    //This function enables the user to toggle the SQL section on and off by clicking
	$("#ToggleSQL").click(function(){
	  $("#SQLEcho").toggle();
	});
	$("#SQLEcho").hide();

	
});


</script>

<?php
$pagetitle="Host detail";
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">

<?php include 'Hydrogen/elemLogoHeadline.php';  

if (isset($_GET["id"]))  {
	$ID=sanitizeGetVar("id");
} else $ID=0;

$sql="select distinct concat(s.hostname,'.',s.domain) as Hostname, s.host_id as Details, concat(s.hostname,'.',s.domain) ";
if ($settings['DEFAULT_DB_TYPE']=='oracle') {
	$sql="select distinct " ;
	$sql .=  "nvl2(s.domain,s.hostname||'.'||s.domain,s.hostname) as Hostname,";
	//$sql .= "host_id ". ' as "Details", ';
	$sql .=" '<a href=' || chr(34) || decode(s.os,'Windows','mstsc://','kitty://') || nvl2(s.domain,s.hostname||'.'||s.domain,s.hostname) || chr(34) || '><img src=' || chr(34) || 'images/' ||  decode(s.os,'Windows','mswin.jpg','ssh-icon.png') || chr(34) || ' height=' || chr(34) || '24' || chr(34) ||'></a>' ";
}
$sql .= ' as "Click here to connect", os as "OS" ';
$sql .=',t.display_name as "Support Team", h.DEVICE_TYPE as "Device Type",h.IP ,h.ILOM_IP as "ILOM IP" , h.ntp1, h.ntp2 ,h.VIRTUALIZATION as "Virtualization",h.OS_VERSION as "OS Version",h.MAKE as "Make",h.MODEL as "Model",h.CPU_TYPE as "CPU Type" ,h.CPU_SPEED_MHZ as "CPU Speed (MHz)",h.CPU_COUNT as "CPU Count",h.MEMORY_MB as "Memory (MB)",h.WARRANTY_EXPIRES "Warranty Expiration date", ' . 
'h.EOSL as "End of Service Life",h.COMMENTS as "Comments" ,h.TIMEZONE as "Time Zone" ,h.HEARTBEAT as "Heartbeat date",h.STATUS as "Status" ,h.HARDWARE_STATUS as "Hardware status",h.SUBLOCATION,h.SERIALNUMBER as "Serial Number",l.loc_name as "Location name",l.address_street as "Street Address", l.address_city as "City", l.address_state as "State" ';
$sql=$sql . " from server_directory s inner join host h on s.hosT_id=h.host_id left join support_team t on t.id=h.support_team_id left join location l on h.location_id=l.loc_id where s.host_id=" . $ID;

if (isset($_GET['servicetype'])) $sql=$sql . " and upper(service_type)=upper('" . $stateVar['servicetype'] . "')";
if (isset($_GET['app_id'])) $sql=$sql . " and app_id =" . $stateVar['app_id'] ;
if (isset($_GET['envid'])) $sql=$sql . " and env_id =" . $stateVar['envid'] ;
if (isset($GET['q'])) $sql=$sql . " and (upper(servicename) like upper('%" . $stateVar['q'] . "%') " . " or upper(hostname) like upper('%" . $stateVar['q'] . "%') )";
if (isset($GET['sort'])) {$sql=$sql . " order by app_name, env_name, service_type";
} else {
$sql=$sql . " order by hostname";
}


paginate($dds,$page_num);
$result = $dds->setMaxRecs(30);
$result = $dds->setSQL($sql);

$page_count = $dds->getPageCount();
if ($page_count>0) {

	$fieldNames=$dds->getFieldNames();
	$i=0;
	while ($result_row = $dds->getNextRow()){
		echo '<h1  style="color:#2222DD;">' . $result_row[0] . '</h1>';
		echo '<table class="b">';
		$length = count($fieldNames) -1;
		for ($i = 1; $i < $length; $i++) {
			$columnvalue= $result_row[$i];
			$hyperlinked = '<a href="http://' . $result_row[$i] . '">' . $result_row[$i] . '</a>';
			if ($fieldNames[$i]=="ILOM IP") $columnvalue=$hyperlinked;
			if (isset($result_row[$i])) echo '<tr><td class="b" style="font-weight: bold;">' . $fieldNames[$i]. ': </td><td class="b">' . $columnvalue. "</td></tr>";
		}
		echo "</table>";
	}
}

//All this is copied from services.php
$sql = 'SELECT service_id as "Service", servicename as "Service Name",  nvl(app_id,99999) as "Application",';
$sql .=' app_name as "Application name",';
$sql .= ' os as "OS", nvl2(server_brand,server_brand || chr(32) || service_type,service_type) as "Svc Type",';
$sql .= ' env_name as "Env Name"';
if($settings['DEFAULT_DB_TYPE']=='oracle') {$sql .= " from server_directory where upper(nvl(service_type,'UNKNOWN')) !='UNKNOWN' and host_id=".$ID;}
else {$sql .= " from server_directory where host_id=".$ID;}
$sql=$sql . " order by servicename";
$result = $dds->setSQL($sql);

unset($address_classes);
unset($linkURLs);
unset($linkTargets);
unset($keycols);
unset($invisible);
$linkTargets=null;
$keycols=null;
$invisible[7]=true;
$address_classes[0]='SvcDetails';
$address_classes[2]='AppDetails';
$linkURLs[0]="service.php?id=";
$linkURLs[2]="application.php?id=";

$page_count = $dds->getPageCount();
if ($page_count>0) {
	echo '<H3>Services</H3>';
	$table=new HTMLTable($dds->getFieldNames(),$dds->getFieldTypes());
	$table->defineRows($linkURLs,$keycols,$invisible,$address_classes,$linkTargets);
	$table->start();
	while ($result_row = $dds->getNextRow()){
		$table->addRow($result_row);
	}
	$table->finish();
}

//Cron listing
$sql = "SELECT username, cron_min || ' ' || cron_hour || ' ' || cron_dom || ' ' || cron_mon || ' ' || cron_dow as " . '"Schedule"' . ', cron_cmd as "Command" from global_cron where host_id='. $ID . ' order by username, cron_cmd';
$result = $dds->setSQL($sql);

unset($address_classes);
unset($linkURLs);
unset($linkTargets);
unset($keycols);
unset($invisible);
$invisible[3]=true;

$page_count = $dds->getPageCount();
if ($page_count>0) {
	echo '<H3>Cron schedules</H3>';
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
