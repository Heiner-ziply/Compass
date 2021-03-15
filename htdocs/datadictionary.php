<?php
//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Data Dictionary";
$headline="<h1>Data Dictionary</h1>";
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
?>


<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  ?>

<H2>Contents</H2>
<ul>
<li><a href="#Overview">Overview</a></li>
<li><a href="#Tables">Tables</a></li>
<li><a href="#Encryption">Encryption</a></li>
<li><a href="#Backups">Backups</a></li>
</ul>
<H2><a name="Overview">Overview</a> </H2>

<P>Data is stored in the ODWNWP database in the OVERDRIVE schema.
For convenience, you may want to set the schema to "OVERDRIVE" at the session level to avoid having to preface all the table names in your queries:
</P>

<blockquote>Alter session set current_schema= OVERDRIVE; </blockquote>

<P>The data has been normalized to reduce repetition of information. With the data loaded into an Oracle database rather than a flat file such as a Word doc or Excel sheet, updates can be made concurrently to different tables, and the machine-readable data can be accessed by Linux clients for automated work.  </P>


<H2><a name="Tables">Tables and Views</a> </H2>

<?php

$tablesql = 'select table_name, comments from overdrive.tab_comments order by table_name ';
$table_results = $dds->setSQL($tablesql);
$table_recs = $dds->getDataset();


    $arrlength=count($table_recs);
	for($x=0;$x<$arrlength;$x++) {
        //$table->addRow($table_results);
        echo "<h5>".$table_recs[$x][0]."</h5>";
        echo '<h6 style="margin:50px;">' . $table_recs[$x][1]."</h6>";
        $columnSQL = "select column_name, comments from overdrive.col_comments where table_name='" . $table_recs[$x][0] ."'";
        $columnresults = $dds->setSQL($columnSQL);

        while ($columnresults = $dds->getNextRow()){
            echo '<p style="margin:50px;"><B>'.$columnresults[0] .'</B><br>'. $columnresults[1] . "</p>";
    
        }

	}

?>

<H2><a name="Encryption">Encryption <a/></H2>

<P>This data includes passwords (in the E$HOSTUSER, E$SERVICEUSER and E$PASSWORD tables), and these passwords are encrypted.  E$SERVICEUSER contains team-managed passwords and E$PASSWORD contains personal passwords. E$HOSTUSER may contain either. There is a team encryption key for team-managed passwords and each person will have their own encryption key for their own passwords.  </P>

<P>To make the encryption transparent, the keys can be loaded as session information and then instead of selecting/inserting/updating the base tables,  the passwords can be seen  or updated as plain text in database views. The view PASSWORD decrypts/encrypts  the E$PASSWORD table;  The view PASSWORD selects only the rows inserted  into E$PASSWORD by the current user and then decrypts/encrypts the data. </P>

<P>To load the encryption keys in your Oracle session, do the following, replacing the keys below with the actual key strings: </P>

<blockquote>exec dbms_application_info.set_client_info('my_secret_key');  <BR>
exec dbms_session.set_identifier('my_team_key');
 </blockquote>

<P>Keys should be chosen once and never changed. If  the data is encrypted with a particular key , no other key will decrypt it. </P>

<P>The E$SERVICEUSER, E$HOSTUSER, and E$PASSWORD tables can be ignored, and all work can be done via the views.  </P>

<blockquote>update SERVICEUSER set password=username where lower(domain) like 'q%6eadb'; </blockquote>

<P>Non-encrypted data can be seen in the views without loading the encryption key:</P>

<blockquote>select username, domain  from SERVICEUSER where lower(domain) like 'q%6eadb'; </blockquote>

<P>If encrypted data is selected from the view without loading the encryption key, the following error will occur: </P>

<blockquote>10:06:51 SQL> select username, domain, password from serviceuser<BR>
10:07:32   2  where lower(domain) like 'q%6eadb';<BR>
where lower(domain) like 'q%6eadb'<BR>
           *<BR>
ERROR at line 2:<BR>
ORA-28239: no key provided<BR>
ORA-06512: at "SYS.DBMS_OBFUSCATION_TOOLKIT_FFI", line 40<BR>
ORA-06512: at "SYS.DBMS_OBFUSCATION_TOOLKIT", line 153<BR>
ORA-06512: at "INFRASTRUCTURE.DECRYPT", line 12
 </blockquote>

<H2><a name="Backups">Backups </a></H2>

<P>Data is backed up nightly by the khh8615 account on spfnwfbldpv01. 1 day(s) worth of backups are retained there. The entire ODWNWP database is also backed up regularly by the oracle account on its host server.</P>

<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>

</body></html>