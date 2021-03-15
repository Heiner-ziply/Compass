<?php
//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Home | Overdrive";
$headline = '<h1>Overdrive</h1>' ;
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
unset ($settings['show_sql']);
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  

if (!isset($_GET['menu'])) echo '<p>This application is for viewing and navigating IT infrastructure and organizational data. Each menu item is a listing of assets.</p>';
?>
<br>
<br>
<br>

<h3>Using this site</h3>
<h4>Sidebar icons</h4>
<p>The icons <img src="images/application.png" height="25"> <img src="images/lb.png"> <img src="images/db.png"> <img src="images/service.png"> in the sidebar may also appear elsewhere alongside table data. In this context, clicking them will take you to the same page as they would when clicked in the sidebar, but the page will be sorted and/or filtered differently, appropriate to the context in which you clicked the link.
</p>

<h4>Sorting and filtering</h4>
<p>Most table headers can be clicked to sort results. If there are more results than a single page, only the current page will be sorted.</p>
<p>Filter icons will appear in columns which support filtering. Click the "set filter" icon <img src="images/filter-on.png" height="24">  next to the value you wish to select for that column. To undo filtering, click the "remove filter" icon <img src="images/filter-off.png" height="24">. </p>
<p>Filter criteria and page numbers, when selected, are encoded in the URL of each page. This allows them to be bookmarked.</p>

<h4>External links</h4>
<p>Links to resources other than this site are represented by icons.</p>
<table class="ext_link_icon_table">
<tr><td><img src="images/doc.png"></td><td>This icon indicates a link to more detailed information, perhaps in this application or on another site on the intranet, regarding the item it appears next to.</td></tr>
<tr><td><img src="images/ssh-icon.png"></td><td>When this icon appears next to a server name, it can be clicked to connect to that server via ssh. Help on configuring this ability is available <a href="help.php?menu=help"> here</a>. You can also hover over this icon to see the FQDN ({hostname}.{domain}) of the server.</td></tr>
<tr><td><img src="images/sqlplus.png"></td><td>When this icon appears next to an Oracle database name, it can be clicked to connect to that database via SQL*Plus. Help on configuring this ability is available <a href="help.php?menu=help"> here</a>. You can also hover over this icon to see the TNS connect string ({host}:{port}/{sid}) of the database.</td></tr>
</table>

<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>
</body></html>

