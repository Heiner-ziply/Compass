<style>
ul.pagination li {
   display: inline;
   }
   
ul.pagination {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
table.params {
    border-collapse: collapse;
    border: 1px solid black;
}
tr.params {
  border: 1px solid black;
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
$pagetitle="System Status";
$headline='<h1>System Status</h1><button id="ToggleHelp">Show/hide help</button>';
$top_help_text='<h2>Help for this page:</h2>';
//$top_help_text=$top_help_text . '<p>To use the hyperlinks (<img src="images/ssh-icon.png" alt="ssh link">,<img src="images/mswin.jpg" alt="windows link">) below, you will need to have a corresponding client configured. <a href="help.php"> See instructions here</a>. </p>';
$top_help_text=$top_help_text . '<p>Under construction. </p>';
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">

<div>
<div class="w3-main w3-container w3-padding-16" id="top_help">
<p><?php echo $top_help_text; ?></p>
</div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  	

echo '<H3>Application settings</H3><TABLE class="params">';
echo '<tr class="params"><td>DOCUMENT ROOT:</td><td>';
    echo $_SERVER['DOCUMENT_ROOT'];
    echo "</td></tr>";
echo '<tr class="params"><td>ERROR LOG:</td><td>';
    echo ini_get('error_log');
    echo "</td></tr>";    
echo '<tr class="params"><td>DEBUGGING:</td><td>';
    if($settings['DEBUG']) echo "ON"; else echo "OFF"; 
    echo "</td></tr>";
echo '<tr class="params"><td>PAGE USAGE TRACKING:</td><td>';
    if($settings['page_usage_tracking']) echo "ON"; else echo "OFF"; 
    echo "</td></tr>";
echo "</TABLE>";

echo '<H3>Server</H3><TABLE class="params">';

$exec_array=array();
$return_val=5;
exec("python --version",$exec_array,$return_val);
//exec("dir",$exec_array,$return_val);
$python=$exec_array[1];
echo '<tr><td>PYTHON:';
    echo $python ; //. ' - ' . $return_val . ':' $length($exec_array);
    echo "</td></tr>";
/*
echo '<tr class="params"><td>SSL:</td><td>';
echo $_SERVER['OPENSSL_CONF'];
echo "</td></tr>";

echo '<tr class="params"><td>PATH:</td><td>';
echo $_SERVER['PATH'];
echo "</td></tr>";
*/


echo "</TABLE>";


echo '<H3>Environment</H3><TABLE class="params">';
echo '<tr class="params"><td>OS:</td><td>';
echo php_uname()    ;
echo "</td></tr>";

if (isset($_ENV['USERNAME']))    {
    echo '<tr class="params"><td>RUNNING AS:</td><td>';
        echo $_ENV['USERNAME'];
    echo "</td></tr>";
} else {
    file_put_contents("testFile", "test");
    $hostuserID = fileowner("testFile");
    $hostuser = posix_getpwuid ($hostuserID);
    unlink("testFile");

    echo '<tr class="params"><td>RUNNING AS:</td><td>';
    echo $hostuser['name']    ;
    echo "</td></tr>";

}

if (isset($_ENV['USERDOMAIN']))    {
    echo '<tr class="params"><td>DOMAIN:</td><td>';
        echo $_ENV['USERDOMAIN'];
    echo "</td></tr>";
}

if (isset($_ENV['COMPUTERNAME']))    {
    echo '<tr class="params"><td>RUNNING ON:</td><td>';
        echo $_ENV['COMPUTERNAME'];
    echo "</td></tr>";
}
echo "</TABLE>";

echo '<H3>Server</H3><TABLE class="params">';

echo '<tr class="params"><td>WEB SERVER:</td><td>';
    echo $_SERVER['SERVER_SOFTWARE'];
    echo "</td></tr>";

echo '<tr class="params"><td>SSL:</td><td>';
echo $_SERVER['OPENSSL_CONF'];
echo "</td></tr>";

echo '<tr class="params"><td>PATH:</td><td>';
echo $_SERVER['PATH'];
echo "</td></tr>";



echo "</TABLE>";

$user_is_admin=false;
if (isset($_SESSION['username'])) {
    if ($_SESSION['username']=='khh8615') $user_is_admin=true;
}

if ($user_is_admin) {


    //phpinfo(1);

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
<?php include "Hydrogen/elemFooter.php";

//phpinfo();


?>
</body></html>
