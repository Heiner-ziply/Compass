<?php
//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Role";
$headline = '' ;
$top_help_text="";
$sqlResult="";
include ('Hydrogen/pgTemplate.php');
//include ('Hydrogen/elemLogoHeadline.php');
?>
<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div class="container">
<?php
$roleID=0;
if ( isset( $_GET['id'])) {
  if (is_numeric($_GET['id'])) $roleID= $_GET['id'];
}
if ( isset( $_POST['id'])) {
  if (is_numeric($_POST['id'])) $roleID= $_POST['id'];
}

$action="view";
if ( isset( $_POST['flow'])) {
  //accept new data and show updated record
	if ($_POST['flow']=="update") $action="update";

  if ($_POST['flow']=="insert") $action="insert";

}
if ( isset( $_GET['action'])) {
  //show the edit form
  if ($_GET['action']=="edit") $action="edit";
  //show the add form
  if ($_GET['action']=="add") $action="add";
  //manage user mapping
  if ($_GET['action']=="users") $action="users";
  //manage priv mapping
  if ($_GET['action']=="privs") $action="privs";
  //map user to role
  if ($_GET['action']=="mapuser") $action="mapuser";
  //map priv to role
  if ($_GET['action']=="mappriv") $action="mappriv";
  //unmap user from role
  if ($_GET['action']=="demapuser") $action="demapuser";
  //unmap priv from role
  if ($_GET['action']=="demappriv") $action="demappriv";

}

if ($action!="view" and !$user_is_admin) {
  echo "<br><br><h2>Oops!</h2><p>There must be some mistake. If you are trying to update a record, you won't be able to do that.</p>  </div></div>";
  include "Hydrogen/elemNavbar.php";
  include "Hydrogen/elemFooter.php";
  echo ('</body></html>');
  die();

}

if ($action=="edit") {
  if ( isset( $_GET['column'])) {
    for ($i = 0; $i < count($editableColumns); $i++) {
      if($editableColumns[$i]==$_GET['column']) $column=$_GET['column'];
    }
  }
}

if ($action=="update") {
  if ( isset( $_POST['column'])) {
    for ($i = 0; $i < count($editableColumns); $i++) {
      if($editableColumns[$i]==$_POST['column']) $column=$_POST['column'];
    }
  }
  if ( isset($column) and isset($personID) and isset( $_POST['new_value'])) {

    //validate and perform the update, then show the results (change the action to "view")
    $newValue=sanitizePostVar('new_value');
    $sql="update " . $tableName . " set " . $column . "=:colvalue where " . $keyName . "=:keyvalue";
    $bindVars=array();
    $bindVars[0]=array('colvalue',$newValue);
    $bindVars[1]=array('keyvalue',$roleID);
    $dds->setBindVars($bindVars);
    $dds->setSQL($sql);

    $action="view";
  }

}


if ($action=="mapuser") {
  //process the change and show the results
  if ( isset( $_GET['userid'])) {
    //$sqlResult="Processing GET request for values role:" . $roleID . " and user: " . sanitizeGetVar($_GET['userid']) . "</p>";
    if (is_numeric($_GET['userid'])) $userID= $_GET['userid'];
  }
  if ( isset($roleID) and isset($userID)  ) {
    $sql="insert ignore into m_user_role (role_id,user_id) values (:ID1,:ID2)";
    $bindVars=array();
    $bindVars[0]=array('ID1',$roleID);
    $bindVars[1]=array('ID2',$userID);
    $dds->setBindVars($bindVars);
    $dds->setSQL($sql);
 }

  $action="users";
}

if ($action=="demapuser") {
  //process the change and show the results

  if ( isset( $_GET['userid'])) {
    if (is_numeric($_GET['userid'])) $userID= $_GET['userid'];
  }
  if ( isset($roleID) and isset($userID)  ) {


    $conn=new mysqli($settings['DEFAULT_DB_HOST'], $settings['DEFAULT_DB_USER'] , $settings['DEFAULT_DB_PASS'], $settings['DEFAULT_DB_INST']);
    $sql="delete from m_user_role where role_id=:ID1 and user_id=:ID2";
    $bindVars=array();
    $bindVars[0]=array('ID1',$roleID);
    $bindVars[1]=array('ID2',$userID);
    $dds->setBindVars($bindVars);
    $dds->setSQL($sql);
  }

  $action="users";
}

if ($action=="mappriv") {
  //process the change and show the results
  if ( isset( $_GET['privid'])) {
    //$sqlResult="Processing GET request for values role:" . $roleID . " and user: " . sanitizeGetVar($_GET['userid']) . "</p>";
    if (is_numeric($_GET['privid'])) $privID= $_GET['privid'];
  }
  if ( isset($roleID) and isset($privID)  ) {
    //$sqlResult="Processing map request for values role:" . $roleID . " and user: " . $userID . "</p>";

    $sql="insert ignore into m_role_privilege (role_id,privilege_id) values (:ID1,:ID2)";
    $bindVars=array();
    $bindVars[0]=array('ID1',$roleID);
    $bindVars[1]=array('ID2',$privID);
    $dds->setBindVars($bindVars);
    $dds->setSQL($sql);
  }
  $action="privs";
}

if ($action=="demappriv") {
  //process the change and show the results

  if ( isset( $_GET['privid'])) {
    if (is_numeric($_GET['privid'])) $privID= $_GET['privid'];
  }
  if ( isset($roleID) and isset($privID)  ) {

    $sql="delete from m_role_privilege where role_id=:ID1 and privilege_id=:ID2";
    $bindVars=array();
    $bindVars[0]=array('ID1',$roleID);
    $bindVars[1]=array('ID2',$privID);
    $dds->setBindVars($bindVars);
    $dds->setSQL($sql);
  }




  $action="privs";
}


if ($action=="users") include ("entity/role/main.inc.php");
if ($action=="privs") include ("entity/role/main.inc.php");
if ($action=="view" ) include ("entity/role/main.inc.php");
?>


<!-- END MAIN -->
</div></div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>
</body></html>
