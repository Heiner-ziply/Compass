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
$pagetitle="Service detail";
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">

<?php include 'Hydrogen/elemLogoHeadline.php';  	
if (isset($_GET["id"]))  {
	$ID=sanitizeGetVar("id");
} else $ID=0;
$sql = 'SELECT s.servicename as "Service Name",  t.id as "Team_ID", t.displaY_name as "Support Team", s.service_id as "Service ID", s.host_username as "Runs as",';
if ($settings['DEFAULT_DB_TYPE']=='oracle') { 
	$sql .=" nvl2(d.domain,d.hostname||'.'||d.domain,d.hostname) as " . '"Runs on"' . ", '" . '<a href="';
	$sql .= "' || decode(d.OS,'Windows','mstsc://','kitty://') || nvl2(d.domain,d.hostname||'.'||d.domain,d.hostname) || '" . '"';
	$sql .= ">'" . " || decode(d.OS,'Windows','" . '<img src="images/mswin.jpg" height="24">'. "','" ;
	$sql .= '<img src="images/ssh-icon.png" height="24">' . "') " . " || '</a>' as ".'"Host link"'. ', d.host_id  as "Host ID (click for details)",';} 
else {$sql .= " concat(d.hostname,'.',d.domain) as Hostname, concat(d.hostname,'.',d.domain) as TERM,";}
$sql .= ' d.os as "OS",  d.service_type as "Service Type", s.server_brand as "Brand",';
//$sql .= ' d.env_name as "Environment Name", ';
//$sql .= ' d.app_name as "Application Name", ';
$sql .= ' s.URL, s.admin_url as "Admin URL", s.IP, PORT, oraclenet_fqdn as "Oracle Net8 string", svc_cluster as "Service Cluster",';
$sql .=" nvl2(wiki_page,'<a href='|| chr(34) || wiki_page || chr(34) || '>' || wiki_page || '</a>','') " . 'as "Wiki Page" ';
if($settings['DEFAULT_DB_TYPE']=='oracle') {
	$sql .= " from server_directory d inner join service s on s.service_id=d.service_id " ; 
	$sql .= " left join support_team t on t.id=s.support_team_id ";
	$sql .= " where upper(nvl(d.service_type,'UNKNOWN')) !='UNKNOWN' and d.service_id=".$ID;
}
else {$sql .= " from server_directory where service_id=". $ID;}

if (isset($_GET['servicetype'])) $sql=$sql . " and upper(d.service_type)=upper('" . $stateVar['servicetype'] . "')";
if (isset($_GET['app_id'])) $sql=$sql . " and d.app_id =" . $stateVar['app_id'] ;
if (isset($_GET['envid'])) $sql=$sql . " and d.env_id =" . $stateVar['envid'] ;
if (isset($GET['q'])) $sql=$sql . " and (upper(d.servicename) like upper('%" . $stateVar['q'] . "%') " . " or upper(d.hostname) like upper('%" . $stateVar['q'] . "%') )";
if (isset($GET['sort'])) {$sql=$sql . " order by d.app_name, d.env_name, d.service_type";
} else {
$sql=$sql . " order by d.servicename";
}


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
			if ($fieldNames[$i]=="URL") $columnvalue=$hyperlinked;
			if ($fieldNames[$i]=="Admin URL") $columnvalue=$hyperlinked;
			$hyperlinked = '<a href="host.php?id=' . $result_row[$i] . '">' . $result_row[$i] . '</a>';
			if ($fieldNames[$i]=="Host ID (click for details)") $columnvalue=$hyperlinked;
			$hyperlinked = '<a href="team.php?id=' . $result_row[$i-1] . '">' . $result_row[$i] . '</a>';
			if ($fieldNames[$i]=="Support Team") $columnvalue=$hyperlinked;
			if (isset($result_row[$i])) {
				if ($fieldNames[$i]<>"Team_ID") {
					echo '<tr><td class="b" style="font-weight: bold;">' . $fieldNames[$i]. ': </td><td class="b">' . $columnvalue. "</td></tr>";
				}
			}
		}
		echo "</table>";

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
