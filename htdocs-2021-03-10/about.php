<?php
//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="About";
$headline = '<h1>About this application</h1>' ;
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  ?>

<h4>This application is:</h4>
<ul>
<li> A structured-data alternative to the infrastructure information in Ziply's Confluence wiki. Why? Machine-readable information is machine-actionable. </li>
<li> A monitoring tool. The same database tables which store the asset information mentioned above are also used to automate monitoring and further data collection. </li>

</ul>

See the links in the page footer for more information.
<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>
</body></html>

