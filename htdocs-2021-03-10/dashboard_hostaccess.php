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
$pagetitle="Host Access";
$headline='<h1>Server access dashboard</h1><button id="ToggleHelp">Show/hide help</button>';
$top_help_text='<h2>Help for this page:</h2>';
//$top_help_text=$top_help_text . '<p>To use the hyperlinks (<img src="images/ssh-icon.png" alt="ssh link">,<img src="images/mswin.jpg" alt="windows link">) below, you will need to have a corresponding client configured. <a href="help.php"> See instructions here</a>. </p>';
$top_help_text=$top_help_text . '<p>The below results include any known
 hosts not in the Fort Wayne data center and not administered by an 
 organization other than OSS. If a Ziply employee or Active Directory group is known to have
  access to the host, or if the password to a local account is known for the host, 
the account name or AD group will appear in the "host_access" column. If there is sudo 
 access to a more highly privileged account, that account will appear in the
  "sudo_access" column. 
  If the root or Administrator password is known to be
  in the hands of a Ziply support team, the "root_password" column will 
  contain the name of the root or Administrator account. </p>';
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

//summary 
$sql = "select count(h.hostname) host_count, count(h.ip) ip_count , count(t.display_name) support_team,
count(root_pass.username) as root_pass_count,
count(sudo_access.sudo_user) as sudo_count,
count(decode(host_users.username,'khh8615','OSS DB Team',host_users.username)) as host_access
from overdrive.host h left join
(select username, h.hostname, h.domain
from overdrive.hostuser u, overdrive.host h
where u.host_id=h.host_id
and username in ('root','Administrator')
and password!='NOLOGIN') root_pass
on h.hostname=root_pass.hostname and nvl(h.domain,'D')=nvl(root_pass.domain,'D')
left join (
select max(u.username) username, h.hostname, h.domain
from overdrive.hostuser u, overdrive.host h
where u.host_id=h.host_id
and password!='NOLOGIN'
group by h.hostname, h.domain
) host_users
on h.hostname=host_users.hostname and nvl(h.domain,'D')=nvl(host_users.domain,'D')
left join
(select max(sudo_name) sudo_user,h.hostname, h.domain
from overdrive.sudo_user s inner join overdrive.host h using (host_id)
group by h.hostname, h.domain
) sudo_access
on h.hostname=sudo_access.hostname and nvl(h.domain,'D')=nvl(sudo_access.domain,'D')
left join overdrive.support_team t on h.support_team_id=t.id
where h.eosl is null
and nvl(h.ip,'10.x') like '10.%'
and upper(h.device_type)='SERVER'
and nvl(t.display_name,'OSS ') like 'OSS%'
order by h.hostname, h.domain";

$result = $dds->setSQL($sql);
unset($address_classes);
unset($linkURLs);
unset($linkTargets);
unset($keycols);
unset($invisible);
$linkTargets=null;
$keycols=null;
$invisible[6]=true;
echo "<h2>Summary</h2>";
$table=new HTMLTable($dds->getFieldNames(),$dds->getFieldTypes());
$table->defineRows($linkURLs,$keycols,$invisible,$address_classes,$linkTargets);
$table->start();
while ($result_row = $dds->getNextRow()){
	$table->addRow($result_row);
}
$table->finish();
showPagination($dds,$_SERVER['SCRIPT_NAME']);


//details
$sql = "select h.hostname, h.domain, h.ip,t.display_name support_team,
root_pass.username as root_password,
sudo_access.sudo_user as sudo_access,
decode(host_users.username,'khh8615','OSS DB Team',host_users.username) as host_access
from overdrive.host h left join
(select username, h.hostname, h.domain
from overdrive.hostuser u, overdrive.host h
where u.host_id=h.host_id
and username in ('root','Administrator')
and password!='NOLOGIN') root_pass
on h.hostname=root_pass.hostname and nvl(h.domain,'D')=nvl(root_pass.domain,'D')
left join (
select max(u.username) username, h.hostname, h.domain
from overdrive.hostuser u, overdrive.host h
where u.host_id=h.host_id
and password!='NOLOGIN'
group by h.hostname, h.domain
) host_users
on h.hostname=host_users.hostname and nvl(h.domain,'D')=nvl(host_users.domain,'D')
left join
(select max(sudo_name) sudo_user,h.hostname, h.domain
from overdrive.sudo_user s inner join overdrive.host h using (host_id)
group by h.hostname, h.domain
) sudo_access
on h.hostname=sudo_access.hostname and h.domain=sudo_access.domain
left join overdrive.support_team t on h.support_team_id=t.id
where h.eosl is null
and nvl(h.ip,'10.x') like '10.%'
and upper(h.device_type)='SERVER'
and nvl(t.display_name,'OSS ') like 'OSS%'
order by h.hostname, h.domain";


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
	$invisible[7]=true;

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
