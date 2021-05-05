<?php
//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Users";
$headline = '<h1>Users</h1>' ;
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
?>

<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div class="container">
    <br><BR>
      <p>
        The table below lists registered application users.
        

      </p>
      <table class="sortable">
        <tbody>

          
<?php

$sql="select id, username, email from users where password is not null order by username";		
$result = $dds->setSQL($sql) ;


echo '<tr>';
echo '<th>ID</th>';
echo '<th>Username</th>';
echo '<th>Email</th> 
</tr>';
while ($rrow = $dds->getNextRow()) {
    //echo '<a name="'. $rrow[0] . '"></a>';
    echo "<tr>";
        echo '<td><a href="admin_user.php?id=' . $rrow[0] . 
        '"><img height="20" src="images/profile.png"></td><td>' . 
        $rrow[1] . '</td><td><a href="mailto:' . $rrow[2] . '">' . $rrow[2] . '</a></td>';
		echo "</tr>";
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

