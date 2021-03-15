<?php
//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="System Scope";
$headline = '<h1>Scope of monitoring</h1>' ;
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  ?>

<h4>Host machines</h4>
<p>Windows machines on which the OSS Core DB team has sysadmin access to a SQL server are being monitored for disk usage by SQL server.</p>
<p>UNIX/Linux/Solaris machines on which the OSS Core DB team has ssh access are being monitored for disk usage. Cron activity is tracked for accounts to which the team has direct or sudo access.</p>
<h4>MS SQL Server instances</h4>
<p>Any SQL Server instance that the OSS Core DB team has sysadmin access to is being monitored.</p>
<h4>Oracle databases</h4>
<p>Any Oracle database that the OSS Core DB team has DBA access to is being monitored.</p>
<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>
</body></html>

