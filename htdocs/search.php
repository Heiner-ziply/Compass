<?php

if (isset($_GET['q']) and isset($_GET['context'])) {
    //header("Location: http://www.redirect.to.url.com/"); 
    header("Location: " . $_GET['context']. "?q=" . $_GET['q']); 
}


//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Search | Overdrive";
$headline = '<h1>Search</h1>' ;
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
unset ($settings['show_sql']);
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  



echo '<span class="w3-bar-item w3-button w3-small w3-hide-small">';
echo '<form action="search.php">    
Context: <select name="context" id="context">
<option value="dbmsinstances.php">DBMS instances</option>
<option value="databases.php">Databases</option>
<option value="hosts.php">Hosts</option>
<option value="services.php">Services</option>
</select>
<input type="text" name="q" >';
echo '<input type="submit" value="Search">';
echo '</form>';
echo '</span>';
?>
<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>
</body></html>

