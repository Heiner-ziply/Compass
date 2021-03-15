<?php
//The following three lines provide the variables that incTemplate.php will use to create the page header, menu, and sidebar
$pagetitle="Configuration Help";
$headline="<h1>Configuration Help</h1>";
$top_help_text="";
include ('Hydrogen/pgTemplate.php');
?>


<div id="main" class="w3-main w3-container w3-padding-16" style="margin-left:250px">
<div>
</div>
<?php include 'Hydrogen/elemLogoHeadline.php';  ?>

<ul>
<li><a href="#orcl">Configuring SQL*Plus</a></li>
<li><a href="#kitty">Configuring PuTTY/SSH</a></li>
<li><a href="#mstsc">Configuring Windows Remote Desktop</a></li>
</ul>
<h2 id="orcl">Configuring SQL*Plus</h2>
<p>Hyperlinks can be clicked to connect you to a database via SQL*Plus. Assuming you have already downloaded and installed an Oracle client and included the path to the SQL*Plus executable in your system's PATH, these are the steps to follow.</p>

<h3>Eliminate your dependency on "tnsnames.ora"</h3>

<p>Your Oracle client needs to be able to connect a database when given a host, port and SID even if the database is not in the tnsnames.ora file. Locate "network/admin/sqlnet.ora" in your ORACLE_HOME (or as specified by the TNS_ADMIN environment variable, if set) and make sure it includes "EZCONNECT" in the NAMES.DIRECTORY_PATH. For example, it may include this line: "NAMES.DIRECTORY_PATH= (TNSNAMES, EZCONNECT)". Note that the Oracle Instant Client may not support EZCONNECT method&amp; download and configure the full client instead.</p>


<h3>Store all your SQL*Plus scripts in a single directory</h3>
<p>When SQL*Plus starts, you will want to have immediate access to your scripts. The directory path in this example is "C:\Home\OracleSQL"</p>

<h3>Make all your Oracle passwords the same</h3>
<p>If you want to be able to log in automatically, you will need a script that can be passed an Oracle database identifier and can look up the username and password it needs for that database. The easiest way to do that is to use the same password for every database and to change them all before any one of them is due to expire.</p>

<h3>Create "conn.sql" in your SQL*Plus script directory</h3>
<p>"conn.sql" is a login script which takes the database as a parameter ("&1"). In place of "xy1234/mypassword" below, type your own ID and password.</p>
<blockquote>
<p>set define on<br>
set echo off<br>
connect xy1234/mypassword@&1</p>
</blockquote>

<p>Optionally, you can add other commands or references to other scripts to be executed every time you log in:</p>

<blockquote>

<p>@login<br>
select global_name from global_name;</p>
</blockquote>

<h3>Create a batch file to handle "orcl://" URLs clicked in your browser and hand them off to SQL*Plus </h3>
<p>"orcl.bat" takes the URL clicked in the browser, parses it, formats a command window, and hands off the database ID in host:port/SID format to the "conn.sql" script to be run by sqlplus.exe on startup. In this example "orcl.bat"  will be stored in "C:\Home". Edit the text below if your sql script path is different from the one in this example. You can also edit the "cols" and "lines" values to change the size of your command window. For help on choosing window colors, type "color /?" at the Windows command line.</p>

<blockquote>
<p>set arg=%1<br>
set url=%arg:~7%<br>
cd c:\home\OracleSQL<br>
color F1<br>
mode con: cols=140 lines=60<br>
Title "SQL*Plus"<br>
sqlplus.exe /nolog @conn %url%</p>
</blockquote>

<h3>Registry changes</h3>
<p>The final step is to tell Windows to hand off any "orcl://" URL to your batch file. Save the following into a plain text file. Edit the "orcl.bat" path to match your path if different from the one in this example. Remove any blank lines from the top of the file and any spaces to the left of each line. Save the file with a ".reg" extension and double-click on it to make the registry changes.</p>
<p></p>
<blockquote>
<p>Windows Registry Editor Version 5.00</p>

