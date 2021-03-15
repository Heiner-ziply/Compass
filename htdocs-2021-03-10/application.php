<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $(".HostDetails").html('<img src="images/doc.png" height="24">');  
  $(".SvcDetails").html('<img src="images/doc.png" height="24">');
	 
    //This function enables the user to toggle the SQL section on and off by clicking
	$("#ToggleSQL").click(function(){
	  $("#SQLEcho").toggle();
	});
	$("#SQLEcho").hide();
	
});


</script>
<style>
table.b, th.b, td.b {
  border: 1px solid black;
  padding: 15px;
  border-collapse: collapse;
}
</style>
<?php
$pagetitle="Application detail";
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
<div class="w3-main w3-container w3-padding-16" id="top_help">
<p><?php echo $top_help_text; ?></p>
</div>
</div>

<?php include 'Hydrogen/elemLogoHeadline.php';  
if (isset($_GET["pagenum"]))  {
	$page_num=sanitizeGetVar("pagenum");
} else $page_num=1;
if (isset($_GET["id"]))  {
	$ID=sanitizeGetVar("id");
} else $ID=0;
$sql='select a.app_name as "Name", v.vendor_name as "Vendor", a.app_id as "App. ID", a.app_code as "App. code",  a.Description as "Description", Product_URL as "Product URL", wiki_page as "Wiki Page", max(t.display_name) as "Support Team"';
$sql .= ' from application a left join vendor v on a.vendor_id = v.vendor_id ';
$sql .= ' left join m_app_support_team m on a.app_id = m.app_id ';
$sql .= ' left join support_team t on m.team_id = t.id ';
$sql=$sql . " where a.app_id=" . $ID . 'group by a.app_name,v.vendor_name, a.app_id, a.app_code, a.description, a.product_url, a.wiki_page';

function getVars($pg) {
	$retval="?pagenum=" . $pg;
	return $retval;
}

$result = $dds->setSQL($sql);
 
	$fieldNames=$dds->getFieldNames();
 
	$i=0;
	while ($result_row = $dds->getNextRow()){
		echo '<h1  style="color:#2222DD;">' . $result_row[0] . '</h1>';
		//$table->addRow($result_row);
		echo '<table class="b">';
		$length = count($fieldNames) -1;
		for ($i = 1; $i < $length; $i++) {
			$columnvalue= $result_row[$i];
			$hyperlinked = '<a href="' . $result_row[$i] . '">' . $result_row[$i] . '</a>';
			if ($fieldNames[$i]=="Product URL") $columnvalue=$hyperlinked;
			if ($fieldNames[$i]=="Wiki Page") $columnvalue=$hyperlinked;
			if (isset($result_row[$i])) echo '<tr><td class="b" style="font-weight: bold;">' . $fieldNames[$i]. ': </td><td class="b">' . $columnvalue. "</td></tr>";
		}
		echo "</table>";
	}

//All this is copied from services.php
$sql = 'SELECT distinct service_id as "Service", servicename as "Service Name",  host_id as "Host",';
if ($settings['DEFAULT_DB_TYPE']=='oracle') { 
	$sql .=" nvl2(domain,hostname||'.'||domain,hostname) as Hostname, '" . '<a href="';
	$sql .= "' || decode(OS,'Windows','mstsc://','kitty://') || nvl2(domain,hostname||'.'||domain,hostname) || '" . '"';
	$sql .= ">'" . " || decode(OS,'Windows','" . '<img src="images/mswin.jpg" height="24">'. "','" ;
	$sql .= '<img src="images/ssh-icon.png" height="24">' . "') " . " || '</a>' as TERM, ";} 
else {$sql .= " concat(hostname,'.',domain) as Hostname, concat(hostname,'.',domain) as TERM,";}
$sql .= ' os as "OS", nvl2(server_brand,server_brand || chr(32) || service_type,service_type) as "Svc Type",';
$sql .= ' env_name as "Env Name"';
if($settings['DEFAULT_DB_TYPE']=='oracle') {$sql .= " from server_directory where upper(nvl(service_type,'UNKNOWN')) !='UNKNOWN' and app_id=".$ID;}
else {$sql .= " from server_directory where app_id=".$ID;}
$sql=$sql . " order by servicename";
$result = $dds->setSQL($sql);

unset($address_classes);
unset($linkURLs);
unset($linkTargets);
unset($keycols);
unset($invisible);
$linkTargets=null;
$keycols=null;
$invisible[8]=true;
$address_classes[0]='SvcDetails';
$address_classes[2]='HostDetails';
$linkURLs[0]="service.php?id=";
$linkURLs[2]="host.php?id=";

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

