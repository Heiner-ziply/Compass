<?php
//http://www.clker.com/clipart-gears-greens.html
$logo_image="images/gears-greens-th.png";
$user_is_admin=false;
if (isset($_SESSION['username'])) {
    if ($_SESSION['username']=='khh8615') $user_is_admin=true;
}
$navbar_links=array();  
$sidebar_links=array();  
$active_menu_class="w3-hide-small w3-blue";
$other_menu_class="w3-hide-small w3-hover-blue";
$active_menu="directories";
if (isset($_GET['menu'])) {
    if ($_GET['menu']=="monitoring") $active_menu="monitoring";
    if ($_GET['menu']=="dashboards") $active_menu="dashboards";
    if ($_GET['menu']=="help") $active_menu="help";
    if ($_GET['menu']=="admin" ) {
        if ( $user_is_admin) $active_menu="admin";
    }
}
$navbar_links[sizeof($navbar_links)]=array("name"=>'<img src="images/gears-greens-th.png" height="20">',"href"=>"index.php","class"=>"w3-green");
$menu_class=$other_menu_class;

if ($active_menu=="admin") $menu_class=$active_menu_class;
//
if ($user_is_admin) $navbar_links[sizeof($navbar_links)]=array("name"=>"Admin","href"=>"index.php?menu=admin","class"=>$menu_class);
$menu_class=$other_menu_class;

if ($active_menu=="directories") $menu_class=$active_menu_class;
$navbar_links[sizeof($navbar_links)]=array("name"=>"Directories","href"=>"index.php","class"=>$menu_class);
$menu_class=$other_menu_class;

if ($active_menu=="dashboards") $menu_class=$active_menu_class;
$navbar_links[sizeof($navbar_links)]=array("name"=>"Dashboards","href"=>"index.php?menu=dashboards","class"=>$menu_class);
$menu_class=$other_menu_class;

if ($active_menu=="help") $menu_class=$active_menu_class;
$navbar_links[sizeof($navbar_links)]=array("name"=>"Help","href"=>"index.php?menu=help","class"=>$menu_class);
$menu_class=$other_menu_class;

if ($active_menu=="monitoring") $menu_class=$active_menu_class;
$navbar_links[sizeof($navbar_links)]=array("name"=>"Monitoring","href"=>"index.php?menu=monitoring","class"=>$menu_class);
$menu_class=$other_menu_class;


if (!isset($_GET['menu'])) {
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/application.png" height="25" alt="(PC icon)"></td><td> Applications',"href"=>"applications.php","class"=>"w3-hover-blue");
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/wrench.png" height="20" alt="(wrench icon)"></td><td>Batch Jobs',"href"=>"batchjobs.php","class"=>"w3-hover-blue");
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/lb.png" alt="(icon)"></td><td> Environments',"href"=>"environments.php","class"=>"w3-hover-blue");
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/database.png" height="25" alt="(db icon)"></td><td> Databases',"href"=>"databases.php","class"=>"w3-hover-blue");
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/db.png" alt="(multi-db icon)"></td><td> DBMS Instances',"href"=>"dbmsinstances.php","class"=>"w3-hover-blue");
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/server.png" height="35" alt="(server icon)"></td><td>  Hosts',"href"=>"hosts.php","class"=>"w3-hover-blue");
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/handshake.png" height="25" alt="(handshake icon)"></td><td> Interfaces',"href"=>"interfaces.php","class"=>"w3-hover-blue");
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/globe.png" height="25" alt="(globe icon)"></td><td> Places',"href"=>"locations.php","class"=>"w3-hover-blue");
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/service.png" alt="(server icon)"></td><td> Services',"href"=>"services.php","class"=>"w3-hover-blue");
    $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/team.png" height="24" alt="(team icon)"></td><td> Teams',"href"=>"teams.php","class"=>"w3-hover-blue");
}