<p>[HKEY_CLASSES_ROOT\orcl]<br>
@="URL:orcl Protocol"<br>
"EditFlags"=dword:00000002<br>
"FriendlyTypeName"="@ieframe.dll,-907"<br>
"URL Protocol"=""<br>
"BrowserFlags"=dword:00000008</p>

<p>[HKEY_CLASSES_ROOT\orcl\shell]<br>
@=""</p>

<p>[HKEY_CLASSES_ROOT\orcl\shell\open]</p>

<p>[HKEY_CLASSES_ROOT\orcl\shell\open\command]<br>
@="\"C:\\Home\\orcl.bat\" %1"</p>
</blockquote>
<p>Once the file has been edited, double-click on it to add the entries to your registry. If Windows complains of it not being a valid reg file, your text editor probably did not save it as an ANSI text file.</p>
<p>Restart your browser. When you click on an "orcl://" link for the first time, you may be prompted to confirm that your batch file is the handler for the links.</p>
<p></p>

<p>To enable highlighting of text in the command window for purposes of copying/pasting, see this article: <a href="http://www.winhelponline.com/blog/enable-quick-edit-command-prompt-by-default/">How to Enable Quick Edit Mode in the Command Prompt by Default</a></p>
<p></p>


<h2 id="kitty">Configuring PuTTY/SSH</h2>
<p>Hyperlinks can be clicked to connect to Unix-type servers via ssh. Assuming you already have PuTTY on your computer and have configured your "Default Settings" to use SSH keys to automatically log you in (for more help, see <a href="https://github.com/Heiner-ziply/UnixDBA">here</a>), these are the steps needed to hand off the clickable URL to an SSH client when you click on it.</p>

<h3>Download KiTTY</h3>
<p>KiTTY is a PuTTY fork with the ability to take SSH URLs on the command line. It can be downloaded from <a href="http://www.9bis.net/kitty/?page=Download">http://www.9bis.net/kitty/?page=Download</a>. NOTE: Do not use the "portable" version. Save the kitty.exe file in whatever permanent location you think is best. In the example below, we will use "C:\Program Files\PuTTY\kitty.exe"</p>

<h3>Registry changes</h3>
<p>Save the following into a plain text file. Remove any blank lines from the top of the file and any spaces to the left of each line. Save the file with a ".reg" extension and double-click on it to make the registry changes.</p>

<p>NOTE: the KiTTY registry file at <a href"http://www.9bis.net/kitty/?file=kitty_ssh_handler.reg"> http://www.9bis.net/kitty/?file=kitty_ssh_handler.reg</a> is a good example, but uses the "ssh://" protocol as an identifier. This can cause issues with your setup if you have installed other software (such as WinSCP) which will compete with KiTTY for handling such links. To avoid this problem, we will write our links with a "kitty://" prefix and handle them using the following registry changes:</p>

<blockquote>
<p>Windows Registry Editor Version 5.00</p>

<p>[HKEY_CLASSES_ROOT\ssh]<br>
@="URL:kitty Protocol"<br>
"EditFlags"=dword:00000002<br>
"FriendlyTypeName"="@ieframe.dll,-907"<br>
"URL Protocol"=""<br>
"BrowserFlags"=dword:00000008</p>

<p>[HKEY_CLASSES_ROOT\kitty\DefaultIcon]<br>
@="C:\\Program Files\\PuTTY\\kitty.exe,0"</p>

<p>[HKEY_CLASSES_ROOT\kitty\shell]<br>
@=""</p>

<p>[HKEY_CLASSES_ROOT\kitty\shell\open]</p>

<p>[HKEY_CLASSES_ROOT\kitty\shell\open\command]<br>
@="\"C:\\Home\\kitty.bat\" %1"</p>
</blockquote>

