<?php
//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Privileges";
$headline = '<h1>Privileges</h1>' ;
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div class="container">
<br><BR>
<?php 
    $action="view";
    if ( isset( $_GET['action'])) {
      if ($_GET['action']=="add") {
        $action="add";
        //echo "<p>GET-Action=Add</p>";
      }
    }
    if ( isset( $_POST['flow'])) {
      if ($_POST['flow']=="insert") $action= $_POST['flow'];
    }
    //echo "<p>Action=</p>" . $action;
    if ($action=="add") {
      
      if ($user_is_admin) {
        require ("entity/privilege/add_priv.inc.php");
      } else {
        echo "<br><br><h2>Oops!</h2><p>There must be a mistake. If you are trying to add a record, you won't be able to do that.<p> <br><br>";
      }
    }  

    if ($action=="insert") {
      if ($user_is_admin  and isset($_POST['privname']) and isset($_POST['pdescription']))    {
        //insert the new record and show the results
        $sql="insert into privilege (name,description) values (:privname,:descrip)";
        $bindVars=array();
        $bindVars[0]=array('privname',sanitizePostVar('privname'));
        $bindVars[1]=array('descrip',sanitizePostVar('pdescription'));
        $dds->setBindVars($bindVars);
        $dds->setSQL($sql);

      }
    }  


?>
      <p>
        The table below lists application privileges.
        <?php if($user_is_admin and ($action=="view")) echo '<a href="admin_privs.php?action=add"><img height="20" src="images/dataentry.png"> Add</a>' ?>

      </p>
      <table class="sortable">
        <tbody>

          
<?php

$sql="select id, name, description from privilege";		
$result = $dds->setSQL($sql) ;


echo '<tr>';
echo '<th>ID</th>';
echo '<th>Name</th>';
echo '<th>Description</th> 
</tr>';
while ($rrow = $dds->getNextRow()) {
    //echo '<a name="'. $rrow[0] . '"></a>';
    echo "<tr>";
        echo '<td><a href="admin_priv.php?id=' . $rrow[0] . '"><img height="20" src="images/key.png"></td><td>' . $rrow[1] . '</td><td>' , $rrow[2] . "</td>";
		echo "</tr>
    ";
	}

?>
        
        
        </tbody>
      </table>
    </div>


<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>
</body></html>