if (isset($_GET['menu'])) {
    if ($_GET['menu']=="monitoring"){
        //$sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/gears-greens-th.png" height="25" alt="(gears icon)"></td><td>System Status',"href"=>"system_status.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/globe.png" height="25" alt="(globe icon)"></td><td>System Scope',"href"=>"system_scope.php","class"=>"w3-hover-blue");
     
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/wrench.png" height="25" alt="(wrench icon)"></td><td> Batch Jobs',"href"=>"monitor_batch.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/db.png" height="25" alt="(disk icon)"></td><td> Disk Usage (UNIX)',"href"=>"monitor_disk.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/db.png" height="25" alt="(disk icon)"></td><td> Disk Usage (SQL Server)',"href"=>"monitor_disk_sql.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/db.png" height="25" alt="(disk icon)"></td><td> Disk Usage (Oracle ASM)',"href"=>"monitor_disk_asm.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/database.png" height="25" alt="(server icon)"></td><td> Oracle Backups',"href"=>"monitor_rman.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/database.png" height="25" alt="(server icon)"></td><td> Oracle Tablespaces',"href"=>"monitor_tablespace.php","class"=>"w3-hover-blue");
        
        //$sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/service.png" height="25" alt="(server icon)"></td><td> Service Availability',"href"=>"monitor_heartbeat.php","class"=>"w3-hover-blue");

    }

    if ($_GET['menu']=="dashboards"){
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/db.png" height="25" alt="(db icon)"></td><td>Database Backups',"href"=>"dashboard_db_backups.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/db.png" height="25" alt="(db icon)"></td><td>Database Access',"href"=>"dashboard_dbaccess.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/server.png" height="25" alt="(server icon)"></td><td>Host Access',"href"=>"dashboard_hostaccess.php","class"=>"w3-hover-blue");
    }

    if ($_GET['menu']=="admin"){
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/globe.png" height="25" alt="(globe icon)"></td><td>System Status',"href"=>"admin_sysstatus.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/team.png" height="25" alt="(globe icon)"></td><td>User Privileges',"href"=>"admin_userprivs.php","class"=>"w3-hover-blue");
    }

    if ($_GET['menu']=="help"){
        
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/application.png" height="25" alt="(wrench icon)"></td><td> About this app',"href"=>"about.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/wrench.png" height="25" alt="(disk icon)"></td><td> Configuration',"href"=>"help.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/db.png" height="25" alt="(icon)"></td><td> Data Dictionary',"href"=>"datadictionary.php","class"=>"w3-hover-blue");
        if(isset($_SESSION['username'])) $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/dataentry.png" height="25" alt="(icon)"></td><td> Data Entry',"href"=>"dataentry.php","class"=>"w3-hover-blue");
        if(isset($_SESSION['username'])) $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/key.png" height="25" alt="(icon)"></td><td> My DB Accounts',"href"=>"myaccounts.php","class"=>"w3-hover-blue");
        $sidebar_links[sizeof($sidebar_links)]=array("name"=>'<img src="images/search.png" height="25" alt="(search icon)"></td><td> Search',"href"=>"search.php","class"=>"w3-hover-blue");
    }


}

//$footer_text="This page was generated at " . date("Y-m-d H:i:s");
$footer_text='See source code, issues, and updates on <a href="https://github.com/Ziply-DBA/Overdrive">GitHub</a>';

$settings['DEFAULT_DB_TYPE'] = "mysql";     // set default database type
$settings['DEFAULT_DB_USER'] = "gold_app"; // set default database user
$settings['DEFAULT_DB_HOST'] = "localhost";// set default database host
$settings['DEFAULT_DB_INST'] = "gold";      // set default database name/instance/schema
$settings['DEFAULT_DB_MAXRECS'] = 50;

$settings['DEFAULT_DB_TYPE'] = "oracle";                        // set default database type
$settings['DEFAULT_DB_USER'] = "overdrive_app";                // set default database user
$settings['DEFAULT_DB_HOST'] = "odwnwfbsdpl01.nw1.nwestnetwork.com";// set default database host
$settings['DEFAULT_DB_INST'] = "ODWNWP";                        // set default database name/instance/schema
$settings['DEFAULT_DB_MAXRECS'] = 50;
$settings['DEFAULT_DB_PORT'] = "1521";                          // set default database port


//This has been moved to a separate file ignored by git: settingsPasswords.php
//$settings['DEFAULT_DB_PASS'] ="xxxxxxxxxxx"; // set default database password
require_once 'settingsPasswords.php';

define ("DATAFILE_PATH","C:\Bitnami\wampstack-7.2.29-2\apps\Compass\htdocs");
define ("WEBROOT","C:\Bitnami\wampstack-7.2.29-2\apps\Compass\htdocs");

$settings['DEBUG']=true;
//$hideLoginStatus=true;
//$hideSearchForm=true;
//$settings['prompt_reg']=0;
$settings['page_usage_tracking']=true;
$settings['show_sql']=true;
$settings['uname_label']="Corp ID";
$settings['search_page']="services.php";
$settings['login_page']="login.php";
$stateVarList=array('app_id','envid','servicetype','brand','q','menu');

require_once ('Hydrogen/libDebug.php');
require_once ('Hydrogen/libState.php');
require_once ('Hydrogen/libPagination.php');
require_once ('Hydrogen/clsHTMLTable.php');
require_once ('Hydrogen/clsDataSource.php');

?>