<p>Once the file has been edited, double-click on it to add the entries to your registry. If Windows complains of it not being a valid reg file, your text editor probably did not save it as an ANSI text file.</p>
<p>Restart your browser. When you click on an ssh link for the first time, you may be prompted to confirm that kitty.bat is the handler for ssh links.</p>

<h3>Create a batch file to handle "kitty://" URLs clicked in your browser and hand them off to KiTTY </h3>
<p>"kitty.bat" takes the URL clicked in the browser, parses it (line two strips off the leading eight "kitty://" characters and the final forward slash), and hands it off to KiTTY. In this example "kitty.bat"  will be stored in "C:\Home". Edit the text below if your kitty.exe path is different from the one in this example. </p>

<blockquote>
<p>set url=%1<br>
set hname=%url:~8,-1%<br>
start "KiTTY" "C:\Program Files\PuTTY\kitty.exe" %hname%</p>
</blockquote>



<h2 id="mstsc">Configuring Windows Remote Desktop</h2>
<p>Hyperlinks can be clicked to connect to Windows servers via the Windows Remote Desktop client. These are the steps needed to hand off the clickable URL to the Remote Desktop client when you click on it.</p>

<h3>Registry changes</h3>
<p>Save the following into a plain text file. Remove any blank lines from the top of the file and any spaces to the left of each line. Save the file with a ".reg" extension and double-click on it to make the registry changes.</p>

<blockquote>
<p>Windows Registry Editor Version 5.00</p>

<p>[HKEY_CLASSES_ROOT\mstsc]<br>
@="URL:mstsc Protocol"<br>
"EditFlags"=dword:00000002<br>
"FriendlyTypeName"="@ieframe.dll,-907"<br>
"URL Protocol"=""<br>
"BrowserFlags"=dword:00000008</p>

<p>[HKEY_CLASSES_ROOT\mstsc\shell]<br>
@=""</p>

<p>[HKEY_CLASSES_ROOT\mstsc\shell\open]</p>

<p>[HKEY_CLASSES_ROOT\mstsc\shell\open\command]<br>
@="\"C:\\Home\\mstsc.bat\" %1"</p>
</blockquote>

<p>Once the file has been edited, double-click on it to add the entries to your registry. If Windows complains of it not being a valid reg file, your text editor probably did not save it as an ANSI text file.</p>
<p>Restart your browser. When you click on a "mstsc://" link for the first time, you may be prompted to confirm that mstsc.bat is the handler for ssh links.</p>

<h3>Create a batch file to handle "mstsc://" URLs clicked in your browser and hand them off to Remote Desktop </h3>
<p>"mstsc.bat" takes the URL clicked in the browser, parses it (line three strips off the leading eight "mstsc://" characters and the final forward slash), and hands it off to mstsc.exe. In this example "mstsc.bat"  will be stored in "C:\Home". Edit the text below if your mstsc.exe path is different from the one in this example. </p>

<blockquote>
<p>@echo off <br>
set url=%1<br>
set hname=%url:~8,-1%<br>
cmdkey /generic:"%hname%" /user:"DOMAIN\userID" /pass:"myPassword"<br>
mstsc /v:%hname%</p>
</blockquote>

If you prefer not to hard-code your username and password in the batch file as in the example above, you can also save them as persistent environment variables:

<blockquote>
setx DOMAIN_USER DOMAIN\userID<br>
setx DOMAIN_PASS myPassword</p>
</blockquote>

And then reference these from mstsc.bat as follows:

<blockquote>
<p>@echo off <br>
set url=%1<br>
set hname=%url:~8,-1%<br>
cmdkey /generic:"%hname%" /user:"%DOMAIN_USER%" /pass:"%DOMAIN_PASS%"<br>
mstsc /v:%hname%</p>
</blockquote>

<!-- END MAIN -->
</div>
<?php include "Hydrogen/elemNavbar.php"; ?>
<?php include "Hydrogen/elemFooter.php"; ?>

</body></html>